<?php

use App\Comment;
use Carbon\Carbon;
use tests\FeatureTestCase;

class CommentListTest extends FeatureTestCase
{
    function test_the_user_can_see_the_comments_in_detail_post()
    {
        $comment = factory(Comment::class)->create();

        $this->actingAs($comment->post->user);

        $this->visit($comment->post->url)
            ->seeInElement('h4', 'Comentarios')
            ->see($comment->comment)
            ->see($comment->user->name);
    }

    function test_the_comment_posts_are_paginated()
    {
        /* Having */
        $post = $this->createPost([
            'title' => 'Desarrollo Guiado por Pruebas (Test-Driven Develpment, TDD)',
        ]);

        /* When */
        $first = factory(Comment::class)->create([
            'comment' => 'Mi primer Comentario...',
            'post_id' => $post->id,
            'created_at' => Carbon::now()->subDays(2),
        ]);

        factory(Comment::class)->times(15)->create([
            'post_id' => $post->id,
            'created_at' => Carbon::now()->subDay(),
        ]);

        $last = factory(Comment::class)->create([
            'comment' => 'Mi comentario más reciente...',
            'post_id' => $post->id,
            'created_at' => Carbon::now(),
        ]);

        $this->actingAs($post->user);

        /* Then */
        $this->visit($post->url)
            ->see($last->comment)
            ->dontSee($first->comment)
            ->click('2')
            ->see($first->comment)
            ->dontSee($last->comment);



    }
}
