<?php
/* @var $this FooterController */
/* @var $cta ContactCta */
/* @var $items ContactItem[] */
/* @var $languages Language[] */
/* @var $fontAwesomeIcons array */
?>

<?php
/*
 * ============================================================
 * HELPER PARA SELECTOR DE ICONOS
 * ============================================================
 */

$renderIconPicker = function ($inputId, $value) {

    $pickerId = $inputId . '-picker';

    $selectedIcon = trim((string) $value);
?>

    <div
        class="admin-icon-picker"
        id="<?php echo CHtml::encode($pickerId); ?>"
        data-input-id="<?php echo CHtml::encode($inputId); ?>">

        <input
            type="hidden"
            name="icon"
            id="<?php echo CHtml::encode($inputId); ?>"
            value="<?php echo CHtml::encode($selectedIcon); ?>">


        <div class="admin-icon-picker__selected">

            <div class="admin-icon-picker__selected-preview">

                <i
                    class="<?php echo CHtml::encode($selectedIcon); ?>"
                    data-icon-preview></i>

            </div>


            <div class="admin-icon-picker__selected-info">

                <span class="admin-icon-picker__selected-label">
                    Icono seleccionado
                </span>

                <span
                    class="admin-icon-picker__selected-value"
                    data-icon-selected-value>
                    <?php
                    echo CHtml::encode(
                        $selectedIcon
                            ? $selectedIcon
                            : 'Ninguno'
                    );
                    ?>
                </span>

            </div>


            <button
                type="button"
                class="admin-icon-picker__clear"
                data-icon-clear>
                Limpiar
            </button>

        </div>


        <div class="admin-icon-picker__search">

            <input
                type="text"
                class="form-control"
                placeholder="Buscar icono..."
                autocomplete="off"
                data-icon-search>

        </div>


        <div
            class="admin-icon-picker__grid"
            data-icon-grid></div>


        <div
            class="admin-icon-picker__empty"
            data-icon-empty>
            No se encontraron iconos.
        </div>

    </div>

<?php
};
?>



<div class="page-header">

    <h1>Footer</h1>

</div>



<?php if (Yii::app()->user->hasFlash('success')): ?>

    <div class="alert alert-success">

        <?php
        echo Yii::app()->user->getFlash('success');
        ?>

    </div>

<?php endif; ?>



<?php if (Yii::app()->user->hasFlash('error')): ?>

    <div class="alert alert-danger">

        <?php
        echo Yii::app()->user->getFlash('error');
        ?>

    </div>

<?php endif; ?>



<!-- =========================================================
     CTA
========================================================= -->

