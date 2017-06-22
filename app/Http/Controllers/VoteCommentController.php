<?php

namespace App\Http\Controllers;

use App\Comment;

class VoteCommentController extends Controller
{
    public function upvote(Comment $comment)
    {
        $comment->upvote();

        return [
            'new_score' => $comment->score,
        ];
    }

    public function downvote(Comment $comment)
    {
        $comment->downvote();

        return [
            'new_score' => $comment->score,
        ];
    }

    public function undoVote(Comment $comment)
    {
        $comment->undoVote();

        return [
            'new_score' => $comment->score,
        ];
    }
}
