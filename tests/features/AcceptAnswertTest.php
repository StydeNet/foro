<?php

use App\Comment;

class AcceptAnswertTest extends FeatureTestCase
{
    function test_the_posts_author_can_accept_a_comment_as_the_posts_answer()
    {
        $comment = factory(Comment::class)->create([
            'comment' => 'Esta va a ser la respuesta del post'
        ]);

        $this->actingAs($comment->post->user);

        $this->visit($comment->post->url)
            ->press('Aceptar respuesta');

        $this->seeInDatabase('posts', [
            'id' => $comment->post_id,
            'pending' => false,
            'answer_id' => $comment->id,
        ]);

        $this->seePageIs($comment->post->url)
            ->seeInElement('.answer', $comment->comment);
    }

    /** @test */
    function test_comments_list_are_paginated()
    {
        $post = $this->createPost();

        $first_comment = factory(Comment::class)->create([
            'comment' => 'Este es el primer comentario',
            'post_id' => $post->id,
            'created_at' => \Carbon\Carbon::now()->subDays(5),
        ]);

        factory(Comment::class)->times(15)->create([
            'post_id' => $post->id,
            'created_at' => \Carbon\Carbon::now()->subDays(1),
        ]);

        $last_comment = factory(Comment::class)->create([
            'comment' => 'Este es el ultimo comentario',
            'post_id' => $post->id,
            'created_at' => \Carbon\Carbon::now()->now(),
        ]);

        $this->visit($post->url)
            ->see($last_comment->comment)
            ->dontSee($first_comment->comment)
            ->click('2')
            ->see($first_comment->comment)
            ->dontSee($last_comment->comment);
    }
}
