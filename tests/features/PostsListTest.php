<?php

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

    function test_a_user_can_see_posts_paginated()
    {
        $post = $this->createPost([
            'title' => 'Crea una aplicación con Laravel 5.3'
        ]);

        factory(App\Post::class, 20)->create();

        $this->visit('/')
            ->see($post->title)
            ->click('2')
            ->dontSee($post->title);
    }
}
