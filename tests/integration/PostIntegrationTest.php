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

    function test_the_url_of_the_post_is_generated()
    {
        $user = $this->defaultUser();
        $post = factory(Post::class)->make();
        $user->posts()->save($post);

        $this->assertSame(
            $post->url,
            route('posts.show', [$post->id, $post->slug])
        );
    }

    /** @test */
    function test_a_post_url_is_generated_correctly()
    {
        $post = factory(Post::class)->create();

        $this->assertSame($post->url , env('APP_URL').'/posts/'.$post->id.'-'.$post->slug);
    }
}
