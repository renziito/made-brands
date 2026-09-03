<?php
/* @var $this LanguagesController */
/* @var $model Languages */

$this->breadcrumbs=array(
	'Languages'=>array('index'),
	'Crear',
);

?>

<h1>Crear Languages</h1>

<div class="container">
    <?php $this->renderPartial('_form', array('model'=>$model)); ?></div>


