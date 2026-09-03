<?php
/* @var $this HeroController */
/* @var $model HeroSlides */

$this->breadcrumbs = array(
	'Hero Slides' => array('index'),
	'Crear',
);

?>

<h1>Crear HeroSlides</h1>

<div class="container">
	<?php $this->renderPartial('_form', array('model' => $model, 'translation' => $translation,)); ?></div>