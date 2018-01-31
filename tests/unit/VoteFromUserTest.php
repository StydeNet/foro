<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class VoteFromUserTest extends FeatureTestCase
{
    use DatabaseMigrations;

    public function test_vote_from_user_function_trait_post_can_be_voted()
    {
        //having
        $user = $this->defaultUser();
        $post = $this->createPost(['user_id' => $user->id]);
        $this->actingAs($user);

        //when
        $post->upvote();

        //then
        $voteFromUser = $post->getVoteFrom($user);
        $this->assertSame(1, $voteFromUser);
    }

    public function test_get_current_vote_function_trait_post_can_be_voted()
    {
        //having
        $user = $this->defaultUser();
        $post = $this->createPost(['user_id' => $user->id]);
        $this->actingAs($user);

        //when
        $post->downvote();

        //then
        $voteFromUser = $post->current_vote;
        $this->assertSame(-1, $voteFromUser);
    }
}
