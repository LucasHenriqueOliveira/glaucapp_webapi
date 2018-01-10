<?php

namespace App\Data;

use Illuminate\Support\Facades\DB;

class Dashboard extends Utils {

    public function dashboard($params) {
        $res['error'] = false;

        $params['start'] = (new \DateTime($params['start']))->format('Y-m-d H:i:s');
        $params['end'] = (new \DateTime($params['end']))->format('Y-m-d 23:59:59');

        //setup queries
        $d = [
            'date1' => $params['start'],
            'date2' => $params['end']
        ];

        $estatisticas['qtd_certidoes'] = $estatisticas['qtd_procuracoes'] = $estatisticas['qtd_testamentos'] = 0;
        $result = $this->checkPermissão('dashboard');

        if($result) {

            $qtd = DB::select("SELECT count(*) AS qtd_certidoes FROM `pedido` WHERE `tipo` = 'Certidão' AND `data_hora` BETWEEN :date1 AND :date2", $d);
            $estatisticas['qtd_certidoes'] = $qtd[0]->qtd_certidoes;

            $qtd = DB::select("SELECT count(*) AS qtd_procuracoes FROM `pedido` WHERE `tipo` = 'Procuração' AND `data_hora` BETWEEN :date1 AND :date2", $d);
            $estatisticas['qtd_procuracoes'] = $qtd[0]->qtd_procuracoes;

            $qtd = DB::select("SELECT count(*) AS qtd_testamentos FROM `pedido` WHERE `tipo` = 'Testamento' AND `data_hora` BETWEEN :date1 AND :date2", $d);
            $estatisticas['qtd_testamentos'] = $qtd[0]->qtd_testamentos;
        }

        $estatisticas['total'] = $estatisticas['qtd_certidoes'] + $estatisticas['qtd_procuracoes'] + $estatisticas['qtd_testamentos'];

        $res['quantitativo'] = $estatisticas;

        // Certidões
        $result = $this->checkPermissão('certidao');
        $certidoes = '';
        if($result) {
            $certidoes = $this->getPedidosDashboard('Certidão');
            foreach ($certidoes as $pedido) {
                $pedido->movimentacoes = $this->getMovimentacoes($pedido->pedido_id);
            }
        }
        $res['certidoes'] = $certidoes;

        // Procurações
        $result = $this->checkPermissão('procuracao');
        $procuracoes = '';
        if($result) {
            $procuracoes = $this->getPedidosDashboard('Procuração');
            foreach ($procuracoes as $pedido) {
                $pedido->movimentacoes = $this->getMovimentacoes($pedido->pedido_id);
				$pedido->partes = $this->getPartes($pedido->pedido_id);
            }
        }
        $res['procuracoes'] = $procuracoes;

        // Testamentos
        $result = $this->checkPermissão('testamento');
		$testamentos = '';
        if($result) {
            $testamentos = $this->getPedidosDashboard('Testamento');
            foreach ($testamentos as $pedido) {
                $pedido->movimentacoes = $this->getMovimentacoes($pedido->pedido_id);
            }
        }
        $res['testamentos'] = $testamentos;

        return $res;
    }
}