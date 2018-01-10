<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

error_reporting(E_ALL & ~E_NOTICE);

$api = $app->make(Dingo\Api\Routing\Router::class);


$api->version('v1', function ($api) {

    // certidoes
    $api->get('/certidoes', [
        'uses' => 'App\Http\Controllers\CertidaoController@getCertidoes',
        'as' => 'api.certidoes'
    ]);

    // certidao
    $api->get('/certidao', [
        'uses' => 'App\Http\Controllers\CertidaoController@getCertidao',
        'as' => 'api.certidao'
    ]);

    $api->post('/certidao', [
        'uses' => 'App\Http\Controllers\CertidaoController@addCertidao',
        'as' => 'api.certidao'
    ]);

    $api->delete('/certidao', [
        'uses' => 'App\Http\Controllers\CertidaoController@removeCertidao',
        'as' => 'api.certidao'
    ]);

    // procuracoes
    $api->get('/procuracoes', [
        'uses' => 'App\Http\Controllers\ProcuracaoController@getProcuracoes',
        'as' => 'api.procuracoes'
    ]);

    // procuracao
    $api->get('/procuracao', [
        'uses' => 'App\Http\Controllers\ProcuracaoController@getProcuracao',
        'as' => 'api.procuracao'
    ]);

    $api->post('/procuracao', [
        'uses' => 'App\Http\Controllers\ProcuracaoController@addProcuracao',
        'as' => 'api.procuracao'
    ]);

    $api->delete('/procuracao', [
        'uses' => 'App\Http\Controllers\ProcuracaoController@removeProcuracao',
        'as' => 'api.procuracao'
    ]);

	$api->get('/procuracao/documento', [
		'uses' => 'App\Http\Controllers\ProcuracaoController@getDocumento',
		'as' => 'api.documento.procuracao'
	]);

	$api->get('/procuracao/tipos', [
		'uses' => 'App\Http\Controllers\ProcuracaoController@getTiposProcuracao',
		'as' => 'api.tipos.procuracao'
	]);

	$api->get('/procuracao/tipos/documentos', [
		'uses' => 'App\Http\Controllers\ProcuracaoController@getDocumentosProcuracao',
		'as' => 'api.documentos.procuracao'
	]);

    // testamentos
    $api->get('/testamentos', [
        'uses' => 'App\Http\Controllers\TestamentoController@getTestamentos',
        'as' => 'api.testamentos'
    ]);

    // testamento
    $api->get('/testamento', [
        'uses' => 'App\Http\Controllers\TestamentoController@getTestamento',
        'as' => 'api.testamento'
    ]);

    $api->post('/testamento', [
        'uses' => 'App\Http\Controllers\TestamentoController@addTestamento',
        'as' => 'api.testamento'
    ]);

    $api->delete('/testamento', [
        'uses' => 'App\Http\Controllers\TestamentoController@removeTestamento',
        'as' => 'api.testamento'
    ]);

	$api->get('/testamento/datas', [
		'uses' => 'App\Http\Controllers\TestamentoController@getDatasTestamento',
		'as' => 'api.datas.testamento'
	]);

	$api->post('/testamento/bloquear-agenda', [
		'uses' => 'App\Http\Controllers\TestamentoController@setBloquearAgenda',
		'as' => 'api.agenda.testamento'
	]);

    // movimentar
    $api->post('/movimentar', [
        'uses' => 'App\Http\Controllers\Controller@movimentar',
        'as' => 'api.movimentar'
    ]);

	// histórico
	$api->get('/historico', [
		'uses' => 'App\Http\Controllers\Controller@historico',
		'as' => 'api.historico'
	]);

    // firma
    $api->get('/firma', [
        'uses' => 'App\Http\Controllers\Controller@getFirma',
        'as' => 'api.firma'
    ]);

    // usuarios
    $api->get('/usuarios', [
        'uses' => 'App\Http\Controllers\UsuarioController@getUsuarios',
        'as' => 'api.usuarios'
    ]);

    // usuario
    $api->get('/usuario', [
        'uses' => 'App\Http\Controllers\UsuarioController@getUsuario',
        'as' => 'api.usuario'
    ]);

    $api->post('/usuario', [
        'uses' => 'App\Http\Controllers\UsuarioController@addUsuario',
        'as' => 'api.usuario'
    ]);

	$api->post('/usuario/reset', [
		'uses' => 'App\Http\Controllers\UsuarioController@resetPasswordUsuario',
		'as' => 'api.reset.usuario'
	]);

	$api->put('/usuario', [
		'uses' => 'App\Http\Controllers\UsuarioController@editarUsuario',
		'as' => 'api.usuario'
	]);

	$api->delete('/usuario', [
		'uses' => 'App\Http\Controllers\UsuarioController@removerUsuario',
		'as' => 'api.usuario'
	]);

	// signup
	$api->post('/signup', [
		'uses' => 'App\Http\Controllers\UsuarioController@addUsuarioApp',
		'as' => 'api.usuario'
	]);

	$api->post('/check-social', [
		'uses' => 'App\Http\Controllers\UsuarioController@checkUsuarioSocial',
		'as' => 'api.check.usuario'
	]);

	$api->post('/signup-social', [
		'uses' => 'App\Http\Controllers\UsuarioController@signupUsuarioSocial',
		'as' => 'api.signup.usuario'
	]);

    $api->post('/troca-senha', [
        'uses' => 'App\Http\Controllers\UsuarioController@changePassword',
        'as' => 'api.usuario.troca.senha'
    ]);


    $api->get('/estados', [
        'uses' => 'App\Http\Controllers\EstadoCidadeController@estados',
        'as' => 'api.estados'
    ]);

    $api->get('/cidades', [
        'uses' => 'App\Http\Controllers\EstadoCidadeController@cidades',
        'as' => 'api.cidades'
    ]);

    // dashboard
    $api->get('/dashboard', [
        'uses' => 'App\Http\Controllers\Controller@dashboard',
        'as' => 'api.dashboard'
    ]);

    // authentication user
    $api->post('/auth/login', [
        'as' => 'api.auth.login',
        'uses' => 'App\Http\Controllers\Auth\AuthController@postLogin',
    ]);

    $api->get('/auth/login', function () {
        die('/auth/login');
    });

	// script
	$api->get('/dev/script', [
		'uses' => 'App\Http\Controllers\Controller@script',
		'as' => 'api.script'
	]);

    $api->group([
        'middleware' => 'api.auth',
    ], function ($api) {
        $api->get('/', [
            'uses' => 'App\Http\Controllers\APIController@getIndex',
            'as' => 'api.index'
        ]);
        $api->get('/auth/user', [
            'uses' => 'App\Http\Controllers\Auth\AuthController@getUser',
            'as' => 'api.auth.user'
        ]);
        $api->patch('/auth/refresh', [
            'uses' => 'App\Http\Controllers\Auth\AuthController@patchRefresh',
            'as' => 'api.auth.refresh'
        ]);
        $api->delete('/auth/invalidate', [
            'uses' => 'App\Http\Controllers\Auth\AuthController@deleteInvalidate',
            'as' => 'api.auth.invalidate'
        ]);
    });
});