<div class="card footer-card">

    <div class="card-header">

        <div>

            <h3>Call To Action</h3>

            <p>
                Contenido principal del CTA del footer.
            </p>

        </div>

    </div>


    <div class="card-body">


        <!-- =====================================================
             DATOS GENERALES DEL CTA
        ====================================================== -->

        <form
            method="post"
            action="<?php echo $this->createUrl('saveCta'); ?>"
            class="footer-general-form">

            <div class="row">


                <div class="col-md-6">

                    <div class="form-group">

                        <label>Icono</label>

                        <?php
                        $renderIconPicker(
                            'cta_icon',
                            $cta->icon
                        );
                        ?>

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="form-group">

                        <label>URL</label>

                        <input
                            type="text"
                            name="url"
                            class="form-control"
                            value="<?php
                                    echo CHtml::encode($cta->url);
                                    ?>"
                            placeholder="https://...">

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="form-group">

                        <label>Estado</label>

                        <div class="footer-switch">

                            <label class="switch">

                                <input
                                    type="checkbox"
                                    name="is_active"
                                    value="1"
                                    <?php
                                    echo $cta->is_active
                                        ? 'checked'
                                        : '';
                                    ?>>

                                <span class="slider round"></span>

                            </label>


                            <span>

                                <?php
                                echo $cta->is_active
                                    ? 'Activo'
                                    : 'Inactivo';
                                ?>

                            </span>

                        </div>

                    </div>

                </div>

            </div>


            <div class="form-actions">

                <button
                    type="submit"
                    class="btn btn-primary">
                    Guardar CTA
                </button>

            </div>

        </form>



        <!-- =====================================================
             CTA - TRADUCCIONES
        ====================================================== -->

        <div class="language-section">


            <ul
                class="language-tabs"
                role="tablist">

                <?php foreach ($languages as $index => $language): ?>

                    <?php

                    $languageCode = isset($language->code)
                        ? $language->code
                        : (
                            isset($language->iso_code)
                            ? $language->iso_code
                            : ''
                        );

                    $languageName = isset($language->name)
                        ? $language->name
                        : $languageCode;

                    ?>


                    <li
                        class="<?php
                                echo $index === 0
                                    ? 'active'
                                    : '';
                                ?>">

                        <a
                            href="#cta-language-<?php echo (int) $language->id; ?>"
                            class="language-tab-link"
                            data-tab-target="#cta-language-<?php echo (int) $language->id; ?>">

                            <span class="language-code">

                                <?php
                                echo CHtml::encode(
                                    strtoupper($languageCode)
                                );
                                ?>

                            </span>


                            <span class="language-name">

                                <?php
                                echo CHtml::encode(
                                    $languageName
                                );
                                ?>

                            </span>

                        </a>

                    </li>

                <?php endforeach; ?>

            </ul>



            <div class="tab-content">

                <?php foreach ($languages as $index => $language): ?>

                    <?php

                    $translation =
                        ContactCtaTranslations::model()
                        ->findByAttributes(array(
                            'contact_cta_id' => $cta->id,
                            'language_id' => $language->id,
                        ));

                    ?>


                    <div
                        id="cta-language-<?php echo (int) $language->id; ?>"
                        class="tab-pane <?php
                                        echo $index === 0
                                            ? 'active'
                                            : '';
                                        ?>">

                        <form
                            method="post"
                            action="<?php echo $this->createUrl('saveCtaTranslation'); ?>"
                            class="language-form">

                            <input
                                type="hidden"
                                name="contact_cta_id"
                                value="<?php echo (int) $cta->id; ?>">


                            <input
                                type="hidden"
                                name="language_id"
                                value="<?php echo (int) $language->id; ?>">



                            <!-- TÍTULO -->

                            <div class="row">

                                <div class="col-md-8">

                                    <div class="form-group">

                                        <label>Título</label>

                                        <input
                                            type="text"
                                            name="title"
                                            class="form-control"
                                            value="<?php
                                                    echo $translation
                                                        ? CHtml::encode(
                                                            $translation->title
                                                        )
                                                        : '';
                                                    ?>"
                                            placeholder="Título del CTA">

                                    </div>

                                </div>


                                <div class="col-md-4">

                                    <div class="form-group">

                                        <label>
                                            Tamaño del título
                                        </label>

                                        <input
                                            type="text"
                                            name="title_size"
                                            class="form-control"
                                            value="<?php
                                                    echo $translation
                                                        ? CHtml::encode(
                                                            $translation->title_size
                                                        )
                                                        : '';
                                                    ?>"
                                            placeholder="Ej. 32px">

                                    </div>

                                </div>

                            </div>



                            <!-- TEXTO -->

                            <div class="row">

                                <div class="col-md-8">

                                    <div class="form-group">

                                        <label>Texto</label>

                                        <textarea
                                            name="text"
                                            class="form-control"
                                            rows="4"
                                            placeholder="Texto del CTA"><?php
                                                                        echo $translation
                                                                            ? CHtml::encode(
                                                                                $translation->text
                                                                            )
                                                                            : '';
                                                                        ?></textarea>

                                    </div>

                                </div>


                                <div class="col-md-4">

                                    <div class="form-group">

                                        <label>
                                            Tamaño del texto
                                        </label>

                                        <input
                                            type="text"
                                            name="text_size"
                                            class="form-control"
                                            value="<?php
                                                    echo $translation
                                                        ? CHtml::encode(
                                                            $translation->text_size
                                                        )
                                                        : '';
                                                    ?>"
                                            placeholder="Ej. 16px">

                                    </div>

                                </div>

                            </div>



                            <!-- BOTÓN -->

                            <div class="row">

                                <div class="col-md-8">

                                    <div class="form-group">

                                        <label>
                                            Texto del botón
                                        </label>

                                        <input
                                            type="text"
                                            name="button_text"
                                            class="form-control"
                                            value="<?php
                                                    echo $translation
                                                        ? CHtml::encode(
                                                            $translation->button_text
                                                        )
                                                        : '';
                                                    ?>"
                                            placeholder="Texto del botón">

                                    </div>

                                </div>


                                <div class="col-md-4">

                                    <div class="form-group">

                                        <label>
                                            Tamaño del botón
                                        </label>

                                        <input
                                            type="text"
                                            name="button_text_size"
                                            class="form-control"
                                            value="<?php
                                                    echo $translation
                                                        ? CHtml::encode(
                                                            $translation->button_text_size
                                                        )
                                                        : '';
                                                    ?>"
                                            placeholder="Ej. 16px">

                                    </div>

                                </div>

                            </div>



                            <div class="language-form-actions">

                                <button
                                    type="submit"
                                    class="btn btn-primary">

                                    Guardar Idioma

                                </button>

                            </div>

                        </form>

                    </div>

                <?php endforeach; ?>

            </div>

        </div>

    </div>

