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
            'email' => 'lucas@cartorioapp.com',
            'password' => app('hash')->make('123456'),
            'remember_token' => str_random(10)
        ]);

        DB::table('users')->insert([
            'id' => 2,
            'nome' => 'Bernardo Graciano',
            'email' => 'bernardo@cartorioapp.com',
            'password' => app('hash')->make('123456'),
            'remember_token' => str_random(10)
        ]);

        DB::statement("
            INSERT INTO `permissao` (`permissao_id`, `users_id`, `certidao`, `procuracao`, `testamento`, `usuarios`, `usuarios_add`, `usuarios_editar`, `usuarios_remover`, `relatorios`, `dashboard`)
            VALUES (1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);
        ");

        DB::statement("
            INSERT INTO `permissao` (`permissao_id`, `users_id`, `certidao`, `procuracao`, `testamento`, `usuarios`, `usuarios_add`, `usuarios_editar`, `usuarios_remover`, `relatorios`, `dashboard`)
            VALUES (2, 2, 1, 1, 1, 1, 1, 1, 1, 1, 1);
        ");
    }
}
