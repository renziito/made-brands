<?php
/* @var $this BusinessController */
/* @var $model Businesses */
/* @var $translation BusinessTranslations */
/* @var $defaultLanguage Languages */

$this->breadcrumbs = array(
	'Businesses' => array('index'),
	'Crear',
);

$this->pageTitle = 'Crear business';

$this->renderPartial('_form', array(
	'model' => $model,
	'translation' => $translation,
	'defaultLanguage' => $defaultLanguage,
));