</div>



<!-- =========================================================
     CONTACT ITEMS
========================================================= -->

<div class="section-heading">

    <div>

        <h2>Contact Items</h2>

        <p>
            Elementos de contacto que aparecerán en el footer.
        </p>

    </div>

</div>



<?php foreach ($items as $item): ?>

    <div class="card footer-card contact-item-card">

        <div class="card-header">

            <div>

                <h3>
                    Contact Item #<?php echo (int) $item->id; ?>
                </h3>

                <p>
                    Configuración y traducciones del elemento.
                </p>

            </div>


            <button
                type="button"
                class="btn btn-danger btn-delete-item"
                data-id="<?php echo (int) $item->id; ?>">
                Eliminar
            </button>

        </div>



        <div class="card-body">


            <!-- =================================================
                 DATOS GENERALES DEL ITEM
            ================================================== -->

            <form
                method="post"
                action="<?php echo $this->createUrl('saveItem'); ?>"
                class="footer-general-form">

                <input
                    type="hidden"
                    name="id"
                    value="<?php echo (int) $item->id; ?>">


                <div class="row">


                    <div class="col-md-6">

                        <div class="form-group">

                            <label>Icono</label>

                            <?php
                            $renderIconPicker(
                                'item_' . $item->id . '_icon',
                                $item->icon
                            );
                            ?>

                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="form-group">

                            <label>Orden</label>

                            <input
                                type="number"
                                name="sort_order"
                                class="form-control"
                                value="<?php
                                        echo (int) $item->sort_order;
                                        ?>">

                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="form-group">

                            <label>Estado</label>

                            <div class="footer-switch">

                                <label class="switch">

                                    <input
                                        type="checkbox"
                                        name="is_active"
                                        value="1"
                                        <?php
                                        echo $item->is_active
                                            ? 'checked'
                                            : '';
                                        ?>>

                                    <span class="slider round"></span>

                                </label>


                                <span>

                                    <?php
                                    echo $item->is_active
                                        ? 'Activo'
                                        : 'Inactivo';
                                    ?>

                                </span>

                            </div>

                        </div>

                    </div>

                </div>


                <div class="form-actions">

                    <button
                        type="submit"
                        class="btn btn-primary">
                        Guardar Item
                    </button>

                </div>

            </form>



            <!-- =================================================
                 ITEM - TRADUCCIONES
            ================================================== -->

            <div class="language-section">


                <ul
                    class="language-tabs"
                    role="tablist">

                    <?php foreach ($languages as $index => $language): ?>

                        <?php

                        $languageCode = isset($language->code)
                            ? $language->code
                            : (
                                isset($language->iso_code)
                                ? $language->iso_code
                                : ''
                            );

                        $languageName = isset($language->name)
                            ? $language->name
                            : $languageCode;

                        ?>


                        <li
                            class="<?php
                                    echo $index === 0
                                        ? 'active'
                                        : '';
                                    ?>">

                            <a
                                href="#item-<?php echo (int) $item->id; ?>-language-<?php echo (int) $language->id; ?>"
                                class="language-tab-link"
                                data-tab-target="#item-<?php echo (int) $item->id; ?>-language-<?php echo (int) $language->id; ?>">

                                <span class="language-code">

                                    <?php
                                    echo CHtml::encode(
                                        strtoupper(
                                            $languageCode
                                        )
                                    );
                                    ?>

                                </span>


                                <span class="language-name">

                                    <?php
                                    echo CHtml::encode(
                                        $languageName
                                    );
                                    ?>

                                </span>

                            </a>

                        </li>

                    <?php endforeach; ?>

                </ul>



                <div class="tab-content">

                    <?php foreach ($languages as $index => $language): ?>

                        <?php

                        $translation =
                            ContactItemTranslations::model()
                            ->findByAttributes(array(
                                'contact_item_id' => $item->id,
                                'language_id' => $language->id,
                            ));

                        ?>


                        <div
                            id="item-<?php echo (int) $item->id; ?>-language-<?php echo (int) $language->id; ?>"
                            class="tab-pane <?php
                                            echo $index === 0
                                                ? 'active'
                                                : '';
                                            ?>">

                            <form
                                method="post"
                                action="<?php echo $this->createUrl('saveItemTranslation'); ?>"
                                class="language-form">

                                <input
                                    type="hidden"
                                    name="contact_item_id"
                                    value="<?php echo (int) $item->id; ?>">


                                <input
                                    type="hidden"
                                    name="language_id"
                                    value="<?php echo (int) $language->id; ?>">


                                <!-- LABEL -->

                                <div class="row">

                                    <div class="col-md-8">

                                        <div class="form-group">

                                            <label>Label</label>

                                            <input
                                                type="text"
                                                name="label"
                                                class="form-control"
                                                value="<?php
                                                        echo $translation
                                                            ? CHtml::encode(
                                                                $translation->label
                                                            )
                                                            : '';
                                                        ?>"
                                                placeholder="Label">

                                        </div>

                                    </div>


                                    <div class="col-md-4">

                                        <div class="form-group">

                                            <label>
                                                Tamaño del label
                                            </label>

                                            <input
                                                type="text"
                                                name="label_size"
                                                class="form-control"
                                                value="<?php
                                                        echo $translation
                                                            ? CHtml::encode(
                                                                $translation->label_size
                                                            )
                                                            : '';
                                                        ?>"
                                                placeholder="Ej. 14px">

                                        </div>

                                    </div>

                                </div>



                                <!-- VALUE -->

                                <div class="row">

                                    <div class="col-md-8">

                                        <div class="form-group">

                                            <label>Valor</label>

                                            <textarea
                                                name="value"
                                                class="form-control"
                                                rows="3"
                                                placeholder="Valor"><?php
                                                                    echo $translation
                                                                        ? CHtml::encode(
                                                                            $translation->value
                                                                        )
                                                                        : '';
                                                                    ?></textarea>

                                        </div>

                                    </div>


                                    <div class="col-md-4">

                                        <div class="form-group">

                                            <label>
                                                Tamaño del valor
                                            </label>

                                            <input
                                                type="text"
                                                name="value_size"
                                                class="form-control"
                                                value="<?php
                                                        echo $translation
                                                            ? CHtml::encode(
                                                                $translation->value_size
                                                            )
                                                            : '';
                                                        ?>"
                                                placeholder="Ej. 16px">

                                        </div>

                                    </div>

                                </div>



                                <div class="language-form-actions">

                                    <button
                                        type="submit"
                                        class="btn btn-primary">

                                        Guardar Idioma

                                    </button>

                                </div>

                            </form>

                        </div>

                    <?php endforeach; ?>

                </div>

            </div>

        </div>

    </div>

