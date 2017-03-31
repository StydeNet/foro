<?php

use App\Comment;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CommentListTest extends FeatureTestCase
{
    function test_the_comments_are_paginated()
    {
    	$last = factory(Comment::class)->create([
    		'comment' => 'comentario más antiguo', 
    		'created_at' => Carbon::now()->subDays(2),
		]);

		factory(Comment::class)->times(15)->create([
			'created_at' => Carbon::now()->subDay(), 
			'post_id' => $last->post->id
		]);

		$first = factory(Comment::class)->create([
			'comment' => 'comentario reciente',
			'post_id' => $last->post->id
		]);

		$this->visit($last->post->url)
		     ->see($first->comment)
		     ->dontSee($last->comment)
		     ->click(2)
		     ->see($last->comment)
		     ->dontSee($first->comment);
    }

    function test_write_the_author_comment()
    {
    	$comment = factory(Comment::class)->create();

    	$this->visit($comment->post->url)
    		->seeInElement('.comment-author', $comment->user->name);
    }
}
