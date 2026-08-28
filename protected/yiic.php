<?php

// Project root
$root = dirname(__DIR__);

// Composer autoloader
$loader = require($root . '/vendor/autoload.php');

// Load .env
$dotenv = Dotenv\Dotenv::createImmutable($root);
$dotenv->load();

$yiic=dirname(__FILE__).'/../vendor/yiisoft/yii/framework/yiic.php';
$config=dirname(__FILE__).'/config/console.php';

require_once($yiic);