<?php endforeach; ?>



<!-- =========================================================
     CREAR CONTACT ITEM
========================================================= -->

<div class="card footer-card create-item-card">

    <div class="card-header">

        <div>

            <h3>Agregar Contact Item</h3>

            <p>
                Crear un nuevo elemento de contacto.
            </p>

        </div>

    </div>


    <div class="card-body">

        <form
            method="post"
            action="<?php echo $this->createUrl('createItem'); ?>">


            <div class="row">


                <div class="col-md-6">

                    <div class="form-group">

                        <label>Icono</label>

                        <?php
                        $renderIconPicker(
                            'new_item_icon',
                            ''
                        );
                        ?>

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="form-group">

                        <label>Orden</label>

                        <input
                            type="number"
                            name="sort_order"
                            class="form-control"
                            value="0">

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="form-group">

                        <label>Estado</label>

                        <div class="footer-switch">

                            <label class="switch">

                                <input
                                    type="checkbox"
                                    name="is_active"
                                    value="1"
                                    checked>

                                <span class="slider round"></span>

                            </label>


                            <span>
                                Activo
                            </span>

                        </div>

                    </div>

                </div>

            </div>


            <div class="form-actions">

                <button
                    type="submit"
                    class="btn btn-primary">
                    Crear Contact Item
                </button>

            </div>

        </form>

    </div>

