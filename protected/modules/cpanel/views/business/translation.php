<?php

/* @var $this BusinessController */
/* @var $model BusinessTranslations */
/* @var $business Businesses */
/* @var $language Languages */
/* @var $form CActiveForm */

$this->breadcrumbs = array(
    'Businesses' => array('index'),
    'Editar' => array(
        'update',
        'id' => $business->id,
    ),
    'Traducción',
);

Yii::app()->clientScript->registerCss(
    'admin-business-translation',
    '
.admin-form-page {
	width: 100%;
	max-width: 1100px;
	margin: 0 auto;
}

.admin-form {
	margin-top: 28px;
}

.admin-form-card {
	overflow: hidden;
	background: #fff;
	border: 1px solid #e5e7eb;
	border-radius: 10px;
	box-shadow: 0 1px 2px rgba(0, 0, 0, .03);
}

.admin-form-card__header {
	display: flex;
	align-items: center;
	gap: 12px;
	padding: 18px 20px;
	border-bottom: 1px solid #e5e7eb;
}

.admin-form-card__icon {
	display: flex;
	align-items: center;
	justify-content: center;
	width: 36px;
	height: 36px;
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
}

.admin-form-card__description {
	margin: 3px 0 0;
	color: #9ca3af;
	font-size: 12px;
}

.admin-form-card__body {
	padding: 24px 20px;
}

.admin-form-fields {
	display: grid;
	grid-template-columns: repeat(2, minmax(0, 1fr));
	gap: 20px 18px;
}

.admin-form-field--full {
	grid-column: 1 / -1;
}

.admin-form-field label {
	display: block;
	margin-bottom: 7px;
	color: #374151;
	font-size: 12px;
	font-weight: 600;
}

.admin-form-field input,
.admin-form-field textarea {
	display: block;
	width: 100%;
	box-sizing: border-box;
	padding: 9px 11px;
	border: 1px solid #d1d5db;
	border-radius: 6px;
	background: #fff;
	color: #374151;
	font-family: inherit;
	font-size: 13px;
}

.admin-form-field input {
	height: 40px;
}

.admin-form-field textarea {
	min-height: 130px;
	resize: vertical;
}

.admin-form-language {
	display: flex;
	align-items: center;
	gap: 9px;
	height: 40px;
	padding: 0 11px;
	box-sizing: border-box;
	border: 1px solid #e5e7eb;
	border-radius: 6px;
	background: #f9fafb;
	color: #374151;
	font-size: 13px;
	font-weight: 600;
}

.admin-form-language__code {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	min-width: 30px;
	height: 24px;
	padding: 0 7px;
	box-sizing: border-box;
	border: 1px solid #d1d5db;
	border-radius: 5px;
	background: #fff;
	font-size: 10px;
	font-weight: 700;
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

.admin-form-actions {
	display: flex;
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
	font-size: 13px;
	font-weight: 600;
	text-decoration: none !important;
	cursor: pointer;
}

.admin-form-button--primary {
	background: #111827;
	border-color: #111827;
	color: #fff !important;
}

.admin-form-button--secondary {
	background: #fff;
	border-color: #d1d5db;
	color: #374151 !important;
}

@media (max-width: 768px) {

	.admin-form-fields {
		grid-template-columns: 1fr;
	}

	.admin-form-field--full {
		grid-column: auto;
	}

	.admin-form-card__footer {
		align-items: stretch;
		flex-direction: column;
	}

	.admin-form-actions {
		width: 100%;
	}

	.admin-form-button {
		flex: 1;
	}
}
'
);
?>

<div class="admin-form-page">

    <?php
    $form = $this->beginWidget(
        'CActiveForm',
        array(
            'id' => 'business-translation-form',
            'enableAjaxValidation' => false,
        )
    );
    ?>

    <div class="admin-form-card">

        <div class="admin-form-card__header">

            <div class="admin-form-card__icon">
                <i class="fas fa-language"></i>
            </div>

            <div>

                <h2 class="admin-form-card__title">
                    Traducción
                </h2>

                <p class="admin-form-card__description">

                    Business #<?= (int) $business->id; ?>

                    &nbsp;·&nbsp;

                    <?= CHtml::encode(
                        $business->id
                    ); ?>

                </p>

            </div>

        </div>


        <div class="admin-form-card__body">

            <?= $form->errorSummary(
                $model,
                '<strong>Por favor verifica la información:</strong>'
            ); ?>


            <div class="admin-form-fields">

                <!-- LANGUAGE -->

                <div class="admin-form-field admin-form-field--full">

                    <label>
                        Idioma
                    </label>

                    <div class="admin-form-language">

                        <span class="admin-form-language__code">

                            <?= CHtml::encode(
                                strtoupper(
                                    $language->code
                                )
                            ); ?>

                        </span>

                        <span>

                            <?= CHtml::encode(
                                $language->native_name
                            ); ?>

                        </span>

                        <?php if ((int) $language->is_default): ?>

                            <span
                                style="
								margin-left:auto;
								color:#6b7280;
								font-size:10px;
								font-weight:700;
								">
                                Idioma predeterminado
                            </span>

                        <?php endif; ?>

                    </div>

                </div>


                <!-- NAME -->

                <div class="admin-form-field">

                    <?= $form->labelEx(
                        $model,
                        'name'
                    ); ?>

                    <?= $form->textField(
                        $model,
                        'name',
                        array(
                            'maxlength' => 255,
                        )
                    ); ?>

                    <?= $form->error(
                        $model,
                        'name'
                    ); ?>

                </div>


                <!-- NAME SIZE -->

                <div class="admin-form-field">

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

                </div>


                <!-- DESCRIPTION -->

                <div class="admin-form-field admin-form-field--full">

                    <?= $form->labelEx(
                        $model,
                        'description'
                    ); ?>

                    <?= $form->textArea(
                        $model,
                        'description',
                        array(
                            'rows' => 7,
                        )
                    ); ?>

                    <?= $form->error(
                        $model,
                        'description'
                    ); ?>

                </div>


                <!-- DESCRIPTION SIZE -->

                <div class="admin-form-field">

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

                </div>

            </div>

        </div>


        <div class="admin-form-card__footer">

            <div
                style="
				color:#9ca3af;
				font-size:11px;
				">
                Business #<?= (int) $business->id; ?>
            </div>

            <div class="admin-form-actions">

                <a
                    href="<?php
                            echo $this->createUrl(
                                'update',
                                array(
                                    'id' => $business->id,
                                )
                            );
                            ?>"
                    class="admin-form-button admin-form-button--secondary">
                    <i class="fas fa-arrow-left"></i>
                    Volver
                </a>

                <button
                    type="submit"
                    class="admin-form-button admin-form-button--primary">
                    <i class="fas fa-save"></i>
                    Guardar traducción
                </button>

            </div>

        </div>

    </div>

    <?php $this->endWidget(); ?>

</div>