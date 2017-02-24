<?php

use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\User::class)->create([
            'first_name' => 'Duilio',
            'last_name' => 'Palacios',
            'username' => 'silence',
            'email' => 'duilio@styde.net',
            'role' => 'admin',
        ]);
    }
}
