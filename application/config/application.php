<?php

return array(
	'timezone'	=> 'America/Toronto',

	'upload' => array(
		'directory'	=> 'assets/uploads/'
	),

	'errors' => array(
		'display' 	=> true,
		'reporting' => E_ALL
	),

	'session' => array(
		'driver' 	=> 'native',
		'lifetime' 	=> 3600
	),

	'key' => 'PleaseReplaceWithSecretKey!'
);