<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class ProfileController extends Controller
{
    public function show(User $user)
    {
        $posts = $user->posts()->paginate();

        return view('users.show')->with(compact('user', 'posts'));
    }
}
