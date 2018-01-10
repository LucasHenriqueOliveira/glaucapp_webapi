<?php

namespace App\Data;

use Illuminate\Support\Facades\DB;

class Pedido extends Utils {

    public function movimentar($pedido_id, $descricao, $alerta) {

        try {

            DB::beginTransaction();

			$pedido = $this->getStatus($pedido_id);
            switch ($descricao) {
                case 'Iniciar Análise':

                    if($pedido->status == 'Em análise') {
                        $res['error'] = true;
                        $res['message'] = 'Pedido já está com status em análise.';
                        return $res;
                    }

                    $this->logStatus($pedido_id, $pedido->status, 'Em análise', date('Y-m-d H:i:s'));
                    $this->atualizarStatus($pedido_id, 'Em análise');
                    $descricao = 'Análise iniciada';
                    break;
                case 'Documento pronto':

                    if($pedido->status == 'Pronto') {
                        $res['error'] = true;
                        $res['message'] = 'Pedido já está com status pronto.';
                        return $res;
                    }

                    $this->logStatus($pedido_id, $pedido->status, 'Pronto', date('Y-m-d H:i:s'));
                    $this->atualizarStatus($pedido_id, 'Pronto');
					if($pedido->tipo != 'Testamento') {
						$this->email($pedido->user_id, $pedido->tipo);
					}
                    break;
                case 'Realizar a entrega':

                    if($pedido->status == 'Entregue') {
                        $res['error'] = true;
                        $res['message'] = 'Pedido já está com status entregue.';
                        return $res;
                    }

                    $this->logStatus($pedido_id, $pedido->status, 'Entregue', date('Y-m-d H:i:s'));
                    $this->atualizarStatus($pedido_id, 'Entregue');
                    $descricao = 'Entrega realizada';
                    break;
            }

            $result = DB::select("SELECT (sequencia + 1) AS sequencia FROM `movimentacao`WHERE `pedido_id` = ? ORDER BY sequencia DESC LIMIT 1", [$pedido_id])[0];

            DB::insert('INSERT INTO `movimentacao` (pedido_id, user_id, data_hora, sequencia, descricao) VALUES (?, ?, ?, ?, ?)',
                       [$pedido_id, $this->getUserId()->id, date('Y-m-d H:i:s'), $result->sequencia, $descricao]);

            DB::commit();

			if($alerta) {
				$user = $this->getUser($pedido->user_id);
				$texto = '<br /> Prezado(a) '.$user->nome.',';
				$texto .= '<br /><br />O seu pedido de '.$pedido->tipo.' no '.getenv('nome_cartorio').' teve a seguinte movimentação:';
				$texto .= '<br /><br />- '. $descricao;
				$texto .= '<br /><br /> Att, <br />Cartório App';
				$texto .= '<br /><br /> <h5>Não responda a este email. Os emails enviados a este endereço não serão respondidos.</h5>';
				$this->sendEmail($user->email, 'Movimentação', $texto);
			}

            $pedido = $this->getPedido($pedido_id);
            $pedido->movimentacoes = $this->getMovimentacoes($pedido_id);

            return $pedido;

        } catch (\Exception $e) {
            DB::rollBack();

            $res['error'] = true;
            $res['message'] = 'Erro ao realizar a movimentação.';
            return $res;
        }
    }

	public function email($user_id, $tipo) {
		$user = $this->getUser($user_id);
		$texto = '<br /> Prezado(a) '.$user->nome.',';
		$texto .= '<br /><br />O seu pedido de '.$tipo.' está pronto no '.getenv('nome_cartorio').'!';
		$texto .= '<br /><br />Endereço: ';
		$texto .= '<br />'.getenv('endereco_cartorio');
		$texto .= '<br />'.getenv('cidade_cartorio');
		$texto .= '<br /> Telefone: '.getenv('telefone_cartorio');
		$texto .= '<br /> Atendimento de '.getenv('atendimento_cartorio');
		$texto .= '<br /><br /> Att, <br />Cartório App';
		$texto .= '<br /><br /> <h5>Não responda a este email. Os emails enviados a este endereço não serão respondidos.</h5>';
		$this->sendEmail($user->email, 'Pedido Pronto', $texto);
	}
}