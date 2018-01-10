<?php

namespace App\Data;

use Illuminate\Support\Facades\DB;

class Procuracao extends Utils {

    public function getProcuracoes() {
        $result = $this->checkPermissão('procuracao');

        if($result) {
            $pedidos = $this->getPedidos('Procuração');

            foreach ($pedidos as $pedido) {
                $pedido->movimentacoes = $this->getMovimentacoes($pedido->pedido_id);
				$pedido->partes = $this->getPartes($pedido->pedido_id);
            }

            return $pedidos;

        } else {
            $res['error'] = true;
            $res['message'] = 'Usuário sem permissão.';
            return $res;
        }
    }

    public function getProcuracao($id) {
        $result = $this->checkPermissão('procuracao');

        if($result) {
            $pedido = $this->getPedido($id, 'Procuração');
            $pedido->movimentacoes = $this->getMovimentacoes($id);
			$pedido->partes = $this->getPartes($id);

            return $pedido;
        } else {
            $res['error'] = true;
            $res['message'] = 'Usuário sem permissão.';
            return $res;
        }
    }

    public function addProcuracao($tipo, $outorgantes, $outorgados, $docs, $date, $user_id) {

		try {

			$res = DB::insert('INSERT INTO `pedido` (`tipo`, `tipo_procuracao`, `data_hora`, `user_id`, `status`) VALUES (?, ?, ?, ?, ?)',
			['Procuração', $tipo['tipo_procuracao_id'], $date, $user_id, 'Aguardando']);

			$pedido_id = DB::getPdo()->lastInsertId();

			DB::insert('INSERT INTO `movimentacao` (`pedido_id`, `user_id`, `data_hora`, `sequencia`, `descricao`) VALUES (?, ?, ?, ?, ?)',
				[$pedido_id, $user_id, $date, 1, 'Solicitação de Procuração']);

			foreach ($outorgantes as $outorgante) {

				DB::insert('INSERT INTO `parte` (`tipo`, `nome`, `estado_civil`, `nacionalidade`, `profissao`, `cep`, `logradouro`, `numero`, `complemento`, `bairro`, `cidade`, `estado`, `data_hora`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
					['Outorgante', $outorgante['nome'], $outorgante['estado_civil'], $outorgante['nacionalidade'], $outorgante['profissao'], $outorgante['endereco']['cep'], $outorgante['endereco']['logradouro'], $outorgante['endereco']['numero'], $outorgante['endereco']['complemento'], $outorgante['endereco']['bairro'], $outorgante['endereco']['localidade'], $outorgante['endereco']['uf'], $date]);

				$parte_id = DB::getPdo()->lastInsertId();

				DB::insert('INSERT INTO `pedido_parte` (`pedido_id`, `parte_id`) VALUES (?, ?)', [$pedido_id, $parte_id]);

				foreach ($outorgante['documentos'] as $name => $file) {
					$path = $this->uploadBase64($file, $name, $pedido_id, $parte_id);
					if($path) {
						DB::update('UPDATE `parte` SET '.$name.' = ? WHERE `parte_id` = ?', [$path, $parte_id]);
					}
				}
			}

			foreach ($outorgados as $outorgado) {

				DB::insert('INSERT INTO `parte` (`tipo`, `nome`, `estado_civil`, `nacionalidade`, `profissao`, `cep`, `logradouro`, `numero`, `complemento`, `bairro`, `cidade`, `estado`, `data_hora`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
					['Outorgado', $outorgado['nome'], $outorgado['estado_civil'], $outorgado['nacionalidade'], $outorgado['profissao'], $outorgado['endereco']['cep'], $outorgado['endereco']['logradouro'], $outorgado['endereco']['numero'], $outorgado['endereco']['complemento'], $outorgado['endereco']['bairro'], $outorgado['endereco']['localidade'], $outorgado['endereco']['uf'], $date]);

				$parte_id = DB::getPdo()->lastInsertId();

				DB::insert('INSERT INTO `pedido_parte` (`pedido_id`, `parte_id`) VALUES (?, ?)', [$pedido_id, $parte_id]);

				foreach ($outorgado['documentos'] as $name => $file) {
					$path = $this->uploadBase64($file, $name, $pedido_id, $parte_id);
					if($path) {
						DB::update('UPDATE `parte` SET '.$name.' = ? WHERE `parte_id` = ?', [$path, $parte_id]);
					}
				}
			}

			foreach ($docs as $doc) {
				$path = $this->uploadBase64($doc['file'], $doc['name'], $pedido_id);
				if($path) {
					DB::update('UPDATE `pedido` SET '.$doc['name'].' = ? WHERE `pedido_id` = ?', [$path, $pedido_id]);
				}
			}

			return $res;

		} catch (\Exception $e) {
			$res['error'] = true;
			$res['message'] = 'Erro ao solicitar a Procuração';
			return $res;
		}
    }

	public function getTiposProcuracao() {
		return DB::select("SELECT * FROM `tipo_procuracao`");
	}

	public function getDocumentos($id) {
		$documentos = DB::select("SELECT * FROM `documento_tipo_procuracao` AS dt INNER JOIN `documento` AS d ON dt.`documento_id` = d.`documento_id` WHERE `tipo_procuracao_id` = ?", [$id]);

		$arr = array();
		foreach ($documentos as $documento) {
			if (!array_key_exists($documento->tipo, $arr)) {
				$arr[$documento->tipo]['documentos'] = [];
			}
			$arr[$documento->tipo]['icone'] = $documento->icone;
			array_push($arr[$documento->tipo]['documentos'],$documento);
		}

		return $arr;
	}

	public function getDocumentosProcuracao($id) {
		return DB::select("SELECT * FROM `documento_tipo_procuracao` AS dt INNER JOIN `documento` AS d ON dt.`documento_id` = d.`documento_id` WHERE `tipo_procuracao_id` = ?", [$id]);
	}

	public function email($user_id) {
		$user = $this->getUser($user_id);
		$texto = '<br /> Prezado(a) '.$user->nome.',';
		$texto .= '<br /><br />O seu pedido de procuração está confirmado no '.getenv('nome_cartorio').'!';
		$texto .= '<br /><br />Endereço: ';
		$texto .= '<br />'.getenv('endereco_cartorio');
		$texto .= '<br />'.getenv('cidade_cartorio');
		$texto .= '<br /> Telefone: '.getenv('telefone_cartorio');
		$texto .= '<br /> Atendimento de '.getenv('atendimento_cartorio');
		$texto .= '<br /><br /> Acompanhe o andamento do seu pedido pelo aplicativo. Você receberá um email quando o documento estiver pronto.';
		$texto .= '<br /><br /> Att, <br />Cartório App';
		$texto .= '<br /><br /> <h5>Não responda a este email. Os emails enviados a este endereço não serão respondidos.</h5>';
		$this->sendEmail($user->email, 'Solicitação de Procuração', $texto);
	}

	public function getDocumento($documento, $pedido_id, $parte_id) {
		if($parte_id) {
			$result = DB::select("SELECT $documento FROM `parte` WHERE `parte_id` = ?", [$parte_id])[0];
		} else {
			$result = DB::select("SELECT $documento FROM `pedido` WHERE `pedido_id` = ?", [$pedido_id])[0];
		}
		$documento = $result->{$documento};
		return $this->getUrl($documento);
	}

	public function logDocumento($documento, $pedido_id, $date) {
		return $this->addLogDocumento($documento, $pedido_id, $date);
	}
}