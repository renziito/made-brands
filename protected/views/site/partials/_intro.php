<?php
$themeUrl = Yii::app()->baseUrl;

$aboutHighlightCount = count($introContent['about']['highlights']);

if ($aboutHighlightCount <= 1) {

    $aboutHighlightColumns = 1;
} elseif ($aboutHighlightCount === 2) {

    $aboutHighlightColumns = 2;
} elseif ($aboutHighlightCount === 3) {

    $aboutHighlightColumns = 3;
} elseif ($aboutHighlightCount <= 6) {

    $aboutHighlightColumns = 3;
} else {

    $aboutHighlightColumns = 4;
}

?>


<section
    id="nosotros"
    class="intro">


    <!--
    |--------------------------------------------------------------------------
    | MISSION
    |--------------------------------------------------------------------------
    -->

    <div class="intro__mission">

        <div class="container">

            <div class="intro__mission-content" style="background-color:<?= WebUtils::getSiteSetting('section_alt_background_color') ?>">


                <?php if (!empty($introContent['mission']['eyebrow'])): ?>

                    <span class="section-label" style="font-family:<?= WebUtils::getSiteSetting('eyebrow_font_family') ?>; font-size:<?= $introContent['mission']['eyebrow_size'] ?>">
                        <?php echo CHtml::encode($introContent['mission']['eyebrow']); ?>
                    </span>

                <?php endif; ?>


                <?php if (!empty($introContent['mission']['title'])): ?>

                    <h2 class="intro__mission-title" style="font-family:<?= WebUtils::getSiteSetting('heading_font_family') ?>; font-size:<?= $introContent['mission']['title_size'] ?>">

                        <?php echo $introContent['mission']['title']; ?>

                    </h2>

                <?php endif; ?>


                <?php if (!empty($introContent['mission']['description'])): ?>

                    <p class="intro__mission-description" style="font-family:<?= WebUtils::getSiteSetting('body_font_family') ?>; font-size:<?= $introContent['mission']['text_size'] ?>">

                        <?php echo CHtml::encode($introContent['mission']['description']); ?>

                    </p>

                <?php endif; ?>


            </div>

        </div>

    </div>


    <!--
    |--------------------------------------------------------------------------
    | ABOUT
    |--------------------------------------------------------------------------
    -->

    <div class="intro__about" style="background-color:<?= WebUtils::getSiteSetting('section_background_color') ?>">

        <div class="intro__about-content">


            <!--
            |--------------------------------------------------------------------------
            | ABOUT TEXT
            |--------------------------------------------------------------------------
            -->

            <div class="intro__about-text" style="padding-top:60px">


                <?php if (!empty($introContent['about']['eyebrow'])): ?>

                    <span class="section-label" style="font-family:<?= WebUtils::getSiteSetting('eyebrow_font_family') ?>; font-size:<?= $introContent['about']['eyebrow_size'] ?>">
                        <?php echo CHtml::encode($introContent['about']['eyebrow']); ?>
                    </span>

                <?php endif; ?>


                <?php if (!empty($introContent['about']['title'])): ?>

                    <h2 class="intro__about-title" style="font-family:<?= WebUtils::getSiteSetting('heading_font_family') ?>; font-size:<?= $introContent['about']['title_size'] ?>">

                        <?php echo $introContent['about']['title']; ?>

                    </h2>

                <?php endif; ?>

                <?php if (!empty($introContent['about']['text'])): ?>

                    <p class="intro__about-description" style="font-family:<?= WebUtils::getSiteSetting('body_font_family') ?>; font-size:<?= $introContent['about']['text_size'] ?>">

                        <?php echo CHtml::encode($introContent['about']['text']); ?>

                    </p>

                <?php endif; ?>

                <?php if (!empty($introContent['about']['secondary_text'])): ?>

                    <p class="intro__about-description" style="font-family:<?= WebUtils::getSiteSetting('body_font_family') ?>; font-size:<?= $introContent['about']['secondary_text_size'] ?>">

                        <?php echo CHtml::encode($introContent['about']['secondary_text']); ?>

                    </p>

                <?php endif; ?>






                <!--
                |--------------------------------------------------------------------------
                | ABOUT HIGHLIGHTS
                |--------------------------------------------------------------------------
                -->

                <?php if (!empty($introContent['about']['highlights'])): ?>

                    <div
                        class="intro__about-highlights"
                        style="--about-highlights-columns: <?php echo $aboutHighlightColumns; ?>;">

                        <?php foreach ($introContent['about']['highlights'] as $highlight): ?>

                            <div class="intro__about-highlight">


                                <?php if (!empty($highlight['title'])): ?>

                                    <strong class="intro__about-highlight-title" style="font-family:<?= WebUtils::getSiteSetting('heading_font_family') ?>">

                                        <?php echo CHtml::encode($highlight['title']); ?>

                                    </strong>

                                <?php endif; ?>


                                <?php if (!empty($highlight['description'])): ?>

                                    <span class="intro__about-highlight-description" style="font-family:<?= WebUtils::getSiteSetting('body_font_family') ?>">

                                        <?php echo CHtml::encode($highlight['description']); ?>

                                    </span>

                                <?php endif; ?>


                            </div>

                        <?php endforeach; ?>

                    </div>

                <?php endif; ?>


            </div>


            <!--
            |--------------------------------------------------------------------------
            | ABOUT IMAGE
            |--------------------------------------------------------------------------
            -->

            <?php if (!empty($introContent['about']['image']['src'])): ?>

                <div class="intro__about-image">

                    <img
                        src="<?php echo $themeUrl . $introContent['about']['image']['src']; ?>"
                        alt="<?php echo CHtml::encode($introContent['about']['image']['alt']); ?>"
                        loading="lazy">

                </div>

            <?php endif; ?>


        </div>

    </div>


</section>