<?php

use App\Vote;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class VoteForPostTest extends TestCase
{
    use DatabaseTransactions;

    function test_a_user_can_upvote_for_a_post()
    {
        $this->actingAs($user = $this->defaultUser());

        $post = $this->createPost();

        $this->postJson("posts/{$post->id}/vote/1")
            ->assertSuccessful()
            ->assertJson([
                'new_score' => 1
            ]);

        $this->assertDatabaseHas('votes', [
            'votable_id' => $post->id,
            'votable_type' => \App\Post::class,
            'user_id' => $user->id,
            'vote' => 1,
        ]);

        $this->assertSame(1, $post->fresh()->score);
    }

    function test_a_user_can_downvote_for_a_post()
    {
        $this->actingAs($user = $this->defaultUser());

        $post = $this->createPost();

        $this->postJson("posts/{$post->id}/vote/-1")
            ->assertSuccessful()
            ->assertJson([
                'new_score' => -1
            ]);

        $this->assertDatabaseHas('votes', [
            'votable_id' => $post->id,
            'votable_type' => \App\Post::class,
            'user_id' => $user->id,
            'vote' => -1,
        ]);

        $this->assertSame(-1, $post->fresh()->score);
    }

    function test_a_user_can_unvote_a_post()
    {
        $this->actingAs($user = $this->defaultUser());

        $post = $this->createPost();

        $post->upvote();

        $this->deleteJson("posts/{$post->id}/vote/")
            ->assertSuccessful()
            ->assertJson([
                'new_score' => 0
            ]);

        $this->assertDatabaseMissing('votes', [
            'votable_id' => $post->id,
            'votable_type' => \App\Post::class,
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'score' => 0,
        ]);
    }

    function test_a_guest_user_cannot_vote_for_a_post()
    {
        $user = $this->defaultUser();

        $post = $this->createPost();

        $this->postJson("posts/{$post->id}/vote/1")
            ->assertStatus(401)
            ->assertJson(['error' => 'Unauthenticated.']);

        $this->assertDatabaseMissing('votes', [
            'votable_id' => $post->id,
            'votable_type' => \App\Post::class,
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'score' => 0,
        ]);
    }
}
