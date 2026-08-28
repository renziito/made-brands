<?php
/* @var $this HeroController */
/* @var $model HeroSlides */

$this->breadcrumbs=array(
	'Hero Slides'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Actualizar',
);
?>
<h1>Actualizar HeroSlides <?php echo $model->id; ?></h1>
<div class="container">
    <?php $this->renderPartial('_form', array('model'=>$model)); ?></div>