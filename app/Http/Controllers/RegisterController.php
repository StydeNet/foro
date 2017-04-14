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
        $this->validate($request, [
            'email' => 'required|email|unique:users,email',
            'username' => 'required',
            'first_name' => 'required',
            'last_name' =>'required'
        ], [
            'unique' => 'Este correo electrónico ya existe.',
            'email' => 'Dirección de correo inválida.'
        ]);

        $user = User::create($request->all());

        Token::generateFor($user)->sendByEmail();

        return redirect()->route('register_confirmation');
    }

    public function confirmationMessage()
    {
        return view('register.confirm_message');
    }
}
