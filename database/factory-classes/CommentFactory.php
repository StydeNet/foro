<?php

class CommentFactory extends Factory
{
    public $model = \App\Comment::class;

    public function data()
    {
        return [
            'comment' => $this->paragraph,
            'post_id' => PostFactory::lazy()->create(),
            'user_id' => UserFactory::lazy()->create(),
        ];
    }
}
