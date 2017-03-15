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

    function test_getUrlAttribute_is_returning_the_correct_url()
    {
        //Having ... a post stored in DB
        $user = $this->defaultUser();
        $post = factory(\App\Post::class)->make();
        $user->posts()->save($post);

        //When ... getUrlAttribute accesor is called by $post->url
        //Then ... it must return the correct url of the post
        $this->assertSame(
            $post->url,
            route('posts.show', [$post->id, $post->slug])
        );
    }
}
