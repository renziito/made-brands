<?php
$themeUrl = Yii::app()->theme->baseUrl;
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Primary SEO -->
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <meta name="description" content="<?= WebUtils::getSiteSetting('SEODescription', 'MADE Brands es una empresa dedicada a la importación y comercialización de marcas y productos, conectando oportunidades comerciales entre Perú y Uruguay.') ?>">
    <meta name="robots" content="index, follow">
    <meta name="author" content="MADE Brands">
    <meta name="theme-color" content="#000000">
    <link rel="canonical" href="<?= CHtml::encode(Yii::app()->request->getHostInfo() . Yii::app()->request->getUrl()); ?>">

    <!-- Favicons -->
    <meta name="msapplication-TileColor" content="#000000">
    <meta name="msapplication-TileImage" content="<?= Yii::app()->getBaseUrl(); ?>/images/favicon.png">
    <link rel="shortcut icon" href="<?= Yii::app()->getBaseUrl(); ?>/images/favicon.png">
    <link rel="icon" type="image/x-icon" href="<?= Yii::app()->getBaseUrl(); ?>/images/favicon.png">
    <link rel="icon" href="<?= Yii::app()->getBaseUrl(); ?>/images/favicon.png" sizes="32x32">
    <link rel="icon" href="<?= Yii::app()->getBaseUrl(); ?>/images/favicon.png" sizes="192x192">
    <link rel="apple-touch-icon" href="<?= Yii::app()->getBaseUrl(); ?>/images/favicon.png">

    <!-- Open Graph -->
    <meta property="og:locale" content="es_UY">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="MADE Brands">
    <meta property="og:title" content="<?php echo CHtml::encode($this->pageTitle); ?>">
    <meta property="og:description" content="<?= WebUtils::getSiteSetting('SEODescription', 'MADE Brands es una empresa dedicada a la importación y comercialización de marcas y productos, conectando oportunidades comerciales entre Perú y Uruguay.') ?>">
    <meta property="og:url" content="<?= CHtml::encode(Yii::app()->request->getHostInfo() . Yii::app()->request->getUrl()); ?>">
    <meta property="og:image" content="<?= Yii::app()->getBaseUrl(); ?>/images/og-image.jpg">

    <!-- Twitter / X -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo CHtml::encode($this->pageTitle); ?>">
    <meta name="twitter:description" content="<?= WebUtils::getSiteSetting('SEODescription', 'MADE Brands es una empresa dedicada a la importación y comercialización de marcas y productos, conectando oportunidades comerciales entre Perú y Uruguay.') ?>">
    <meta name="twitter:image" content="<?= Yii::app()->getBaseUrl(); ?>/images/og-image.jpg">

    <!-- Styles -->
    <link rel="stylesheet" href="<?php echo $themeUrl; ?>/assets/css/bootstrap.min.css">
    <link href="<?= Yii::app()->getBaseUrl() ?>/bin/fonts/font-awesome/css/all.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="<?php echo $themeUrl; ?>/assets/css/theme.css">
    <link rel="stylesheet" href="<?php echo $themeUrl; ?>/assets/css/variables.css">
    <link rel="stylesheet" href="<?php echo $themeUrl; ?>/assets/css/hero.css">
    <link rel="stylesheet" href="<?php echo $themeUrl; ?>/assets/css/intro.css">
    <link rel="stylesheet" href="<?php echo $themeUrl; ?>/assets/css/business.css">
    <link rel="stylesheet" href="<?php echo $themeUrl; ?>/assets/css/products.css">
    <link rel="stylesheet" href="<?php echo $themeUrl; ?>/assets/css/clients.css">
    <link rel="stylesheet" href="<?php echo $themeUrl; ?>/assets/css/faq.css">
    <link rel="stylesheet" href="<?php echo $themeUrl; ?>/assets/css/footer.css">
    <link rel="stylesheet" href="<?php echo $themeUrl; ?>/assets/css/login.css">
    <link rel="stylesheet" href="<?php echo $themeUrl; ?>/assets/css/responsive.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script src="<?php echo $themeUrl; ?>/assets/js/theme.js"></script>
</head>

<?php
$isHome = $this->getRoute() === 'site/index';

$sectionUrl = function ($section) use ($isHome) {
    return $isHome
        ? '#' . $section
        : $this->createUrl('site/index') . '#' . $section;
};

$languageCode = Yii::app()->session->get('language', 'es');
$language = Languages::model()->find('code = :code', array(':code' => $languageCode));
$languageId = $language ? $language->id : null;
?>

