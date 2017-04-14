<?php

class UserFactory extends Factory
{
    public $model = \App\User::class;

    public function data()
    {
        return [
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'username' => $this->unique()->userName,
            'email' => $this->unique()->safeEmail,
            'remember_token' => $this->randomString(10),
        ];
    }
}
