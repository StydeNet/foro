<?php

namespace Tests\Browser;

use App\Post;
use App\Category;
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

        $category = factory(Category::class)->create();

        $this->browse(function ($browser) use ($user, &$post, $category) {
            // Having
            $browser->loginAs($user)
                ->visitRoute('posts.create')
                ->type('title', $this->title)
                ->type('content', $this->content)
                ->select('category_id', (string) $category->id)
                ->press('Publicar');

            // Test the post was created
            $this->assertNotNull($post = Post::first());

            // Test a user is redirected to the posts details after creating it.
            $browser->assertPathIs("/posts/{$post->id}-esta-es-una-pregunta");
        });

        $this->assertSame($this->title, $post->title);

        $this->assertSame($this->content, $post->content);

        $this->assertTrue($post->pending);

        $this->assertSame($user->id, $post->user_id);

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
