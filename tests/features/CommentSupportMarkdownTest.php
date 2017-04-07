<?php

use App\Comment;

class CommentSupportMarkdownTest extends FeatureTestCase
{
    function test_the_post_comments_support_markdown()
    {
        $italicText = "texto en cursivas";

        $comment = factory(Comment::class)->create([
        	'comment' => "Este es un comentario con _{$italicText}_"
        ]);


        $this->visit($comment->post->url)
        	->seeInElement('em', $italicText);
    }

    function test_the_comment_its_printed_secure()
    {
    	$script = "<script>alert('Hacked')</script>";

    	$comment = factory(Comment::class)->create([
    		'comment' => $script
    	]);

    	$this->visit($comment->post->url)
    		->dontSee($script);
    }
}
