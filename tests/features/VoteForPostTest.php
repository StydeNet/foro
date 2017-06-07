<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class VoteForPostTest extends TestCase
{
    use DatabaseTransactions;

    function test_a_user_can_vote_for_a_post()
    {
        $this->actingAs($user = $this->defaultUser());

        $post = $this->createPost();

        $this->postJson($post->url . '/vote')
            ->assertSuccessful()
            ->assertJson([
                'new_score' => 1
            ]);

        $this->assertDatabaseHas('votes', [
            'post_id' => $post->id,
            'user_id' => $user->id,
            'vote' => 1,
        ]);

        $this->assertSame(1, $post->fresh()->score);
    }
}
