<?php
/* @var $this HeroSlidesController */
/* @var $model HeroSlides */
/* @var $translation HeroSlideTranslations */
/* @var $form CActiveForm */
?>

<?php
Yii::app()->clientScript->registerCss('admin-form-hero-slides', '

/* ==========================================================
   PAGE
   ========================================================== */

.admin-form-page {
	width: 100%;
	max-width: 1100px;
	margin: 0 auto;
}

/* ==========================================================
   FORM
   ========================================================== */

.admin-form {
	margin-top: 28px;
}

/* ==========================================================
   CARD
   ========================================================== */

.admin-form-card {
	overflow: hidden;
	background: #fff;
	border: 1px solid #e5e7eb;
	border-radius: 10px;
	box-shadow: 0 1px 2px rgba(0, 0, 0, .03);
}

/* ==========================================================
   CARD HEADER
   ========================================================== */

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

/* ==========================================================
   STATUS
   ========================================================== */

.admin-form-status {
	display: flex;
	align-items: center;
	gap: 20px;
	flex-shrink: 0;
}

.admin-form-status__item {
	display: flex;
	align-items: center;
	gap: 10px;
}

.admin-form-status__text {
	display: flex;
	flex-direction: column;
	gap: 2px;
}

.admin-form-status__label {
	color: #374151;
	font-size: 12px;
	font-weight: 600;
	line-height: 1.3;
}

.admin-form-status__description {
	color: #9ca3af;
	font-size: 11px;
	line-height: 1.3;
}

/* ==========================================================
   SWITCH
   ========================================================== */

.admin-form-switch {
	position: relative;
	display: inline-flex;
	align-items: center;
	width: 42px;
	height: 24px;
	flex-shrink: 0;
}

.admin-form-switch input {
	position: absolute;
	width: 1px;
	height: 1px;
	opacity: 0;
}

.admin-form-switch__track {
	position: relative;
	display: block;
	width: 42px;
	height: 24px;
	border-radius: 999px;
	background: #d1d5db;
	cursor: pointer;
	transition:
		background-color .15s ease,
		box-shadow .15s ease;
}

.admin-form-switch__track::after {
	position: absolute;
	top: 3px;
	left: 3px;
	width: 18px;
	height: 18px;
	border-radius: 50%;
	background: #fff;
	box-shadow: 0 1px 2px rgba(0, 0, 0, .18);
	content: "";
	transition: transform .15s ease;
}

.admin-form-switch input:checked + .admin-form-switch__track {
	background: #111827;
}

.admin-form-switch input:checked + .admin-form-switch__track::after {
	transform: translateX(18px);
}

.admin-form-switch input:focus + .admin-form-switch__track {
	box-shadow: 0 0 0 3px rgba(17, 24, 39, .08);
}

/* ==========================================================
   BODY
   ========================================================== */

.admin-form-card__body {
	padding: 24px 20px;
}

/* ==========================================================
   SECTION
   ========================================================== */

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

/* ==========================================================
   LANGUAGE BADGE
   ========================================================== */

.admin-form-language {
	display: inline-flex;
	align-items: center;
	gap: 7px;
	padding: 6px 10px;
	border: 1px solid #e5e7eb;
	border-radius: 6px;
	background: #f9fafb;
	color: #374151;
	font-size: 12px;
	font-weight: 600;
}

.admin-form-language i {
	color: #6b7280;
	font-size: 11px;
}

/* ==========================================================
   REQUIRED NOTE
   ========================================================== */

.admin-form-required-note {
	display: flex;
	align-items: center;
	gap: 6px;
	margin: 0 0 22px;
	color: #6b7280;
	font-size: 12px;
}

.admin-form-required-note .required {
	color: #dc2626;
	font-weight: 700;
}

/* ==========================================================
   ERROR SUMMARY
   ========================================================== */

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

.admin-form-card .errorSummary li {
	margin: 3px 0;
}

.admin-form-card .errorSummary a {
	color: #991b1b;
}

/* ==========================================================
   FIELDS
   ========================================================== */

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

/* ==========================================================
   INPUTS
   ========================================================== */

.admin-form-field input[type="text"],
.admin-form-field input[type="password"],
.admin-form-field input[type="email"],
.admin-form-field input[type="number"],
.admin-form-field input[type="url"],
.admin-form-field input[type="tel"],
.admin-form-field input[type="date"],
.admin-form-field input[type="datetime"],
.admin-form-field input[type="datetime-local"],
.admin-form-field input[type="time"],
.admin-form-field input[type="search"],
.admin-form-field input[type="file"],
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
	transition:
		border-color .15s ease,
		box-shadow .15s ease,
		background-color .15s ease;
}

