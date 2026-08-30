<?php

/* @var $this FaqController */
/* @var $model Faqs */
/* @var $languages Languages[] */
/* @var $translations FaqTranslations[] */
/* @var $translationsByLanguage array */
/* @var $faqForms FaqForms[] */

$this->breadcrumbs = array(
	'Faqs' => array('index'),
	$model->id => array(
		'update',
		'id' => $model->id,
	),
	'Actualizar',
);

?>

<h1>
	Actualizar FAQ <?php echo (int) $model->id; ?>
</h1>

<div class="container">

	<?php

	$this->renderPartial(
		'_form',
		array(
			'model' => $model,
			'languages' => $languages,
			'translations' => $translations,
			'translationsByLanguage' =>
			$translationsByLanguage,
			'faqForms' => $faqForms,
		)
	);

	?>

</div>