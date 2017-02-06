<?php

namespace App\Http\Controllers;

use App\{User, Token};
use Illuminate\Http\Request;

class TokenController extends Controller
{
    public function create()
    {
        return view('token.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|exists:users'
        ]);

        $user = User::where('email', $request->get('email'))->first();

        Token::generateFor($user)->sendByEmail();

        alert('Enviamos a tu email un enlace para que inicies sesión');

        return back();
    }
}
