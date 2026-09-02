<?php

/* @var $this IntroController */
/* @var $model IntroSections */
/* @var $languages Languages[] */
/* @var $translations IntroSectionTranslations[] */

$this->pageTitle = 'Intro';

$isNewRecord = $model->isNewRecord;

$formAction = $this->createUrl('update');


$this->breadcrumbs = array(
    'Intro' => array('index'),
    'Administrar',
);


?>

<div class="admin-form-page">

    <div class="admin-page-header">

        <div>
            <span class="admin-page-header__eyebrow">CONTENIDO DEL SITIO</span>

            <h1 class="admin-page-header__title">Intro</h1>

            <p class="admin-page-header__description">
                Administra el contenido principal de la sección Intro.
            </p>
        </div>

    </div>

    <?php if (Yii::app()->user->hasFlash('success')): ?>

        <div class="alert alert-success">
            <?php echo CHtml::encode(Yii::app()->user->getFlash('success')); ?>
        </div>

    <?php endif; ?>


    <?php if (Yii::app()->user->hasFlash('error')): ?>

        <div class="alert alert-danger">
            <?php echo CHtml::encode(Yii::app()->user->getFlash('error')); ?>
        </div>

    <?php endif; ?>


    <form
        id="intro-form"
        method="post"
        action="<?php echo CHtml::encode($formAction); ?>"
        autocomplete="off">

        <?php echo CHtml::hiddenField(
            'YII_CSRF_TOKEN',
            Yii::app()->request->csrfToken
        ); ?>


        <!-- =========================================================
             TRADUCCIONES
        ========================================================= -->

        <div class="admin-form-card">

            <div class="admin-form-card__header">

                <div class="admin-form-card__heading">

                    <div class="admin-form-card__icon">
                        <i class="fas fa-language"></i>
                    </div>

                    <div>
                        <h2 class="admin-form-card__title">
                            Traducciones
                        </h2>

                        <p class="admin-form-card__description">
                            Administra el contenido de Intro por idioma.
                        </p>
                    </div>

                </div>

            </div>

            <div class="admin-form-card__body">

                <?php if (!empty($languages)): ?>

                    <!-- =================================================
                         TABS DE IDIOMAS
                    ================================================== -->

                    <div class="language-tabs">

                        <?php foreach ($languages as $languageIndex => $language): ?>

                            <?php

                            $languageId = (int) $language->id;

                            $languageCode = '';

                            if (isset($language->code)) {

                                $languageCode = $language->code;
                            } elseif (isset($language->language_code)) {

                                $languageCode = $language->language_code;
                            }

                            $languageName = '';

                            if (isset($language->name)) {

                                $languageName = $language->name;
                            } elseif (isset($language->language)) {

                                $languageName = $language->language;
                            } else {

                                $languageName = 'Idioma #' . $languageId;
                            }

                            ?>

                            <button
                                type="button"
                                class="language-tab <?php echo $languageIndex === 0 ? 'is-active' : ''; ?>"
                                data-language-tab="<?php echo $languageId; ?>">

                                <?php echo CHtml::encode($languageName); ?>

                                <?php if ($languageCode !== ''): ?>

                                    <span>
                                        <?php echo CHtml::encode(strtoupper($languageCode)); ?>
                                    </span>

                                <?php endif; ?>

                            </button>

                        <?php endforeach; ?>

                    </div>


                    <!-- =================================================
                         PANELES DE IDIOMAS
                    ================================================== -->

                    <div class="language-panels">

                        <?php foreach ($languages as $languageIndex => $language): ?>

                            <?php

                            $languageId = (int) $language->id;

                            $translation = isset($translations[$languageId])
                                ? $translations[$languageId]
                                : null;

                            $eyebrow = $translation
                                ? $translation->eyebrow
                                : '';

                            $eyebrowSize = $translation
                                ? $translation->eyebrow_size
                                : '';

                            $title = $translation
                                ? $translation->title
                                : '';

                            $titleSize = $translation
                                ? $translation->title_size
                                : '';

                            $text = $translation
                                ? $translation->text
                                : '';

                            $textSize = $translation
                                ? $translation->text_size
                                : '';

                            ?>

                            <div
                                class="language-panel <?php echo $languageIndex === 0 ? 'is-active' : ''; ?>"
                                data-language-panel="<?php echo $languageId; ?>">


                                <!-- =====================================
                                     CONTENIDO
                                ====================================== -->

                                <div
                                    class="form-grid"
                                    style="grid-template-columns: repeat(4, minmax(0, 1fr));">


                                    <!-- EYEBROW -->

                                    <div class="form-group form-group-full">

                                        <label>
                                            Eyebrow
                                        </label>

                                        <input
                                            type="text"
                                            name="translations[<?php echo $languageId; ?>][eyebrow]"
                                            class="form-control"
                                            value="<?php echo CHtml::encode($eyebrow); ?>">

                                    </div>


                                    <!-- TÍTULO -->

                                    <div class="form-group form-group-full">

                                        <label>
                                            Título
                                        </label>

                                        <textarea
                                            name="translations[<?php echo $languageId; ?>][title]"
                                            class="form-control form-control-title"
                                            rows="3"><?php echo CHtml::encode($title); ?></textarea>

                                    </div>


                                    <!-- TEXTO -->

                                    <div class="form-group form-group-full">

                                        <label>
                                            Texto
                                        </label>

                                        <textarea
                                            name="translations[<?php echo $languageId; ?>][text]"
                                            class="form-control"
                                            rows="5"><?php echo CHtml::encode($text); ?></textarea>

                                    </div>


                                    <!-- =================================
                                         TAMAÑOS
                                    ================================== -->

                                    <div class="form-group form-group-small">

                                        <label>
                                            Tamaño del eyebrow
                                        </label>

                                        <input
                                            type="text"
                                            name="translations[<?php echo $languageId; ?>][eyebrow_size]"
                                            class="form-control"
                                            value="<?php echo CHtml::encode($eyebrowSize); ?>"
                                            placeholder="Ej. 14px">

                                    </div>


                                    <div class="form-group form-group-small">

                                        <label>
                                            Tamaño del título
                                        </label>

                                        <input
                                            type="text"
                                            name="translations[<?php echo $languageId; ?>][title_size]"
                                            class="form-control"
                                            value="<?php echo CHtml::encode($titleSize); ?>"
                                            placeholder="Ej. 48px">

                                    </div>


                                    <div class="form-group form-group-small">

                                        <label>
                                            Tamaño del texto
                                        </label>

                                        <input
                                            type="text"
                                            name="translations[<?php echo $languageId; ?>][text_size]"
                                            class="form-control"
                                            value="<?php echo CHtml::encode($textSize); ?>"
                                            placeholder="Ej. 18px">

                                    </div>


                                </div>

                            </div>

                        <?php endforeach; ?>

                    </div>

                <?php else: ?>

                    <div class="empty-state">

                        <p>
                            No hay idiomas configurados.
                        </p>

                    </div>

                <?php endif; ?>

            </div>

        </div>


        <!-- =========================================================
             ACCIONES
        ========================================================= -->

        <?php if (!empty($languages)): ?>

            <div class="admin-form-card admin-form-card--actions">

                <div class="admin-form-card__footer">

                    <div class="admin-form-footer__note">

                        <span class="required">*</span>

                        Campos obligatorios

                    </div>

                    <div class="admin-form-actions">

                        <button
                            type="submit"
                            class="admin-form-button admin-form-button--primary"
                            id="save-intro">

                            <i class="fas fa-save"></i>

                            <?php echo $isNewRecord
                                ? 'Crear sección'
                                : 'Guardar cambios';
                            ?>

                        </button>

                    </div>

                </div>

            </div>

        <?php endif; ?>

    </form>

