<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Result;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewResultTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function guests_cannot_view_the_search_result()
    {
        $response = $this->get("/results/1");

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }
    
    /** @test */
    public function users_can_view_the_search_result()
    {
        $this->withExceptionHandling();

        $user = User::factory()->create();

        $result = Result::create([
            'keyword' => 'Hello',
            'total_advertisers' => 10,
            'total_links' => 20,
            'search_summary' => 'About 89,700,000 results (0.41 seconds)',
            'web_content' => '<h2>This is result html.</h2>',
            'user_id' => $user->id
        ]);

        $response = $this->actingAs($user)->get("/results/{$result->id}");

        $response->assertOk();
        $response->assertViewIs('results.show');
        $response->assertViewHas('result', $result);
    
        $response->assertSee('Hello');
        $response->assertSee(10);
        $response->assertSee(20);
        $response->assertSee('About 89,700,000 results (0.41 seconds)');
        $response->assertSee('<h2>This is result html.</h2>', false);
    }

    /** @test */
    public function users_cannot_view_other_search_result()
    {
        $this->withExceptionHandling();

        $user = User::factory()->create();
        $anotherUser = User::factory()->create();

        $result = Result::factory()->create(['user_id' => $anotherUser->id]);

        $response = $this->actingAs($user)->get("/results/{$result->id}");

        $response->assertStatus(404);
    }
}
