<?php
$themeUrl = Yii::app()->theme->baseUrl;
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo CHtml::encode($this->pageTitle); ?> </title>
    <link rel="stylesheet" href="<?php echo $themeUrl; ?>/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $themeUrl; ?>/assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo $themeUrl; ?>/assets/css/theme.css">
    <link rel="stylesheet" href="<?php echo $themeUrl; ?>/assets/css/hero.css">
    <link rel="stylesheet" href="<?php echo $themeUrl; ?>/assets/css/responsive.css">

</head>

<body>
    <header class="site-header">
        <div class="container">
            <strong>
                MADE.BRANDS
            </strong>
            <nav class="site-menu">
                <a href="<?php echo Yii::app()->homeUrl; ?>">Inicio</a>
                <a href="#nosotros">Nosotros </a>
                <a href="#negocios"> Nuestros negocios </a>
                <a href="#productos"> Productos </a>
                <a href="#clientes"> Nuestros clientes </a>
                <a href="#contacto"> Contacto </a>
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

        <div class="footer-contact">

            <div class="container">

                <div class="footer-contact__content">


                    <!--
                |--------------------------------------------------------------------------
                | CONTACT INFORMATION
                |--------------------------------------------------------------------------
                -->

                    <div class="footer-contact__info">


                        <!-- ADDRESS -->

                        <div class="footer-contact__item">

                            <div class="footer-contact__icon">

                                <i
                                    class="fa fa-map-marker"
                                    aria-hidden="true"></i>

                            </div>


                            <div class="footer-contact__text">

                                <strong>
                                    Dirección
                                </strong>

                                <span>
                                    Av. Italia 1234, Oficina 456
                                </span>

                                <span>
                                    Montevideo, Uruguay
                                </span>

                            </div>

                        </div>


                        <!-- PHONE -->

                        <div class="footer-contact__item">

                            <div class="footer-contact__icon">

                                <i
                                    class="fa fa-phone"
                                    aria-hidden="true"></i>

                            </div>


                            <div class="footer-contact__text">

                                <strong>
                                    Teléfono
                                </strong>

                                <span>
                                    +598 2628 1234
                                </span>

                            </div>

                        </div>


                        <!-- EMAIL -->

                        <div class="footer-contact__item">

                            <div class="footer-contact__icon">

                                <i
                                    class="fa fa-envelope-o"
                                    aria-hidden="true"></i>

                            </div>


                            <div class="footer-contact__text">

                                <strong>
                                    Email
                                </strong>

                                <a
                                    href="mailto:holo@madebrands.com">
                                    holo@madebrands.com
                                </a>

                            </div>

                        </div>


                    </div>


                    <!--
                |--------------------------------------------------------------------------
                | CONTACT CTA
                |--------------------------------------------------------------------------
                -->

                    <div class="footer-contact__cta">

                        <a
                            href="mailto:holo@madebrands.com"
                            class="footer-contact__button">

                            <span class="footer-contact__button-icon">

                                <i
                                    class="fa fa-envelope-o"
                                    aria-hidden="true"></i>

                            </span>


                            <span class="footer-contact__button-content">

                                <strong>
                                    Escríbenos
                                </strong>

                                <small>
                                    Te responderemos a la brevedad
                                </small>

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
                            MADE.BRANDS
                        </strong>



                    </div>


                    <!--
                |--------------------------------------------------------------------------
                | COPYRIGHT
                |--------------------------------------------------------------------------
                -->

                    <div class="footer-bottom__copyright">

                        <span>
                            © <?php echo date('Y'); ?> MADE.BRANDS.
                        </span>

                        <span>
                            Todos los derechos reservados.
                        </span>

                    </div>


                    <!--
                |--------------------------------------------------------------------------
                | SOCIAL
                |--------------------------------------------------------------------------
                -->

                    <div class="footer-bottom__social">


                        <!-- LINKEDIN -->

                        <a
                            href="#"
                            target="_blank"
                            rel="noopener noreferrer"
                            aria-label="LinkedIn">

                            <i
                                class="fa fa-linkedin"
                                aria-hidden="true"></i>

                        </a>


                        <!-- INSTAGRAM -->

                        <a
                            href="#"
                            target="_blank"
                            rel="noopener noreferrer"
                            aria-label="Instagram">

                            <i
                                class="fa fa-instagram"
                                aria-hidden="true"></i>

                        </a>


                        <!-- WHATSAPP -->

                        <a
                            href="#"
                            target="_blank"
                            rel="noopener noreferrer"
                            aria-label="WhatsApp">

                            <i
                                class="fa fa-whatsapp"
                                aria-hidden="true"></i>

                        </a>


                    </div>


                </div>

            </div>

        </div>


    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script src="<?php echo $themeUrl; ?>/assets/js/theme.js"></script>
</body>

</html>