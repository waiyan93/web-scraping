<?php

namespace Tests\Feature;

use App\Models\Result;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewResultListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_view_the_search_result_list()
    {
        $response = $this->get("/results");

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /** @test */
    public function users_can_view_the_search_result_list()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        Result::factory(3)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/results');

        $response->assertOk();
        $response->assertViewIs('results.index');

        $this->assertNotEmpty($response['results']);
    }

    /** @test */
    public function users_cannot_view_other_search_result_list()
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();

        Result::factory(3)->create(['user_id' => $anotherUser->id]);

        $response = $this->actingAs($user)->get('/results');

        $this->assertEmpty($response['results']);
    }
}
