<?php
/* @var $this CategoriesController */
/* @var $model Categories */

$this->breadcrumbs=array(
	'Categories'=>array('index'),
	'Crear',
);

?>

<h1>Crear Categories</h1>

<div class="container">
    <?php $this->renderPartial('_form', array('model'=>$model)); ?></div>


