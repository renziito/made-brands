<?php
return array(
	'basePath'       => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
	'name'           => 'made.brands',
	'theme' 		 => 'made',
	'language'       => 'es',
	'sourceLanguage' => 'en',
	'timeZone'       => 'America/Lima',
	'import'         => array(
		'application.models.*',
		'application.components.*',
		'application.extensions.*'
	),
	'modules'        => array(
		'gii' => array(
			'class'          => 'system.gii.GiiModule',
			'password'       => $_ENV['GII'],
			'ipFilters'      => array('127.0.0.1', '::1'),
			'generatorPaths' => [
				'ext.giiext'
			],
		),
		'panel',
	),
	'components' => array(
		'user' => array(
			'allowAutoLogin' => true,
			'loginUrl'       => array('admin/login'),
		),
		'urlManager' => array(
			'urlFormat'      => 'path',
			'showScriptName' => false,
			'rules' => array(
				'login' 									=> 'site/login',
				'logout' 									=> 'site/logout',
				'<controller:\w+>/<id:\d+>'                 => '<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'    => '<controller>/<action>',
				'<controller:\w+>/<action:\w+>'             => '<controller>/<action>',
			),
		),
		'db' => array(
			'connectionString' => 'mysql:host=' . $_ENV['DB_HOST']
				. ';port=' . $_ENV['DB_PORT']
				. ';dbname=' . $_ENV['DB_NAME'],
			'username' => $_ENV['DB_USER'],
			'password' => $_ENV['DB_PASSWORD'],
			'charset' => 'utf8',
			'tablePrefix' => '',
		),
		'errorHandler' => array(
			'errorAction' => YII_DEBUG ? null : 'site/error',
		),
	),

	'params' => array(
		'adminEmail' => 'webmaster@example.com',
		'googleFontsApiKey' => $_ENV['GOOGLEFONTAPIKEY']
	),
);
