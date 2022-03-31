<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use InvalidArgumentException;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ScrapeGoolgeSearchResult implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $keywords;

    public $csv;

    public $tries = 3;

    public $timeout = 120;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($keywords, $csv)
    {
        $this->keywords = $keywords;
        $this->csv = $csv;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $host = 'https://www.google.com';
        $userAgents = config('services.user_agents');

        foreach($this->keywords as $keyword) { 
            $response = Http::withHeaders([
                'User-Agent' => $userAgents[array_rand($userAgents)]
            ])->get("{$host}/search", ['q' => $keyword]);
            

            $htmlContent = $response->body();
            $crwaler = new Crawler($htmlContent);
            
            $searchSummary = $crwaler->filter('#result-stats')->text();

            $totalAdvertisers = 0;

            try {
                $totalAdvertisers = $crwaler->filter('#tads')->children('div')->count();
            } catch(InvalidArgumentException $e) {
                $totalAdvertisers = 0;
            }

            $totalLinks = collect($crwaler->filter('#rso a')->extract(['href']))->filter(function ($link) {
                return $link !== '#';
            })->count();

            $webContent = $crwaler->html();
            
            $this->csv->results()->create([
                'keyword' => $keyword,
                'total_advertisers' => $totalAdvertisers,
                'total_links' => $totalLinks,
                'search_summary' => $searchSummary,
                'web_content' => $webContent,
            ]);
        }
    }
}
