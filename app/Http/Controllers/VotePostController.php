<?php

namespace App\Http\Controllers;

use App\{Post, Vote};

class VotePostController extends Controller
{
    public function upvote(Post $post)
    {
        $post->upvote();

        return [
            'new_score' => $post->score,
        ];
    }

    public function downvote(Post $post)
    {
        $post->downvote();

        return [
            'new_score' => $post->score,
        ];
    }

    public function undoVote(Post $post)
    {
        $post->undoVote();

        return [
            'new_score' => $post->score,
        ];
    }
}
