<?php
/* @var $this SocialController */
/* @var $model SocialLinks */

$this->breadcrumbs=array(
	'Social Links'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Actualizar',
);
?>
<h1>Actualizar SocialLinks <?php echo $model->id; ?></h1>
<div class="container">
    <?php $this->renderPartial('_form', array('model'=>$model)); ?></div>