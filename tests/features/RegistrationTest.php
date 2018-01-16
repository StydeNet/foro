<?php

use App\{User, Token};
use App\Mail\TokenMail;
use Illuminate\Support\Facades\Mail;

class RegistrationTest extends FeatureTestCase
{
    function test_a_user_can_create_an_account()
    {
        Mail::fake();

        $this->visitRoute('register')
            ->type('admin@styde.net', 'email')
            ->type('silence', 'username')
            ->type('Duilio', 'first_name')
            ->type('Palacios', 'last_name')
            ->press('Regístrate');

        $this->seeInDatabase('users', [
            'email' => 'admin@styde.net',
            'username' => 'silence',
            'first_name' => 'Duilio',
            'last_name' => 'Palacios',
        ]);

        $user = User::first();

        $this->seeInDatabase('tokens', [
            'user_id' => $user->id
        ]);

        $token = Token::where('user_id', $user->id)->first();

        $this->assertNotNull($token);

        Mail::assertSentTo($user, TokenMail::class, function ($mail) use ($token) {
            return $mail->token->id == $token->id;
        });

        //todo: finish this feature!
        return;

        $this->seeRouteIs('register_confirmation')
            ->see('Gracias por registrarte')
            ->see('Enviamos a tu email un enlace para que inicies sesión');
    }
    
    // test de validación del formulario de registro

    function test_fill_all_inputs_of_register_form(){

        $this->visitRoute('register')
            ->press('Regístrate')
            ->seeErrors([
                'username' => 'El campo usuario es obligatorio',
                'email' => 'El campo correo electrónico es obligatorio',
                'first_name' => 'El campo nombre es obligatorio',
                'last_name' => 'El campo apellido es obligatorio'
        ]);
    }

    function test_email_have_correct_format(){
        $this->visitRoute('register')
            ->type("josefk@gmail", 'email')
            ->type('josefk', 'username' )
            ->type('Josep', 'first_name')
            ->type('Sellés', 'last_name')
            ->press('Regístrate')
            ->seeErrors([
                'email' => 'El correo electrónico no es un correo válido',
            ]);
    }
}






