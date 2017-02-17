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
}