</div>


<style>
    /* =========================================================
       ADMIN PAGE
    ========================================================= */

    .admin-form-page {
        width: 100%;
        max-width: 1100px;
        margin: 0 auto;
    }

    .admin-page-header {
        margin-bottom: 28px;
    }

    .admin-page-header__eyebrow {
        display: block;
        margin-bottom: 8px;
        color: #6b7280;
        font-size: 12px;
        font-weight: 500;
        letter-spacing: .08em;
        line-height: 1.4;
        text-transform: uppercase;
    }

    .admin-page-header__title {
        margin: 0;
        color: #111827;
        font-size: 32px;
        font-weight: 400;
        line-height: 1.2;
    }

    .admin-page-header__description {
        margin: 12px 0 0;
        color: #6b7280;
        font-size: 14px;
        line-height: 1.5;
    }


    /* =========================================================
       ALERTS
    ========================================================= */

    .admin-form-page .alert {
        margin: 0 0 20px;
        padding: 12px 14px;
        border-radius: 7px;
        font-size: 13px;
        line-height: 1.5;
    }

    .admin-form-page .alert-success {
        border: 1px solid #bbf7d0;
        background: #f0fdf4;
        color: #166534;
    }

    .admin-form-page .alert-danger {
        border: 1px solid #fecaca;
        background: #fef2f2;
        color: #991b1b;
    }


    /* =========================================================
       CARDS
    ========================================================= */

    .admin-form-card {
        overflow: hidden;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, .03);
    }

    .admin-form-card--actions {
        margin-top: 20px;
    }

    .admin-form-card__header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        padding: 18px 20px;
        border-bottom: 1px solid #e5e7eb;
    }

    .admin-form-card__heading {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .admin-form-card__icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        flex-shrink: 0;
        border-radius: 7px;
        background: #f3f4f6;
        color: #374151;
        font-size: 14px;
    }

    .admin-form-card__title {
        margin: 0;
        color: #111827;
        font-size: 15px;
        font-weight: 600;
        line-height: 1.3;
    }

    .admin-form-card__description {
        margin: 2px 0 0;
        color: #9ca3af;
        font-size: 12px;
        line-height: 1.4;
    }

    .admin-form-card__body {
        padding: 24px 20px;
    }

    .admin-form-card__footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 16px 20px;
        border-top: 1px solid #e5e7eb;
        background: #f9fafb;
    }

    .admin-form-footer__note {
        color: #9ca3af;
        font-size: 11px;
    }

    .admin-form-footer__note .required {
        color: #dc2626;
        font-weight: 700;
    }

    .admin-form-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .admin-form-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        height: 38px;
        padding: 0 14px;
        box-sizing: border-box;
        border: 1px solid transparent;
        border-radius: 7px;
        cursor: pointer;
        font-family: inherit;
        font-size: 13px;
        font-weight: 600;
        line-height: 1;
        text-decoration: none !important;
    }

    .admin-form-button--primary {
        background: #111827;
        border-color: #111827;
        color: #fff !important;
    }

    .admin-form-button--primary:hover {
        background: #1f2937;
        border-color: #1f2937;
        color: #fff !important;
    }

    .admin-form-button:disabled {
        opacity: .7;
        cursor: wait;
    }


    /* =========================================================
       LANGUAGE TABS
    ========================================================= */

    .language-tabs {
        display: flex;
        gap: 0;
        margin: -24px -20px 24px;
        padding: 0 20px;
        border-bottom: 1px solid #e5e7eb;
        overflow-x: auto;
    }

    .language-tab {
        position: relative;
        border: 0;
        border-bottom: 2px solid transparent;
        background: transparent;
        padding: 14px 16px;
        cursor: pointer;
        color: #9ca3af;
        font-family: inherit;
        font-size: 13px;
        font-weight: 500;
        line-height: 1.4;
        white-space: nowrap;
        transition:
            color .15s ease,
            border-color .15s ease;
    }

    .language-tab:first-child {
        padding-left: 0;
    }

    .language-tab span {
        margin-left: 5px;
        color: #9ca3af;
        font-size: 11px;
        font-weight: 500;
    }

    .language-tab:hover {
        color: #374151;
    }

    .language-tab.is-active {
        color: #111827;
        border-bottom-color: #111827;
    }

    .language-tab.is-active span {
        color: #6b7280;
    }

    .language-tab:focus {
        outline: none;
    }


    /* =========================================================
       LANGUAGE PANELS / FORM
    ========================================================= */

    .language-panel {
        display: none;
    }

    .language-panel.is-active {
        display: block;
    }

    .form-grid {
        display: grid;
        gap: 20px 18px;
    }

    .form-group {
        min-width: 0;
    }

    .form-group-full {
        grid-column: 1 / -1;
    }

    .form-group-small {
        max-width: 180px;
    }

    .form-group label {
        display: block;
        margin: 0 0 7px;
        color: #374151;
        font-size: 12px;
        font-weight: 600;
        line-height: 1.4;
    }

    .form-control {
        display: block;
        width: 100%;
        min-height: 40px;
        box-sizing: border-box;
        padding: 9px 11px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        outline: none;
        background: #fff;
        color: #374151;
        font-family: inherit;
        font-size: 13px;
        line-height: 1.5;
        transition:
            border-color .15s ease,
            box-shadow .15s ease,
            background-color .15s ease;
    }

    .form-control:focus {
        border-color: #9ca3af;
        box-shadow: 0 0 0 3px rgba(17, 24, 39, .06);
    }

    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }

    .form-control-title {
        min-height: 90px !important;
    }

    .empty-state {
        padding: 35px 20px;
        border: 1px dashed #d1d5db;
        border-radius: 8px;
        background: #f9fafb;
        color: #9ca3af;
        font-size: 12px;
        text-align: center;
    }

    .empty-state p {
        margin: 0;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 768px) {

        .admin-form-page {
            max-width: none;
        }

        .admin-page-header {
            margin-bottom: 20px;
        }

        .admin-page-header__title {
            font-size: 28px;
        }

        .admin-form-card__header {
            align-items: flex-start;
            flex-direction: column;
        }

        .admin-form-card__body {
            padding: 20px 16px;
        }

        .language-tabs {
            margin: -20px -16px 20px;
            padding: 0 16px;
        }

        .language-tab {
            padding: 13px 12px;
        }

        .language-tab:first-child {
            padding-left: 0;
        }

        .form-grid {
            grid-template-columns: 1fr !important;
        }

        .form-group-full {
            grid-column: auto;
        }

        .form-group-small {
            max-width: none;
        }

        .admin-form-card__footer {
            align-items: stretch;
            flex-direction: column;
        }

        .admin-form-actions {
            width: 100%;
        }

        .admin-form-button {
            width: 100%;
        }
    }
