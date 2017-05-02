<?php

use App\User;

class UserFactory extends Styde\Factory\Factory
{
    protected $model = User::class;

    public function data()
    {
        return [
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'username' => $this->unique()->userName,
            'email' => $this->unique()->safeEmail,
            'remember_token' => str_random(10),
        ];
    }
}
