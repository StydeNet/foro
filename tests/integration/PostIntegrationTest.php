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

    function test_the_generation_of_the_post_url()
    {
        $user = $this->defaultUser();

        $post = factory(Post::class)->make();

        $user->posts()->save($post);

        $this->assertSame(
            $post->url,
            route('posts.show', [$post->id, $post->slug])
        );
    }
}
