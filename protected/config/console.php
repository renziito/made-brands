<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
	'name' => 'My Console Application',

	// preloading 'log' component
	'preload' => array('log'),

	// application components
	'components' => array(

		'db' => array(
			'connectionString' => 'mysql:host=' . $_ENV['DB_HOST']
				. ';port=' . $_ENV['DB_PORT']
				. ';dbname=' . $_ENV['DB_NAME'],
			'username' => $_ENV['DB_USER'],
			'password' => $_ENV['DB_PASSWORD'],
			'charset' => 'utf8',
			'tablePrefix' => '',
		),

		'log' => array(
			'class' => 'CLogRouter',
			'routes' => array(
				array(
					'class' => 'CFileLogRoute',
					'levels' => 'error, warning',
				),
			),
		),

	),
);
