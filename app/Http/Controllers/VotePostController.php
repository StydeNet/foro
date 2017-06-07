<?php

namespace App\Http\Controllers;

use App\{Post, Vote};
use Illuminate\Http\Request;

class VotePostController extends Controller
{
    public function upvote(Post $post)
    {
        Vote::upvote($post);

        return [
            'new_score' => $post->score,
        ];
    }
}
