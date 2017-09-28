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

        $this->see('Enviamos a tu email un enlace para que inicies sesión');
    }



   function test_a_user_can_not_create_an_account_with_invalid_email()
   {
		$this->visitRoute('register')
			->type('lnorambuena@', 'email')
			->type('lnorambuena', 'username')
			->type('Leonardo', 'first_name')
			->type('Norambuena', 'last_name')
			->press('Regístrate');

		$this->seeErrors([
			'email' => 'correo electrónico no es un correo válido'
		]);
   }

   function test_a_user_can_not_create_an_account_without_email()
   {
		$this->visitRoute('register')
			->type('', 'email')
			->type('lnorambuena', 'username')
			->type('Leonardo', 'first_name')
			->type('Norambuena', 'last_name')
			->press('Regístrate');

		$this->seeErrors([
			'email' => 'El campo correo electrónico es obligatorio'
		]);
   }

   function test_a_user_can_not_create_an_account_with_exist_email()
   {
   		$user = $this->defaultUser([
   			'email' => 'leonardo.norambuena@styde.net'
		]);
		$this->visitRoute('register')
			->type('leonardo.norambuena@styde.net', 'email')
			->type('lnorambuena', 'username')
			->type('Leonardo', 'first_name')
			->type('Norambuena', 'last_name')
			->press('Regístrate');

		$this->seeErrors([
			'email' => 'correo electrónico ya ha sido registrado'
		]);
   }

   function test_a_user_can_not_create_an_account_with_exist_username()
   {
   		$user = $this->defaultUser([
   			'username' => 'lnorambuena'
		]);
		$this->visitRoute('register')
			->type('leonardo.norambuena@styde.net', 'email')
			->type('lnorambuena', 'username')
			->type('Leonardo', 'first_name')
			->type('Norambuena', 'last_name')
			->press('Regístrate');

		$this->seeErrors([
			'username' => 'usuario ya ha sido registrado.'
		]);
   }

   function test_a_user_can_not_create_an_account_without_required_fields()
   {
		$this->visitRoute('register')
			->type('', 'email')
			->type('', 'username')
			->type('', 'first_name')
			->type('', 'last_name')
			->press('Regístrate');

		$this->seeErrors([
			'email' => 'El campo correo electrónico es obligatorio',
			'username' => 'El campo usuario es obligatorio',
			'first_name' => 'El campo nombre es obligatorio',
			'last_name' => 'El campo apellido es obligatorio',
		]);
   }

   function test_a_user_can_not_create_an_account_with_invalid_length_fields()
   {
		$this->visitRoute('register')
			->type('a', 'email')
			->type('a', 'username')
			->type('a', 'first_name')
			->type('a', 'last_name')
			->press('Regístrate');

		$this->seeErrors([
			'email' => 'El campo correo electrónico debe contener al menos 2 caracteres',
			'username' => 'El campo usuario debe contener al menos 2 caracteres',
			'first_name' => 'El campo nombre debe contener al menos 2 caracteres',
			'last_name' => 'El campo apellido debe contener al menos 2 caracteres',
		]);
   }

}






