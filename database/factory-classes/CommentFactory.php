<?php

use App\Comment;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function data()
    {
        return [
            'comment' => $this->paragraph,
            'post_id' => function () {
                return PostFactory::create();
            },
            'user_id' => function () {
                return UserFactory::create();
            }
        ];
    }
}
