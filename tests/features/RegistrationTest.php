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

        Mail::assertSent(TokenMail::class, function ($mail) use ($token, $user) {
            return $mail->hasTo($user) && $mail->token->id == $token->id;
        });

        //todo: finish this feature!
        return;

        $this->seeRouteIs('register_confirmation')
            ->see('Gracias por registrarte')
            ->see('Enviamos a tu email un enlace para que inicies sesión');
    }

    function test_create_user_form_validation()
    {
        $this->visitRoute('register')
            ->press('Regístrate')
            ->seePageIs('/register')
            ->seeErrors([
                'email' => 'El campo correo electrónico es obligatorio',
                'username' => 'El campo usuario es obligatorio',
                'first_name' => 'El campo nombre es obligatorio',
                'last_name' => 'El campo apellido es obligatorio',
            ]);
    }

    function test_create_user_must_register_unique_email()
    {
        $this->anyone([
            'email' => 'admin@styde.net'
        ]);

        $this->visitRoute('register')
            ->type('admin@styde.net', 'email')
            ->type('johnDoe', 'username')
            ->type('John', 'first_name')
            ->type('Doe', 'last_name')
            ->press('Regístrate')
            ->seePageIs('/register')
            ->seeErrors([
                'email' => 'correo electrónico ya ha sido registrado.',
            ]);
    }

    function test_create_user_must_register_unique_username()
    {
        $this->anyone([
            'username' => 'silence'
        ]);

        $this->visitRoute('register')
            ->type('another@styde.net', 'email')
            ->type('silence', 'username')
            ->type('John', 'first_name')
            ->type('Doe', 'last_name')
            ->press('Regístrate')
            ->seePageIs('/register')
            ->seeErrors([
                'username' => 'usuario ya ha sido registrado.',
            ]);
    }
}
