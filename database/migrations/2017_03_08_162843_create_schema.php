<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        DB::statement("
            CREATE TABLE IF NOT EXISTS `users` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `nome` varchar(255) DEFAULT NULL,
              `email` varchar(100) NOT NULL,
              `password` text NOT NULL,
              `remember_token` varchar(100) DEFAULT NULL,
              `data_nascimento` DATETIME DEFAULT NULL,
              `profissao` varchar(100) DEFAULT NULL,
              `estado_civil` ENUM('Solteiro', 'Casado', 'Viúvo', 'Separado', 'Divorciado') DEFAULT NULL,
              `perfil` TINYINT(1) NULL DEFAULT 0,
              `telefone` varchar(20) DEFAULT NULL,
              `foto` varchar(100) DEFAULT NULL,
              `login_default` tinyint(1) NOT NULL DEFAULT '1',
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL,
              `ativo` TINYINT(1) NULL DEFAULT 1,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB;
        ");

        DB::statement("
            CREATE TABLE IF NOT EXISTS `log_session` (
              `log_session_id` INT NOT NULL AUTO_INCREMENT,
              `user_id` INT NULL,
              `ip` VARCHAR(45) NULL,
              `proxy` VARCHAR(45) NULL,
              `data_hora` DATETIME NULL,
              PRIMARY KEY (`log_session_id`),
              INDEX `fk_log_session_user_idx` (`user_id` ASC),
              CONSTRAINT `fk_log_session_user`
                FOREIGN KEY (`user_id`)
                REFERENCES `users` (`id`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION)
            ENGINE = InnoDB;
        ");

        DB::statement("
            CREATE TABLE IF NOT EXISTS `auth` (
              `auth_id` INT NOT NULL AUTO_INCREMENT,
              `user_id` INT NULL,
              `tipo` ENUM('facebook', 'google') NULL,
              `valor` VARCHAR(45) NULL,
              PRIMARY KEY (`auth_id`),
              INDEX `fk_auth_user_idx` (`user_id` ASC),
              CONSTRAINT `fk_auth_user`
                FOREIGN KEY (`user_id`)
                REFERENCES `users` (`id`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION)
            ENGINE = InnoDB;
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
        Schema::drop('log_session');
        Schema::drop('auth');
    }
}
