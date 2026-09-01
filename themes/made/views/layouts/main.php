<?php

$themeUrl = Yii::app()->theme->baseUrl;

?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">


    <!--
    |--------------------------------------------------------------------------
    | PRIMARY SEO
    |--------------------------------------------------------------------------
    -->

    <title>
        <?php echo CHtml::encode($this->pageTitle); ?>
    </title>


    <meta
        name="description"
        content="<?= WebUtils::getSiteSetting(
                        'SEODescription',
                        'MADE Brands es una empresa dedicada a la importación y comercialización de marcas y productos, conectando oportunidades comerciales entre Perú y Uruguay.'
                    ) ?>">


    <meta
        name="robots"
        content="index, follow">


    <meta
        name="author"
        content="MADE Brands">


    <meta
        name="theme-color"
        content="#000000">


    <link
        rel="canonical"
        href="<?= CHtml::encode(
                    Yii::app()->request->getHostInfo() .
                        Yii::app()->request->getUrl()
                ); ?>">


    <!--
    |--------------------------------------------------------------------------
    | FAVICONS
    |--------------------------------------------------------------------------
    -->

    <meta
        name="msapplication-TileColor"
        content="#000000">


    <meta
        name="msapplication-TileImage"
        content="<?= Yii::app()->getBaseUrl(); ?>/images/favicon.png">


    <link
        rel="shortcut icon"
        href="<?= Yii::app()->getBaseUrl(); ?>/images/favicon.png">


    <link
        rel="icon"
        type="image/x-icon"
        href="<?= Yii::app()->getBaseUrl(); ?>/images/favicon.png">


    <link
        rel="icon"
        href="<?= Yii::app()->getBaseUrl(); ?>/images/favicon.png"
        sizes="32x32">


    <link
        rel="icon"
        href="<?= Yii::app()->getBaseUrl(); ?>/images/favicon.png"
        sizes="192x192">


    <link
        rel="apple-touch-icon"
        href="<?= Yii::app()->getBaseUrl(); ?>/images/favicon.png">


    <!--
    |--------------------------------------------------------------------------
    | OPEN GRAPH
    |--------------------------------------------------------------------------
    -->

    <meta
        property="og:locale"
        content="es_UY">


    <meta
        property="og:type"
        content="website">


    <meta
        property="og:site_name"
        content="MADE Brands">


    <meta
        property="og:title"
        content="<?php echo CHtml::encode($this->pageTitle); ?>">


    <meta
        property="og:description"
        content="<?= WebUtils::getSiteSetting(
                        'SEODescription',
                        'MADE Brands es una empresa dedicada a la importación y comercialización de marcas y productos, conectando oportunidades comerciales entre Perú y Uruguay.'
                    ) ?>">


    <meta
        property="og:url"
        content="<?= CHtml::encode(
                        Yii::app()->request->getHostInfo() .
                            Yii::app()->request->getUrl()
                    ); ?>">


    <meta
        property="og:image"
        content="<?= Yii::app()->getBaseUrl(); ?>/images/og-image.jpg">


    <!--
    |--------------------------------------------------------------------------
    | TWITTER / X
    |--------------------------------------------------------------------------
    -->

    <meta
        name="twitter:card"
        content="summary_large_image">


    <meta
        name="twitter:title"
        content="<?php echo CHtml::encode($this->pageTitle); ?>">


    <meta
        name="twitter:description"
        content="<?= WebUtils::getSiteSetting(
                        'SEODescription',
                        'MADE Brands es una empresa dedicada a la importación y comercialización de marcas y productos, conectando oportunidades comerciales entre Perú y Uruguay.'
                    ) ?>">


    <meta
        name="twitter:image"
        content="<?= Yii::app()->getBaseUrl(); ?>/images/og-image.jpg">


    <!--
    |--------------------------------------------------------------------------
    | STYLES
    |--------------------------------------------------------------------------
    -->

    <link
        rel="stylesheet"
        href="<?php echo $themeUrl; ?>/assets/css/bootstrap.min.css">


    <link
        href="<?= Yii::app()->getBaseUrl() ?>/bin/fonts/font-awesome/css/all.min.css"
        rel="stylesheet"
        type="text/css">


    <link
        rel="stylesheet"
        href="<?php echo $themeUrl; ?>/assets/css/theme.css">


    <link
        rel="stylesheet"
        href="<?php echo $themeUrl; ?>/assets/css/variables.css">


    <link
        rel="stylesheet"
        href="<?php echo $themeUrl; ?>/assets/css/hero.css">


    <link
        rel="stylesheet"
        href="<?php echo $themeUrl; ?>/assets/css/intro.css">


    <link
        rel="stylesheet"
        href="<?php echo $themeUrl; ?>/assets/css/business.css">


    <link
        rel="stylesheet"
        href="<?php echo $themeUrl; ?>/assets/css/products.css">


    <link
        rel="stylesheet"
        href="<?php echo $themeUrl; ?>/assets/css/clients.css">


    <link
        rel="stylesheet"
        href="<?php echo $themeUrl; ?>/assets/css/faq.css">


    <link
        rel="stylesheet"
        href="<?php echo $themeUrl; ?>/assets/css/footer.css">


    <link
        rel="stylesheet"
        href="<?php echo $themeUrl; ?>/assets/css/login.css">


    <link
        rel="stylesheet"
        href="<?php echo $themeUrl; ?>/assets/css/responsive.css">


    <!--
    |--------------------------------------------------------------------------
    | SCRIPTS
    |--------------------------------------------------------------------------
    -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>


    <script src="<?php echo $themeUrl; ?>/assets/js/theme.js"></script>


    <!--
    |--------------------------------------------------------------------------
    | LANGUAGE BUTTONS STYLE
    |--------------------------------------------------------------------------
    -->

    <style>
        .header-languages {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-left: 8px;
        }


        .header-language-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            width: 42px;
            height: 42px;

            padding: 0;

            border: 1px solid #d9dde3;
            border-radius: 6px;

            background-color: #ffffff;

            color: #4f5965;

            font-size: 14px;
            font-weight: 700;
            line-height: 1;

            text-decoration: none;

            box-sizing: border-box;

            transition:
                background-color .2s ease,
                border-color .2s ease,
                color .2s ease,
                transform .2s ease,
                opacity .2s ease;
        }


        .header-language-button:hover,
        .header-language-button:focus {

            background-color: #f5f6f8;

            border-color: #cbd1d8;

            color: #20252b;

            text-decoration: none;

            outline: none;
        }


        .header-language-button:active {

            transform: scale(.97);
        }


        .header-language-button.active {

            background-color: #f3f4f6;

            border-color: #cbd1d8;

            color: #15191d;
        }


        .header-language-button.loading {

            pointer-events: none;

            opacity: .55;
        }


        @media (max-width: 767px) {

            .header-languages {

                gap: 5px;

                margin-left: 0;
            }


            .header-language-button {

                width: 40px;

                height: 40px;

                font-size: 13px;
            }

        }
    </style>

