<?php

// --------------------------------------------------------------
// Load default configuration.
// --------------------------------------------------------------
Config::load(array('application', 'database', 'session'));

// --------------------------------------------------------------
// Start the session with the config-defined driver.
// --------------------------------------------------------------
Session::start(Config::get('session.driver'));

// --------------------------------------------------------------
// Set some defaults for the application.
// --------------------------------------------------------------
date_default_timezone_set(Config::get('application.timezone'));
ini_set('display_errors', Config::get('application.errors.display'));
ini_set('error_reporting', Config::get('application.errors.reporting'));
