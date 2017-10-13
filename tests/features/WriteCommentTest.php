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
    function test_user_cant_not_send_empty_comment()
    {
        $post  = $this->createPost();
        $user = $this->defaultUser();

        $this->actingAs($user)
            ->visit($post->url)
            ->type('', 'comment')
            ->press('Publicar comentario')
            ->seePageIs($post->url)
            ->see('El campo comentario es obligatorio.');
    }
}
