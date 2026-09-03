<?php
/* @var $this FormsController */
/* @var $model FaqForms */

$this->breadcrumbs=array(
	'Faq Forms'=>array('index'),
	'Crear',
);

?>

<h1>Crear FaqForms</h1>

<div class="container">
    <?php $this->renderPartial('_form', array('model'=>$model)); ?></div>


