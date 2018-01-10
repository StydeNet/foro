<?php

use App\{Comment, User, Post};
use App\Policies\CommentPolicy;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CommentPolicyTest extends TestCase
{
    use DatabaseTransactions;

    function test_the_posts_author_can_select_a_comment_as_an_answer()
    {
        $comment = factory(Comment::class)->create();

        $policy = new CommentPolicy;

        $this->assertTrue(
            $policy->accept($comment->post->user, $comment)
        );
    }

    function test_non_authors_cannot_select_a_comment_as_an_answer()
    {
        $comment = factory(Comment::class)->create();

        $policy = new CommentPolicy;

        $this->assertFalse(
            $policy->accept(factory(User::class)->create(), $comment)
        );
    }
    
  
    function test_user_method_owns(){

        $comment = factory(Comment::class)->create();
        $userMakeComment = User::find($comment->user_id);
        $post = factory(Post::class)->create();
        $userMakePost = $post->user;

        $user = $this->defaultUser();
        $this->assertTrue( $userMakeComment->owns($comment) );
        $this->assertTrue( $userMakePost->owns($post) );
        $this->assertFalse( $user->owns($comment) );
        $this->assertFalse( $user->owns($post) );

    }
}
