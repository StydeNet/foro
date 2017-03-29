<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ShowPostTest extends DuskTestCase
{
    use DatabaseMigrations;

    function test_a_user_can_see_the_post_details()
    {
        // Having
        $user = $this->defaultUser([
            'first_name' => 'Duilio',
            'last_name' => 'Palacios',
        ]);

        $post = $this->createPost([
            'title' => 'Este es el titulo del post',
            'content' => 'Este es el contenido del post',
            'user_id' => $user->id,
        ]);

        // When
        $this->browse(function($browser) use($post){
            $browser->visit($post->url)
                ->assertSeeIn('h1', $post->title)
                ->assertSee($post->content)
                ->assertSee('Duilio Palacios');
        });
    }

    function test_old_urls_are_redirected()
    {
        // Having
        $post = $this->createPost([
            'title' => 'Old title',
        ]);

        $url = $post->url;

        $post->update(['title' => 'New title']);

        $this->browse(function($browser) use($post, $url){
           $browser->visit($url)
               ->assertPathIs('/posts/1-new-title');
        });
    }
}
