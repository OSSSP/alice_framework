<?php

use Alice\Routing\Router;

Router::get('index', 'indexController@method', 'home');
Router::get('users/list', 'UserController@showUsers');
Router::get('users/list/{id}', 'UserController@showUserA');
Router::get('users/{id}', 'UserController@showUserB');
Router::get('users/{param1}/{param2}', 'UserController@showUserC');
Router::get('users/{param1}/{param2}/{param3}', 'UserController@showUserD');

Router::post('testpost/{id,hello}', 'testController@showPost');
Router::post('testpost/{id,username}', 'testController@showPost1');
