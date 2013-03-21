<?php

Session::start(Config::get('application.session.driver'));

// --------------------------------------------------------------
// Load default configuration.
// --------------------------------------------------------------
Config::load('application');
Config::load('database');

// --------------------------------------------------------------
// Set the default time zone.
// --------------------------------------------------------------
date_default_timezone_set(Config::get('application.timezone'));

// --------------------------------------------------------------
// PHP display errors configuration.
// --------------------------------------------------------------
ini_set('display_errors', Config::get('application.errors.display'));
ini_set('error_reporting', Config::get('application.errors.reporting'));