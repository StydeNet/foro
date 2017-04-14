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
            'user_id' => UserFactory::id(),
            'category_id' => CategoryFactory::id(),
        ];
    }
}