</div>



<style>
    /*
     * ============================================================
     * FOOTER CARDS
     * ============================================================
     */

    .footer-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e8e8e8;
        margin-bottom: 24px;
        overflow: hidden;
    }


    .footer-card .card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 22px 24px;
        border-bottom: 1px solid #eeeeee;
    }


    .footer-card .card-header h3 {
        margin: 0 0 5px;
        font-size: 18px;
        font-weight: 600;
        color: #222;
    }


    .footer-card .card-header p {
        margin: 0;
        color: #888;
        font-size: 13px;
    }


    .footer-card .card-body {
        padding: 24px;
    }


    /*
     * ============================================================
     * SECTION
     * ============================================================
     */

    .section-heading {
        margin: 34px 0 18px;
    }


    .section-heading h2 {
        margin: 0 0 5px;
        font-size: 22px;
        font-weight: 600;
    }


    .section-heading p {
        margin: 0;
        color: #888;
        font-size: 14px;
    }


    /*
     * ============================================================
     * FORM
     * ============================================================
     */

    .form-group {
        margin-bottom: 20px;
    }


    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-size: 13px;
        font-weight: 600;
        color: #444;
    }


    .form-control {
        width: 100%;
        height: 42px;
        border: 1px solid #dedede;
        border-radius: 7px;
        padding: 9px 12px;
        background: #fff;
        box-shadow: none;
    }


    textarea.form-control {
        height: auto;
        resize: vertical;
    }


    .form-control:focus {
        border-color: #222;
        box-shadow: none;
    }


    .form-actions {
        display: flex;
        justify-content: flex-end;
        padding-top: 5px;
    }


    .language-form-actions {
        display: flex;
        justify-content: flex-end;
        margin-top: 5px;
    }


    /*
     * ============================================================
     * LANGUAGE TABS
     * ============================================================
     */

    .language-section {
        margin-top: 28px;
    }


    .language-tabs {
        display: flex;
        align-items: center;
        gap: 8px;
        list-style: none;
        padding: 0;
        margin: 0;
        border-bottom: 1px solid #e7e7e7;
    }


    .language-tabs li {
        margin: 0;
    }


    .language-tabs li a {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 15px;
        color: #888;
        text-decoration: none;
        border-bottom: 2px solid transparent;
        transition: all .2s ease;
        cursor: pointer;
    }


    .language-tabs li a:hover {
        color: #222;
        text-decoration: none;
    }


    .language-tabs li.active a {
        color: #222;
        border-bottom-color: #222;
    }


    .language-code {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .5px;
    }


    .language-name {
        font-size: 13px;
    }


    .tab-content {
        padding-top: 24px;
    }


    .language-section .tab-pane {
        display: none;
    }


    .language-section .tab-pane.active {
        display: block;
    }


    /*
     * ============================================================
     * BUTTONS
     * ============================================================
     */

    .btn {
        border-radius: 7px;
        padding: 9px 18px;
        font-size: 13px;
        font-weight: 600;
        border: 0;
        cursor: pointer;
    }


    .btn-primary {
        background: #222;
        color: #fff;
    }


    .btn-primary:hover {
        background: #111;
        color: #fff;
    }


    .btn-danger {
        background: #fff;
        color: #d9534f;
        border: 1px solid #e4e4e4;
    }


    .btn-danger:hover {
        background: #d9534f;
        color: #fff;
        border-color: #d9534f;
    }


    /*
     * ============================================================
     * SWITCH
     * ============================================================
     */

    .footer-switch {
        display: flex;
        align-items: center;
        gap: 10px;
        min-height: 42px;
    }


    .footer-switch>span {
        font-size: 13px;
        color: #555;
    }


    .switch {
        position: relative;
        display: inline-block;
        width: 44px;
        height: 24px;
        margin: 0;
    }


    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }


    .slider {
        position: absolute;
        cursor: pointer;
        inset: 0;
        background-color: #ccc;
        transition: .2s;
        border-radius: 24px;
    }


    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        top: 3px;
        background-color: white;
        transition: .2s;
        border-radius: 50%;
    }


    .switch input:checked+.slider {
        background-color: #222;
    }


    .switch input:checked+.slider:before {
        transform: translateX(20px);
    }


    /*
     * ============================================================
     * FONT AWESOME ICON PICKER
     * ============================================================
     */

    .admin-icon-picker {
        width: 100%;
        border: 1px solid #e1e1e1;
        border-radius: 8px;
        background: #fff;
        overflow: hidden;
    }


    .admin-icon-picker__selected {
        display: flex;
        align-items: center;
        min-height: 70px;
        padding: 12px 14px;
        border-bottom: 1px solid #eeeeee;
    }


    .admin-icon-picker__selected-preview {
        width: 46px;
        height: 46px;
        min-width: 46px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e5e5e5;
        border-radius: 7px;
        background: #fafafa;
        font-size: 21px;
        color: #222;
    }


    .admin-icon-picker__selected-info {
        display: flex;
        flex-direction: column;
        margin-left: 12px;
        min-width: 0;
        flex: 1;
    }


    .admin-icon-picker__selected-label {
        font-size: 11px;
        color: #999;
        margin-bottom: 3px;
    }


    .admin-icon-picker__selected-value {
        font-size: 13px;
        color: #333;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }


    .admin-icon-picker__clear {
        border: 1px solid #e2e2e2;
        background: #fff;
        border-radius: 6px;
        padding: 7px 11px;
        font-size: 12px;
        color: #777;
        cursor: pointer;
        margin-left: 10px;
    }


    .admin-icon-picker__clear:hover {
        border-color: #ccc;
        color: #222;
    }


    .admin-icon-picker__search {
        padding: 12px;
        border-bottom: 1px solid #eeeeee;
    }


    .admin-icon-picker__search .form-control {
        height: 38px;
        margin: 0;
    }


    .admin-icon-picker__grid {
        display: grid;
        grid-template-columns: repeat(8, minmax(0, 1fr));
        gap: 6px;
        padding: 12px;
        max-height: 250px;
        overflow-y: auto;
    }


    .admin-icon-picker__item {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 62px;
        padding: 7px 4px;
        border: 1px solid transparent;
        border-radius: 7px;
        background: #fafafa;
        cursor: pointer;
        transition: all .15s ease;
    }


    .admin-icon-picker__item:hover {
        border-color: #ddd;
        background: #f4f4f4;
    }


    .admin-icon-picker__item.is-selected {
        border-color: #222;
        background: #f3f3f3;
    }


    .admin-icon-picker__item-icon {
        font-size: 18px;
        color: #333;
        line-height: 1;
        margin-bottom: 6px;
    }


    .admin-icon-picker__item-name {
        width: 100%;
        padding: 0 2px;
        font-size: 9px;
        line-height: 11px;
        color: #888;
        text-align: center;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }


    .admin-icon-picker__empty {
        display: none;
        padding: 20px;
        text-align: center;
        color: #999;
        font-size: 13px;
    }


    .admin-icon-picker__empty.is-visible {
        display: block;
    }


    /*
     * ============================================================
     * CREATE ITEM
     * ============================================================
     */

    .create-item-card {
        margin-top: 10px;
    }


    /*
     * ============================================================
     * RESPONSIVE
     * ============================================================
     */

    @media (max-width: 991px) {

        .admin-icon-picker__grid {
            grid-template-columns: repeat(6, minmax(0, 1fr));
        }

    }


    @media (max-width: 767px) {

        .footer-card .card-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }


        .footer-card .card-body {
            padding: 18px;
        }


        .language-tabs {
            overflow-x: auto;
            flex-wrap: nowrap;
        }


        .language-tabs li {
            flex-shrink: 0;
        }


        .admin-icon-picker__grid {
            grid-template-columns: repeat(5, minmax(0, 1fr));
        }

    }
