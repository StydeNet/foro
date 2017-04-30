<?php

use App\{Comment,User};
use tests\FeatureTestCase;

class AcceptAnswerTest extends FeatureTestCase
{
    function test_the_post_author_can_accept_a_comment_as_the_posts_answer()
    {
        $comment = factory(Comment::class)->create([
            'comment' => 'Esta va a ser la respuesta del post'
        ]);

        $this->actingAs($comment->post->user);

        $this->visit($comment->post->url)
            ->press('Aceptar Respuesta');

        $this->seeInDatabase('posts', [
            'id'        => $comment->post_id,
            'pending'   => false,
            'answer_id' => $comment->id,
        ]);

        $this->seePageIs($comment->post->url)
            ->seeInElement('.answer', $comment->comment);
    }

    function test_non_post_author_not_see_the_accept_answer_button()
    {
        $comment = factory(Comment::class)->create([
            'comment' => 'Esta va a ser la respuesta del post'
        ]);

        $otherUser = factory(User::class)->create();

        $this->actingAs($otherUser);

        $this->visit($comment->post->url)
            ->dontSee('Aceptar Respuesta');
    }

    function test_non_post_author_cannot_accept_a_comment_as_the_posts_answer()
    {
        $comment = factory(Comment::class)->create([
            'comment' => 'Esta va a ser la respuesta del post'
        ]);

        $otherUser = factory(User::class)->create();

        $this->actingAs($otherUser);

        $this->post(route('comments.accept', $comment));

        $this->seeInDatabase('posts', [
            'id'        => $comment->post_id,
            'pending'   => true,
        ]);
    }

    function test_accept_answer_button_is_hidden_when_the_comment_is_already_the_posts_answer()
    {
        $comment = factory(Comment::class)->create([
            'comment' => 'Esta va a ser la respuesta del post'
        ]);

        $this->actingAs($comment->post->user);

        $comment->markAsAnswer();

        $this->visit($comment->post->url)
            ->dontSee('Aceptar Respuesta');
    }
}
