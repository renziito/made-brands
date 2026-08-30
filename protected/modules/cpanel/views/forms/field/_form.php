<?php
/* @var $this FormsController */
/* @var $model FaqFormFields */
/* @var $formModel FaqForms */
/* @var $form CActiveForm */

Yii::app()->clientScript->registerCss('admin-faq-field-form', '

.admin-faq-field-page {
	width: 100%;
	max-width: 1100px;
	margin: 0 auto;
}

.admin-faq-field-card {
	margin-top: 28px;
	overflow: hidden;
	background: #fff;
	border: 1px solid #e5e7eb;
	border-radius: 10px;
	box-shadow: 0 1px 2px rgba(0, 0, 0, .03);
}

/* ==========================================================
   HEADER
   ========================================================== */

.admin-faq-field-card__header {
	display: flex;
	align-items: center;
	gap: 12px;
	padding: 18px 20px;
	border-bottom: 1px solid #e5e7eb;
}

.admin-faq-field-card__icon {
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

.admin-faq-field-card__heading {
	min-width: 0;
}

.admin-faq-field-card__title {
	margin: 0;
	color: #111827;
	font-size: 15px;
	font-weight: 600;
	line-height: 1.3;
}

.admin-faq-field-card__description {
	margin: 3px 0 0;
	color: #9ca3af;
	font-size: 12px;
	line-height: 1.4;
}

/* ==========================================================
   BODY
   ========================================================== */

.admin-faq-field-card__body {
	padding: 24px 20px;
}

.admin-faq-field-grid {
	display: grid;
	grid-template-columns: repeat(2, minmax(0, 1fr));
	gap: 20px 18px;
}

.admin-faq-field-item {
	min-width: 0;
}

.admin-faq-field-item--full {
	grid-column: 1 / -1;
}

.admin-faq-field-item label {
	display: block;
	margin: 0 0 7px;
	color: #374151;
	font-size: 12px;
	font-weight: 600;
	line-height: 1.4;
}

.admin-faq-field-item label .required {
	margin-left: 2px;
	color: #dc2626;
	font-weight: 700;
}

/* ==========================================================
   INPUTS
   ========================================================== */

.admin-faq-field-item input[type="text"],
.admin-faq-field-item input[type="number"],
.admin-faq-field-item select,
.admin-faq-field-item textarea {
	display: block;
	width: 100%;
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

.admin-faq-field-item input[type="text"],
.admin-faq-field-item input[type="number"],
.admin-faq-field-item select {
	height: 40px;
}

.admin-faq-field-item textarea {
	min-height: 110px;
	resize: vertical;
}

.admin-faq-field-item input:focus,
.admin-faq-field-item select:focus,
.admin-faq-field-item textarea:focus {
	border-color: #9ca3af;
	box-shadow: 0 0 0 3px rgba(17, 24, 39, .06);
}

.admin-faq-field-item .error {
	display: block;
	margin-top: 6px;
	color: #dc2626;
	font-size: 11px;
	line-height: 1.4;
}

.admin-faq-field-item input.error,
.admin-faq-field-item select.error,
.admin-faq-field-item textarea.error {
	border-color: #fca5a5;
	background: #fffafa;
}

.admin-faq-field-item .hint {
	display: block;
	margin-top: 6px;
	color: #9ca3af;
	font-size: 11px;
	line-height: 1.4;
}

/* ==========================================================
   SWITCH
   ========================================================== */

.admin-faq-field-switch {
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 15px;
	min-height: 40px;
	padding: 10px 12px;
	box-sizing: border-box;
	border: 1px solid #e5e7eb;
	border-radius: 7px;
	background: #f9fafb;
}

.admin-faq-field-switch__text {
	display: flex;
	flex-direction: column;
	gap: 2px;
}

.admin-faq-field-switch__label {
	color: #374151;
	font-size: 12px;
	font-weight: 600;
}

.admin-faq-field-switch__description {
	color: #9ca3af;
	font-size: 11px;
}

.admin-faq-field-switch input[type="checkbox"] {
	width: 16px;
	height: 16px;
	margin: 0;
	cursor: pointer;
}

/* ==========================================================
   ERROR SUMMARY
   ========================================================== */

.admin-faq-field-card .errorSummary {
	margin: 0 0 22px;
	padding: 14px 16px;
	border: 1px solid #fecaca;
	border-radius: 7px;
	background: #fef2f2;
	color: #991b1b;
	font-size: 13px;
	line-height: 1.5;
}

.admin-faq-field-card .errorSummary ul {
	margin: 7px 0 0 18px;
	padding: 0;
}

/* ==========================================================
   FOOTER
   ========================================================== */

.admin-faq-field-card__footer {
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 16px;
	padding: 16px 20px;
	border-top: 1px solid #e5e7eb;
	background: #f9fafb;
}

.admin-faq-field-footer__note {
	color: #9ca3af;
	font-size: 11px;
}

.admin-faq-field-footer__note .required {
	color: #dc2626;
	font-weight: 700;
}

.admin-faq-field-actions {
	display: flex;
	align-items: center;
	gap: 8px;
}

/* ==========================================================
   BUTTONS
   ========================================================== */

.admin-faq-field-button {
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
	transition:
		background-color .15s ease,
		border-color .15s ease,
		box-shadow .15s ease,
		color .15s ease;
}

.admin-faq-field-button:hover {
	text-decoration: none !important;
}

.admin-faq-field-button--primary {
	background: #111827;
	border-color: #111827;
	color: #fff !important;
}

.admin-faq-field-button--primary:hover {
	background: #1f2937;
	border-color: #1f2937;
	color: #fff !important;
}

.admin-faq-field-button--secondary {
	background: #fff;
	border-color: #d1d5db;
	color: #374151 !important;
}

.admin-faq-field-button--secondary:hover {
	background: #f3f4f6;
	border-color: #9ca3af;
	color: #111827 !important;
}

/* ==========================================================
   RESPONSIVE
   ========================================================== */

@media (max-width: 768px) {

	.admin-faq-field-grid {
		grid-template-columns: 1fr;
	}

	.admin-faq-field-item--full {
		grid-column: auto;
	}

	.admin-faq-field-card__footer {
		align-items: stretch;
		flex-direction: column;
	}

	.admin-faq-field-actions {
		width: 100%;
	}

	.admin-faq-field-button {
		flex: 1;
	}

}

');
?>

<div class="admin-faq-field-page">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'faq-form-field-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array(
            'class' => 'admin-faq-field-form',
        ),
    ));
    ?>

    <div class="admin-faq-field-card">

        <!-- ======================================================
		     HEADER
		     ======================================================= -->

        <div class="admin-faq-field-card__header">

            <div class="admin-faq-field-card__icon">

                <?php
                echo $model->isNewRecord
                    ? '<i class="fas fa-plus"></i>'
                    : '<i class="fas fa-pen"></i>';
                ?>

            </div>

            <div class="admin-faq-field-card__heading">

                <h2 class="admin-faq-field-card__title">

                    <?php
                    echo $model->isNewRecord
                        ? 'Añadir campo'
                        : 'Editar campo';
                    ?>

                </h2>

                <p class="admin-faq-field-card__description">

                    Formulario:
                    <strong>
                        <?php echo CHtml::encode($formModel->title); ?>
                    </strong>

                </p>

            </div>

        </div>


        <!-- ======================================================
		     BODY
		     ======================================================= -->

        <div class="admin-faq-field-card__body">

            <?php
            echo $form->errorSummary(
                $model,
                '<strong>Por favor verifica la información:</strong>'
            );
            ?>

            <div class="admin-faq-field-grid">


                <!-- ==================================================
				     NAME
				     =================================================== -->

                <div class="admin-faq-field-item">

                    <?php echo $form->labelEx($model, 'name'); ?>

                    <?php
                    echo $form->textField(
                        $model,
                        'name',
                        array(
                            'maxlength' => 100,
                            'placeholder' => 'Ej. first_name',
                        )
                    );
                    ?>

                    <span class="hint">
                        Nombre interno del campo.
                    </span>

                    <?php echo $form->error($model, 'name'); ?>

                </div>


                <!-- ==================================================
				     LABEL
				     =================================================== -->

                <div class="admin-faq-field-item">

                    <?php echo $form->labelEx($model, 'label'); ?>

                    <?php
                    echo $form->textField(
                        $model,
                        'label',
                        array(
                            'maxlength' => 255,
                            'placeholder' => 'Ej. Nombre',
                        )
                    );
                    ?>

                    <span class="hint">
                        Texto que verá el usuario.
                    </span>

                    <?php echo $form->error($model, 'label'); ?>

                </div>


                <!-- ==================================================
				     TYPE
				     =================================================== -->

                <div class="admin-faq-field-item">

                    <?php echo $form->labelEx($model, 'type'); ?>

                    <?php
                    echo $form->dropDownList(
                        $model,
                        'type',
                        array(
                            'text' => 'Text',
                            'email' => 'Email',
                            'tel' => 'Teléfono',
                            'number' => 'Número',
                            'textarea' => 'Textarea',
                            'select' => 'Select',
                            'radio' => 'Radio',
                            'checkbox' => 'Checkbox',
                            'date' => 'Fecha',
                            'file' => 'Archivo',
                        ),
                        array(
                            'prompt' => 'Selecciona un tipo',
                        )
                    );
                    ?>

                    <?php echo $form->error($model, 'type'); ?>

                </div>


                <!-- ==================================================
				     SORT ORDER
				     =================================================== -->

                <div class="admin-faq-field-item">

                    <?php echo $form->labelEx($model, 'sort_order'); ?>

                    <?php
                    echo $form->numberField(
                        $model,
                        'sort_order',
                        array(
                            'min' => 0,
                            'placeholder' => '0',
                        )
                    );
                    ?>

                    <span class="hint">
                        Define el orden de aparición del campo.
                    </span>

                    <?php echo $form->error($model, 'sort_order'); ?>

                </div>


                <!-- ==================================================
				     PLACEHOLDER
				     =================================================== -->

                <div class="admin-faq-field-item admin-faq-field-item--full">

                    <?php echo $form->labelEx($model, 'placeholder'); ?>

                    <?php
                    echo $form->textField(
                        $model,
                        'placeholder',
                        array(
                            'maxlength' => 255,
                            'placeholder' => 'Ej. Ingresa tu nombre',
                        )
                    );
                    ?>

                    <?php echo $form->error($model, 'placeholder'); ?>

                </div>


                <!-- ==================================================
				     DEFAULT VALUE
				     =================================================== -->

                <div class="admin-faq-field-item">

                    <?php echo $form->labelEx($model, 'default_value'); ?>

                    <?php
                    echo $form->textArea(
                        $model,
                        'default_value',
                        array(
                            'rows' => 4,
                            'placeholder' => 'Valor inicial del campo',
                        )
                    );
                    ?>

                    <?php echo $form->error($model, 'default_value'); ?>

                </div>


                <!-- ==================================================
				     OPTIONS
				     =================================================== -->

                <div class="admin-faq-field-item">

                    <?php echo $form->labelEx($model, 'options'); ?>

                    <?php
                    echo $form->textArea(
                        $model,
                        'options',
                        array(
                            'rows' => 4,
                            'placeholder' => 'Opciones del campo',
                        )
                    );
                    ?>

                    <span class="hint">
                        Utilizado para Select, Radio y Checkbox.
                    </span>

                    <?php echo $form->error($model, 'options'); ?>

                </div>


                <!-- ==================================================
				     REQUIRED
				     =================================================== -->

                <div class="admin-faq-field-item">

                    <div class="admin-faq-field-switch">

                        <div class="admin-faq-field-switch__text">

                            <span class="admin-faq-field-switch__label">
                                Is Required
                            </span>

                            <span class="admin-faq-field-switch__description">
                                El usuario debe completar este campo.
                            </span>

                        </div>

                        <?php
                        echo $form->checkBox(
                            $model,
                            'is_required',
                            array(
                                'uncheckValue' => '0',
                            )
                        );
                        ?>

                    </div>

                    <?php echo $form->error($model, 'is_required'); ?>

                </div>


                <!-- ==================================================
				     ACTIVE
				     =================================================== -->

                <div class="admin-faq-field-item">

                    <div class="admin-faq-field-switch">

                        <div class="admin-faq-field-switch__text">

                            <span class="admin-faq-field-switch__label">
                                Is Active
                            </span>

                            <span class="admin-faq-field-switch__description">
                                El campo estará disponible en el formulario.
                            </span>

                        </div>

                        <?php
                        echo $form->checkBox(
                            $model,
                            'is_active',
                            array(
                                'uncheckValue' => '0',
                                'checked' => $model->isNewRecord
                                    ? true
                                    : (bool) $model->is_active,
                            )
                        );
                        ?>

                    </div>

                    <?php echo $form->error($model, 'is_active'); ?>

                </div>


            </div>

        </div>


        <!-- ======================================================
		     FOOTER
		     ======================================================= -->

        <div class="admin-faq-field-card__footer">

            <div class="admin-faq-field-footer__note">

                <span class="required">*</span>
                Campos obligatorios

            </div>


            <div class="admin-faq-field-actions">

                <a
                    href="<?php echo $this->createUrl('update', array(
                                'id' => $formModel->id,
                            )); ?>"
                    class="admin-faq-field-button admin-faq-field-button--secondary">
                    <i class="fas fa-times"></i>
                    Cancelar
                </a>


                <button
                    type="submit"
                    class="admin-faq-field-button admin-faq-field-button--primary">

                    <?php
                    if ($model->isNewRecord) {
                        echo '<i class="fas fa-plus"></i> Añadir campo';
                    } else {
                        echo '<i class="fas fa-save"></i> Guardar cambios';
                    }
                    ?>

                </button>

            </div>

        </div>

    </div>

    <?php $this->endWidget(); ?>

</div>