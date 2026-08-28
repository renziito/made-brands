<?php
/* @var $this ProductsController */
/* @var $model Products */

$this->breadcrumbs = array(
	'Productos' => array('index'),
	'Crear',
);

?>

<h1>Crear Producto</h1>

<div class="container">
	<?php $this->renderPartial('_form_create', array('model' => $model)); ?></div>