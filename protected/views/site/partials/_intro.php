<?php
$themeUrl = Yii::app()->baseUrl;
/*
$introContent = array(
    'mission' => array(

        'eyebrow' => 'Nuestra misión',

        'title' => 'Llevamos grandes marcas<br>a grandes <em>personas</em>',

        'description' => 'Trabajamos con marcas internacionales de prestigio para ofrecer productos de la más alta calidad, con diseño, innovación y propósito.',

    ),

    'about' => array(

        'eyebrow' => 'Sobre nosotros',

        'title' => 'Construimos relaciones<br>que generan valor',

        'descriptions' => array(

            'Representamos marcas que comparten nuestra visión y las conectamos con consumidores que buscan productos diferentes.',

            'Nuestro trabajo combina experiencia, conocimiento del mercado y una mirada enfocada en construir relaciones de largo plazo.',

        ),

        'highlights' => array(

            array(
                'title' => '6+',
                'description' => 'Años en el mercado',
            ),

            array(
                'title' => '5+',
                'description' => 'Socios comerciales',
            ),

            array(
                'title' => '5',
                'description' => 'Categorías de productos',
            ),

            array(
                'title' => '#2',
                'description' => 'Marca de granola en Perú',
            ),

        ),

        'image' => array(

            'src' => '/images/team/team-01.png',

            'alt' => 'Nuestro equipo',

        ),

    ),

);

*/
/*
|--------------------------------------------------------------------------
| ABOUT HIGHLIGHTS GRID
|--------------------------------------------------------------------------
| Determines the ideal number of columns depending on the number
| of highlights.
|--------------------------------------------------------------------------
*/

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

            <div class="intro__mission-content">


                <?php if (!empty($introContent['mission']['eyebrow'])): ?>

                    <span class="section-label">
                        <?php echo CHtml::encode($introContent['mission']['eyebrow']); ?>
                    </span>

                <?php endif; ?>


                <?php if (!empty($introContent['mission']['title'])): ?>

                    <h2 class="intro__mission-title">

                        <?php echo $introContent['mission']['title']; ?>

                    </h2>

                <?php endif; ?>


                <?php if (!empty($introContent['mission']['description'])): ?>

                    <p class="intro__mission-description">

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

    <div class="intro__about">

        <div class="intro__about-content">


            <!--
            |--------------------------------------------------------------------------
            | ABOUT TEXT
            |--------------------------------------------------------------------------
            -->

            <div class="intro__about-text">


                <?php if (!empty($introContent['about']['eyebrow'])): ?>

                    <span class="section-label">
                        <?php echo CHtml::encode($introContent['about']['eyebrow']); ?>
                    </span>

                <?php endif; ?>


                <?php if (!empty($introContent['about']['title'])): ?>

                    <h2 class="intro__about-title">

                        <?php echo $introContent['about']['title']; ?>

                    </h2>

                <?php endif; ?>


                <?php if (!empty($introContent['about']['descriptions'])): ?>

                    <?php foreach ($introContent['about']['descriptions'] as $description): ?>

                        <?php if (!empty($description)): ?>

                            <p class="intro__about-description">

                                <?php echo CHtml::encode($description); ?>

                            </p>

                        <?php endif; ?>

                    <?php endforeach; ?>

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

                                    <strong class="intro__about-highlight-title">

                                        <?php echo CHtml::encode($highlight['title']); ?>

                                    </strong>

                                <?php endif; ?>


                                <?php if (!empty($highlight['description'])): ?>

                                    <span class="intro__about-highlight-description">

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