<?php
/* @var $this LanguagesController */
/* @var $model Languages */

$this->breadcrumbs=array(
	'Languages'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Actualizar',
);
?>
<h1>Actualizar Languages <?php echo $model->id; ?></h1>
<div class="container">
    <?php $this->renderPartial('_form', array('model'=>$model)); ?></div>