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

    public function test_a_url_is_generated_and_visit_show_page()
    {
        // Having
        $user = $this->defaultUser();

        $post = factory(Post::class)->make([
            'title' => 'Como instalar Homestead'
        ]);

        $user->posts()->save($post);

        // When
        $this->visit($post->url);

        // Then
        $this->seePageIs("posts/{$post->id}-{$post->slug}")
            ->seeInElement('h1', $post->title)
            ->seeInElement('p', $post->content);
    }
}
