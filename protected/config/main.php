<?php
return array(
	'basePath'       => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
	'name'           => 'Made.Brands',
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
			'password'       => 'password',
			'ipFilters'      => array('127.0.0.1', '::1'),
			'generatorPaths' => [
				'ext.giiext'
			],
		),
		'admin',
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
				'<controller:\w+>/<id:\d+>'                 => '<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'    => '<controller>/<action>',
				'<controller:\w+>/<action:\w+>'             => '<controller>/<action>',
			),
		),
		'db' => require(dirname(__FILE__) . '/database.php'),
		'errorHandler' => array(
			'errorAction' => YII_DEBUG ? null : 'site/error',
		),
	),

	'params' => array(
		'adminEmail' => 'webmaster@example.com',
	),
);
