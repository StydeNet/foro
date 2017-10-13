<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class WriteCommentTest extends FeatureTestCase
{
    function test_a_user_can_write_a_comment()
    {
        $post = $this->createPost();

        $user = $this->defaultUser();

        $this->actingAs($user)
            ->visit($post->url)
            ->type('Un comentario', 'comment')
            ->press('Publicar comentario');

        $this->seeInDatabase('comments', [
            'comment' => 'Un comentario',
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);

        $this->seePageIs($post->url);
    }

    /** @test */
    function test_can_see_a_comment_autor()
    {
        $post = $this->createPost();

        $user = $this->defaultUser();

        $partaker = factory(\App\User::class)->create([
            'name' => 'Duilio Palcios'
        ]);

        $comment = factory(\App\Comment::class)->create([
            'comment' => 'Este comentario es el mas genial',
            'post_id' => $post->id,
            'user_id' => $partaker->id,
        ]);

        $this->actingAs($user)
            ->visit($post->url)
            ->seeInElement('h1', $post->title)
            ->see($comment->comment)
            ->seeInElement('h4','Duilio Palcios');
    }
}
