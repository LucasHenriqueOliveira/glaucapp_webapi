<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Exception\HttpResponseException;

class CertidaoController extends BaseController{

    public function getCertidoes(Request $request) {
        $certidoes = new \App\Data\Certidao();

        $res = $certidoes->getCertidoes();

        echo json_encode($res);
        exit;
    }

    public function getCertidao(Request $request) {
        $certidao = new \App\Data\Certidao();

        $res = $certidao->getCertidao($request->input('id'));

        echo json_encode($res);
        exit;
    }

    public function addCertidao(Request $request) {
        $certidao = new \App\Data\Certidao();

        $res = $certidao->addCertidao($request->input('ato'), $request->input('livro'),
                                        $request->input('folha'), $request->input('outorgante'), $request->input('outorgado'),
                                        date('Y-m-d H:i:s'), $request->input('user_id'));
		$certidao->email($request->input('user_id'));

        echo json_encode($res);
        exit;
    }
}