</head>


<?php

/*
|--------------------------------------------------------------------------
| HOME / SECTION URL
|--------------------------------------------------------------------------
*/

$isHome =
    $this->getRoute() === 'site/index';


$isProductsPage =
    $this->getRoute() === 'site/productos';


$sectionUrl =
    function ($section) use (
        $isHome
    ) {

        return $isHome
            ? '#' . $section
            : $this->createUrl(
                'site/index'
            ) . '#' . $section;
    };


/*
|--------------------------------------------------------------------------
| CURRENT LANGUAGE
|--------------------------------------------------------------------------
*/

$languageCode =
    Yii::app()->session->get(
        'language',
        'es'
    );


$language =
    Languages::model()->find(
        'code = :code',
        array(
            ':code' =>
            $languageCode
        )
    );


$languageId =
    $language
    ? $language->id
    : null;


/*
|--------------------------------------------------------------------------
| ACTIVE LANGUAGES
|--------------------------------------------------------------------------
*/

$languages =
    WebUtils::getActiveLanguages();

?>


<body
    style="
        font-family:<?= WebUtils::getSiteSetting('font_family') ?>;
        background-color:<?= WebUtils::getSiteSetting('body_background_color') ?>">


    <!--
    |--------------------------------------------------------------------------
    | HEADER
    |--------------------------------------------------------------------------
    -->

    <header
        class="site-header"
        style="
            background-color:<?= WebUtils::getSiteSetting('header_background_color') ?>">

        <div class="site-header__inner">


            <!--
            |--------------------------------------------------------------------------
            | LOGO
            |--------------------------------------------------------------------------
            -->

            <strong>

                <a
                    href="<?php echo Yii::app()->homeUrl; ?>"
                    style="
                        font-size:<?= WebUtils::getSiteSetting('logo_menu_size') ?>px;
                        font-family:<?= WebUtils::getSiteSetting('logo_font_family') ?>">

                    <?= WebUtils::getSiteSetting(
                        'site_name'
                    ) ?>

                </a>


                <?php if (
                    WebUtils::getSiteSetting(
                        'tagline_menu'
                    )
                ): ?>

                    <br>


                    <small
                        style="font-size:10px">

                        <?= WebUtils::getSiteSetting(
                            'tagline'
                        ) ?>

                    </small>

                <?php endif; ?>

            </strong>


            <!--
            |--------------------------------------------------------------------------
            | MENU
            |--------------------------------------------------------------------------
            -->

            <div id="home-menu">

                <?php

                echo $this->renderPartial(
                    '../partials/_site_menu',
                    array(
                        'menuItems' =>
                        WebUtils::getMenu(
                            $languageId
                        ),

                        'isHome' =>
                        $isHome,

                        'languageCode' =>
                        $languageCode,

                        'languages' =>
                        $languages,

                        'sectionUrl' =>
                        $sectionUrl,
                    ),
                    true
                );

                ?>

            </div>


            <!--
            |--------------------------------------------------------------------------
            | MOBILE MENU
            |--------------------------------------------------------------------------
            -->

            <button
                type="button"
                class="menu-toggle"
                aria-label="Abrir menú">

                <span></span>

                <span></span>

                <span></span>

            </button>


        </div>

    </header>


    <!--
    |--------------------------------------------------------------------------
    | MAIN CONTENT
    |--------------------------------------------------------------------------
    -->

    <main>

        <?php echo $content; ?>

    </main>


    <!--
    |--------------------------------------------------------------------------
    | FOOTER
    |--------------------------------------------------------------------------
    -->

    <footer
        id="contacto"
        class="site-footer">


        <!--
        |--------------------------------------------------------------------------
        | CONTACT SECTION
        |--------------------------------------------------------------------------
        -->

        <div id="home-footer-contact">

            <?php

            echo $this->renderPartial(
                '../partials/_footer_contact',
                array(
                    'contactItems' =>
                    WebUtils::getContactItems(
                        $languageId
                    ),

                    'contactCta' =>
                    WebUtils::getContactCta(
                        $languageId
                    ),

                    'languageId' =>
                    $languageId,
                ),
                true
            );

            ?>

        </div>


        <!--
        |--------------------------------------------------------------------------
        | FOOTER BOTTOM
        |--------------------------------------------------------------------------
        -->

        <div class="footer-bottom">

            <div class="container">

                <div class="footer-bottom__content">


                    <!--
                    |--------------------------------------------------------------------------
                    | BRAND
                    |--------------------------------------------------------------------------
                    -->

                    <div
                        class="footer-bottom__brand">

                        <strong>

                            <span
                                style="
                                    font-size:<?= WebUtils::getSiteSetting('logo_footer_size') ?>px;
                                    font-family:<?= WebUtils::getSiteSetting('logo_font_family') ?>">

                                <?= WebUtils::getSiteSetting(
                                    'site_name'
                                ) ?>

                            </span>


                            <?php if (
                                WebUtils::getSiteSetting(
                                    'tagline_footer'
                                )
                            ): ?>

                                <br>


                                <small
                                    style="font-size:10px">

                                    <?= WebUtils::getSiteSetting(
                                        'tagline'
                                    ) ?>

                                </small>

                            <?php endif; ?>

                        </strong>

                    </div>


                    <!--
                    |--------------------------------------------------------------------------
                    | COPYRIGHT
                    |--------------------------------------------------------------------------
                    -->

                    <div id="home-footer-copyright">

                        <?php

                        echo $this->renderPartial(
                            '../partials/_footer_copyright',
                            array(
                                'languageId' =>
                                $languageId,
                            ),
                            true
                        );

                        ?>

                    </div>


                    <!--
                    |--------------------------------------------------------------------------
                    | SOCIAL
                    |--------------------------------------------------------------------------
                    -->

                    <?php

                    $socialLinks =
                        WebUtils::getSocialLinks();

                    ?>


                    <div
                        class="footer-bottom__social">


                        <?php foreach (
                            $socialLinks
                            as $socialLink
                        ): ?>

                            <a
                                href="<?php echo CHtml::encode(
                                            $socialLink->url
                                        ); ?>"
                                target="_blank"
                                rel="noopener noreferrer"
                                aria-label="<?php echo CHtml::encode(
                                                $socialLink->name
                                            ); ?>">

                                <i
                                    class="<?php echo CHtml::encode(
                                                $socialLink->icon
                                            ); ?>"
                                    aria-hidden="true">
                                </i>

                            </a>

                        <?php endforeach; ?>


                    </div>


                </div>

            </div>

        </div>


    </footer>


    <!--
    |--------------------------------------------------------------------------
    | LANGUAGE AJAX
    |--------------------------------------------------------------------------
    -->

    <?php if (
        count($languages) > 1
    ): ?>

        <script>
            (function($) {

                'use strict';


                /*
                |--------------------------------------------------------------------------
                | CONFIGURATION
                |--------------------------------------------------------------------------
                */

                var changeLanguageUrl =
                    '<?= Yii::app()->createUrl(
                            'site/changeLanguage'
                        ); ?>';


                /*
                |--------------------------------------------------------------------------
                | CURRENT LANGUAGE
                |--------------------------------------------------------------------------
                */

                var currentLanguage =
                    '<?= CHtml::encode(
                            strtolower(
                                trim(
                                    $languageCode
                                )
                            )
                        ); ?>';


                /*
                |--------------------------------------------------------------------------
                | IS PRODUCTS PAGE
                |--------------------------------------------------------------------------
                */

                var isProductsPage =
                    <?= $isProductsPage
                        ? 'true'
                        : 'false'; ?>;


                /*
                |--------------------------------------------------------------------------
                | LANGUAGE BUTTON CLICK
                |--------------------------------------------------------------------------
                */

                $(document).on(
                    'click',
                    '.header-language-button',
                    function(e) {

                        e.preventDefault();


                        var button =
                            $(this);


                        var language =
                            String(
                                button.data(
                                    'language'
                                ) || ''
                            ).toLowerCase();


                        /*
                        |--------------------------------------------------------------------------
                        | VALIDATION
                        |--------------------------------------------------------------------------
                        */

                        if (!language) {

                            return;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | SAME LANGUAGE
                        |--------------------------------------------------------------------------
                        */

                        if (
                            language ===
                            currentLanguage &&
                            !button.hasClass(
                                'loading'
                            )
                        ) {

                            return;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | PREVENT MULTIPLE REQUESTS
                        |--------------------------------------------------------------------------
                        */

                        if (
                            $(
                                '.header-language-button.loading'
                            ).length
                        ) {

                            return;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | DISABLE BUTTONS
                        |--------------------------------------------------------------------------
                        */

                        $(
                                '.header-language-button'
                            )
                            .addClass(
                                'loading'
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | AJAX REQUEST
                        |--------------------------------------------------------------------------
                        */

                        $.ajax({

                            url: changeLanguageUrl,

                            type: 'POST',

                            dataType: 'json',

                            data: {

                                language: language,

                                YII_CSRF_TOKEN: '<?= CHtml::encode(
                                                        Yii::app()->request->csrfToken
                                                    ); ?>'

                            },


                            /*
                            |--------------------------------------------------------------------------
                            | SUCCESS
                            |--------------------------------------------------------------------------
                            */

                            success: function(response) {

                                if (
                                    !response ||
                                    !response.success
                                ) {

                                    return;
                                }


                                /*
                                |--------------------------------------------------------------------------
                                | UPDATE LANGUAGE
                                |--------------------------------------------------------------------------
                                */

                                currentLanguage =
                                    String(
                                        response.language ||
                                        language
                                    ).toLowerCase();


                                /*
                                |--------------------------------------------------------------------------
                                | UPDATE HTML LANG
                                |--------------------------------------------------------------------------
                                */

                                $('html').attr(
                                    'lang',
                                    currentLanguage
                                );


                                /*
                                |--------------------------------------------------------------------------
                                | UPDATE MENU
                                |--------------------------------------------------------------------------
                                */

                                if (
                                    response.html &&
                                    response.html.menu !==
                                    undefined &&
                                    $('#home-menu').length
                                ) {

                                    $('#home-menu').html(
                                        response.html.menu
                                    );

                                }

                                /*
                                |--------------------------------------------------------------------------
                                | UPDATE FOOTER CONTACT
                                |--------------------------------------------------------------------------
                                */
                                if (
                                    response.html &&
                                    response.html.footerContact !==
                                    undefined &&
                                    $('#home-footer-contact').length
                                ) {

                                    $('#home-footer-contact').html(
                                        response.html.footerContact
                                    );

                                }


                                /*
                                |--------------------------------------------------------------------------
                                | UPDATE COPYRIGHT
                                |--------------------------------------------------------------------------
                                */

                                if (
                                    response.html &&
                                    response.html.copyright !==
                                    undefined &&
                                    $('#home-footer-copyright').length
                                ) {
                                    console.log("aqui2");
                                    $('#home-footer-copyright').html(
                                        response.html.copyright
                                    );

                                }


                                /*
                                |--------------------------------------------------------------------------
                                | UPDATE ACTIVE LANGUAGE
                                |--------------------------------------------------------------------------
                                */

                                $(
                                        '.header-language-button'
                                    )
                                    .removeClass(
                                        'active'
                                    );


                                $(
                                        '.header-language-button[data-language="' +
                                        currentLanguage +
                                        '"]'
                                    )
                                    .addClass(
                                        'active'
                                    );


                                /*
                                |--------------------------------------------------------------------------
                                | PRODUCTS PAGE
                                |--------------------------------------------------------------------------
                                */

                                if (
                                    isProductsPage &&
                                    response.html &&
                                    response.html.catalog !==
                                    undefined &&
                                    $('#productos-content').length
                                ) {

                                    /*
                                    |--------------------------------------------------------------------------
                                    | Replace catalog
                                    |--------------------------------------------------------------------------
                                    */

                                    $('#productos-content').html(
                                        response.html.catalog
                                    );


                                    /*
                                    |--------------------------------------------------------------------------
                                    | Reinitialize catalog
                                    |--------------------------------------------------------------------------
                                    */

                                    if (
                                        window.MadeProducts &&
                                        typeof window.MadeProducts.init ===
                                        'function'
                                    ) {

                                        window.MadeProducts.init();

                                    }


                                    /*
                                    |--------------------------------------------------------------------------
                                    | Keep URL product state
                                    |--------------------------------------------------------------------------
                                    */

                                    if (
                                        typeof window.history !==
                                        'undefined'
                                    ) {

                                        /*
                                        |--------------------------------------------------------------------------
                                        | The selected product is represented
                                        | by the current URL.
                                        |--------------------------------------------------------------------------
                                        */

                                    }


                                    return;
                                }


                                /*
                                |--------------------------------------------------------------------------
                                | UPDATE HERO
                                |--------------------------------------------------------------------------
                                */

                                if (
                                    response.html &&
                                    response.html.hero !==
                                    undefined &&
                                    $('#home-hero').length
                                ) {

                                    $('#home-hero').html(
                                        response.html.hero
                                    );

                                }


                                /*
                                |--------------------------------------------------------------------------
                                | UPDATE INTRO
                                |--------------------------------------------------------------------------
                                */

                                if (
                                    response.html &&
                                    response.html.intro !==
                                    undefined &&
                                    $('#home-intro').length
                                ) {

                                    $('#home-intro').html(
                                        response.html.intro
                                    );

                                }


                                /*
                                |--------------------------------------------------------------------------
                                | UPDATE BUSINESS
                                |--------------------------------------------------------------------------
                                */

                                if (
                                    response.html &&
                                    response.html.business !==
                                    undefined &&
                                    $('#home-business').length
                                ) {

                                    $('#home-business').html(
                                        response.html.business
                                    );

                                }


                                /*
                                |--------------------------------------------------------------------------
                                | UPDATE PRODUCTS
                                |--------------------------------------------------------------------------
                                */

                                if (
                                    response.html &&
                                    response.html.products !==
                                    undefined &&
                                    $('#home-products').length
                                ) {

                                    $('#home-products').html(
                                        response.html.products
                                    );

                                }


                                /*
                                |--------------------------------------------------------------------------
                                | UPDATE CLIENTS
                                |--------------------------------------------------------------------------
                                */

                                if (
                                    response.html &&
                                    response.html.clients !==
                                    undefined &&
                                    $('#home-clients').length
                                ) {

                                    $('#home-clients').html(
                                        response.html.clients
                                    );

                                }


                                /*
                                |--------------------------------------------------------------------------
                                | UPDATE FAQ
                                |--------------------------------------------------------------------------
                                */

                                if (
                                    response.html &&
                                    response.html.faq !==
                                    undefined &&
                                    $('#home-faq').length
                                ) {

                                    $('#home-faq').html(
                                        response.html.faq
                                    );

                                }




                            },


                            /*
                            |--------------------------------------------------------------------------
                            | ERROR
                            |--------------------------------------------------------------------------
                            */

                            error: function(xhr) {

                                console.error(
                                    'Error al cambiar el idioma.',
                                    xhr
                                );

                            },


                            /*
                            |--------------------------------------------------------------------------
                            | COMPLETE
                            |--------------------------------------------------------------------------
                            */

                            complete: function() {

                                $(
                                        '.header-language-button'
                                    )
                                    .removeClass(
                                        'loading'
                                    );

                            }

                        });

                    }

                );


            })(jQuery);
        </script>

    <?php endif; ?>


</body>

</html>