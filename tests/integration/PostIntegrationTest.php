<?php

use App\Post;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PostIntegrationTest extends TestCase
{
    use DatabaseTransactions;

    function test_a_slug_is_generated_and_saved_to_the_database()
    {
        $user = $this->defaultUser();

        $post = factory(Post::class)->make([
            'title' => 'Como instalar Laravel',
        ]);

        $user->posts()->save($post);

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

    function test_actividad_leccion_14_comprobar_url_generada(){

        //having
        $post = $this->createPost([
            'title' => 'Actividad lección 14',
        ]);

        $id = $post->id;
        $slug = Str::slug($post->title);

        //when
        $url = $post->url;

        //then
        $this->assertSame( $url, route('posts.show', [$id, $slug]) );

    }
}