</style>



<script>
    $(document).ready(function() {


        /*
         * ============================================================
         * FONT AWESOME ICONS
         * ============================================================
         */

        var fontAwesomeIcons = <?php
                                echo CJSON::encode($fontAwesomeIcons);
                                ?>;


        /*
         * ============================================================
         * LANGUAGE TABS
         * ============================================================
         */

        $('.language-tab-link').on(
            'click',
            function(e) {

                e.preventDefault();


                var $link = $(this);

                var target = $link.attr(
                    'data-tab-target'
                );


                if (!target) {
                    return false;
                }


                if ($(target).length === 0) {
                    return false;
                }


                var $languageSection =
                    $link.closest(
                        '.language-section'
                    );


                if ($languageSection.length === 0) {
                    return false;
                }


                $languageSection
                    .find('.language-tabs li')
                    .removeClass('active');


                $link
                    .closest('li')
                    .addClass('active');


                $languageSection
                    .find('.tab-content > .tab-pane')
                    .removeClass('active')
                    .hide();


                $(target)
                    .addClass('active')
                    .show();


                return false;

            }
        );



        /*
         * ============================================================
         * INITIALIZE LANGUAGE TABS
         * ============================================================
         */

        $('.language-section').each(
            function() {

                var $section = $(this);


                var $tabs = $section
                    .find('.language-tabs')
                    .first();


                var $panes = $section
                    .find('.tab-content > .tab-pane');


                if (
                    $tabs.length === 0 ||
                    $panes.length === 0
                ) {
                    return;
                }


                $panes
                    .hide()
                    .removeClass('active');


                $tabs
                    .find('li')
                    .removeClass('active');


                var $firstTab = $tabs
                    .find('li:first-child');


                var $firstLink = $firstTab
                    .find('.language-tab-link')
                    .first();


                if ($firstLink.length === 0) {
                    return;
                }


                $firstTab.addClass('active');


                var firstTarget =
                    $firstLink.attr(
                        'data-tab-target'
                    );


                if (
                    firstTarget &&
                    $(firstTarget).length
                ) {

                    $(firstTarget)
                        .addClass('active')
                        .show();

                }

            }
        );



        /*
         * ============================================================
         * FONT AWESOME PICKER
         * ============================================================
         */

        function normalizeIconData(icon) {

            if (!icon) {
                return null;
            }


            if (
                typeof icon === 'object' &&
                icon.name &&
                icon.class
            ) {

                return {
                    name: icon.name,
                    className: icon.class,
                    style: icon.style || ''
                };

            }


            if (typeof icon === 'string') {

                return {
                    name: icon,
                    className: icon,
                    style: ''
                };

            }


            return null;

        }



        function getIconSearchText(icon) {

            var data = normalizeIconData(
                icon
            );


            if (!data) {
                return '';
            }


            return (
                data.name +
                ' ' +
                data.className +
                ' ' +
                data.style
            ).toLowerCase();

        }



        function renderIconPicker($picker) {

            var inputId = $picker.attr(
                'data-input-id'
            );


            var $input = $('#' + inputId);


            if ($input.length === 0) {
                return;
            }


            var $grid = $picker.find(
                '[data-icon-grid]'
            );


            var $search = $picker.find(
                '[data-icon-search]'
            );


            var $empty = $picker.find(
                '[data-icon-empty]'
            );


            var currentValue =
                $input.val() || '';



            function updateSelected() {

                currentValue =
                    $input.val() || '';


                var $preview =
                    $picker.find(
                        '[data-icon-preview]'
                    );


                var $selectedValue =
                    $picker.find(
                        '[data-icon-selected-value]'
                    );


                $preview
                    .attr('class', '')
                    .attr(
                        'data-icon-preview',
                        ''
                    );


                if (currentValue) {

                    $preview.attr(
                        'class',
                        currentValue
                    );


                    $selectedValue.text(
                        currentValue
                    );

                } else {

                    $selectedValue.text(
                        'Ninguno'
                    );

                }


                $grid
                    .find(
                        '.admin-icon-picker__item'
                    )
                    .removeClass(
                        'is-selected'
                    );


                if (currentValue) {

                    $grid
                        .find(
                            '.admin-icon-picker__item'
                        )
                        .filter(
                            function() {

                                return $(this).attr(
                                    'data-icon'
                                ) === currentValue;

                            }
                        )
                        .addClass(
                            'is-selected'
                        );

                }

            }



            function renderIcons(filter) {

                var search = (
                        filter || ''
                    )
                    .toLowerCase()
                    .trim();


                $grid.empty();


                var visibleCount = 0;


                $.each(
                    fontAwesomeIcons,
                    function(
                        index,
                        icon
                    ) {

                        var data =
                            normalizeIconData(
                                icon
                            );


                        if (!data) {
                            return;
                        }


                        if (
                            search &&
                            getIconSearchText(
                                icon
                            ).indexOf(
                                search
                            ) === -1
                        ) {

                            return;

                        }


                        var $item =
                            $('<button>', {
                                type: 'button',
                                class: 'admin-icon-picker__item'
                            });


                        $item.attr(
                            'data-icon',
                            data.className
                        );


                        var $icon =
                            $('<i>', {
                                class: 'admin-icon-picker__item-icon ' +
                                    data.className
                            });


                        var $name =
                            $('<span>', {
                                class: 'admin-icon-picker__item-name',
                                text: data.name
                            });


                        $item.append($icon);
                        $item.append($name);


                        if (
                            data.className ===
                            currentValue
                        ) {

                            $item.addClass(
                                'is-selected'
                            );

                        }


                        $grid.append($item);


                        visibleCount++;

                    }
                );


                if (visibleCount === 0) {

                    $empty.addClass(
                        'is-visible'
                    );

                } else {

                    $empty.removeClass(
                        'is-visible'
                    );

                }

            }



            $search.on(
                'input',
                function() {

                    renderIcons(
                        $(this).val()
                    );

                }
            );



            $grid.on(
                'click',
                '.admin-icon-picker__item',
                function() {

                    var iconClass =
                        $(this).attr(
                            'data-icon'
                        );


                    if (!iconClass) {
                        return;
                    }


                    /*
                     * ESTE ES EL VALOR QUE SE
                     * ENVÍA AL CONTROLLER:
                     *
                     * name="icon"
                     */

                    $input.val(
                        iconClass
                    );


                    updateSelected();

                }
            );



            $picker.find(
                '[data-icon-clear]'
            ).on(
                'click',
                function() {

                    $input.val('');

                    updateSelected();

                }
            );


            renderIcons('');

            updateSelected();

        }



        /*
         * Inicializar todos los selectores.
         */

        $('.admin-icon-picker').each(
            function() {

                renderIconPicker(
                    $(this)
                );

            }
        );



        /*
         * ============================================================
         * DELETE CONTACT ITEM
         * ============================================================
         */

        $('.btn-delete-item').on(
            'click',
            function() {

                var itemId =
                    $(this).data('id');


                if (!itemId) {
                    return;
                }


                if (
                    !confirm(
                        '¿Estás seguro de eliminar este contact item?'
                    )
                ) {

                    return;

                }


                var form = $('<form>', {
                    method: 'POST',
                    action: '<?php echo $this->createUrl('deleteItem'); ?>/' +
                        itemId
                });


                $('body').append(form);


                form.submit();

            }
        );

    });
</script>