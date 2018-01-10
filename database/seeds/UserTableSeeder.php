<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'id' => 1,
            'nome' => 'Lucas Henrique',
            'email' => 'lucashen@gmail.com',
            'password' => app('hash')->make('123456'),
            'remember_token' => str_random(10)
        ]);
    }
}
