<?php
use Laravel\Lumen\Routing\Router;

/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->group(['prefix' => 'api', 'middleware'=>['valid']], function () use ($router) {
    /*
     * Auth
     */
    $router->group(['prefix' => 'auth'], function () use ($router) {
        $router->post('/login', 'AuthenticationController@login');
        $router->post('/register', 'AuthenticationController@register');
        $router->get('/activate', 'AuthenticationController@activate');
        $router->post('/resend-activation', 'AuthenticationController@resendActivation');
        $router->post('/forgot-password', 'AuthenticationController@forgotPassword');
        $router->get('/reset-password', 'AuthenticationController@resetPassword');
    });

    /*
     * UserModel
     */
    $router->group(['prefix' => 'user', 'middleware'=>['auth']], function () use ($router) {
        $router->get('/me', 'UserController@authUser');
        $router->get('/{id}', 'UserController@show');
        $router->get('/', 'UserController@get');
        $router->post('/', 'UserController@create');
        $router->put('/{id}', 'UserController@update');
        $router->delete('/{id}', 'UserController@delete');

        /**
         * UserMeta
         */
        $router->group(['prefix' => '{userId}/user-meta'], function () use ($router) {
            $router->get('/', 'UserMetaController@show');
            $router->post('/', 'UserMetaController@create');
            $router->put('/', 'UserMetaController@update');
            $router->delete('/', 'UserMetaController@delete');
        });
    });
});