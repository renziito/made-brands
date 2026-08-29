<?php
/* @var $this HeroSlidesController */
/* @var $model HeroSlideTranslations */
/* @var $heroSlide HeroSlides */
/* @var $language Languages */
/* @var $form CActiveForm */

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'hero-slide-translation-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array(
        'class' => 'admin-form',
    ),
));

Yii::app()->clientScript->registerCss('admin-form-hero-slide-translation', "

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

.admin-form-language {
	display: inline-flex;
	align-items: center;
	gap: 7px;
	padding: 7px 11px;
	border: 1px solid #e5e7eb;
	border-radius: 6px;
	background: #f9fafb;
	color: #374151;
	font-size: 12px;
	font-weight: 600;
	white-space: nowrap;
}

.admin-form-language i {
	color: #6b7280;
	font-size: 11px;
}

.admin-form-card__body {
	padding: 24px 20px;
}

.admin-form-card .errorSummary {
	margin: 0 0 22px;
	padding: 14px 16px;
	border: 1px solid #fecaca;
	border-radius: 7px;
	background: #fef2f2;
	color: #991b1b;
	font-size: 13px;
	line-height: 1.5;
}

.admin-form-card .errorSummary ul {
	margin: 7px 0 0 18px;
	padding: 0;
}

.admin-form-section {
	margin-top: 32px;
	padding-top: 28px;
	border-top: 1px solid #e5e7eb;
}

.admin-form-section:first-of-type {
	margin-top: 0;
	padding-top: 0;
	border-top: 0;
}

.admin-form-section__header {
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 16px;
	margin-bottom: 20px;
}

.admin-form-section__heading {
	display: flex;
	align-items: center;
	gap: 10px;
}

.admin-form-section__icon {
	display: flex;
	align-items: center;
	justify-content: center;
	width: 30px;
	height: 30px;
	flex-shrink: 0;
	border-radius: 6px;
	background: #f3f4f6;
	color: #374151;
	font-size: 12px;
}

.admin-form-section__title {
	margin: 0;
	color: #111827;
	font-size: 14px;
	font-weight: 600;
	line-height: 1.3;
}

.admin-form-section__description {
	margin: 2px 0 0;
	color: #9ca3af;
	font-size: 11px;
	line-height: 1.4;
}

.admin-form-fields {
	display: grid;
	grid-template-columns: repeat(2, minmax(0, 1fr));
	gap: 20px 18px;
}

.admin-form-field {
	min-width: 0;
}

.admin-form-field--full {
	grid-column: 1 / -1;
}

.admin-form-field label {
	display: block;
	margin: 0 0 7px;
	color: #374151;
	font-size: 12px;
	font-weight: 600;
	line-height: 1.4;
}

.admin-form-field label .required {
	margin-left: 2px;
	color: #dc2626;
	font-weight: 700;
}

.admin-form-field input[type=\"text\"],
.admin-form-field input[type=\"number\"],
.admin-form-field select,
.admin-form-field textarea {
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
	transition: border-color .15s ease, box-shadow .15s ease;
}

.admin-form-field input[type=\"text\"],
.admin-form-field input[type=\"number\"],
.admin-form-field select {
	min-height: 40px;
}

.admin-form-field textarea {
	min-height: 120px;
	resize: vertical;
}

.admin-form-field textarea.admin-form-textarea--small {
	min-height: 90px;
}

.admin-form-field input:focus,
.admin-form-field select:focus,
.admin-form-field textarea:focus {
	border-color: #9ca3af;
	box-shadow: 0 0 0 3px rgba(17, 24, 39, .06);
}

.admin-form-field .error {
	display: block;
	margin-top: 6px;
	color: #dc2626;
	font-size: 11px;
	line-height: 1.4;
}

.admin-form-field input.error,
.admin-form-field select.error,
.admin-form-field textarea.error {
	border-color: #fca5a5;
	background: #fffafa;
}

.admin-form-field .hint {
	display: block;
	margin-top: 6px;
	color: #9ca3af;
	font-size: 11px;
	line-height: 1.4;
}

.admin-form-context {
	display: flex;
	align-items: center;
	gap: 8px;
	margin-bottom: 22px;
	padding: 11px 13px;
	border: 1px solid #e5e7eb;
	border-radius: 7px;
	background: #f9fafb;
	color: #6b7280;
	font-size: 12px;
}

.admin-form-context strong {
	color: #374151;
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
	transition: background-color .15s ease, border-color .15s ease, color .15s ease;
}

.admin-form-button:hover {
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

.admin-form-button--secondary {
	background: #fff;
	border-color: #d1d5db;
	color: #374151 !important;
}

.admin-form-button--secondary:hover {
	background: #f3f4f6;
	border-color: #9ca3af;
	color: #111827 !important;
}

@media (max-width: 768px) {
	.admin-form-card__header {
		align-items: flex-start;
		flex-direction: column;
	}

	.admin-form-section__header {
		align-items: flex-start;
		flex-direction: column;
	}

	.admin-form-fields {
		grid-template-columns: 1fr;
	}

	.admin-form-field--full {
		grid-column: auto;
	}

	.admin-form-card__body {
		padding: 20px 16px;
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

");
?>

<div class="admin-form-page">

    <div class="admin-form-card">

        <div class="admin-form-card__header">

            <div class="admin-form-card__heading">

                <div class="admin-form-card__icon">
                    <i class="fas fa-language"></i>
                </div>

                <div>

                    <h2 class="admin-form-card__title">
                        <?php echo $model->isNewRecord ? 'Agregar traducción' : 'Editar traducción'; ?>
                    </h2>

                    <p class="admin-form-card__description">
                        Edita el contenido del Hero Slide para este idioma.
                    </p>

                </div>

            </div>

            <div class="admin-form-language">
                <i class="fas fa-globe"></i>
                <?php echo CHtml::encode($language->name); ?>
            </div>

        </div>

        <div class="admin-form-card__body">

            <?php echo $form->errorSummary(
                $model,
                '<strong>Por favor verifica la información:</strong>'
            ); ?>

            <div class="admin-form-context">

                <?php if ($heroSlide->image): ?>

                    <div class="admin-form-context__image">
                        <img
                            src="<?php echo CHtml::encode(
                                        Yii::app()->baseUrl .
                                            '/images/hero-slides/' .
                                            $heroSlide->image
                                    ); ?>"
                            alt="Hero Slide #<?php echo (int) $heroSlide->id; ?>" style="    max-width: 600px;">
                    </div>

                <?php else: ?>

                    <div class="admin-form-context__image admin-form-context__image--empty">
                        <i class="fas fa-image"></i>
                    </div>

                <?php endif; ?>

                <div class="admin-form-context__info">

                    <div class="admin-form-context__label">
                        Hero Slide
                    </div>

                    <strong>
                        #<?php echo (int) $heroSlide->id; ?>
                    </strong>

                </div>

            </div>

            <div class="admin-form-section">

                <div class="admin-form-section__header">

                    <div class="admin-form-section__heading">

                        <div class="admin-form-section__icon">
                            <i class="fas fa-align-left"></i>
                        </div>

                        <div>

                            <h3 class="admin-form-section__title">
                                Contenido
                            </h3>

                            <p class="admin-form-section__description">
                                Textos que aparecerán sobre el Hero Slide.
                            </p>

                        </div>

                    </div>

                </div>

                <div class="admin-form-fields">

                    <div class="admin-form-field">

                        <?php echo $form->labelEx($model, 'eyebrow'); ?>

                        <?php echo $form->textField(
                            $model,
                            'eyebrow',
                            array(
                                'maxlength' => 255,
                            )
                        ); ?>

                        <?php echo $form->error($model, 'eyebrow'); ?>

                    </div>

                    <div class="admin-form-field">

                        <?php echo $form->labelEx($model, 'eyebrow_size'); ?>

                        <?php echo $form->textField(
                            $model,
                            'eyebrow_size',
                            array(
                                'maxlength' => 20,
                                'placeholder' => 'Ej. 16px',
                            )
                        ); ?>

                        <span class="hint">
                            Tamaño del texto del eyebrow.
                        </span>

                        <?php echo $form->error($model, 'eyebrow_size'); ?>

                    </div>

                    <div class="admin-form-field admin-form-field--full">

                        <?php echo $form->labelEx($model, 'title'); ?>

                        <?php echo $form->textArea(
                            $model,
                            'title',
                            array(
                                'rows' => 3,
                            )
                        ); ?>

                        <?php echo $form->error($model, 'title'); ?>

                    </div>

                    <div class="admin-form-field">

                        <?php echo $form->labelEx($model, 'title_size'); ?>

                        <?php echo $form->textField(
                            $model,
                            'title_size',
                            array(
                                'maxlength' => 20,
                                'placeholder' => 'Ej. 48px',
                            )
                        ); ?>

                        <span class="hint">
                            Tamaño del título.
                        </span>

                        <?php echo $form->error($model, 'title_size'); ?>

                    </div>

                    <div class="admin-form-field">

                        <?php echo $form->labelEx($model, 'text_size'); ?>

                        <?php echo $form->textField(
                            $model,
                            'text_size',
                            array(
                                'maxlength' => 20,
                                'placeholder' => 'Ej. 18px',
                            )
                        ); ?>

                        <span class="hint">
                            Tamaño del texto.
                        </span>

                        <?php echo $form->error($model, 'text_size'); ?>

                    </div>

                    <div class="admin-form-field admin-form-field--full">

                        <?php echo $form->labelEx($model, 'text'); ?>

                        <?php echo $form->textArea(
                            $model,
                            'text',
                            array(
                                'rows' => 5,
                            )
                        ); ?>

                        <?php echo $form->error($model, 'text'); ?>

                    </div>



                    <div class="admin-form-field">

                        <?php echo $form->labelEx($model, 'button_text'); ?>

                        <?php echo $form->textField(
                            $model,
                            'button_text',
                            array(
                                'maxlength' => 255,
                            )
                        ); ?>

                        <?php echo $form->error($model, 'button_text'); ?>

                    </div>

                    <div class="admin-form-field">

                        <?php echo $form->labelEx($model, 'button_text_size'); ?>

                        <?php echo $form->textField(
                            $model,
                            'button_text_size',
                            array(
                                'maxlength' => 20,
                                'placeholder' => 'Ej. 16px',
                            )
                        ); ?>

                        <span class="hint">
                            Tamaño del texto del botón.
                        </span>

                        <?php echo $form->error($model, 'button_text_size'); ?>

                    </div>

                </div>

            </div>

        </div>

        <div class="admin-form-card__footer">

            <div class="admin-form-footer__note">
                <span class="required">*</span>
                Campos obligatorios
            </div>

            <div class="admin-form-actions">

                <a
                    href="<?php echo $this->createUrl('index'); ?>"
                    class="admin-form-button admin-form-button--secondary">
                    <i class="fas fa-times"></i>
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="admin-form-button admin-form-button--primary">
                    <i class="fas fa-save"></i>
                    Guardar
                </button>

            </div>

        </div>

    </div>

</div>

<?php $this->endWidget(); ?>