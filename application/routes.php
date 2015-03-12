<?php

use Alice\Routing\Router;

Router::get('index', 'indexController@index', 'home');
Router::get('users/list', 'testController@showUsers');
Router::get('users/list/{id}', 'testController@showUserA');
Router::get('users/{id}', 'testController@showUserB');
Router::get('users/{param1}/{param2}', 'testController@showUserC');
Router::get('users/{param1}/{param2}/{param3}', 'testController@showUserD');

Router::post('testpost/{id,hello}', 'testController@showPost');
Router::post('testpost/{id,username}', 'testController@showPostA');

Router::get('database', 'testController@database');
