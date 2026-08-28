<?php
/* @var $this CategoriesController */
/* @var $model Subcategories */
/* @var $category Categories */
/* @var $translation SubcategoryTranslations */
/* @var $defaultLanguage Languages */


$translation = isset($translation)
    ? $translation
    : new SubcategoryTranslations;


$defaultLanguage = isset($defaultLanguage)
    ? $defaultLanguage
    : null;


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
	     CATEGORY
	     ====================================================== -->


    <div class="admin-modal-form-field admin-modal-form-field--full">


        <?= $form->labelEx(
            $model,
            'category_id'
        ); ?>


        <div class="admin-form-language">


            <div class="admin-form-language__name">


                <span class="admin-form-language__code">

                    <?= CHtml::encode(
                        'ID ' . $category->id
                    ); ?>

                </span>


                <span>

                    <?= CHtml::encode(
                        'Categoría #' . $category->id
                    ); ?>

                </span>


            </div>


            <span class="admin-form-language__badge">

                Categoría padre

            </span>


        </div>


        <?= CHtml::activeHiddenField(
            $model,
            'category_id'
        ); ?>


        <?= $form->error(
            $model,
            'category_id'
        ); ?>


    </div>


    <?php if ($model->isNewRecord): ?>


        <!-- ==================================================
		     DEFAULT LANGUAGE
		     ================================================== -->


        <div class="admin-modal-form-field admin-modal-form-field--full">


            <label>

                Idioma

                <span class="required">*</span>

            </label>


            <div class="admin-form-language">


                <div class="admin-form-language__name">


                    <?php if ($defaultLanguage): ?>


                        <span class="admin-form-language__code">

                            <?= CHtml::encode(
                                strtoupper(
                                    $defaultLanguage->code
                                )
                            ); ?>

                        </span>


                        <div>

                            <?= CHtml::encode(
                                $defaultLanguage->name
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
                                    $defaultLanguage->native_name
                                ); ?>

                            </span>

                        </div>


                    <?php else: ?>


                        <span>

                            Idioma predeterminado no configurado

                        </span>


                    <?php endif; ?>


                </div>


                <span class="admin-form-language__badge">

                    Idioma predeterminado

                </span>


            </div>


            <?php if ($defaultLanguage): ?>


                <?= CHtml::hiddenField(
                    'SubcategoryTranslations[language_id]',
                    $defaultLanguage->id
                ); ?>


            <?php endif; ?>


        </div>


        <!-- ==================================================
		     NAME
		     ================================================== -->


        <div class="admin-modal-form-field">


            <?= $form->labelEx(
                $translation,
                'name'
            ); ?>


            <?= $form->textField(
                $translation,
                'name',
                array(
                    'maxlength' => 255,
                    'autocomplete' => 'off',
                )
            ); ?>


            <?= $form->error(
                $translation,
                'name'
            ); ?>


        </div>


        <!-- ==================================================
		     NAME SIZE
		     ================================================== -->


        <div class="admin-modal-form-field">


            <?= $form->labelEx(
                $translation,
                'name_size'
            ); ?>


            <?= $form->textField(
                $translation,
                'name_size',
                array(
                    'maxlength' => 20,
                )
            ); ?>


            <?= $form->error(
                $translation,
                'name_size'
            ); ?>


            <span class="admin-modal-form-field__hint">

                Tamaño o clase utilizada para el nombre.

            </span>


        </div>


        <!-- ==================================================
		     DESCRIPTION
		     ================================================== -->


        <div class="admin-modal-form-field admin-modal-form-field--full">


            <?= $form->labelEx(
                $translation,
                'description'
            ); ?>


            <?= $form->textArea(
                $translation,
                'description',
                array(
                    'rows' => 6,
                )
            ); ?>


            <?= $form->error(
                $translation,
                'description'
            ); ?>


        </div>


        <!-- ==================================================
		     DESCRIPTION SIZE
		     ================================================== -->


        <div class="admin-modal-form-field">


            <?= $form->labelEx(
                $translation,
                'description_size'
            ); ?>


            <?= $form->textField(
                $translation,
                'description_size',
                array(
                    'maxlength' => 20,
                )
            ); ?>


            <?= $form->error(
                $translation,
                'description_size'
            ); ?>


            <span class="admin-modal-form-field__hint">

                Tamaño o clase utilizada para la descripción.

            </span>


        </div>


        <!-- ==================================================
		     SORT ORDER
		     ================================================== -->


        <div class="admin-modal-form-field">


            <?= $form->labelEx(
                $model,
                'sort_order'
            ); ?>


            <?= $form->textField(
                $model,
                'sort_order',
                array(
                    'type' => 'number',
                    'min' => '0',
                )
            ); ?>


            <?= $form->error(
                $model,
                'sort_order'
            ); ?>


            <span class="admin-modal-form-field__hint">

                Determina la posición de la subcategoría.

            </span>


        </div>


        <!-- ==================================================
		     STATUS
		     ================================================== -->


        <div class="admin-modal-form-field">


            <label>

                Estado

            </label>


            <div class="admin-form-field--switch">


                <div>

                    <span
                        class="admin-modal-form-field__hint"
                        style="margin: 0;">

                        <?php

                        echo $model->is_active
                            ? 'Subcategoría activa'
                            : 'Subcategoría inactiva';

                        ?>

                    </span>

                </div>


                <label class="admin-form-switch">


                    <?php

                    echo CHtml::activeCheckBox(
                        $model,
                        'is_active',
                        array(
                            'uncheckValue' => '0',
                            'class' =>
                            'admin-form-status__input',
                        )
                    );

                    ?>


                    <span class="admin-form-switch__track"></span>


                </label>


            </div>


            <?= $form->error(
                $model,
                'is_active'
            ); ?>


        </div>


    <?php else: ?>


        <!-- ==================================================
		     SORT ORDER
		     ================================================== -->


        <div class="admin-modal-form-field">


            <?= $form->labelEx(
                $model,
                'sort_order'
            ); ?>


            <?= $form->textField(
                $model,
                'sort_order',
                array(
                    'type' => 'number',
                    'min' => '0',
                )
            ); ?>


            <?= $form->error(
                $model,
                'sort_order'
            ); ?>


            <span class="admin-modal-form-field__hint">

                Determina la posición de la subcategoría.

            </span>


        </div>


        <!-- ==================================================
		     STATUS
		     ================================================== -->


        <div class="admin-modal-form-field">


            <label>

                Estado

            </label>


            <div class="admin-form-field--switch">


                <div>

                    <span
                        class="admin-modal-form-field__hint"
                        style="margin: 0;">

                        <?php

                        echo $model->is_active
                            ? 'Subcategoría activa'
                            : 'Subcategoría inactiva';

                        ?>

                    </span>

                </div>


                <label class="admin-form-switch">


                    <?php

                    echo CHtml::activeCheckBox(
                        $model,
                        'is_active',
                        array(
                            'uncheckValue' => '0',
                            'class' =>
                            'admin-form-status__input',
                        )
                    );

                    ?>


                    <span class="admin-form-switch__track"></span>


                </label>


            </div>


            <?= $form->error(
                $model,
                'is_active'
            ); ?>


        </div>


    <?php endif; ?>


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
            ? 'Agregar subcategoría'
            : 'Guardar cambios';

        ?>


    </button>


</div>


<?php $this->endWidget(); ?>