<?php

use App\Category;
use App\Post;
use Carbon\Carbon;

class PostsListTest extends FeatureTestCase
{
    function test_a_user_can_see_the_posts_list_and_go_to_the_details()
    {
        $post = $this->createPost([
            'title' => '¿Debo usar Laravel 5.3 o 5.1 LTS?'
        ]);

        $this->visit('/')
            ->seeInElement('h1', 'Posts')
            ->see($post->title)
            ->click($post->title)
            ->seePageIs($post->url);
    }

    function test_a_user_can_see_posts_filtered_by_category()
    {
        $laravel = CategoryFactory::create([
            'name' => 'Laravel', 'slug' => 'laravel'
        ]);

        $vue = CategoryFactory::create([
            'name' => 'Vue.js', 'slug' => 'vue-js'
        ]);

        $laravelPost = PostFactory::create([
            'title' => 'Post de Laravel',
            'category_id' => $laravel->id
        ]);

        $vuePost = PostFactory::create([
            'title' => 'Post de Vue.js',
            'category_id' => $vue->id
        ]);

        $this->visit('/')
            ->see($laravelPost->title)
            ->see($vuePost->title)
            ->within('.categories', function () {
                $this->click('Laravel');
            })
            ->seeInElement('h1', 'Posts de Laravel')
            ->see($laravelPost->title)
            ->dontSee($vuePost->title);
    }

    function test_a_user_can_see_its_own_posts()
    {
        $user = $this->defaultUser();

        $userPost = $this->createPost([
            'title' => 'Post del usuario',
            'user_id' => $user->id,
        ]);

        $anotherUserPost = $this->createPost([
            'title' => 'Post del otro usuario'
        ]);

        $this->actingAs($user)
            ->visitRoute('posts.index')
            ->click('Mis posts')
            ->see($userPost->title)
            ->dontSee($anotherUserPost->title);
    }

    function test_a_user_can_see_posts_filtered_by_status()
    {
        $pendingPost = PostFactory::states('pending')->create([
            'title' => 'Post pendiente',
        ]);

        $completedPost = PostFactory::states('completed')->create([
            'title' => 'Post completado'
        ]);

        $this->visitRoute('posts.pending')
            ->see($pendingPost->title)
            ->dontSee($completedPost->title);

        $this->visitRoute('posts.completed')
            ->see($completedPost->title)
            ->dontSee($pendingPost->title);
    }

    function test_a_user_can_see_posts_filtered_by_status_and_category()
    {
        $laravel = CategoryFactory::create([
            'name' => 'Categoria de Laravel', 'slug' => 'laravel'
        ]);

        $vue = CategoryFactory::create([
            'name' => 'Vue.js', 'slug' => 'vue-js'
        ]);

        $pendingLaravelPost = PostFactory::create([
            'title' => 'Post pendiente de Laravel',
            'category_id' => $laravel->id,
            'pending' => true,
        ]);

        $pendingVuePost = PostFactory::create([
            'title' => 'Post pendiente de Vue.js',
            'category_id' => $vue->id,
            'pending' => true,
        ]);

        $completedPost = PostFactory::create([
            'title' => 'Post completado',
            'pending' => false,
        ]);

        $this->visitRoute('posts.index')
            ->click('Posts pendientes')
            ->click('Categoria de Laravel')
            ->seePageIs('posts-pendientes/laravel')
            ->see($pendingLaravelPost->title)
            ->dontSee($completedPost->title)
            ->dontSee($pendingVuePost->title);
    }

    function test_the_posts_are_paginated()
    {
        $first = PostFactory::create([
            'title' => 'Post más antiguo',
            'created_at' => Carbon::now()->subDays(2)
        ]);

        $posts = PostFactory::times(15)->create([
            'created_at' => Carbon::now()->subDay()
        ]);

        $last = PostFactory::create([
            'title' => 'Post más reciente',
            'created_at' => Carbon::now()
        ]);

        $this->visit('/')
            ->see($last->title)
            ->dontSee($first->title)
            ->click('2')
            ->see($first->title)
            ->dontSee($last->title);
    }
}
