/**
 * MADE Theme
 *
 * Global theme functionality.
 *
 * Requires:
 * - jQuery
 */

(function ($) {

    'use strict';


    /*
    |--------------------------------------------------------------------------
    | DOCUMENT READY
    |--------------------------------------------------------------------------
    */

    $(document).ready(function () {


        /*
        |--------------------------------------------------------------------------
        | ELEMENTS
        |--------------------------------------------------------------------------
        */

        var $body = $('body');

        var $header = $('.site-header');

        var $menuToggle = $('.menu-toggle');

        var $siteMenu = $('.site-menu');


        /*
        |--------------------------------------------------------------------------
        | MOBILE MENU
        |--------------------------------------------------------------------------
        */

        function openMenu() {

            if (!$menuToggle.length || !$siteMenu.length) {
                return;
            }


            $siteMenu.addClass('is-open');

            $menuToggle.addClass('is-active');

            $menuToggle.attr(
                'aria-expanded',
                'true'
            );

            $body.addClass('no-scroll');

        }


        function closeMenu() {

            if (!$menuToggle.length || !$siteMenu.length) {
                return;
            }


            $siteMenu.removeClass('is-open');

            $menuToggle.removeClass('is-active');

            $menuToggle.attr(
                'aria-expanded',
                'false'
            );

            $body.removeClass('no-scroll');

        }


        function toggleMenu() {

            if (!$siteMenu.length) {
                return;
            }


            if ($siteMenu.hasClass('is-open')) {

                closeMenu();

            } else {

                openMenu();

            }

        }


        /*
        |--------------------------------------------------------------------------
        | MENU ACCESSIBILITY
        |--------------------------------------------------------------------------
        */

        if (
            $menuToggle.length &&
            $siteMenu.length
        ) {

            if (!$siteMenu.attr('id')) {

                $siteMenu.attr(
                    'id',
                    'site-menu'
                );

            }


            $menuToggle.attr(
                'aria-controls',
                $siteMenu.attr('id')
            );


            $menuToggle.attr(
                'aria-expanded',
                'false'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | MENU TOGGLE
        |--------------------------------------------------------------------------
        */

        $menuToggle.on(
            'click',
            function (event) {

                event.preventDefault();

                event.stopPropagation();

                toggleMenu();

            }
        );


        /*
        |--------------------------------------------------------------------------
        | CLOSE MENU AFTER CLICKING A LINK
        |--------------------------------------------------------------------------
        */

        $siteMenu.on(
            'click',
            'a',
            function () {

                closeMenu();

            }
        );


        /*
        |--------------------------------------------------------------------------
        | CLOSE MENU WITH ESC
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'keydown',
            function (event) {

                if (
                    event.key === 'Escape' ||
                    event.keyCode === 27
                ) {

                    if (
                        $siteMenu.hasClass(
                            'is-open'
                        )
                    ) {

                        closeMenu();

                    }

                }

            }
        );


        /*
        |--------------------------------------------------------------------------
        | CLOSE MENU WHEN CLICKING OUTSIDE
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'click',
            function (event) {

                if (
                    !$siteMenu.hasClass(
                        'is-open'
                    )
                ) {
                    return;
                }


                var $target =
                    $(event.target);


                if (
                    $target.closest(
                        '.site-menu'
                    ).length
                ) {
                    return;
                }


                if (
                    $target.closest(
                        '.menu-toggle'
                    ).length
                ) {
                    return;
                }


                closeMenu();

            }
        );


        /*
        |--------------------------------------------------------------------------
        | SMOOTH INTERNAL NAVIGATION
        |--------------------------------------------------------------------------
        */

        $('a[href^="#"]').on(
            'click',
            function (event) {

                var href =
                    $(this).attr('href');


                if (
                    !href ||
                    href === '#' ||
                    href.length <= 1
                ) {
                    return;
                }


                var $target;


                try {

                    $target =
                        $(href);

                } catch (error) {

                    return;

                }


                if (
                    !$target.length
                ) {
                    return;
                }


                event.preventDefault();


                var headerHeight =
                    $header.length
                        ? $header.outerHeight()
                        : 0;


                var targetPosition =
                    $target.offset().top -
                    headerHeight;


                $('html, body').animate(
                    {
                        scrollTop:
                            Math.max(
                                0,
                                targetPosition
                            )
                    },
                    600
                );


                closeMenu();

            }
        );


        /*
        |--------------------------------------------------------------------------
        | HEADER SCROLL STATE
        |--------------------------------------------------------------------------
        */

        function updateHeader() {

            if (!$header.length) {
                return;
            }


            if (
                $(window).scrollTop() > 20
            ) {

                $header.addClass(
                    'is-scrolled'
                );

            } else {

                $header.removeClass(
                    'is-scrolled'
                );

            }

        }


        updateHeader();


        $(window).on(
            'scroll',
            function () {

                updateHeader();

            }
        );


        /*
        |--------------------------------------------------------------------------
        | RESIZE
        |--------------------------------------------------------------------------
        */

        var resizeTimer = null;


        $(window).on(
            'resize',
            function () {

                clearTimeout(
                    resizeTimer
                );


                resizeTimer =
                    setTimeout(
                        function () {

                            /*
                            |--------------------------------------------------------------------------
                            | Close mobile menu when returning to desktop
                            |--------------------------------------------------------------------------
                            */

                            if (
                                $(window).width() > 768
                            ) {

                                closeMenu();

                            }

                        },
                        150
                    );

            }
        );


        /*
        |--------------------------------------------------------------------------
        | INITIAL STATE
        |--------------------------------------------------------------------------
        */

        closeMenu();

    });


})(jQuery);