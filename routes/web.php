<?php
/** @var Router $router */

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

use Laravel\Lumen\Routing\Router;

$router->get('/', function () use ($router) {
    return $router->app->version();
});
$router->get('/student-control', 'Teacher\MeetingController@studentControl');
$router->get('/send-mail','Auth\AuthController@sendMail');

//GUEST ROUTES HERE...


//USER REGISTRATION HERE...
$router->group(['prefix' => 'auth'], function () use ($router) {
    $router->post('/register', 'Auth\AuthController@register');
    $router->post('/login', 'Auth\AuthController@login');
});
//AUTHENTICATED ROUTES HERE ...
$router->group(['middleware' => 'auth'], function () use ($router) {
    //AUTHENTICATED ROUTES HERE

    $router->get('/user/get-user','Auth\AuthController@getUser');
    $router->post('/user/submit-edit/{role_type}','Auth\AuthController@saveUser');
    $router->post('/user/change-password','Auth\AuthController@changePassword');
    $router->group(['prefix' => 'profile'], function () use ($router) {
        $router->get('/', 'Teacher\ProfileController@getProfile');
    });

    //TEACHER AUTHENTICATED ROUTES HERE...
    $router->group(['middleware' => 'teacher', 'prefix' => 'teacher'], function () use ($router) {

        $router->group(['prefix' => 'courses'], function () use ($router) {
            $router->get('/', 'CoursesController@getAll');
            $router->post('/add', 'CoursesController@add');
            $router->put('/update', 'CoursesController@update');
            $router->put('/update', 'CoursesController@update');
            $router->get('/delete/{id}', 'CoursesController@delete');
            $router->post('/enrol', 'CoursesController@enrol');
            $router->get('/get-enrolled/{id}', 'CoursesController@getEnrolled');

        });

        $router->group(['prefix' => 'students'], function () use ($router) {
            $router->get('/', 'Teacher\StudentController@index');
            $router->post('/add', 'Teacher\StudentController@add');
            $router->get('/add-from-all/{id}', 'Teacher\StudentController@addFromAll');
            $router->get('/get-all', 'Teacher\StudentController@getAll');
            $router->put('/update', 'Teacher\StudentController@update');
            $router->get('/delete/{id}', 'Teacher\StudentController@delete');
            $router->get('/get/{id}', 'Teacher\StudentController@getStudent');
        });
        $router->group(['prefix' => 'meeting'], function () use ($router) {
            $router->get('/details/{code}', 'Teacher\MeetingController@getDetails');
            $router->get('/details/{meeting_id}/{student_id}', 'Teacher\MeetingController@getStudentActivity');

            $router->get('/start/{id}/{code}', 'Teacher\MeetingController@start');
            $router->get('/end/{code}', 'Teacher\MeetingController@end');
            $router->get('/get', 'Teacher\MeetingController@getMeetings');

        });


    });


    //STUDENT AUTHENTICATED ROUTES HERE...
    $router->group(['middleware' => 'student', 'prefix' => 'student'], function () use ($router) {
        $router->group(['prefix' => 'courses'], function () use ($router) {
            $router->get('/', 'Student\StudentController@getCourses');
        });
        $router->group(['prefix' => 'meeting'], function () use ($router) {
            $router->get('/activity/{meeting_code}/{activity}', 'Student\StudentController@recordActiviy');
        });
        $router->group(['prefix' => 'teachers'], function () use ($router) {
            $router->get('/', 'Student\StudentController@getTeacher');
        });
    });

});
