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

$router->group(['prefix' => '/api/v1'], function () use ($router) { // добавляем префикс для API

    $router->post('/sign_up', 'UserController@register'); // методы для авторизации
    $router->post('/sign_in', 'UserController@login'); // методы для регистрации

    $router->get('/specialities', 'AdditionalController@getSpecialities'); // получение специальностей

    $router->group(['prefix' => '/doctors'], function () use ($router) { // префикс для докторов
        $router->get('/get_by_speciality/{id}', 'DoctorController@getBySpeciality'); // метод получения врачей по специальности
        $router->get('/{id}', 'DoctorController@getById'); // получить врача по ID
    });

    $router->group(['prefix' => '/schedules'], function ()use ($router){ // префикс для расписания
        $router->get('/time_intervals', 'SchedulesController@getTimeIntervals'); // получение временных интервалов
        $router->get('/get_by_doctor', 'SchedulesController@getByDoctorId'); // получение расписания по врачу
        $router->get('/get_available_dates', 'SchedulesController@getAvailableDates'); // получение доступных дат для записи
    });

    $router->group(['middleware' => 'auth'], function() use ($router){ // проверка на авторизованность пользователя

        $router->group(['middleware' => 'doctor', 'prefix' => '/doctor'], function () use ($router) { // проверка на доктора/префикс для доктора
            $router->get('/profile', 'DoctorController@getProfile'); //  получение профиля доктора
            $router->get('/get_schedule', 'DoctorController@getSchedule'); // получение расписания
            $router->post('/create_schedule', 'DoctorController@createSchedule'); // создание расписания
        });

        $router->group(['middleware' => 'patient'], function () use ($router){ // проверка на пациента/префикс пациента
            $router->group(['prefix' => '/patient'], function () use ($router) {
                $router->get('/profile', 'PatientController@getProfile'); // получение профиля пациента
            });
            $router->group(['prefix' => '/visits'], function () use ($router) {
                $router->post('/create', 'VisitsController@create'); // создание визита
            });
        });
    });
});

