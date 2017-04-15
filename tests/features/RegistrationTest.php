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

        $this->seeRouteIs('register_confirmation')
            ->see('Gracias por registrarte')
            ->see('Enviamos a tu email un enlace para que inicies sesión');
    }

    function test_registration_fields_are_validated()
    {
        $this->visitRoute('register')
            ->type('vpwejvqpeovqv', 'email')
            ->press('Regístrate')
            ->seePageIs('register')
            ->seeErrors([
                'email' => 'Dirección de correo inválida',
                'username' => 'El campo usuario es obligatorio',
                'first_name' => 'El campo nombre es obligatorio',
                'last_name' => 'El campo apellido es obligatorio',
            ]);
    }

    function test_a_guest_can_not_register_the_same_email()
    {
        factory(User::class)->create([
            'email' => 'admin@styde.net'
        ]);

        $this->visitRoute('register')
            ->type('admin@styde.net', 'email')
            ->press('Regístrate')
            ->seePageIs('register')
            ->seeErrors([
                'email' => 'Este correo electrónico ya existe.'
            ]);
    }
}






