<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class PostIntegrationTest extends TestCase
{
    use DatabaseTransactions;

    function test_a_slug_is_generated_and_saved_to_the_database()
    {
        $post = $this->createPost([
            'title' => 'Como instalar Laravel',
        ]);

        $this->assertSame(
            'como-instalar-laravel',
            $post->fresh()->slug
        );

        /*
                $this->seeInDatabase('posts', [
                    'slug' => 'como-instalar-laravel'
                ]);

                $this->assertSame('como-instalar-laravel', $post->slug);
        */
    }
    
    //ejercicio unidad 21
    function test_comments_are_shown_and_paginated_in_post()
    {
        //having
        $post = factory(Post::class)->create([
            'title' => 'Test paginación comentarios',
        ]);

        $comments = factory(Comment::class)->times(20)->create([
            'post_id' => $post->id,
        ]);

        $firstComment = $comments->first();
        $lastComment = $comments->last();

        //when
        $this->visit( $post->url )
            //then
            ->seeInElement('h1', 'Test paginación comentarios' )
            ->seeInElement('.contenido', $post->content )
            ->seeInElement('.author', $firstComment->user->name )
            ->seeInElement('article', $firstComment->comment );

        $this->press('2')
            ->seePageIs( $post->url )
            ->seeInElement('article', $lastComment->comment );
    }
    
}
