<?php
/* @var $this CategoriesController */
/* @var $model SubcategoryTranslations */
/* @var $category Categories */
/* @var $subcategory Subcategories */
/* @var $language Languages */
?>

<?php

$form = $this->beginWidget(
    'CActiveForm',
    array(
        'id' => 'admin-ajax-modal-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array(
            'class' => 'admin-modal-form',
        ),
    )
);

?>


<div class="admin-modal-form-fields">


    <!-- ======================================================
	     LANGUAGE
	     ====================================================== -->


    <div class="admin-modal-form-field admin-modal-form-field--full">


        <label>

            Idioma

            <span class="required">*</span>

        </label>


        <div class="admin-form-language">


            <div class="admin-form-language__name">


                <span class="admin-form-language__code">

                    <?= CHtml::encode(
                        strtoupper($language->code)
                    ); ?>

                </span>


                <div>

                    <?= CHtml::encode(
                        $language->name
                    ); ?>


                    <span
                        style="
							display: block;
							margin-top: 2px;
							color: #9ca3af;
							font-size: 11px;
							font-weight: 400;
						">

                        <?= CHtml::encode(
                            $language->native_name
                        ); ?>

                    </span>

                </div>


            </div>


            <span class="admin-form-language__badge">

                Traducción

            </span>


        </div>


        <?= CHtml::activeHiddenField(
            $model,
            'language_id'
        ); ?>


        <?= CHtml::activeHiddenField(
            $model,
            'subcategory_id'
        ); ?>


        <?= $form->error(
            $model,
            'language_id'
        ); ?>


    </div>


    <!-- ======================================================
	     SUBCATEGORY
	     ====================================================== -->


    <div class="admin-modal-form-field admin-modal-form-field--full">


        <label>

            Subcategoría

        </label>


        <div
            style="
				padding: 10px 11px;
				box-sizing: border-box;
				border: 1px solid #e5e7eb;
				border-radius: 6px;
				background: #f9fafb;
				color: #374151;
				font-size: 13px;
				font-weight: 600;
			">

            <?= CHtml::encode(
                'Subcategoría #' . $subcategory->id
            ); ?>


            <span
                style="
					display: block;
					margin-top: 3px;
					color: #9ca3af;
					font-size: 11px;
					font-weight: 400;
				">

                Categoría #<?= CHtml::encode(
                                $category->id
                            ); ?>

            </span>


        </div>


    </div>


    <!-- ======================================================
	     NAME
	     ====================================================== -->


    <div class="admin-modal-form-field">


        <?= $form->labelEx(
            $model,
            'name'
        ); ?>


        <?= $form->textField(
            $model,
            'name',
            array(
                'maxlength' => 255,
                'autocomplete' => 'off',
            )
        ); ?>


        <?= $form->error(
            $model,
            'name'
        ); ?>


    </div>


    <!-- ======================================================
	     NAME SIZE
	     ====================================================== -->


    <div class="admin-modal-form-field">


        <?= $form->labelEx(
            $model,
            'name_size'
        ); ?>


        <?= $form->textField(
            $model,
            'name_size',
            array(
                'maxlength' => 20,
            )
        ); ?>


        <?= $form->error(
            $model,
            'name_size'
        ); ?>


        <span class="admin-modal-form-field__hint">

            Tamaño o clase utilizada para el nombre.

        </span>


    </div>


    <!-- ======================================================
	     DESCRIPTION
	     ====================================================== -->


    <div class="admin-modal-form-field admin-modal-form-field--full">


        <?= $form->labelEx(
            $model,
            'description'
        ); ?>


        <?= $form->textArea(
            $model,
            'description',
            array(
                'rows' => 6,
            )
        ); ?>


        <?= $form->error(
            $model,
            'description'
        ); ?>


    </div>


    <!-- ======================================================
	     DESCRIPTION SIZE
	     ====================================================== -->


    <div class="admin-modal-form-field">


        <?= $form->labelEx(
            $model,
            'description_size'
        ); ?>


        <?= $form->textField(
            $model,
            'description_size',
            array(
                'maxlength' => 20,
            )
        ); ?>


        <?= $form->error(
            $model,
            'description_size'
        ); ?>


        <span class="admin-modal-form-field__hint">

            Tamaño o clase utilizada para la descripción.

        </span>


    </div>


</div>


<!-- ======================================================
     FOOTER
     ====================================================== -->


<div class="admin-form-modal__footer">


    <button
        type="button"
        class="admin-form-small-button js-modal-cancel">

        <i class="fas fa-times"></i>

        Cancelar

    </button>


    <button
        type="submit"
        class="admin-form-small-button admin-form-small-button--primary">

        <i class="fas fa-save"></i>

        <?php

        echo $model->isNewRecord
            ? 'Agregar traducción'
            : 'Guardar cambios';

        ?>

    </button>


</div>


<?php $this->endWidget(); ?>