<body style="font-family:<?= WebUtils::getSiteSetting('font_family') ?>; background-color:<?= WebUtils::getSiteSetting('body_background_color') ?>">
    <header class="site-header" style="background-color:<?= WebUtils::getSiteSetting('header_background_color') ?>">
        <div class="site-header__inner">
            <strong>
                <a href="<?php echo Yii::app()->homeUrl; ?>" style="font-size:<?= WebUtils::getSiteSetting('logo_menu_size') ?>px; font-family:<?= WebUtils::getSiteSetting('logo_font_family') ?>"><?= WebUtils::getSiteSetting('site_name') ?></a>
                <?php if (WebUtils::getSiteSetting('tagline_menu')) : ?>
                    <br><small style="font-size:10px"><?= WebUtils::getSiteSetting('tagline') ?></small>
                <?php endif; ?>
            </strong>
            <?php $menuItems = WebUtils::getMenu($languageId); ?>

            <nav class="site-menu">
                <?php foreach ($menuItems as $menuItem): ?>
                    <?php
                    $url  = $sectionUrl($menuItem['link']);
                    if (str_starts_with($menuItem['link'], "#")) {
                        $url = $menuItem['link'];
                    }
                    ?>
                    <?php if ($menuItem['is_button']): ?>
                        <a href="<?= $url; ?>" class="header-contact-button" style="background-color:<?= WebUtils::getSiteSetting('contact_button_background_color') ?>!important ;color:<?= WebUtils::getSiteSetting('contact_button_text_color') ?> !important"><?= $menuItem['label'] ?></a>
                    <?php else: ?>
                        <a href="<?= $url; ?>"><?= $menuItem['label'] ?> </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </nav>


            <button type="button" class="menu-toggle" aria-label="Abrir menú"> <span></span> <span></span> <span></span> </button>
        </div>
    </header>

    <main>
        <?php echo $content; ?>
    </main>

    <footer
        id="contacto"
        class="site-footer">


        <!--
    |--------------------------------------------------------------------------
    | CONTACT SECTION
    |--------------------------------------------------------------------------
    -->

        <?php
        $contactItems = WebUtils::getContactItems($languageId);
        ?>

        <div class="footer-contact">

            <div class="container">

                <div class="footer-contact__content">


                    <div class="footer-contact__info">

                        <?php foreach ($contactItems as $item): ?>

                            <div class="footer-contact__item">

                                <div class="footer-contact__icon">
                                    <i
                                        class="<?php echo CHtml::encode($item['icon']); ?>"
                                        aria-hidden="true">
                                    </i>
                                </div>

                                <div class="footer-contact__text">

                                    <strong>
                                        <?php echo CHtml::encode($item['label']); ?>
                                    </strong>

                                    <?php foreach (preg_split('/\r\n|\r|\n/', $item['value']) as $value): ?>

                                        <span>
                                            <?php echo CHtml::encode(trim($value)); ?>
                                        </span>

                                    <?php endforeach; ?>

                                </div>

                            </div>

                        <?php endforeach; ?>


                    </div>


                    <!--
                |--------------------------------------------------------------------------
                | CONTACT CTA
                |--------------------------------------------------------------------------
                -->

                    <?php $contactCta = WebUtils::getContactCta($languageId); ?>

                    <div class="footer-contact__cta">

                        <a
                            href="<?php echo CHtml::encode($contactCta->url); ?>"
                            class="footer-contact__button" style="background-color:<?= WebUtils::getSiteSetting('cta_background_color') ?>!important ;color:<?= WebUtils::getSiteSetting('cta_text_color') ?> !important">

                            <span class="footer-contact__button-icon">
                                <i class="<?php echo CHtml::encode($contactCta->icon); ?>" aria-hidden="true"></i>
                            </span>
                            <?php $translation = $contactCta->contactCtaTranslations[0]; ?>

                            <span class="footer-contact__button-content">

                                <strong><?php echo CHtml::encode($translation->title); ?></strong>

                                <?php if (!empty($translation->text)): ?>

                                    <small>
                                        <?php echo CHtml::encode($translation->text); ?>
                                    </small>

                                <?php endif; ?>

                            </span>

                        </a>

                    </div>


                </div>

            </div>

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

                    <div class="footer-bottom__brand">

                        <strong>
                            <span style="font-size:<?= WebUtils::getSiteSetting('logo_footer_size') ?>px; font-family:<?= WebUtils::getSiteSetting('logo_font_family') ?>"><?= WebUtils::getSiteSetting('site_name') ?></span>
                            <?php if (WebUtils::getSiteSetting('tagline_footer')) : ?>
                                <br><small style="font-size:10px"><?= WebUtils::getSiteSetting('tagline') ?></small>
                            <?php endif; ?>
                        </strong>



                    </div>


                    <!--
                |--------------------------------------------------------------------------
                | COPYRIGHT
                |--------------------------------------------------------------------------
                -->

                    <div class="footer-bottom__copyright">

                        <span>
                            © <?php echo date('Y'); ?> <?= WebUtils::getSiteSetting('site_name') ?>
                        </span>

                        <span>
                            <?= WebUtils::getMenuItemByKey('all_rights_reserved', $languageId)['label'] ?>
                        </span>

                    </div>


                    <!--
                |--------------------------------------------------------------------------
                | SOCIAL
                |--------------------------------------------------------------------------
                -->

                    <?php $socialLinks = WebUtils::getSocialLinks(); ?>

                    <div class="footer-bottom__social">


                        <?php foreach ($socialLinks as $socialLink): ?>

                            <a
                                href="<?php echo CHtml::encode($socialLink->url); ?>"
                                target="_blank"
                                rel="noopener noreferrer"
                                aria-label="<?php echo CHtml::encode($socialLink->name); ?>">

                                <i
                                    class="<?php echo CHtml::encode($socialLink->icon); ?>"
                                    aria-hidden="true">
                                </i>

                            </a>

                        <?php endforeach; ?>

                    </div>


                </div>

            </div>

        </div>


    </footer>


</body>

</html>