<?php

namespace Tests\Browser;

use App\Comment;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class VoteForCommentTest extends DuskTestCase
{
    use DatabaseMigrations;

    function test_a_user_can_vote_for_a_comment()
    {
        $user = $this->defaultUser();

        $comment = factory(Comment::class)->create();

        $this->browse(function (Browser $browser) use ($user, $comment) {
            $browser->loginAs($user)
                ->visit($comment->post->url)
                ->with('.comment', function ($browser) {
                    $browser->pressAndWaitFor('+1');

                    $browser->assertSeeIn('.current-score', 1);
                });
        });

        $comment->refresh();

        $this->assertSame(1, $comment->score);

        $this->assertSame(1, $comment->getVoteFrom($user));
    }
}
