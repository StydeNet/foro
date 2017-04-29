<?php

namespace App\Http\Controllers;

use App\{Post, Comment};
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $this->validate($request, [
            'comment' => 'required'
        ]);

        auth()->user()->comment($post, $request->get('comment'));

        return redirect($post->url);
    }

    public function accept(Comment $comment)
    {
        $comment->markAsAnswer();

        return redirect($comment->post->url);
    }
}
