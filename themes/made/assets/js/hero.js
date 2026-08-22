/**
 * MADE Theme
 *
 * Hero slider functionality.
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

        var $hero = $('.hero');

        var $slider = $('.hero__slider');


        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        if (!$hero.length || !$slider.length) {
            return;
        }


        var $slides =
            $slider.find('.hero__slide');


        var $previousButton =
            $slider.find('.hero__arrow--prev');


        var $nextButton =
            $slider.find('.hero__arrow--next');


        var $dots =
            $slider.find('.hero__dot');


        /*
        |--------------------------------------------------------------------------
        | CONFIGURATION
        |--------------------------------------------------------------------------
        */

        var autoplayDelay = 6000;

        var transitionDuration = 600;

        var swipeThreshold = 50;


        /*
        |--------------------------------------------------------------------------
        | STATE
        |--------------------------------------------------------------------------
        */

        var currentSlide = 0;

        var autoplayTimer = null;

        var animationTimer = null;

        var isAnimating = false;

        var isHovering = false;

        var touchStartX = 0;

        var touchStartY = 0;

        var touchEndX = 0;

        var touchEndY = 0;


        /*
        |--------------------------------------------------------------------------
        | SLIDE COUNT
        |--------------------------------------------------------------------------
        */

        var totalSlides =
            $slides.length;


        /*
        |--------------------------------------------------------------------------
        | NO SLIDES
        |--------------------------------------------------------------------------
        */

        if (totalSlides === 0) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | INITIAL SLIDE
        |--------------------------------------------------------------------------
        |
        | Make sure exactly one slide is active.
        |
        */

        $slides.removeClass(
            'hero__slide--active'
        );


        $slides.eq(0).addClass(
            'hero__slide--active'
        );


        currentSlide = 0;


        /*
        |--------------------------------------------------------------------------
        | SINGLE SLIDE
        |--------------------------------------------------------------------------
        */

        if (totalSlides === 1) {

            $previousButton.hide();

            $nextButton.hide();

            $dots.hide();


            /*
            |--------------------------------------------------------------------------
            | Accessibility
            |--------------------------------------------------------------------------
            */

            $slides.attr(
                'aria-hidden',
                'false'
            );


            /*
            |--------------------------------------------------------------------------
            | READY
            |--------------------------------------------------------------------------
            */

            $hero.addClass(
                'is-ready'
            );


            return;

        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE DOTS
        |--------------------------------------------------------------------------
        */

        function updateDots() {

            $dots.each(
                function (index) {

                    var $dot =
                        $(this);


                    var isActive =
                        index === currentSlide;


                    $dot.toggleClass(
                        'hero__dot--active',
                        isActive
                    );


                    if (isActive) {

                        $dot.attr(
                            'aria-current',
                            'true'
                        );

                    } else {

                        $dot.removeAttr(
                            'aria-current'
                        );

                    }

                }
            );

        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE ACCESSIBILITY
        |--------------------------------------------------------------------------
        */

        function updateAccessibility() {

            $slides.each(
                function (index) {

                    var isActive =
                        index === currentSlide;


                    $(this).attr(
                        'aria-hidden',
                        isActive
                            ? 'false'
                            : 'true'
                    );

                }
            );

        }


        /*
        |--------------------------------------------------------------------------
        | SHOW SLIDE
        |--------------------------------------------------------------------------
        */

        function showSlide(index) {

            /*
            |--------------------------------------------------------------------------
            | Prevent overlapping transitions
            |--------------------------------------------------------------------------
            */

            if (isAnimating) {
                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Normalize index
            |--------------------------------------------------------------------------
            */

            if (index < 0) {

                index =
                    totalSlides - 1;

            }


            if (index >= totalSlides) {

                index = 0;

            }


            /*
            |--------------------------------------------------------------------------
            | Same slide
            |--------------------------------------------------------------------------
            */

            if (index === currentSlide) {
                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Animation lock
            |--------------------------------------------------------------------------
            */

            isAnimating = true;


            /*
            |--------------------------------------------------------------------------
            | Clear previous animation timer
            |--------------------------------------------------------------------------
            */

            if (animationTimer !== null) {

                clearTimeout(
                    animationTimer
                );

                animationTimer = null;

            }


            /*
            |--------------------------------------------------------------------------
            | Current slide
            |--------------------------------------------------------------------------
            */

            var $currentSlide =
                $slides.eq(
                    currentSlide
                );


            /*
            |--------------------------------------------------------------------------
            | New slide
            |--------------------------------------------------------------------------
            */

            var $nextSlide =
                $slides.eq(
                    index
                );


            /*
            |--------------------------------------------------------------------------
            | Activate new slide
            |--------------------------------------------------------------------------
            */

            $currentSlide.removeClass(
                'hero__slide--active'
            );


            $nextSlide.addClass(
                'hero__slide--active'
            );


            /*
            |--------------------------------------------------------------------------
            | Update state
            |--------------------------------------------------------------------------
            */

            currentSlide = index;


            /*
            |--------------------------------------------------------------------------
            | Update UI
            |--------------------------------------------------------------------------
            */

            updateDots();

            updateAccessibility();


            /*
            |--------------------------------------------------------------------------
            | Release animation lock
            |--------------------------------------------------------------------------
            */

            animationTimer =
                setTimeout(
                    function () {

                        isAnimating = false;

                        animationTimer = null;

                    },
                    transitionDuration
                );

        }


        /*
        |--------------------------------------------------------------------------
        | NEXT SLIDE
        |--------------------------------------------------------------------------
        */

        function nextSlide() {

            showSlide(
                currentSlide + 1
            );

        }


        /*
        |--------------------------------------------------------------------------
        | PREVIOUS SLIDE
        |--------------------------------------------------------------------------
        */

        function previousSlide() {

            showSlide(
                currentSlide - 1
            );

        }


        /*
        |--------------------------------------------------------------------------
        | START AUTOPLAY
        |--------------------------------------------------------------------------
        */

        function startAutoplay() {

            stopAutoplay();


            autoplayTimer =
                setInterval(
                    function () {

                        /*
                        |--------------------------------------------------------------------------
                        | Don't advance while:
                        |
                        | - Browser tab is hidden
                        | - User is hovering the Hero
                        |--------------------------------------------------------------------------
                        */

                        if (
                            document.hidden ||
                            isHovering
                        ) {
                            return;
                        }


                        nextSlide();

                    },
                    autoplayDelay
                );

        }


        /*
        |--------------------------------------------------------------------------
        | STOP AUTOPLAY
        |--------------------------------------------------------------------------
        */

        function stopAutoplay() {

            if (
                autoplayTimer !== null
            ) {

                clearInterval(
                    autoplayTimer
                );

                autoplayTimer = null;

            }

        }


        /*
        |--------------------------------------------------------------------------
        | RESET AUTOPLAY
        |--------------------------------------------------------------------------
        */

        function resetAutoplay() {

            stopAutoplay();

            startAutoplay();

        }


        /*
        |--------------------------------------------------------------------------
        | PREVIOUS BUTTON
        |--------------------------------------------------------------------------
        */

        $previousButton.on(
            'click',
            function (event) {

                event.preventDefault();

                previousSlide();

                resetAutoplay();

            }
        );


        /*
        |--------------------------------------------------------------------------
        | NEXT BUTTON
        |--------------------------------------------------------------------------
        */

        $nextButton.on(
            'click',
            function (event) {

                event.preventDefault();

                nextSlide();

                resetAutoplay();

            }
        );


        /*
        |--------------------------------------------------------------------------
        | DOTS
        |--------------------------------------------------------------------------
        */

        $dots.on(
            'click',
            function (event) {

                event.preventDefault();


                var index =
                    $dots.index(this);


                showSlide(
                    index
                );


                resetAutoplay();

            }
        );


        /*
        |--------------------------------------------------------------------------
        | MOUSE ENTER
        |--------------------------------------------------------------------------
        */

        $slider.on(
            'mouseenter',
            function () {

                isHovering = true;

            }
        );


        /*
        |--------------------------------------------------------------------------
        | MOUSE LEAVE
        |--------------------------------------------------------------------------
        */

        $slider.on(
            'mouseleave',
            function () {

                isHovering = false;

            }
        );


        /*
        |--------------------------------------------------------------------------
        | PAGE VISIBILITY
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'visibilitychange',
            function () {

                if (document.hidden) {

                    stopAutoplay();

                } else {

                    startAutoplay();

                }

            }
        );


        /*
        |--------------------------------------------------------------------------
        | TOUCH START
        |--------------------------------------------------------------------------
        */

        $slider.on(
            'touchstart',
            function (event) {

                var originalEvent =
                    event.originalEvent;


                if (
                    !originalEvent.touches ||
                    !originalEvent.touches.length
                ) {
                    return;
                }


                touchStartX =
                    originalEvent.touches[0].clientX;


                touchStartY =
                    originalEvent.touches[0].clientY;


                touchEndX =
                    touchStartX;


                touchEndY =
                    touchStartY;


                stopAutoplay();

            }
        );


        /*
        |--------------------------------------------------------------------------
        | TOUCH MOVE
        |--------------------------------------------------------------------------
        */

        $slider.on(
            'touchmove',
            function (event) {

                var originalEvent =
                    event.originalEvent;


                if (
                    !originalEvent.touches ||
                    !originalEvent.touches.length
                ) {
                    return;
                }


                touchEndX =
                    originalEvent.touches[0].clientX;


                touchEndY =
                    originalEvent.touches[0].clientY;

            }
        );


        /*
        |--------------------------------------------------------------------------
        | TOUCH END
        |--------------------------------------------------------------------------
        */

        $slider.on(
            'touchend',
            function () {

                var deltaX =
                    touchEndX -
                    touchStartX;


                var deltaY =
                    touchEndY -
                    touchStartY;


                /*
                |--------------------------------------------------------------------------
                | Ignore vertical gestures
                |--------------------------------------------------------------------------
                */

                if (
                    Math.abs(deltaX) <
                    Math.abs(deltaY)
                ) {

                    startAutoplay();

                    return;

                }


                /*
                |--------------------------------------------------------------------------
                | Swipe left
                |--------------------------------------------------------------------------
                */

                if (
                    deltaX <=
                    -swipeThreshold
                ) {

                    nextSlide();

                }


                /*
                |--------------------------------------------------------------------------
                | Swipe right
                |--------------------------------------------------------------------------
                */

                else if (
                    deltaX >=
                    swipeThreshold
                ) {

                    previousSlide();

                }


                /*
                |--------------------------------------------------------------------------
                | Restart autoplay
                |--------------------------------------------------------------------------
                */

                startAutoplay();

            }
        );


        /*
        |--------------------------------------------------------------------------
        | KEYBOARD NAVIGATION
        |--------------------------------------------------------------------------
        */

        $slider.attr(
            'tabindex',
            '0'
        );


        $slider.on(
            'keydown',
            function (event) {

                /*
                |--------------------------------------------------------------------------
                | Arrow left
                |--------------------------------------------------------------------------
                */

                if (
                    event.key === 'ArrowLeft' ||
                    event.keyCode === 37
                ) {

                    event.preventDefault();

                    previousSlide();

                    resetAutoplay();

                }


                /*
                |--------------------------------------------------------------------------
                | Arrow right
                |--------------------------------------------------------------------------
                */

                if (
                    event.key === 'ArrowRight' ||
                    event.keyCode === 39
                ) {

                    event.preventDefault();

                    nextSlide();

                    resetAutoplay();

                }

            }
        );


        /*
        |--------------------------------------------------------------------------
        | INITIAL ACCESSIBILITY STATE
        |--------------------------------------------------------------------------
        */

        updateAccessibility();


        /*
        |--------------------------------------------------------------------------
        | INITIAL DOT STATE
        |--------------------------------------------------------------------------
        */

        updateDots();


        /*
        |--------------------------------------------------------------------------
        | HERO READY
        |--------------------------------------------------------------------------
        |
        | The controls remain hidden until the slider has
        | completely initialized.
        |
        */

        $hero.addClass(
            'is-ready'
        );


        /*
        |--------------------------------------------------------------------------
        | START AUTOPLAY
        |--------------------------------------------------------------------------
        */

        startAutoplay();


    });


})(jQuery);