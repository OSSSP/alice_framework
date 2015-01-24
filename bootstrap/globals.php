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

$GLOBALS['INVALID_ROUTE_HANDLER_CODE'] = 3;
$GLOBALS['INVALID_ROUTE_HANDLER_MESSAGE'] = 'Routing Error: Controller or Method not recognized.';
