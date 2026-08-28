<?php
/* @var $this UsersController */
/* @var $model Users */

$this->breadcrumbs=array(
	'Users'=>array('index'),
	'Crear',
);

?>

<h1>Crear Users</h1>

<div class="container">
    <?php $this->renderPartial('_form', array('model'=>$model)); ?></div>


