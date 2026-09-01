<div id="home-hero">
    <?php $this->renderPartial('partials/_hero', ['heroSlidesModels' => $heroSlides, 'languageId' => $languageId]); ?>
</div>


<div id="home-intro">
    <?php $this->renderPartial('partials/_intro', ['introContent' => $introContent, 'languageId' => $languageId]); ?>
</div>

<div id="home-business">
    <?php $this->renderPartial('partials/_business', ['businesses' => $businesses, 'languageId' => $languageId]); ?>
</div>

<div id="home-products">
    <?php $this->renderPartial('partials/_products', ['featuredCategories' => $featuredCategories, 'languageId' => $languageId]); ?>
</div>

<div id="home-clients">
    <?php $this->renderPartial('partials/_clients', ['brandSection' => $brandSection, 'featuredBrands' => $featuredBrands, 'brands' => $brands, 'languageId' => $languageId]); ?>
</div>

<div id="home-faq">
    <?php $this->renderPartial('partials/_faq', ['faqItems' => $faqItems, 'languageId' => $languageId]); ?>
</div>