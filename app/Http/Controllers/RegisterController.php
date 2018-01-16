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

    public function store(Request $req)
    {
        $this->validate( $req,[
            'username' => 'required',
            'email' => 'required|email',
            'first_name' => 'required',
            'last_name' => 'required'
        ]);

        $user = User::create($req->all());

        Token::generateFor($user)->sendByEmail();

        return redirect( 'register-confirmation' );
    }

    public function confirmation(){
        return view('register/confirmation');
    }
}
