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
