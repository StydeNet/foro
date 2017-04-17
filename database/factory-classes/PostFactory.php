<?php

class PostFactory extends Factory
{
    public $model = \App\Post::class;

    public function data()
    {
        return [
            'title' => $this->sentence,
            'content' => $this->paragraph,
            'pending' => true,
            'user_id' => UserFactory::lazy()->create(),
            'category_id' => CategoryFactory::lazy()->create(),
        ];
    }
}
