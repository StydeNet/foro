<?php

use App\{Post, User};
use Illuminate\Database\Seeder;

class CommentTableSeeder extends Seeder
{
    public function run()
    {
        $users = User::select('id')->get();

        $posts = Post::select('id')->get();

        for ($i = 0; $i < 250; ++$i) {
            factory(\App\Comment::class)->create([
                'user_id' => $users->random()->id,
                'post_id' => $posts->random()->id,
            ]);
        }
    }
}
