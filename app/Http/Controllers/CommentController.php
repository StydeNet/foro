<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        //todo: Add validation!
        $this->validateComment($request);

        auth()->user()->comment($post, $request->get('comment'));

        return redirect($post->url);
    }

    public function validateComment(Request $request)
    {
    	$this->validate($request, [
    		'comment' => 'required'
    	], [
    		'comment.required' => 'El comentario es requerido'
     	]);
    }
}
