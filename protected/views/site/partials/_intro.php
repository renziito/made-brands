<?php
/*
|--------------------------------------------------------------------------
| INTRO / ABOUT
|--------------------------------------------------------------------------
| Institutional section.
|
| Contains:
| - Mission statement
| - About us block
|--------------------------------------------------------------------------
*/

$themeUrl = Yii::app()->baseUrl;
?>

<section
    id="nosotros"
    class="intro"
>


    <!--
    |--------------------------------------------------------------------------
    | MISSION
    |--------------------------------------------------------------------------
    -->

    <div class="intro__mission">

        <div class="container">

            <div class="intro__mission-content">

                <span class="section-label">
                    Nuestra misión
                </span>


                <h2 class="intro__mission-title">

                    Llevamos grandes marcas
                    <br>

                    a grandes <em>personas</em>

                </h2>


                <p class="intro__mission-description">

                    Trabajamos con marcas internacionales de prestigio
                    para ofrecer productos de la más alta calidad,
                    con diseño, innovación y propósito.

                </p>

            </div>

        </div>

    </div>


    <!--
    |--------------------------------------------------------------------------
    | ABOUT US
    |--------------------------------------------------------------------------
    -->

    <div class="intro__about">

        <div class="intro__about-content">


            <!--
            |--------------------------------------------------------------------------
            | TEXT
            |--------------------------------------------------------------------------
            -->

            <div class="intro__about-text">

                <span class="section-label">
                    Sobre nosotros
                </span>


                <h2 class="intro__about-title">
                    Construimos relaciones
                    <br>
                    que generan valor
                </h2>


                <p class="intro__about-description">

                    Representamos marcas que comparten nuestra
                    visión y las conectamos con consumidores
                    que buscan productos diferentes.

                </p>


                <p class="intro__about-description">

                    Nuestro trabajo combina experiencia,
                    conocimiento del mercado y una mirada
                    enfocada en construir relaciones de largo plazo.

                </p>

            </div>


            <!--
            |--------------------------------------------------------------------------
            | IMAGE
            |--------------------------------------------------------------------------
            -->

            <div class="intro__about-image">

                <img
                    src="<?php echo $themeUrl; ?>/images/team/team-01.png"
                    alt="Nuestro equipo"
                    loading="lazy"
                >

            </div>

        </div>

    </div>


</section>