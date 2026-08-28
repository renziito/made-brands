<?php
/* @var $this ProductsController */
/* @var $model Products */

$this->breadcrumbs=array(
	'Products'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Actualizar',
);
?>
<h1>Actualizar Products <?php echo $model->id; ?></h1>
<div class="container">
    <?php $this->renderPartial('_form', array('model'=>$model)); ?></div>