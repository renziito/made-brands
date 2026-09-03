<?php
/* @var $this BrandsController */
/* @var $model Brands */

$this->breadcrumbs=array(
	'Brands'=>array('index'),
	'Crear',
);

?>

<h1>Crear Brands</h1>

<div class="container">
    <?php $this->renderPartial('_form', array('model'=>$model)); ?></div>


