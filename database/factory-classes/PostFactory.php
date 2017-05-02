<?php

use App\Post;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function data()
    {
        return [
            'title' => $this->sentence,
            'content' => $this->paragraph,
            'pending' => true,
            'user_id' => function () {
                return UserFactory::create();
            },
            'category_id' => function () {
                return CategoryFactory::create();
            }
        ];
    }

    public function statePending()
    {
        return [
            'pending' => true
        ];
    }

    public function stateCompleted()
    {
        return [
            'pending' => false
        ];
    }
}
