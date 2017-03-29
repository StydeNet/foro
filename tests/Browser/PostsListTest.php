<?php

namespace Tests\Browser;

use App\Category;
use App\Post;
use Carbon\Carbon;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PostsListTest extends DuskTestCase
{
    use DatabaseMigrations;

    function test_a_user_can_see_the_posts_list_and_go_to_the_details()
    {
        $post = $this->createPost([
            'title' => '¿Debo usar Laravel 5.3 o 5.1 LTS?'
        ]);

        $this->browse(function($browser) use($post){
            $browser->visit('/')
                ->assertSeeIn('h1', 'Posts')
                ->assertSee($post->title)
                ->clickLink($post->title)
                ->assertPathIs('/posts/1-debo-usar-laravel-53-o-51-lts');
        });
    }

    function test_a_user_can_see_posts_filtered_by_category()
    {
        $laravel = factory(Category::class)->create([
            'name' => 'Laravel', 'slug' => 'laravel'
        ]);

        $vue = factory(Category::class)->create([
            'name' => 'Vue.js', 'slug' => 'vue-js'
        ]);

        $laravelPost = factory(Post::class)->create([
            'title' => 'Post de Laravel',
            'category_id' => $laravel->id
        ]);

        $vuePost = factory(Post::class)->create([
            'title' => 'Post de Vue.js',
            'category_id' => $vue->id
        ]);

        $this->browse(function($browser) use($laravelPost, $vuePost){
           $browser->visit('/')
               ->assertSee($laravelPost->title)
               ->assertSee($vuePost->title)
               ->clickLink('Laravel')
               ->assertSeeIn('h1', 'Posts de Laravel')
               ->assertSee($laravelPost->title)
               ->assertDontSee($vuePost->title);
        });
    }

    function test_a_user_can_see_posts_filtered_by_status()
    {
        $pendingPost = factory(Post::class)->create([
            'title' => 'Post pendiente',
            'pending' => true,
        ]);

        $completedPost = factory(Post::class)->create([
            'title' => 'Post completado',
            'pending' => false,
        ]);

        $this->browse(function($browser) use($pendingPost, $completedPost){
            $browser->visit(route('posts.pending'))
                ->assertSee($pendingPost->title)
                ->assertDontSee($completedPost->title);

            $browser->visit(route('posts.completed'))
                ->assertSee($completedPost->title)
                ->assertDontSee($pendingPost->title);
        });
    }

    function test_a_user_can_see_posts_filtered_by_status_and_category()
    {
        $laravel = factory(Category::class)->create([
            'name' => 'Categoria de Laravel', 'slug' => 'laravel'
        ]);

        $vue = factory(Category::class)->create([
            'name' => 'Vue.js', 'slug' => 'vue-js'
        ]);

        $pendingLaravelPost = factory(Post::class)->create([
            'title' => 'Post pendiente de Laravel',
            'category_id' => $laravel->id,
            'pending' => true,
        ]);

        $pendingVuePost = factory(Post::class)->create([
            'title' => 'Post pendiente de Vue.js',
            'category_id' => $vue->id,
            'pending' => true,
        ]);

        $completedPost = factory(Post::class)->create([
            'title' => 'Post completado',
            'pending' => false,
        ]);

        $this->browse(function($browser) use($pendingLaravelPost, $pendingVuePost, $completedPost){
            $browser->visit(route('posts.index'))
                ->clickLink('Posts pendientes')
                ->clickLink('Categoria de Laravel')
                ->assertPathIs('/posts-pendientes/laravel')
                ->assertSee($pendingLaravelPost->title)
                ->assertDontSee($completedPost->title)
                ->assertDontSee($pendingVuePost->title);
        });
    }

    function test_the_posts_are_paginated()
    {
        // Having...
        $first = factory(Post::class)->create([
            'title' => 'Post más antiguo',
            'created_at' => Carbon::now()->subDays(2)
        ]);

        $posts = factory(Post::class)->times(15)->create([
            'created_at' => Carbon::now()->subDay()
        ]);

        $last = factory(Post::class)->create([
            'title' => 'Post más reciente',
            'created_at' => Carbon::now()
        ]);

        $this->browse(function($browser) use($last, $first){
            $browser->visit('/')
                ->assertSee($last->title)
                ->assertDontSee($first->title)
                ->clickLink('2')
                ->assertSee($first->title)
                ->assertDontSee($last->title);
        });
    }
}
