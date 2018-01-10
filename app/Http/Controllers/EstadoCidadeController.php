<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Exception\HttpResponseException;

class EstadoCidadeController extends BaseController{

    public function estados(Request $request) {
        $estados = new \App\Data\EstadoCidade();

        $res = $estados->getEstados();

        echo json_encode($res);
        exit;
    }

    public function cidades(Request $request) {
        $cidades = new \App\Data\EstadoCidade();

        $res = $cidades->getCidades($request->input('id'));

        echo json_encode($res);
        exit;
    }
}
