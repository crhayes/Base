<?php

// --------------------------------------------------------------
// Create aliases for ease of use.
// --------------------------------------------------------------
define('DS', DIRECTORY_SEPARATOR);
define('EXT', '.php');

// --------------------------------------------------------------
// Define useful app constants.
// --------------------------------------------------------------
define('DOC_ROOT', realpath(dirname(__FILE__)).DS);
define('SYS_PATH', DOC_ROOT.'system'.DS);
define('APP_PATH', DOC_ROOT.'application'.DS);
define('BASE_PATH', substr(DOC_ROOT, strlen($_SERVER['DOCUMENT_ROOT'])));

// --------------------------------------------------------------
// Load the autoloader and helper functions.
// --------------------------------------------------------------
require SYS_PATH.'autoload'.EXT;
require SYS_PATH.'helpers'.EXT;

// --------------------------------------------------------------
// Bootstrap the application.
// --------------------------------------------------------------
require APP_PATH.'bootstrap'.EXT;

// --------------------------------------------------------------
// Create a new request, route it, and get the response.
// --------------------------------------------------------------
$request = new Request();
$response = $request->route();

// --------------------------------------------------------------
// Appropriately handle the response.
// --------------------------------------------------------------
if ($response instanceOf Response) {
	$response->send();
} else {
	echo $response;
}

// --------------------------------------------------------------
// Clean up any flashed session data.
// --------------------------------------------------------------
Session::sweep();
