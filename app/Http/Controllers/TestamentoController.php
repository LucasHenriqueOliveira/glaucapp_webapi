<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Exception\HttpResponseException;

class TestamentoController extends BaseController{

    public function getTestamentos(Request $request) {
        $testamentos = new \App\Data\Testamento();

        $res = $testamentos->getTestamentos();

        echo json_encode($res);
        exit;
    }

    public function getTestamento(Request $request) {
        $testamento = new \App\Data\Testamento();

        $res = $testamento->getTestamento($request->input('id'));

        echo json_encode($res);
        exit;
    }

    public function addTestamento(Request $request) {
        $testamento = new \App\Data\Testamento();

        $res = $testamento->addTestamento($request->input('data'), $request->input('hora'), $request->input('user_id'));
		$testamento->email($request->input('user_id'), $request->input('data'), $request->input('hora'));
		$testamento->emailCartorio($request->input('data'), $request->input('hora'));

        echo json_encode($res);
        exit;
    }

	public function getDatasTestamento(Request $request) {
		$testamento = new \App\Data\Testamento();

		$res = $testamento->getDatasTestamento();

		echo json_encode($res);
		exit;
	}

	public function setBloquearAgenda(Request $request) {
		$testamento = new \App\Data\Testamento();

		$res = $testamento->setBloquearAgenda($request->input('data'), $request->input('horas'));

		echo json_encode($res);
		exit;
	}
}