</style>


<script>
    (function() {

        'use strict';


        /*
         * =========================================================
         * TABS DE IDIOMAS
         * =========================================================
         */

        var tabs = document.querySelectorAll(
            '[data-language-tab]'
        );

        var panels = document.querySelectorAll(
            '[data-language-panel]'
        );


        Array.prototype.forEach.call(
            tabs,
            function(tab) {

                tab.addEventListener(
                    'click',
                    function() {

                        var languageId =
                            this.getAttribute(
                                'data-language-tab'
                            );


                        Array.prototype.forEach.call(
                            tabs,
                            function(item) {

                                item.classList.remove(
                                    'is-active'
                                );

                            }
                        );


                        Array.prototype.forEach.call(
                            panels,
                            function(panel) {

                                panel.classList.remove(
                                    'is-active'
                                );

                            }
                        );


                        this.classList.add(
                            'is-active'
                        );


                        var panel =
                            document.querySelector(
                                '[data-language-panel="' +
                                languageId +
                                '"]'
                            );


                        if (panel) {

                            panel.classList.add(
                                'is-active'
                            );

                        }

                    }
                );

            }
        );


        /*
         * =========================================================
         * EVITAR DOBLE ENVÍO
         * =========================================================
         */

        var form =
            document.getElementById(
                'intro-form'
            );


        if (form) {

            form.addEventListener(
                'submit',
                function() {

                    var button =
                        document.getElementById(
                            'save-intro'
                        );


                    if (!button) {
                        return;
                    }


                    button.disabled = true;


                    button.innerHTML =
                        '<i class="fa fa-spinner fa-spin"></i> Guardando...';

                }
            );

        }

    })();
</script>