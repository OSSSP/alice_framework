<?php

/**
 * AliceException specific errors.
 */
$GLOBALS['EXCEPTION_METHOD_NOT_FOUND_CODE'] = 0;
$GLOBALS['EXCEPTION_METHOD_NOT_FOUND_MESSAGE'] = 'Exception Controller: The method specified to handle exception was not found. Please check your configuration.';

/**
 * Routing specific errors.
 */
$GLOBALS['ROUTE_ALREADY_EXISTS_CODE'] = 1;
$GLOBALS['ROUTE_ALREADY_EXISTS_MESSAGE'] = 'Routing Error: duplicate route entry.';

$GLOBALS['INVALID_GET_ROUTE_CODE'] = 2;
$GLOBALS['INVALID_GET_ROUTE_MESSAGE'] = 'Routing Error: GET route not recognized.';

$GLOBALS['INVALID_POST_ROUTE_CODE'] = 2;
$GLOBALS['INVALID_POST_ROUTE_MESSAGE'] = 'Routing Error: GET route not recognized.';

$GLOBALS['INVALID_ROUTE_HANDLER_CODE'] = 3;
$GLOBALS['INVALID_ROUTE_HANDLER_MESSAGE'] = 'Routing Error: Controller or Method not recognized.';

$GLOBALS['UNSUPPORTED_REQUEST_METHOD_CODE'] = 4;
$GLOBALS['UNSUPPORTED_REQUEST_METHOD_MESSAGE'] = 'Routing Error: Unsupported request method, currently only GET and POST are available.';

$GLOBALS['DISPATCH_METHOD_DOESNT_EXISTS_CODE'] = 5;
$GLOBALS['DISPATCH_METHOD_DOESNT_EXISTS_MESSAGE'] = 'Dispatching error: The specified Method doesn\'t exists';

$GLOBALS['NAMED_ROUTE_NOT_FOUND_CODE'] = 6;
$GLOBALS['NAMED_ROUTE_NOT_FOUND_MESSAGE'] = 'Routing Error: The specified named Route doesn\'t exists.';

$GLOBALS['404_METHOD_NOT_FOUND_CODE'] = 7;
$GLOBALS['404_METHOD_NOT_FOUND_MESSAGE'] = 'Routing Error: The method specified to handle 404 page was not found. Please check your configuration.';

/**
 * Database specific errors.
 */
$GLOBALS['DB_CONNECTION_ERROR_CODE'] = 8;
$GLOBALS['DB_CONNECTION_ERROR_MESSAGE'] = 'Database Error: An error occurred while attempting to connect to the database.';

$GLOBALS['DB_CONTROLLER_NOT_FOUND_CODE'] = 9;
$GLOBALS['DB_CONTROLLER_NOT_FOUND_MESSAGE'] = 'Database Error: The controller specified to handle DB error was not found. Please check your configuration.';

$GLOBALS['DB_METHOD_NOT_FOUND_CODE'] = 10;
$GLOBALS['DB_METHOD_NOT_FOUND_MESSAGE'] = 'Database Error: The method specified to handle DB error was not found. Please check your configuration.';

$GLOBALS['DB_SYNTAX_ERROR_CODE'] = 11;
$GLOBALS['DB_SYNTAX_ERROR_MESSAGE'] = 'Database Error: Syntax error or access violation.';

$GLOBALS['DB_PARAM_BIND_ERROR_CODE'] = 12;
$GLOBALS['DB_PARAM_BIND_ERROR_MESSAGE'] = 'Parameter Binding: An error occurred while binding parameters.';
