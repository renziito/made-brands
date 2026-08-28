<?php
/* @var $this BrandingController */
/* @var $branding array */

$this->pageTitle = 'Branding';

$editUrl = $this->createUrl('edit');
$fontsUrl = $this->createUrl('fonts');


$getBranding = function ($key, $default = '') use ($branding) {

    return isset($branding[$key]) && $branding[$key] !== null
        ? $branding[$key]
        : $default;
};
?>


<style>
    /* =========================================================
	   BRANDING PAGE
	========================================================= */

    .branding-page {
        max-width: 1100px;
        margin: 0 auto;
        padding-bottom: 60px;
    }


    .branding-page .cpanel-page-header {
        margin-bottom: 30px;
    }


    .branding-page .cpanel-page-header h1 {
        margin-bottom: 6px;
    }


    .branding-page .cpanel-page-header p {
        margin: 0;
        color: #6b7280;
    }


    /* =========================================================
	   CARDS
	========================================================= */

    .branding-card {
        position: relative;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        margin-bottom: 22px;
        overflow: visible;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
    }


    .branding-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 22px 24px;
        border-bottom: 1px solid #eeeeee;
    }


    .branding-card-header h2 {
        margin: 0 0 5px;
        font-size: 20px;
        font-weight: 600;
        color: #222222;
    }


    .branding-card-header p {
        margin: 0;
        font-size: 13px;
        color: #737373;
    }


    .branding-card-body {
        padding: 24px;
    }


    /* =========================================================
	   FORM GRID
	========================================================= */

    .branding-page .admin-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 22px;
    }


    .branding-page .admin-form-field {
        min-width: 0;
    }


    .branding-page .admin-form-field>label {
        display: block;
        margin-bottom: 8px;
        font-size: 13px;
        font-weight: 600;
        color: #444444;
    }


    .branding-field {
        width: 100%;
        height: 40px;
        padding: 0 12px;
        border: 1px solid #d9d9d9;
        border-radius: 7px;
        background: #ffffff;
        box-sizing: border-box;
        font-size: 14px;
        color: #333333;
        outline: none;
        transition:
            border-color .15s ease,
            box-shadow .15s ease;
    }


    .branding-field:focus {
        border-color: #999999;
        box-shadow: 0 0 0 3px rgba(0, 0, 0, .05);
    }


    /* =========================================================
	   SAVE BUTTON
	========================================================= */

    .branding-save-button {
        flex: 0 0 auto;
        height: 34px;
        padding: 0 14px;
        border: 1px solid #d5d5d5;
        border-radius: 6px;
        background: #be5050;
        color: #fff;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all .15s ease;
    }


    .branding-save-button:hover {
        background: #f5f5f5;
        border-color: #bbbbbb;
    }


    .branding-save-button:disabled {
        opacity: .55;
        cursor: wait;
    }


    /* =========================================================
	   COLORS
	========================================================= */

    .branding-color-field {
        display: flex;
        align-items: center;
        gap: 10px;
    }


    .branding-color-picker {
        width: 46px;
        height: 40px;
        padding: 3px;
        border: 1px solid #d9d9d9;
        border-radius: 7px;
        background: #ffffff;
        cursor: pointer;
        box-sizing: border-box;
    }


    .branding-color-text {
        flex: 1;
    }


    /* =========================================================
	   GOOGLE FONT SELECTOR
	========================================================= */

    .branding-font-selector {
        position: relative;
    }


    .branding-font-search {
        position: relative;
    }


    .branding-font-search-icon {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: #888888;
        font-size: 15px;
        pointer-events: none;
        z-index: 2;
    }


    .branding-font-search-input {
        padding-left: 38px;
    }


    .branding-font-results {
        display: none;
        position: absolute;
        z-index: 10000;
        top: calc(100% + 7px);
        left: 0;
        right: 0;
        max-height: 350px;
        overflow-y: auto;
        background: #ffffff;
        border: 1px solid #dedede;
        border-radius: 9px;
        box-shadow: 0 12px 30px rgba(0, 0, 0, .12);
    }


    .branding-font-results.is-open {
        display: block;
    }


    .branding-font-result {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 14px 15px;
        cursor: pointer;
        border-bottom: 1px solid #f0f0f0;
        background: #ffffff;
        transition: background .12s ease;
    }


    .branding-font-result:last-child {
        border-bottom: 0;
    }


    .branding-font-result:hover {
        background: #f7f7f7;
    }


    .branding-font-result-name {
        min-width: 0;
        font-size: 17px;
        line-height: 1.3;
        color: #333333;
    }


    .branding-font-result-category {
        flex: 0 0 auto;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: #999999;
    }


    .branding-font-result-empty {
        padding: 20px;
        text-align: center;
        font-size: 13px;
        color: #888888;
    }


    .branding-font-result-loading {
        padding: 20px;
        text-align: center;
        font-size: 13px;
        color: #888888;
    }


    /* =========================================================
	   SELECTED FONT
	========================================================= */

    .branding-font-selected {
        margin-top: 10px;
        padding: 14px 16px;
        border: 1px solid #e5e5e5;
        border-radius: 8px;
        background: #fafafa;
    }


    .branding-font-selected-label {
        margin-bottom: 5px;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: #999999;
    }


    .branding-font-selected-preview {
        font-size: 23px;
        line-height: 1.25;
        color: #222222;
        transition: font-family .15s ease;
    }


    /* =========================================================
	   TOAST
	========================================================= */

    .branding-toast {
        position: fixed;
        right: 25px;
        bottom: 25px;
        z-index: 99999;
        min-width: 240px;
        max-width: 380px;
        padding: 13px 17px;
        border-radius: 8px;
        background: #222222;
        color: #ffffff;
        font-size: 13px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, .18);
        opacity: 0;
        transform: translateY(10px);
        pointer-events: none;
        transition:
            opacity .2s ease,
            transform .2s ease;
    }


    .branding-toast.show {
        opacity: 1;
        transform: translateY(0);
    }


    .branding-toast.error {
        background: #b42318;
    }


    /* =========================================================
	   RESPONSIVE
	========================================================= */

    @media (max-width: 800px) {

        .branding-page .admin-form-grid {
            grid-template-columns: 1fr;
        }

    }


    @media (max-width: 600px) {

        .branding-card-header {
            align-items: flex-start;
            flex-direction: column;
        }


        .branding-save-button {
            width: 100%;
        }

    }

    /* =========================================================
   GENERAL SWITCH
========================================================= */

    .branding-switch-field {
        display: flex;
        align-items: center;
        gap: 12px;
        min-height: 40px;
    }


    .branding-switch {
        position: relative;
        display: inline-block;
        width: 44px;
        height: 24px;
        margin: 0;
        cursor: pointer;
    }


    .branding-switch input[type="checkbox"] {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }


    .branding-switch-slider {
        position: absolute;
        inset: 0;
        background: #d4d4d4;
        border-radius: 24px;
        transition: background .2s ease;
    }


    .branding-switch-slider:before {
        content: "";
        position: absolute;
        width: 18px;
        height: 18px;
        left: 3px;
        top: 3px;
        background: #ffffff;
        border-radius: 50%;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .2);
        transition: transform .2s ease;
    }


    .branding-switch input[type="checkbox"]:checked+.branding-switch-slider {
        background: #111111;
    }


    .branding-switch input[type="checkbox"]:checked+.branding-switch-slider:before {
        transform: translateX(20px);
    }


    .branding-switch-label {
        font-size: 13px;
        color: #666666;
    }


    /* =========================================================
   FULL SHEET LINK
========================================================= */

    .branding-file-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        height: 40px;
        padding: 0 14px;
        border: 1px solid #d9d9d9;
        border-radius: 7px;
        background: #ffffff;
        color: #333333;
        text-decoration: none;
        font-size: 13px;
        transition:
            background .15s ease,
            border-color .15s ease;
    }


    .branding-file-link:hover {
        background: #f7f7f7;
        border-color: #bbbbbb;
        text-decoration: none;
    }


    .branding-file-link-icon {
        font-size: 15px;
    }


    .branding-file-empty {
        display: flex;
        align-items: center;
        height: 40px;
        padding: 0 12px;
        border: 1px dashed #d9d9d9;
        border-radius: 7px;
        color: #999999;
        font-size: 13px;
    }
