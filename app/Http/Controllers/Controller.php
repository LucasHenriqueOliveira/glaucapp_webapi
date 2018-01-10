<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Exception\HttpResponseException;

class Controller extends BaseController{

    public function dashboard(Request $request) {
        $dashboard = new \App\Data\Dashboard();

        $res = $dashboard->dashboard([
            'start' => $request->input('start'),
            'end' => $request->input('end')
        ]);

        echo json_encode($res);
        exit;
    }

    public function movimentar(Request $request) {
        $pedido = new \App\Data\Pedido();

        $res = $pedido->movimentar($request->input('pedido_id'), $request->input('descricao'), $request->input('alerta'));

        echo json_encode($res);
        exit;
    }

    public function getFirma(Request $request) {
		$pedido = new \App\Data\Utils();

		$res = $pedido->getFirma($request->input('nome'), $request->input('cpf'));

		echo json_encode($res);
		exit;
	}

	public function historico(Request $request) {
		$pedidos = new \App\Data\Utils();

		$res = $pedidos->historico($request->input('user_id'));

		echo json_encode($res);
		exit;
	}

	public function script(Request $request) {
		$pedidos = new \App\Data\Utils();

		$res = $pedidos->script();

		echo json_encode($res);
		exit;
	}
}
