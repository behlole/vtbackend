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







    //TEACHER AUTHENTICATED ROUTES HERE...
    $router->group(['middleware' => 'teacher','prefix'=>'teacher'], function () use ($router) {
        $router->group(['prefix'=>'course'], function() use ($router){
            $router->get('/','CoursesController@getAll');
            $router->post('/add','CoursesController@add');
            $router->put('/update','CoursesController@update');
            $router->delete('/delete','CoursesController@delete');

            $router->post('/enrol','CoursesController@enrol');
        });

        $router->group(['prefix'=>'students'],function () use ($router)
        {
            $router->get('/','Teacher\StudentController@index');
            $router->post('/add','Teacher\StudentController@add');
            $router->post('/update','Teacher\StudentController@update');
            $router->delete('/delete/{id}','Teacher\StudentController@delete');
        });

    });

















    //STUDENT AUTHENTICATED ROUTES HERE...
    $router->group(['middleware' => 'student','prefix'=>'student'], function () use ($router) {
        $router->Get('/checkStudent', function () {
            return "Student Checked";
        });
    });
});