</style>


<div class="cpanel-page branding-page">


    <!-- =========================================================
		 PAGE HEADER
	========================================================= -->

    <div class="cpanel-page-header">

        <div>

            <h1>Branding</h1>

            <p>
                Configura la identidad visual global del sitio.
            </p>

        </div>

    </div>

    <!-- =========================================================
     GENERAL
========================================================= -->

    <div class="branding-card">

        <div class="branding-card-header">

            <div>

                <h2>General</h2>

                <p>
                    Configura la información general y elementos globales
                    del sitio.
                </p>

            </div>


            <button
                type="button"
                class="branding-save-button js-branding-save"
                data-section="general">

                Guardar

            </button>

        </div>


        <div class="branding-card-body">

            <div class="admin-form-grid">


                <!-- =================================================
				 SITE NAME
			================================================= -->

                <div class="admin-form-field">

                    <label for="site_name">
                        Site Name
                    </label>

                    <input
                        type="text"
                        id="site_name"
                        name="site_name"
                        value="<?= CHtml::encode($getBranding('site_name', 'MADE.BRANDS')); ?>"
                        class="branding-field">

                </div>


                <!-- =================================================
				 TAGLINE
			================================================= -->

                <div class="admin-form-field">

                    <label for="tagline">
                        Tagline
                    </label>

                    <input
                        type="text"
                        id="tagline"
                        name="tagline"
                        value="<?= CHtml::encode($getBranding('tagline', 'Llevamos grandes marcas a grandes personas')); ?>"
                        class="branding-field">

                </div>


                <!-- =================================================
				 TAGLINE MENU
			================================================= -->

                <div class="admin-form-field">

                    <label>
                        Tagline en el menú
                    </label>

                    <div class="branding-switch-field">

                        <label class="branding-switch">

                            <input
                                type="hidden"
                                name="tagline_menu"
                                value="0">

                            <input
                                type="checkbox"
                                name="tagline_menu"
                                value="1"
                                <?= $getBranding('tagline_menu', '0') == '1' ? 'checked' : ''; ?>>

                            <span class="branding-switch-slider"></span>

                        </label>


                        <span class="branding-switch-label">
                            Mostrar tagline en el menú
                        </span>

                    </div>

                </div>


                <!-- =================================================
				 TAGLINE FOOTER
			================================================= -->

                <div class="admin-form-field">

                    <label>
                        Tagline en el footer
                    </label>

                    <div class="branding-switch-field">

                        <label class="branding-switch">

                            <input
                                type="hidden"
                                name="tagline_footer"
                                value="0">

                            <input
                                type="checkbox"
                                name="tagline_footer"
                                value="1"
                                <?= $getBranding('tagline_footer', '0') == '1' ? 'checked' : ''; ?>>

                            <span class="branding-switch-slider"></span>

                        </label>


                        <span class="branding-switch-label">
                            Mostrar tagline en el footer
                        </span>

                    </div>

                </div>


                <!-- =================================================
				 FULL SHEET
			================================================= -->

                <div class="admin-form-field">

                    <label for="full_sheet">
                        Full Sheet URL
                    </label>


                    <input
                        type="url"
                        id="full_sheet"
                        name="full_sheet"
                        value="<?= CHtml::encode($getBranding('full_sheet', '')); ?>"
                        placeholder="https://..."
                        class="branding-field">


                    <?php

                    $fullSheet = $getBranding(
                        'full_sheet',
                        ''
                    );

                    ?>


                    <?php if ($fullSheet): ?>

                        <div class="branding-url-preview">

                            <a
                                href="<?= CHtml::encode($fullSheet); ?>"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="branding-file-link">

                                <span class="branding-file-link-icon">
                                    ↗
                                </span>

                                <span>
                                    Abrir Full Sheet
                                </span>

                            </a>

                        </div>

                    <?php endif; ?>

                </div>


            </div>

        </div>

    </div>


    <!-- =========================================================
		 TYPOGRAPHY
	========================================================= -->

    <div class="branding-card">

        <div class="branding-card-header">

            <div>

                <h2>Tipografía</h2>

                <p>
                    Selecciona las familias tipográficas de Google Fonts
                    para cada elemento del sitio.
                </p>

            </div>


            <button
                type="button"
                class="branding-save-button js-branding-save"
                data-section="typography">

                Guardar

            </button>

        </div>


        <div class="branding-card-body">

            <div class="admin-form-grid">


                <?php

                $fontFields = array(
                    'font_family' => 'Font Family',
                    'logo_font_family' => 'Logo Font Family',
                    'heading_font_family' => 'Heading Font Family',
                    'eyebrow_font_family' => 'Eyebrow Font Family',
                    'body_font_family' => 'Body Font Family',
                    'button_font_family' => 'Button Font Family',
                );

                ?>


                <?php foreach ($fontFields as $field => $label): ?>

                    <?php

                    $currentFont = $getBranding(
                        $field,
                        'Inter'
                    );

                    ?>


                    <div class="admin-form-field">

                        <label for="<?= CHtml::encode($field); ?>_search">
                            <?= CHtml::encode($label); ?>
                        </label>


                        <div
                            class="branding-font-selector"
                            data-font-field="<?= CHtml::encode($field); ?>"
                            data-value="<?= CHtml::encode($currentFont); ?>">


                            <div class="branding-font-search">

                                <span class="branding-font-search-icon">
                                    ⌕
                                </span>


                                <input
                                    type="text"
                                    id="<?= CHtml::encode($field); ?>_search"
                                    class="branding-field branding-font-search-input"
                                    placeholder="Buscar Google Font..."
                                    autocomplete="off"
                                    value="<?= CHtml::encode($currentFont); ?>">

                            </div>


                            <div class="branding-font-results"></div>


                            <input
                                type="hidden"
                                name="<?= CHtml::encode($field); ?>"
                                value="<?= CHtml::encode($currentFont); ?>"
                                class="branding-field branding-font-value">


                            <div class="branding-font-selected">

                                <div class="branding-font-selected-label">
                                    Seleccionada
                                </div>


                                <div
                                    class="branding-font-selected-preview"
                                    data-font-preview
                                    style="font-family: '<?= CHtml::encode($currentFont); ?>', sans-serif;">

                                    <?= CHtml::encode($currentFont); ?>

                                </div>

                            </div>


                        </div>

                    </div>

                <?php endforeach; ?>


            </div>

        </div>

    </div>



    <!-- =========================================================
		 TEXT COLORS
	========================================================= -->

    <div class="branding-card">

        <div class="branding-card-header">

            <div>

                <h2>Colores de texto</h2>

                <p>
                    Define los colores globales para títulos, eyebrows,
                    textos y separadores.
                </p>

            </div>


            <button
                type="button"
                class="branding-save-button js-branding-save"
                data-section="text">

                Guardar

            </button>

        </div>


        <div class="branding-card-body">

            <div class="admin-form-grid">


                <?php

                $textColors = array(

                    'heading_color' => array(
                        'label' => 'Heading Color',
                        'default' => '#111111',
                    ),

                    'eyebrow_color' => array(
                        'label' => 'Eyebrow Color',
                        'default' => '#666666',
                    ),

                    'body_text_color' => array(
                        'label' => 'Body Text Color',
                        'default' => '#444444',
                    ),

                    'separator_color' => array(
                        'label' => 'Separator Color',
                        'default' => '#D9D9D9',
                    ),

                );

                ?>


                <?php foreach ($textColors as $field => $config): ?>

                    <?php

                    $value = $getBranding(
                        $field,
                        $config['default']
                    );

                    ?>


                    <div class="admin-form-field">

                        <label for="<?= CHtml::encode($field); ?>">
                            <?= CHtml::encode($config['label']); ?>
                        </label>


                        <div class="branding-color-field">

                            <input
                                type="color"
                                id="<?= CHtml::encode($field); ?>_picker"
                                value="<?= CHtml::encode($value); ?>"
                                class="branding-color-picker">


                            <input
                                type="text"
                                id="<?= CHtml::encode($field); ?>"
                                name="<?= CHtml::encode($field); ?>"
                                value="<?= CHtml::encode($value); ?>"
                                class="branding-field branding-color-text">

                        </div>

                    </div>

                <?php endforeach; ?>


            </div>

        </div>

    </div>



    <!-- =========================================================
		 BUTTONS & CTA
	========================================================= -->

    <div class="branding-card">

        <div class="branding-card-header">

            <div>

                <h2>Botones & CTA</h2>

                <p>
                    Define los colores de los botones principales
                    y llamadas a la acción.
                </p>

            </div>


            <button
                type="button"
                class="branding-save-button js-branding-save"
                data-section="buttons">

                Guardar

            </button>

        </div>


        <div class="branding-card-body">

            <div class="admin-form-grid">


                <?php

                $buttonColors = array(

                    'contact_button_background_color' => array(
                        'label' => 'Contacto Menu — Background',
                        'default' => '#111111',
                    ),

                    'contact_button_text_color' => array(
                        'label' => 'Contacto Menu — Text',
                        'default' => '#FFFFFF',
                    ),

                    'category_button_background_color' => array(
                        'label' => 'Categorías — Background',
                        'default' => '#111111',
                    ),

                    'category_button_text_color' => array(
                        'label' => 'Categorías — Text',
                        'default' => '#FFFFFF',
                    ),

                    'cta_background_color' => array(
                        'label' => 'Escribenos — Background',
                        'default' => '#111111',
                    ),

                    'cta_text_color' => array(
                        'label' => 'Escribenos — Text',
                        'default' => '#FFFFFF',
                    ),

                );

                ?>


                <?php foreach ($buttonColors as $field => $config): ?>

                    <?php

                    $value = $getBranding(
                        $field,
                        $config['default']
                    );

                    ?>


                    <div class="admin-form-field">

                        <label for="<?= CHtml::encode($field); ?>">
                            <?= CHtml::encode($config['label']); ?>
                        </label>


                        <div class="branding-color-field">

                            <input
                                type="color"
                                id="<?= CHtml::encode($field); ?>_picker"
                                value="<?= CHtml::encode($value); ?>"
                                class="branding-color-picker">


                            <input
                                type="text"
                                id="<?= CHtml::encode($field); ?>"
                                name="<?= CHtml::encode($field); ?>"
                                value="<?= CHtml::encode($value); ?>"
                                class="branding-field branding-color-text">

                        </div>

                    </div>

                <?php endforeach; ?>


            </div>

        </div>

    </div>



    <!-- =========================================================
		 BACKGROUNDS
	========================================================= -->

    <div class="branding-card">

        <div class="branding-card-header">

            <div>

                <h2>Fondos</h2>

                <p>
                    Configura los colores de fondo globales del sitio.
                </p>

            </div>


            <button
                type="button"
                class="branding-save-button js-branding-save"
                data-section="backgrounds">

                Guardar

            </button>

        </div>


        <div class="branding-card-body">

            <div class="admin-form-grid">


                <?php

                $backgroundColors = array(

                    'body_background_color' => array(
                        'label' => 'Body Background',
                        'default' => '#FFFFFF',
                    ),

                    'header_background_color' => array(
                        'label' => 'Header Background',
                        'default' => '#FFFFFF',
                    ),

                    'section_background_color' => array(
                        'label' => 'Section Background',
                        'default' => '#FFFFFF',
                    ),

                    'section_alt_background_color' => array(
                        'label' => 'Section Alternate Background',
                        'default' => '#F5F5F5',
                    ),

                    'footer_background_color' => array(
                        'label' => 'Footer Background',
                        'default' => '#111111',
                    ),

                );

                ?>


                <?php foreach ($backgroundColors as $field => $config): ?>

                    <?php

                    $value = $getBranding(
                        $field,
                        $config['default']
                    );

                    ?>


                    <div class="admin-form-field">

                        <label for="<?= CHtml::encode($field); ?>">
                            <?= CHtml::encode($config['label']); ?>
                        </label>


                        <div class="branding-color-field">

                            <input
                                type="color"
                                id="<?= CHtml::encode($field); ?>_picker"
                                value="<?= CHtml::encode($value); ?>"
                                class="branding-color-picker">


                            <input
                                type="text"
                                id="<?= CHtml::encode($field); ?>"
                                name="<?= CHtml::encode($field); ?>"
                                value="<?= CHtml::encode($value); ?>"
                                class="branding-field branding-color-text">

                        </div>

                    </div>

                <?php endforeach; ?>


            </div>

        </div>

    </div>


