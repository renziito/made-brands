<?php
/* @var $this FormsController */
/* @var $model FaqForms */

$this->breadcrumbs=array(
	'Faq Forms'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Actualizar',
);
?>
<h1>Actualizar FaqForms <?php echo $model->id; ?></h1>
<div class="container">
    <?php $this->renderPartial('_form', array('model'=>$model)); ?></div>