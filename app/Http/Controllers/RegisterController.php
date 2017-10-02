<?php

namespace App\Http\Controllers;

use App\{User, Token};
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function create()
    {
        return view('register/create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|unique:users',
            'username' => 'required|unique:users',
            'first_name' => 'required',
            'last_name' => 'required'
        ]);

        $user = User::create($request->all());

        Token::generateFor($user)->sendByEmail();
    }
}