</div>



<!-- =========================================================
     TOAST
========================================================= -->

<div
    id="branding-toast"
    class="branding-toast"
    aria-live="polite">
</div>



<script>
    (function($) {

        'use strict';


        /* =========================================================
           GOOGLE FONTS STATE
        ========================================================= */

        var brandingFonts = [];

        var fontsLoaded = false;

        var fontsLoading = false;

        var fontsLoadCallbacks = [];


        /* =========================================================
           LOAD GOOGLE FONT CSS
        ========================================================= */

        function loadFont(fontFamily) {

            if (!fontFamily) {
                return;
            }


            var existing = false;


            $('link[data-branding-font]').each(function() {

                if ($(this).data('branding-font') === fontFamily) {
                    existing = true;
                    return false;
                }

            });


            if (existing) {
                return;
            }


            var encodedFamily = encodeURIComponent(fontFamily)
                .replace(/%20/g, '+');


            var href =
                'https://fonts.googleapis.com/css2?family=' +
                encodedFamily +
                ':wght@300;400;500;600;700;800' +
                '&display=swap';


            var link = $('<link>', {
                rel: 'stylesheet',
                href: href
            });


            link.attr(
                'data-branding-font',
                fontFamily
            );


            $('head').append(link);

        }


        /* =========================================================
           LOAD FONT CATALOG
        ========================================================= */

        function loadGoogleFonts(callback) {

            if (fontsLoaded) {

                if (typeof callback === 'function') {
                    callback();
                }

                return;
            }


            if (typeof callback === 'function') {
                fontsLoadCallbacks.push(callback);
            }


            if (fontsLoading) {
                return;
            }


            fontsLoading = true;


            $.ajax({

                url: <?= CJSON::encode($fontsUrl); ?>,

                type: 'GET',

                dataType: 'json',

                cache: true,

                success: function(response) {

                    if (
                        !response ||
                        !response.success ||
                        !$.isArray(response.fonts)
                    ) {

                        showBrandingToast(
                            'No se pudieron cargar las Google Fonts.',
                            false
                        );

                        return;
                    }


                    brandingFonts = response.fonts;

                    fontsLoaded = true;


                    /*
                     * Load all currently selected fonts.
                     */
                    $('.branding-font-selector').each(function() {

                        var selector = $(this);

                        var currentFont =
                            selector
                            .find('.branding-font-value')
                            .val();


                        if (currentFont) {
                            loadFont(currentFont);
                            applyFont(selector, currentFont);
                        }

                    });


                    var callbacks = fontsLoadCallbacks.slice();

                    fontsLoadCallbacks = [];


                    $.each(callbacks, function(index, fn) {

                        fn();

                    });

                },

                error: function(xhr) {

                    fontsLoading = false;

                    showBrandingToast(
                        'No se pudo conectar con Google Fonts.',
                        false
                    );

                },

                complete: function() {

                    fontsLoading = false;

                }

            });

        }


        /* =========================================================
           FONT SEARCH
        ========================================================= */

        function searchFonts(query) {

            query = $.trim(query).toLowerCase();


            if (!query) {

                return brandingFonts.slice(0, 40);

            }


            return brandingFonts.filter(function(font) {

                return font.family
                    .toLowerCase()
                    .indexOf(query) !== -1;

            }).slice(0, 40);

        }


        /* =========================================================
           RENDER FONT RESULTS
        ========================================================= */

        function renderFontResults(selector) {

            var results =
                selector.find('.branding-font-results');


            var searchInput =
                selector.find('.branding-font-search-input');


            var query = searchInput.val();


            results.empty();


            var filteredFonts = searchFonts(query);


            if (!filteredFonts.length) {

                results
                    .append(
                        $('<div>', {
                            class: 'branding-font-result-empty',
                            text: 'No se encontraron fuentes.'
                        })
                    )
                    .addClass('is-open');

                return;
            }


            $.each(filteredFonts, function(index, font) {

                /*
                 * Load the actual Google Font before displaying it.
                 */
                loadFont(font.family);


                var result = $('<div>', {
                    class: 'branding-font-result'
                });


                var name = $('<div>', {
                    class: 'branding-font-result-name',
                    text: font.family
                });


                /*
                 * IMPORTANT:
                 * The result itself gets the selected Google Font.
                 */
                name.css(
                    'font-family',
                    '"' + font.family + '", sans-serif'
                );


                var category = $('<div>', {
                    class: 'branding-font-result-category',
                    text: font.category || ''
                });


                result.append(name);
                result.append(category);


                /*
                 * mousedown is intentional.
                 *
                 * If we use click, the input can lose focus first
                 * and the dropdown may disappear before selection.
                 */
                result.on('mousedown', function(e) {

                    e.preventDefault();

                    selectFont(
                        selector,
                        font.family
                    );

                });


                results.append(result);

            });


            results.addClass('is-open');

        }


        /* =========================================================
           APPLY FONT TO SELECTOR
        ========================================================= */

        function applyFont(selector, fontFamily) {

            if (!fontFamily) {
                return;
            }


            loadFont(fontFamily);


            var searchInput =
                selector.find('.branding-font-search-input');


            var preview =
                selector.find('[data-font-preview]');


            var fontValue =
                selector.find('.branding-font-value');


            /*
             * The search input itself uses the selected font.
             */
            searchInput.css(
                'font-family',
                '"' + fontFamily + '", sans-serif'
            );


            /*
             * The selected preview uses the actual font.
             */
            preview.css(
                'font-family',
                '"' + fontFamily + '", sans-serif'
            );


            /*
             * Make sure the hidden value contains only
             * the family name, e.g. "Roboto".
             */
            fontValue.val(fontFamily);

        }


        /* =========================================================
           SELECT FONT
        ========================================================= */

        function selectFont(selector, fontFamily) {

            var searchInput =
                selector.find('.branding-font-search-input');


            var preview =
                selector.find('[data-font-preview]');


            var hiddenInput =
                selector.find('.branding-font-value');


            var results =
                selector.find('.branding-font-results');


            /*
             * Load actual font.
             */
            loadFont(fontFamily);


            /*
             * Save selected value.
             */
            hiddenInput.val(fontFamily);


            selector.attr(
                'data-value',
                fontFamily
            );


            /*
             * Update search field.
             */
            searchInput.val(fontFamily);


            /*
             * Apply actual Google Font to search field.
             */
            searchInput.css(
                'font-family',
                '"' + fontFamily + '", sans-serif'
            );


            /*
             * Update selected preview.
             */
            preview
                .text(fontFamily)
                .css(
                    'font-family',
                    '"' + fontFamily + '", sans-serif'
                );


            /*
             * Close dropdown.
             */
            results.removeClass('is-open');

        }


        /* =========================================================
           FONT INPUT FOCUS
        ========================================================= */

        $('.branding-font-search-input').on(
            'focus',
            function() {

                var input = $(this);

                var selector =
                    input.closest('.branding-font-selector');


                /*
                 * If fonts are already loaded,
                 * render immediately.
                 */
                if (fontsLoaded) {

                    renderFontResults(selector);

                    return;

                }


                /*
                 * Otherwise wait until AJAX finishes.
                 */
                loadGoogleFonts(function() {

                    renderFontResults(selector);

                });

            }
        );


        /* =========================================================
           FONT INPUT SEARCH
        ========================================================= */

        $('.branding-font-search-input').on(
            'input',
            function() {

                var input = $(this);

                var selector =
                    input.closest('.branding-font-selector');


                if (!fontsLoaded) {

                    loadGoogleFonts(function() {

                        renderFontResults(selector);

                    });

                    return;

                }


                renderFontResults(selector);

            }
        );


        /* =========================================================
           CLOSE FONT DROPDOWNS
        ========================================================= */

        $(document).on('mousedown', function(e) {

            if (
                !$(e.target)
                .closest('.branding-font-selector')
                .length
            ) {

                $('.branding-font-results')
                    .removeClass('is-open');

            }

        });


        /* =========================================================
           INITIALIZE SELECTED FONTS
        ========================================================= */

        loadGoogleFonts();


        /* =========================================================
           COLOR PICKER -> TEXT
        ========================================================= */

        $('.branding-color-picker').on(
            'input change',
            function() {

                var picker = $(this);


                var textInputId =
                    picker
                    .attr('id')
                    .replace('_picker', '');


                var textInput =
                    $('#' + textInputId);


                textInput.val(
                    picker.val()
                );

            }
        );


        /* =========================================================
           TEXT -> COLOR PICKER
        ========================================================= */

        $('.branding-color-text').on(
            'input change',
            function() {

                var textInput = $(this);


                var pickerId =
                    textInput.attr('id') +
                    '_picker';


                var picker =
                    $('#' + pickerId);


                var value =
                    $.trim(textInput.val());


                if (
                    /^#[0-9A-Fa-f]{6}$/.test(value)
                ) {

                    picker.val(value);

                }

            }
        );


        /* =========================================================
           TOAST
        ========================================================= */

        function showBrandingToast(
            message,
            success
        ) {

            var toast =
                $('#branding-toast');


            toast
                .removeClass('success error')
                .addClass(
                    success ?
                    'success' :
                    'error'
                )
                .text(message)
                .addClass('show');


            setTimeout(function() {

                toast.removeClass('show');

            }, 3000);

        }


        /* =========================================================
           SAVE SECTION
        ========================================================= */

        $('.js-branding-save').on(
            'click',
            function(e) {

                e.preventDefault();


                var button = $(this);

                var section =
                    button.data('section');


                var card =
                    button.closest('.branding-card');


                var data = {
                    section: section
                };


                /*
                 * Regular fields.
                 */
                card.find('.branding-field[name]').each(function() {

                    var field = $(this);

                    data[field.attr('name')] = field.val();

                });


                /*
                 * Checkboxes / switches.
                 */
                card.find('input[type="checkbox"][name]').each(function() {

                    var field = $(this);

                    data[field.attr('name')] = field.is(':checked') ?
                        '1' :
                        '0';

                });


                var originalText =
                    button.text();


                button
                    .prop('disabled', true)
                    .text('Guardando...');


                $.ajax({

                    url: <?= CJSON::encode($editUrl); ?>,

                    type: 'POST',

                    dataType: 'json',

                    data: data,

                    success: function(response) {

                        if (
                            response &&
                            response.success
                        ) {

                            showBrandingToast(
                                response.message ||
                                'Guardado correctamente.',
                                true
                            );

                        } else {

                            showBrandingToast(
                                response.message ||
                                'No se pudo guardar.',
                                false
                            );

                        }

                    },

                    error: function() {

                        showBrandingToast(
                            'No se pudo guardar el Branding.',
                            false
                        );

                    },

                    complete: function() {

                        button
                            .prop('disabled', false)
                            .text(originalText);

                    }

                });

            }
        );


    })(jQuery);
</script>