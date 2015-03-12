<?php

return array(
    'host' => '',
    'database' => '',
    'username' => '',
    'password' => '',
    'options' => array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'),
    'handle_with_controller' => true,
    'handle_controller' => 'errorController@handleDBError', // Format is Controller@Method
    'pass_query' => true,
    'log_exception' => false
);
