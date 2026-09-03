<?php
/* @var $this ExtrasController */
/* @var $model MenuItems */

$this->breadcrumbs=array(
	'Menu Items'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Actualizar',
);
?>
<h1>Actualizar MenuItems <?php echo $model->id; ?></h1>
<div class="container">
    <?php $this->renderPartial('_form', array('model'=>$model)); ?></div>