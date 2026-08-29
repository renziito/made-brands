<?php
/* @var $this BusinessController */
/* @var $model Businesses */
/* @var $languages Languages[] */
/* @var $translations BusinessTranslations[] */
/* @var $translationsByLanguage BusinessTranslations[] */
/* @var $defaultLanguage Languages */
/* @var $created integer */

$this->breadcrumbs = array(
	'Businesses' => array('index'),
	'Editar',
);

$this->pageTitle = 'Editar business';

$this->renderPartial('_form', array(
	'model' => $model,
	'languages' => $languages,
	'translations' => $translations,
	'translationsByLanguage' => $translationsByLanguage,
	'defaultLanguage' => $defaultLanguage,
	'created' => $created,
));