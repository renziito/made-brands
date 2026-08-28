<?php
/* @var $this CategoriesController */
/* @var $model Categories */
/* @var $languages Languages[] */
/* @var $translations CategoryTranslations[] */
/* @var $translationsByLanguage array */
/* @var $subcategories Subcategories[] */
/* @var $subcategoryTranslations SubcategoryTranslations[] */
/* @var $defaultLanguage Languages */
/* @var $created integer */

$this->breadcrumbs = array(
	'Categories' => array('index'),
	$model->id => array('update', 'id' => $model->id),
	'Actualizar',
);
?>

<h1>
	Actualizar categoría <?php echo CHtml::encode($model->id); ?>
</h1>


<div class="container">

	<?php

	$this->renderPartial(
		'_form',
		array(
			'model' => $model,

			'languages' => $languages,

			'translations' => $translations,

			'translationsByLanguage' => $translationsByLanguage,

			'subcategories' => $subcategories,

			'subcategoryTranslations' => $subcategoryTranslations,

			'defaultLanguage' => $defaultLanguage,

			'created' => $created,
		)
	);

	?>

</div>