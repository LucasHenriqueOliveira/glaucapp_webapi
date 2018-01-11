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