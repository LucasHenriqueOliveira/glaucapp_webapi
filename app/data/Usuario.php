<?php

namespace App\Data;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Mailgun\Mailgun;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\User;

class Usuario extends Utils {

    public function getUsuarios() {
        $result = $this->checkPermissão('usuarios');

        if($result) {
            return DB::select("SELECT u.`nome`, u.`email`, p.*  FROM `users` AS u INNER JOIN `permissao` AS p ON u.`id` = p.`user_id` WHERE u.`ativo` = 1");

        } else {
            $res['error'] = true;
            $res['message'] = 'Usuário sem permissão.';
            return $res;
        }
    }

    public function getUsuario($id) {
        $result = $this->checkPermissão('usuarios');

        if($result) {
            return DB::select("SELECT u.`nome`, u.`email`, p.*  FROM `users` AS u INNER JOIN `permissao` AS p ON u.`id` = p.`user_id` WHERE u.`id` = ?", [$id])[0];

        } else {
            $res['error'] = true;
            $res['message'] = 'Usuário sem permissão.';
            return $res;
        }
    }

    public function addUsuario($nome, $email, $password, $remember_token, $date, $certidao, $procuracao, $testamento, $usuarios, $usuarios_add, $usuarios_editar, $usuarios_remover, $relatorios, $dashboard) {

        $result = $this->checkPermissão('usuarios_add');

        if($result) {
            try {
                $response = DB::select("SELECT * FROM `users` WHERE `email` = ? AND `ativo` = ?", [$email, 1])[0];

                if ($response) {
					if($response->app) {
						DB::update('UPDATE `users` SET `app` = ? WHERE `users_id` = ?', [0, $response->users_id]);
						$res['error'] = false;
						$res['message'] = 'Usuário adicionado com sucesso!';
						return $res;
					}
                    $res['error'] = true;
                    $res['message'] = 'Usuário ' . $email . ' já cadastrado.';
                    return $res;
                }

                $response = DB::select("SELECT * FROM `users` WHERE `email` = ? AND `ativo` = ?", [$email, 0]);

                if ($response) {

                    DB::update('UPDATE `users` AS u INNER JOIN `permissao` AS p ON u.id = p.user_id
                        SET u.`nome` = ?, u.`password` = ?, u.`remember_token` = ?, u.`ativo` = ?, u.`updated_at` = ?, p.`certidao` = ?, p.`procuracao` = ?, p.`testamento` = ?, p.`usuarios` = ?, p.`usuarios_add` = ?, p.`usuarios_editar` = ?, p.`usuarios_remover` = ?, p.`relatorios` = ?, p.`dashboard` = ? WHERE u.`email` = ?',
                    [$nome, $password, $remember_token, 1, $date, $certidao, $procuracao, $testamento, $usuarios, $usuarios_add, $usuarios_editar, $usuarios_remover, $relatorios, $dashboard, $email]);

                } else {

                    DB::insert('INSERT INTO `users` (`nome`, `email`, `password`, `remember_token`, `created_at`, `app`) VALUES (?, ?, ?, ?, ?, ?)',
                    [$nome, $email, $password, $remember_token, $date, 0]);

                    $last_id = DB::getPdo()->lastInsertId();

                    DB::insert('INSERT INTO `permissao` (`user_id`, `certidao`, `procuracao`, `testamento`, `usuarios`, `usuarios_add`, `usuarios_editar`, `usuarios_remover`, `relatorios`, `dashboard`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                    [$last_id, $certidao, $procuracao, $testamento , $usuarios, $usuarios_add, $usuarios_editar, $usuarios_remover, $relatorios, $dashboard]);

                }
            } catch (\Exception $e) {
                $res['error'] = true;
                $res['message'] = 'Erro ao adicionar o usuário';
                return $res;
            }

            $res['error'] = false;
            $res['message'] = 'Usuário adicionado com sucesso!';
            return $res;

        } else {
            $res['error'] = true;
            $res['message'] = 'Usuário sem permissão.';
            return $res;
        }
    }

	public function addUsuarioApp($nome, $email, $cpf, $telefone, $senha, $token, $date) {

		try {
			$response = DB::select("SELECT * FROM `users` WHERE `email` = ?", [$email]);

			if ($response) {
				$res['error'] = true;
				$res['message'] = 'Usuário ' . $email . ' já cadastrado.';
				return $res;
			}

			DB::insert('INSERT INTO `users` (`nome`, `email`, `telefone`, `cpf`, `password`, `remember_token`, `created_at`, `login_default`, `app`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)',
				[$nome, $email, $telefone, $cpf, $senha, $token, $date, 0, 1]);

		} catch (\Exception $e) {
			$res['error'] = true;
			$res['message'] = 'Erro ao cadastrar o usuário';
			return $res;
		}

		$res['error'] = false;
		return $res;
	}

    public function editarUsuario($users_id, $nome, $email, $date, $certidao, $procuracao, $testamento, $usuarios, $usuarios_add, $usuarios_editar, $usuarios_remover, $relatorios, $dashboard) {

        $result = $this->checkPermissão('usuarios_editar');

        if($result) {
            try {
                DB::beginTransaction();

                $result = DB::select("SELECT * FROM `permissao` WHERE `user_id` = ?", [$users_id])[0];

                if($result) {
                    DB::insert('INSERT INTO `log_permissao` (`permissao_id`,`users_id_responsavel`, `certidao`, `procuracao`, `testamento`, `usuarios`, `usuarios_add`, `usuarios_editar`, `usuarios_remover`, `relatorios`, `dashboard`, `data_hora`, `ip`, `proxy`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                    [$result->permissao_id, $this->getUserId()->id, $result->certidao, $result->procuracao, $result->testamento , $result->usuarios, $result->usuarios_add, $result->usuarios_editar, $result->usuarios_remover, $result->relatorios, $result->dashboard, $date, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_X_FORWARDED_FOR']]);
                }

                DB::update('UPDATE `users` AS u INNER JOIN `permissao` AS p ON u.id = p.user_id
                    SET u.`nome` = ?, u.`email` = ?, u.`updated_at` = ?, p.`certidao` = ?, p.`procuracao` = ?, p.`testamento` = ?, p.`usuarios` = ?, p.`usuarios_add` = ?, p.`usuarios_editar` = ?, p.`usuarios_remover` = ?, p.`relatorios` = ?, p.`dashboard` = ? WHERE u.`id` = ?',
                [$nome, $email, $date, $certidao, $procuracao, $testamento, $usuarios, $usuarios_add, $usuarios_editar, $usuarios_remover, $relatorios, $dashboard, $users_id]);

                DB::commit();

            } catch (\Exception $e) {
                DB::rollBack();

                $res['error'] = true;
                $res['message'] = 'Erro ao editar o usuário';
                return $res;
            }

            $res['error'] = false;
            $res['message'] = 'Usuário alterado com sucesso!';
            return $res;

        } else {
            $res['error'] = true;
            $res['message'] = 'Usuário sem permissão.';
            return $res;
        }
    }

    public function removerUsuario($id, $date) {
        $result = $this->checkPermissão('usuarios_remover');

        if($result) {
            return DB::update('UPDATE users SET `ativo` = ?, `updated_at` = ? WHERE id = ?', [0, $date, $id]);
        } else {
            $res['error'] = true;
            $res['message'] = 'Usuário sem permissão.';
            return $res;
        }
    }

    public function logSession($id) {
        return DB::insert('INSERT INTO `log_session` (`user_id`, `ip`, `proxy`, `data_hora`) VALUES (?, ?, ?, ?)',
        [$id, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_X_FORWARDED_FOR'], date('Y-m-d H:i:s')]);
    }

	public function email($nome, $email) {
		$texto = '<br /> Prezado(a) ' . $nome . ',';
		$texto .= '<br /><br />O seu cadastro no '.getenv("nome_cartorio").' - Cartório App foi realizado com sucesso!';
		$texto .= '<br /><br /> Att, <br />Cartório App';
		$texto .= '<br /><br /> <h5>Não responda a este email. Os emails enviados a este endereço não serão respondidos.</h5>';
		$this->sendEmail($email, 'Cadastro de Usuário', $texto);
	}

	public function checkUsuarioSocial($id, $email, $tipo) {
		return DB::select("SELECT * FROM `users` AS p INNER JOIN `auth` AS a ON p.`id` = a.`user_id` WHERE p.`email` = ? AND a.`tipo` = ? AND a.`valor` = ? LIMIT 1", [$email, $tipo, $id]);
	}

	public function signupUsuarioSocial($id, $nome, $email, $tipo, $telefone, $cpf, $remember_token, $date) {

		$result = DB::select("SELECT * FROM `users` WHERE `email` = ?", [$email])[0];

		if($result) {
			DB::insert('INSERT INTO `auth` (`user_id`, `tipo`, `valor`) VALUES (?, ?, ?)', [$result->id, $tipo, $id]);
		} else {
			DB::insert('INSERT INTO `users` (`nome`, `email`, `created_at`, `remember_token`, `cpf`, `telefone`, `app`, `login_default`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)',
				[$nome, $email, $date, $remember_token, $cpf, $telefone, 1, 0]);

			$user_id = DB::getPdo()->lastInsertId();

			DB::insert('INSERT INTO `auth` (`user_id`, `tipo`, `valor`) VALUES (?, ?, ?)', [$user_id, $tipo, $id]);
		}
	}

	public function resetPasswordUsuario($email) {

		$result = DB::select("SELECT * FROM `users` WHERE `email` = ?", [$email])[0];

		if($result) {
			$password = str_random(6);
			$hash_password = Hash::make(stripslashes($password));
			$remember_token = str_random(10);

			$texto = '<br /> Prezado(a) ' . $result->nome . ',';
			$texto .= '<br /><br />Conforme solicitado, segue sua senha de acesso provisória para o '.getenv("nome_cartorio").' - Cartório App.';
			$texto .= '<br /><br />Senha: '.$password;
			$texto .= '<br /><br /> Att, <br />Cartório App';
			$texto .= '<br /><br /> <h5>Não responda a este email. Os emails enviados a este endereço não serão respondidos.</h5>';

			$mg = Mailgun::create(getenv("MAILGUN_KEY"));

			$mg->messages()->send(getenv("MAILGUN_DOMAIN"), [
				'from' => "CartorioApp <postmaster@cartorioapp.com>",
				'to'      => $email,
				'subject' => 'Esqueceu a senha',
				'html'    => $texto
			]);

			DB::update('UPDATE users SET `login_default` = ?, `password` = ?, remember_token = ? WHERE id = ?', [1, $hash_password, $remember_token, $result->id]);

			$res['error'] = false;
			return $res;
		} else {
			$res['error'] = true;
			$res['message'] = 'Email não encontrado.';
			return $res;
		}
	}

	public function changePassword($password, $new_password) {
		$user = JWTAuth::parseToken()->toUser();

		if(Hash::check($password, $user->password)) {
			$obj_user = User::find($user->id);
			$obj_user->password = Hash::make(stripslashes($new_password));
			$obj_user->login_default = 0;
			$obj_user->save();

			$res['error'] = false;
			$res['message'] = 'Senha alterada com sucesso.';
			return $res;

		} else {
			$res['error'] = true;
			$res['message'] = 'Senha atual está incorreta.';
			return $res;
		}
	}
}