<?php

/* @var $this FaqController */
/* @var $model Faqs */
/* @var $languages Languages[] */
/* @var $defaultLanguage Languages */
/* @var $translation FaqTranslations */
/* @var $faqForms FaqForms[] */

$this->breadcrumbs = array(
    'Faqs' => array('index'),
    'Crear',
);

?>

<h1>Crear FAQ</h1>

<div class="container">

	<?php

    $this->renderPartial(
        '_form',
        array(
            'model' => $model,
            'languages' => $languages,
            'defaultLanguage' => $defaultLanguage,
            'translation' => $translation,
            'faqForms' => $faqForms,
        )
    );

?>

</div>