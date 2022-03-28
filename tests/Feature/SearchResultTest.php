<?php

namespace Tests\Feature;

use App\Models\Result;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SearchResultTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_view_search_the_results()
    {
        $response = $this->get("/results/search");

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /** @test */
    public function users_can_search_the_results_by_keyword()
    {
        $this->withExceptionHandling();
        
        $user = User::factory()->create();
        $keyword = 'LARAVEL';
        Result::factory()->create([
            'keyword' => $keyword,
            'user_id' => $user->id
        ]);
        Result::factory()->create(['user_id' => $user->id]);
       

        $response = $this->actingAs($user)->get("/results/search?keyword={$keyword}");

        $response->assertOk();
        $response->assertViewIs('results.search');
        $this->assertCount(1, $response['results']);
    }

    /** @test */
    public function users_can_go_to_other_pages_with_the_searched_keyword()
    {
        $user = User::factory()->create();
        $keyword = 'LARAVEL';
        Result::factory(12)->create([
            'keyword' => $keyword,
            'user_id' => $user->id
        ]);

        $response = $this->actingAs($user)->get("/results/search?keyword={$keyword}");

        $response->assertOk();
        $this->assertStringContainsString($keyword, $response['results']->nextPageUrl());
    }

    /** @test */
    public function the_search_results_must_not_contain_the_other_users_results()
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();
        $keyword = 'LARAVEL';
        Result::factory()->create([
            'keyword' => $keyword,
            'user_id' => $anotherUser->id
        ]);

        $response = $this->actingAs($user)->get("/results/search?keyword={$keyword}");

        $response->assertOk();
        $this->assertEmpty($response['results']);
    }
}