.admin-form-field input[type="text"],
.admin-form-field input[type="password"],
.admin-form-field input[type="email"],
.admin-form-field input[type="number"],
.admin-form-field input[type="url"],
.admin-form-field input[type="tel"],
.admin-form-field input[type="date"],
.admin-form-field input[type="datetime"],
.admin-form-field input[type="datetime-local"],
.admin-form-field input[type="time"],
.admin-form-field input[type="search"],
.admin-form-field input[type="file"],
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

.admin-form-field input:disabled,
.admin-form-field select:disabled,
.admin-form-field textarea:disabled {
	background: #f9fafb;
	color: #9ca3af;
	cursor: not-allowed;
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

/* ==========================================================
   IMAGE UPLOAD
   ========================================================== */

.admin-form-image-upload {
	display: flex;
	flex-direction: column;
	gap: 10px;
}

.admin-form-image-preview {
	display: none;
	width: 100%;
	max-width: 600px;
	overflow: hidden;
	border: 1px solid #e5e7eb;
	border-radius: 7px;
	background: #f9fafb;
}

.admin-form-image-preview img {
	display: block;
	width: 100%;
	height: auto;
}

/* ==========================================================
   FOOTER
   ========================================================== */

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

/* ==========================================================
   BUTTONS
   ========================================================== */

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
	transition:
		background-color .15s ease,
		border-color .15s ease,
		box-shadow .15s ease,
		color .15s ease;
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

/* ==========================================================
   RESPONSIVE
   ========================================================== */

@media (max-width: 768px) {

	.admin-form-card__header {
		align-items: flex-start;
		flex-direction: column;
	}

	.admin-form-status {
		width: 100%;
		justify-content: space-between;
		padding-top: 12px;
		border-top: 1px solid #f0f1f3;
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

 .is-visible{
 display:block !important
 }

');
?>

<div class="admin-form-page">

	<?php
	$form = $this->beginWidget('CActiveForm', array(
		'id' => 'hero-slides-form',
		'enableAjaxValidation' => false,
		'htmlOptions' => array(
			'class' => 'admin-form',
			'enctype' => 'multipart/form-data',
		),
	));
	?>

	<div class="admin-form-card">

		<!-- ======================================================
		     HEADER
		     ====================================================== -->

		<div class="admin-form-card__header">

			<div class="admin-form-card__heading">


				<div class="admin-form-section__heading">

					<div class="admin-form-section__icon">
						<i class="fas fa-image"></i>
					</div>

					<div>

						<h1 class="admin-form-section__title">
							Información del Slide
						</h1>

						<p class="admin-form-section__description">
							Configuración visual y comportamiento del slide.
						</p>

					</div>

				</div>


			</div>

			<div class="admin-form-status">

				<div class="admin-form-status__item">

					<div class="admin-form-status__text">

						<span class="admin-form-status__label">
							Is Active
						</span>

						<span class="admin-form-status__description">
							Activo por defecto
						</span>

					</div>

					<label class="admin-form-switch">

						<?php
						echo CHtml::activeCheckBox(
							$model,
							'is_active',
							array(
								'uncheckValue' => '0',
								'class' => 'admin-form-status__input',
								'checked' => true,
							)
						);
						?>

						<span class="admin-form-switch__track"></span>

					</label>

				</div>

			</div>

		</div>

		<!-- ======================================================
		     BODY
		     ====================================================== -->

		<div class="admin-form-card__body">

			<?= $form->errorSummary(
				$model,
				'<strong>Por favor verifica la información:</strong>'
			); ?>

			<?php
			if (isset($translation)) {
				echo $form->errorSummary(
					$translation,
					'<strong>Por favor verifica el contenido en español:</strong>'
				);
			}
			?>

			<!-- ==================================================
			     SLIDE INFORMATION
			     ================================================== -->

			<div class="admin-form-section">



				<div class="admin-form-fields">

					<!-- IMAGE -->

					<div class="admin-form-field admin-form-field--full">
						<?= $form->labelEx($model, 'image'); ?>

						<div class="admin-form-image-upload">

							<?= $form->fileField(
								$model,
								'image',
								array(
									'accept' => 'image/jpeg,image/png,image/webp',
								)
							); ?>

							<div
								id="hero-slide-image-preview"
								class="admin-form-image-preview<?= $model->image ? ' is-visible' : ''; ?>">

								<?php if ($model->image): ?>

									<img
										id="hero-slide-image-preview-img"
										src="<?= CHtml::encode(
													Yii::app()->baseUrl .
														'/images/hero-slides/' .
														$model->image
												); ?>"
										alt="Imagen actual">

								<?php else: ?>

									<img
										id="hero-slide-image-preview-img"
										src=""
										alt="Vista previa">

								<?php endif; ?>

							</div>

						</div>

						<span class="hint">
							<?php if ($model->image): ?>
								Imagen actual. Si seleccionas una nueva imagen, reemplazará la actual.
							<?php endif; ?>

							Formatos permitidos: JPG, PNG o WebP.
							La imagen será optimizada para web manteniendo sus dimensiones originales.
						</span>

						<?= $form->error($model, 'image'); ?>

					</div>


					<!-- ALIGNMENT -->

					<div class="admin-form-field">

						<?= $form->labelEx($model, 'alignment'); ?>

						<?= $form->dropDownList(
							$model,
							'alignment',
							array(
								'left' => 'Left',
								'center' => 'Center',
								'right' => 'Right',
							),
							array(
								'class' => 'form-control',
							)
						); ?>

						<?= $form->error($model, 'alignment'); ?>

					</div>

					<!-- BUTTON URL -->

					<div class="admin-form-field">

						<?= $form->labelEx($model, 'button_url'); ?>

						<?= $form->textField(
							$model,
							'button_url',
							array(
								'class' => 'form-control',
								'size' => 60,
								'maxlength' => 255,
							)
						); ?>

						<span class="hint">
							URL a la que dirigirá el botón del slide.
						</span>

						<?= $form->error($model, 'button_url'); ?>

					</div>

					<!-- SORT ORDER -->

					<div class="admin-form-field">

						<?= $form->labelEx($model, 'sort_order'); ?>

						<?= $form->textField(
							$model,
							'sort_order',
							array(
								'type' => 'number',
								'min' => 0,
								'class' => 'form-control',
							)
						); ?>

						<span class="hint">
							Define el orden en que aparecerá el slide.
						</span>

						<?= $form->error($model, 'sort_order'); ?>

					</div>

				</div>

			</div>

			<!-- ==================================================
			     SPANISH TRANSLATION
			     ================================================== -->

			<?php if (isset($translation)): ?>

				<div class="admin-form-section">

					<div class="admin-form-section__header">

						<div class="admin-form-section__heading">

							<div class="admin-form-section__icon">
								<i class="fas fa-language"></i>
							</div>

							<div>

								<h1 class="admin-form-section__title">
									Contenido
								</h1>

								<p class="admin-form-section__description">
									Contenido del hero slide en el idioma predeterminado.
								</p>

							</div>

						</div>

						<div class="admin-form-language">
							<i class="fas fa-globe"></i>
							Español
						</div>

					</div>

					<div class="admin-form-fields">

						<!-- EYEBROW -->

						<div class="admin-form-field">

							<?= $form->labelEx($translation, 'eyebrow'); ?>

							<?= CHtml::activeTextField(
								$translation,
								'eyebrow',
								array(
									'class' => 'form-control',
									'maxlength' => 255,
								)
							); ?>

							<?= CHtml::error(
								$translation,
								'eyebrow'
							); ?>

						</div>

						<!-- EYEBROW SIZE -->

						<div class="admin-form-field">

							<?= $form->labelEx($translation, 'eyebrow_size'); ?>

							<?= CHtml::activeTextField(
								$translation,
								'eyebrow_size',
								array(
									'class' => 'form-control',
									'maxlength' => 20,
									'placeholder' => 'Ej. 16px',
								)
							); ?>

							<?= CHtml::error(
								$translation,
								'eyebrow_size'
							); ?>

						</div>

						<!-- TITLE -->

						<div class="admin-form-field admin-form-field--full">

							<?= $form->labelEx($translation, 'title'); ?>

							<?= CHtml::activeTextArea(
								$translation,
								'title',
								array(
									'class' => 'form-control',
									'rows' => 3,
								)
							); ?>

							<?= CHtml::error(
								$translation,
								'title'
							); ?>

						</div>

						<!-- TITLE SIZE -->

						<div class="admin-form-field">

							<?= $form->labelEx($translation, 'title_size'); ?>

							<?= CHtml::activeTextField(
								$translation,
								'title_size',
								array(
									'class' => 'form-control',
									'maxlength' => 20,
									'placeholder' => 'Ej. 48px',
								)
							); ?>

							<?= CHtml::error(
								$translation,
								'title_size'
							); ?>

						</div>

						<!-- TEXT SIZE -->

						<div class="admin-form-field">

							<?= $form->labelEx($translation, 'text_size'); ?>

							<?= CHtml::activeTextField(
								$translation,
								'text_size',
								array(
									'class' => 'form-control',
									'maxlength' => 20,
									'placeholder' => 'Ej. 18px',
								)
							); ?>

							<?= CHtml::error(
								$translation,
								'text_size'
							); ?>

						</div>


						<!-- TEXT -->

						<div class="admin-form-field admin-form-field--full">

							<?= $form->labelEx($translation, 'text'); ?>

							<?= CHtml::activeTextArea(
								$translation,
								'text',
								array(
									'class' => 'form-control',
									'rows' => 5,
								)
							); ?>

							<?= CHtml::error(
								$translation,
								'text'
							); ?>

						</div>


						<!-- BUTTON TEXT -->

						<div class="admin-form-field">

							<?= $form->labelEx($translation, 'button_text'); ?>

							<?= CHtml::activeTextField(
								$translation,
								'button_text',
								array(
									'class' => 'form-control',
									'maxlength' => 255,
								)
							); ?>

							<?= CHtml::error(
								$translation,
								'button_text'
							); ?>

						</div>

						<!-- BUTTON TEXT SIZE -->

						<div class="admin-form-field">

							<?= $form->labelEx($translation, 'button_text_size'); ?>

							<?= CHtml::activeTextField(
								$translation,
								'button_text_size',
								array(
									'class' => 'form-control',
									'maxlength' => 20,
									'placeholder' => 'Ej. 16px',
								)
							); ?>

							<?= CHtml::error(
								$translation,
								'button_text_size'
							); ?>

						</div>

					</div>

				</div>

			<?php endif; ?>

		</div>

		<!-- ======================================================
		     FOOTER
		     ====================================================== -->

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
					<i class="fas fa-plus"></i>
					<?= $model->isNewRecord ? 'Crear' : 'Actualizar'  ?>
				</button>

			</div>

		</div>

	</div>

	<?php $this->endWidget(); ?>

</div>

<script type="text/javascript">
	(function() {

		var input = document.getElementById('HeroSlides_image');
		var preview = document.getElementById('hero-slide-image-preview');
		var previewImage = document.getElementById('hero-slide-image-preview-img');

		if (!input || !preview || !previewImage) {
			return;
		}

		input.addEventListener('change', function() {

			if (!this.files || !this.files.length) {

				preview.style.display = 'none';
				previewImage.src = '';

				return;
			}

			var file = this.files[0];

			if (!file.type || file.type.indexOf('image/') !== 0) {

				preview.style.display = 'none';
				previewImage.src = '';

				return;
			}

			var reader = new FileReader();

			reader.onload = function(event) {

				previewImage.src = event.target.result;
				preview.style.display = 'block';

			};

			reader.readAsDataURL(file);

		});

	})();
</script>