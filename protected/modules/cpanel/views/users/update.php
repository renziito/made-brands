<?php
/* @var $this UsersController */
/* @var $model Users */

$this->breadcrumbs=array(
	'Users'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Actualizar',
);
?>
<h1>Actualizar Users <?php echo $model->id; ?></h1>
<div class="container">
    <?php $this->renderPartial('_form', array('model'=>$model)); ?></div>