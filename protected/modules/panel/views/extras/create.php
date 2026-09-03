<?php
/* @var $this ExtrasController */
/* @var $model MenuItems */

$this->breadcrumbs=array(
	'Menu Items'=>array('index'),
	'Crear',
);

?>

<h1>Crear MenuItems</h1>

<div class="container">
    <?php $this->renderPartial('_form', array('model'=>$model)); ?></div>


