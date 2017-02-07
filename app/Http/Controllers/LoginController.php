<?php

namespace App\Http\Controllers;

use App\Token;

class LoginController extends Controller
{
    public function login($token)
    {
        $token = Token::findActive($token);

        if ($token == null) {
            alert('Este enlace ya expiró, por favor solicita otro', 'danger');

            return redirect()->route('token');
        }

        $token->login();

        return redirect('/');
    }
}
