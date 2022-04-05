<?php

namespace App\Http\Controllers;

use Storage;
use App\Models\Result;
use Illuminate\Support\Facades\Auth;
use App\Jobs\ScrapeGoolgeSearchResult;
use App\Http\Requests\StoreResultRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Str;

class ResultController extends Controller
{
    public function index()
    {
        $results = Result::query()
            ->select(
                'id', 'keyword', 'total_advertisers', 'total_links', 'search_summary', 'csv_id'
            )
            ->latest()
            ->paginate(10);
        
        return view('results.index', compact('results'));
    }

    public function create()
    {
        return view('results.create');
    }

    public function store(StoreResultRequest $request)
    {
        $keywords = collect(array_map('str_getcsv', file($request->file('csv'))))
            ->flatten()
            ->reject(function($keyword) {
                return $keyword === "" || $keyword === null;
            });   
        
        if ($keywords->count() > 100) {
            return response()->json([
                'errors' => [
                    'csv' => [
                        'The keywords is more than 100.'
                    ]
                ]
            ], 422);  
        }

        $path = Storage::disk('public')->putFile('/csvs', $request->file('csv'));

        $csv = Auth::user()->csvs()->create([
            'path' => $path,
            'total_keywords' => $keywords->count(),
            'total_scraped_keywords' => 0
        ]);

        foreach ($keywords->chunk(10) as $chunkedKeywords) {
            dispatch(new ScrapeGoolgeSearchResult($chunkedKeywords, $csv));
        }

        return response()->json([
            'csv' => $csv
        ], 201);
    }

    public function show($id)
    {
        $result = Result::select('id', 'keyword', 'total_advertisers', 'total_links', 'search_summary')->findOrFail($id);

        return view('results.show', compact('result')); 
    }

    public function showHTML($id)
    {
        $result = Result::select('id', 'web_content')->findOrFail($id);

        return $result->web_content;
    }

    public function search()
    {
        $results = Result::query()
            ->select(
                'id', 'keyword', 'total_advertisers', 'total_links', 'search_summary', 'csv_id'
            )
            ->when($keyword = request('keyword'), function($query, $keyword) {
                $lowerKeyword = Str::lower($keyword);

                $query->where('keyword', 'like', "%{$keyword}%")
                    ->orWhere('keyword', 'like', "%{$lowerKeyword}%");
            })
            ->latest()
            ->paginate(10);
        
        $results->appends(['keyword' => request('keyword')]);
            
        return view('results.search', compact('results'));
    }
}
