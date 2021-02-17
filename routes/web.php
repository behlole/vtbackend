<?php
/** @var \Laravel\Lumen\Routing\Router $router */

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
$router->get('/', function () use ($router) {
    return $router->app->version();
});
//GUEST ROUTES HERE...


//USER REGISTRATION HERE...
$router->group(['prefix' => 'auth'], function () use ($router) {
    $router->post('/register', 'Auth\AuthController@register');
    $router->post('/login', 'Auth\AuthController@login');
});


//AUTHENTICATED ROUTES HERE ...
$router->group(['middleware' => 'auth'], function () use ($router) {
    //AUTHENTICATED ROUTES HERE
    $router->get('/checkAuth', function () {
        return "Auth Checked";
    });
    $router->group(['middleware' => 'teacher'], function () use ($router) {
        //TEACHER AUTHENTICATED ROUTES HERE...
    });

    $router->group(['middleware' => 'student'], function () use ($router) {
        //STUDENT AUTHENTICATED ROUTES HERE...
    });
});
