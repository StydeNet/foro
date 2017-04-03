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
    		'email' => 'required|email|min:2|unique:users',
    		'username' => 'required|min:2|unique:users',
    		'first_name' => 'required|min:2',
    		'last_name' => 'required|min:2'
		]);

    	$user = User::create($request->all());

    	Token::generateFor($user)->sendByEmail();

    	alert('Enviamos a tu email un enlace para que inicies sesión');

    	return back();
    }
}
