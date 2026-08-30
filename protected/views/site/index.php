<?php

$this->renderPartial('partials/_hero', ['heroSlidesModels' => $heroSlides, 'languageId' => $languageId]);
$this->renderPartial('partials/_intro', ['introContent' => $introContent, 'languageId' => $languageId]);
$this->renderPartial('partials/_business', ['businesses' => $businesses, 'languageId' => $languageId]);
$this->renderPartial('partials/_products', ['featuredCategories' => $featuredCategories, 'languageId' => $languageId]);
$this->renderPartial('partials/_clients', ['brandSection' => $brandSection, 'featuredBrands' => $featuredBrands, 'brands' => $brands, 'languageId' => $languageId]);
$this->renderPartial('partials/_faq', ['faqItems' => $faqItems, 'languageId' => $languageId]);
