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

<div class="container">


    <div class="page-header">

        <div>
            <span class="section-label">CONTENIDO DEL SITIO</span>

            <h1>Intro</h1>

            <p class="text-muted">
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

        <div class="admin-card">




            <div class="admin-card-body">

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

            <div class="form-actions">

                <button
                    type="submit"
                    class="btn btn-primary"
                    id="save-intro">

                    <i class="fa fa-save"></i>

                    <?php echo $isNewRecord
                        ? 'Crear sección'
                        : 'Guardar cambios';
                    ?>

                </button>

            </div>

        <?php endif; ?>

    </form>

</div>


<style>
    .language-tabs,
    .stat-language-tabs {
        display: flex;
        gap: 5px;
        border-bottom: 1px solid #e5e7eb;
        margin-bottom: 24px;
        overflow-x: auto;
    }

    .language-tab,
    .stat-language-tab {
        border: 0;
        background: transparent;
        padding: 10px 15px;
        cursor: pointer;
        color: #6b7280;
        border-bottom: 2px solid transparent;
        white-space: nowrap;
    }

    .language-tab span,
    .stat-language-tab span {
        margin-left: 5px;
        font-size: 11px;
        opacity: .65;
    }

    .language-tab.is-active,
    .stat-language-tab.is-active {
        color: #111827;
        border-bottom-color: #111827;
    }

    .language-panel,
    .stat-language-panel {
        display: none;
    }

    .language-panel.is-active,
    .stat-language-panel.is-active {
        display: block;
    }

    .form-grid {
        display: grid;
        gap: 20px;
    }

    .form-group-full {
        grid-column: 1 / -1;
    }

    .form-group-small {
        max-width: 180px;
    }

    .form-group label {
        display: block;
        margin-bottom: 7px;
        font-weight: 600;
        font-size: 13px;
    }

    .form-control {
        width: 100%;
        box-sizing: border-box;
        padding: 10px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        background: #fff;
        font-size: 14px;
    }

    .form-control:focus {
        outline: none;
        border-color: #111827;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    .form-control-title {
        min-height: 80px;
    }

    .empty-state {
        padding: 35px 20px;
        text-align: center;
        color: #6b7280;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        padding: 10px 0 40px;
    }

    @media (max-width: 768px) {

        .form-grid {
            grid-template-columns: 1fr !important;
        }

        .form-group-small {
            max-width: none;
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