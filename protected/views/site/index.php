<?php
$this->renderPartial('partials/_hero', ['heroSlidesModels' => $heroSlides]);
$this->renderPartial('partials/_intro', ['introContent' => $introContent]);
$this->renderPartial('partials/_business', ['businesses' => $businesses]);
$this->renderPartial('partials/_products');
$this->renderPartial('partials/_clients');
$this->renderPartial('partials/_faq');
