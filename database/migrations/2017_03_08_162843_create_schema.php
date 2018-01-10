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
              `login_default` tinyint(1) NOT NULL DEFAULT '1',
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL,
              `ativo` TINYINT(1) NULL DEFAULT 1,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB;
        ");

        DB::statement("
            CREATE TABLE IF NOT EXISTS `permissao` (
              `permissao_id` INT NOT NULL AUTO_INCREMENT,
              `users_id` INT NULL,
              `certidao` TINYINT(1) NULL DEFAULT '0',
              `procuracao` TINYINT(1) NULL DEFAULT '0',
              `testamento` TINYINT(1) NULL DEFAULT '0',
              `usuarios` TINYINT(1) NULL DEFAULT '0',
              `usuarios_add` TINYINT(1) NULL DEFAULT '0',
              `usuarios_editar` TINYINT(1) NULL DEFAULT '0',
              `usuarios_remover` TINYINT(1) NULL DEFAULT '0',
              `relatorios` TINYINT(1) NULL DEFAULT '0',
              `dashboard` TINYINT(1) NULL DEFAULT '0',
              PRIMARY KEY (`permissao_id`),
              INDEX `fk_permissao_users_idx` (`users_id` ASC),
              CONSTRAINT `fk_permissao_users`
                FOREIGN KEY (`users_id`)
                REFERENCES `users` (`id`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION)
            ENGINE = InnoDB;
        ");

        DB::statement("
            CREATE TABLE IF NOT EXISTS `log_permissao` (
              `log_permissao_id` INT NOT NULL AUTO_INCREMENT,
              `certidao` TINYINT(1) NULL,
              `procuracao` TINYINT(1) NULL,
              `testamento` TINYINT(1) NULL,
              `usuarios` TINYINT(1) NULL,
              `usuarios_add` TINYINT(1) NULL,
              `usuarios_editar` TINYINT(1) NULL,
              `usuarios_remover` TINYINT(1) NULL,
              `relatorios` TINYINT(1) NULL,
              `dashboard` TINYINT(1) NULL,
              `data_hora` DATETIME NULL,
              `users_id_responsavel` INT NULL,
              `permissao_id` INT NULL,
              `ip` VARCHAR(45) NULL,
              `proxy` VARCHAR(45) NULL,
              PRIMARY KEY (`log_permissao_id`),
              INDEX `fk_log_permissao_users_idx` (`users_id_responsavel` ASC),
              CONSTRAINT `fk_log_permissao_users`
                FOREIGN KEY (`users_id_responsavel`)
                REFERENCES `users` (`id`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION)
            ENGINE = InnoDB;
        ");

        DB::statement("
            CREATE TABLE IF NOT EXISTS `cliente` (
              `cliente_id` INT NOT NULL AUTO_INCREMENT,
              `nome` VARCHAR(100) NULL,
              `cpf` VARCHAR(45) NULL,
              `email` VARCHAR(45) NULL,
              `telefone` VARCHAR(45) NULL,
              `senha` VARCHAR(100) NULL,
              `data_hora` DATETIME NULL,
              PRIMARY KEY (`cliente_id`))
            ENGINE = InnoDB;
        ");

        DB::statement("
            CREATE TABLE IF NOT EXISTS `pedido` (
              `pedido_id` INT NOT NULL AUTO_INCREMENT,
              `tipo` ENUM('Certidão', 'Procuração', 'Testamento') NULL,
              `ato` ENUM('Procuração', 'Escritura', 'Outro') NULL,
              `livro` VARCHAR(45) NULL,
              `folha` VARCHAR(45) NULL,
              `outorgante` VARCHAR(100) NULL,
              `outorgado` VARCHAR(100) NULL,
              `data_hora` DATETIME NULL,
              `cliente_id` INT NULL,
              `status` ENUM('Aguardando', 'Em análise', 'Pronto', 'Entregue', 'Cancelado') NULL,
              `rg` VARCHAR(45) NULL,
              `cpf` VARCHAR(45) NULL,
              `casamento` VARCHAR(45) NULL,
              PRIMARY KEY (`pedido_id`),
              INDEX `fk_certidao_cliente_idx` (`cliente_id` ASC),
              CONSTRAINT `fk_certidao_cliente`
                FOREIGN KEY (`cliente_id`)
                REFERENCES `cliente` (`cliente_id`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION)
            ENGINE = InnoDB;
        ");

        DB::statement("
            CREATE TABLE IF NOT EXISTS `movimentacao` (
              `movimentacao_id` INT NOT NULL AUTO_INCREMENT,
              `pedido_id` INT NULL,
              `cliente_id` INT NULL,
              `data_hora` DATETIME NULL,
              `sequencia` INT NULL,
              `descricao` VARCHAR(255) NULL,
              PRIMARY KEY (`movimentacao_id`),
              INDEX `fk_movimentacao_pedido_idx` (`pedido_id` ASC),
              INDEX `fk_movimentacao_user_idx` (`user_id` ASC),
              CONSTRAINT `fk_movimentacao_pedido`
                FOREIGN KEY (`pedido_id`)
                REFERENCES `pedido` (`pedido_id`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION,
              CONSTRAINT `fk_movimentacao_users`
                FOREIGN KEY (`user_id`)
                REFERENCES `users` (`id`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION)
            ENGINE = InnoDB;
        ");

        DB::statement("
            CREATE TABLE IF NOT EXISTS `log_status` (
              `log_status_id` INT NOT NULL AUTO_INCREMENT,
              `pedido_id` INT NULL,
              `data_hora` DATETIME NULL,
              `status_antigo` ENUM('Aguardando', 'Em análise', 'Pronto', 'Entregue', 'Cancelado') NULL,
              `status_novo` ENUM('Aguardando', 'Em análise', 'Pronto', 'Entregue', 'Cancelado') NULL,
              `user_id` INT NULL,
              `ip` VARCHAR(45) NULL,
              `proxy` VARCHAR(45) NULL,
              PRIMARY KEY (`log_status_id`),
              INDEX `fk_log_status_user_idx` (`user_id` ASC),
              CONSTRAINT `fk_log_status_user`
                FOREIGN KEY (`user_id`)
                REFERENCES `users` (`id`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION)
            ENGINE = InnoDB;
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
              `cliente_id` INT NULL,
              `tipo` ENUM('facebook', 'google') NULL,
              `valor` VARCHAR(45) NULL,
              PRIMARY KEY (`auth_id`),
              INDEX `fk_auth_cliente_idx` (`cliente_id` ASC),
              CONSTRAINT `fk_auth_cliente`
                FOREIGN KEY (`cliente_id`)
                REFERENCES `cliente` (`cliente_id`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION)
            ENGINE = InnoDB;
        ");

        DB::statement("
            CREATE TABLE IF NOT EXISTS `firma` (
              `firma_id` INT NOT NULL AUTO_INCREMENT,
              `nome` VARCHAR(100) NULL,
              `cpf` VARCHAR(45) NULL,
              `data_hora` DATETIME NULL,
              PRIMARY KEY (`firma_id`))
            ENGINE = InnoDB;
        ");

        DB::statement("
            CREATE OR REPLACE VIEW `vw_firma` AS SELECT `nome`,`cpf` FROM `firma`;
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
        Schema::drop('permissao');
        Schema::drop('log_permissao');
        Schema::drop('cliente');
        Schema::drop('pedido');
        Schema::drop('movimentacao');
        Schema::drop('log_status');
        Schema::drop('log_session');
        Schema::drop('auth');
        Schema::drop('firma');
        Schema::drop('vw_firma');
    }
}
