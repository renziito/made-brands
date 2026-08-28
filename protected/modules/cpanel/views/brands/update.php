<?php
/* @var $this BrandsController */
/* @var $model Brands */

$this->breadcrumbs=array(
	'Brands'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Actualizar',
);
?>
<h1>Actualizar Brands <?php echo $model->id; ?></h1>
<div class="container">
    <?php $this->renderPartial('_form', array('model'=>$model)); ?></div>