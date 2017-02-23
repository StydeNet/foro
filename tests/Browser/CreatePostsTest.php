<?php

namespace Tests\Browser;

use App\Post;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreatePostsTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected $title = 'Esta es una pregunta';
    protected $content = 'Este es el contenido';

    public function test_a_user_create_a_post()
    {
        $user = $this->defaultUser();

        $this->browse(function ($browser) use ($user) {
            // Having
            $browser->loginAs($user)
                ->visitRoute('posts.create')
                ->type('title', $this->title)
                ->type('content', $this->content)
                ->press('Publicar')
                // Test a user is redirected to the posts details after creating it.
                ->assertPathIs('/posts/1-esta-es-una-pregunta');
        });

        // Then
        $this->assertDatabaseHas('posts', [
            'title' => $this->title,
            'content' => $this->content,
            'pending' => true,
            'user_id' => $user->id,
            'slug' => 'esta-es-una-pregunta',
        ]);

        $post = Post::first();

        // Test the author is suscribed automatically to the post.
        $this->assertDatabaseHas('subscriptions', [
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);
    }

    function test_creating_a_post_requires_authentication()
    {
        $this->browse(function ($browser) {
            $browser->visitRoute('posts.create')
                ->assertRouteIs('token');
        });
    }

    function test_create_post_form_validation()
    {
        $this->browse(function ($browser) {
            $browser->loginAs($this->defaultUser())
                ->visitRoute('posts.create')
                ->press('Publicar')
                ->assertRouteIs('posts.create')
                ->assertSeeErrors([
                    'title' => 'El campo título es obligatorio',
                    'content' => 'El campo contenido es obligatorio'
                ]);
        });
    }
}
