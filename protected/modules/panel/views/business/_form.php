<?php

/* @var $this BusinessController */
/* @var $model Businesses */
/* @var $translation BusinessTranslations */
/* @var $defaultLanguage Languages */
/* @var $languages Languages[] */
/* @var $translationsByLanguage BusinessTranslations[] */
/* @var $form CActiveForm */

Yii::app()->clientScript->registerCss(
	'admin-form-businesses',
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

.admin-form-card + .admin-form-card {
	margin-top: 18px;
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

.admin-form-field input[type="text"],
.admin-form-field input[type="number"],
.admin-form-field textarea,
.admin-form-field select {
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
		box-shadow .15s ease;
}

.admin-form-field input[type="text"],
.admin-form-field input[type="number"],
.admin-form-field select {
	height: 40px;
}

.admin-form-field textarea {
	min-height: 120px;
	resize: vertical;
}

.admin-form-field input:focus,
.admin-form-field textarea:focus,
.admin-form-field select:focus {
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

.admin-form-field .hint {
	display: block;
	margin-top: 6px;
	color: #9ca3af;
	font-size: 11px;
	line-height: 1.4;
}


/* ==========================================================
   IMAGE
   ========================================================== */

.admin-form-image-upload {
	display: flex;
	align-items: flex-start;
	gap: 16px;
}

.admin-form-image-preview {
	display: flex;
	align-items: center;
	justify-content: center;
	width: 180px;
	height: 110px;
	flex-shrink: 0;
	overflow: hidden;
	border: 1px solid #e5e7eb;
	border-radius: 8px;
	background: #f9fafb;
}

.admin-form-image-preview img {
	display: block;
	width: 100%;
	height: 100%;
	object-fit: contain;
}

.admin-form-image-preview__empty {
	display: flex;
	align-items: center;
	justify-content: center;
	flex-direction: column;
	gap: 6px;
	color: #9ca3af;
	font-size: 11px;
	text-align: center;
}

.admin-form-image-preview__empty i {
	font-size: 22px;
	color: #d1d5db;
}

.admin-form-image-input {
	flex: 1;
	min-width: 0;
}

.admin-form-file {
	display: block;
	width: 100%;
	box-sizing: border-box;
	padding: 8px;
	border: 1px dashed #d1d5db;
	border-radius: 7px;
	background: #f9fafb;
	color: #4b5563;
	font-family: inherit;
	font-size: 12px;
	cursor: pointer;
}

.admin-form-file:hover {
	border-color: #9ca3af;
	background: #fff;
}

.admin-form-image-current {
	margin-top: 7px;
	color: #9ca3af;
	font-size: 10px;
	line-height: 1.4;
}


/* ==========================================================
   FONT AWESOME PICKER
   ========================================================== */

.admin-icon-picker {
	width: 100%;
}

.admin-icon-picker__selected {
	display: flex;
	align-items: center;
	gap: 12px;
	min-height: 52px;
	padding: 8px 12px;
	box-sizing: border-box;
	border: 1px solid #e5e7eb;
	border-radius: 7px;
	background: #f9fafb;
}

.admin-icon-picker__selected-icon {
	display: flex;
	align-items: center;
	justify-content: center;
	width: 36px;
	height: 36px;
	flex-shrink: 0;
	border-radius: 6px;
	background: #fff;
	border: 1px solid #e5e7eb;
	color: #374151;
	font-size: 17px;
}

.admin-icon-picker__selected-info {
	min-width: 0;
}

.admin-icon-picker__selected-label {
	display: block;
	margin-bottom: 2px;
	color: #9ca3af;
	font-size: 10px;
}

.admin-icon-picker__selected-value {
	display: block;
	overflow: hidden;
	color: #374151;
	font-family: monospace;
	font-size: 11px;
	text-overflow: ellipsis;
	white-space: nowrap;
}

.admin-icon-picker__search {
	margin-top: 10px;
}

.admin-icon-picker__search input {
	display: block;
	width: 100%;
	height: 40px;
	padding: 0 11px;
	box-sizing: border-box;
	border: 1px solid #d1d5db;
	border-radius: 6px;
	outline: none;
	background: #fff;
	color: #374151;
	font-family: inherit;
	font-size: 13px;
}

.admin-icon-picker__search input:focus {
	border-color: #9ca3af;
	box-shadow: 0 0 0 3px rgba(17, 24, 39, .06);
}

.admin-icon-picker__count {
	margin-top: 7px;
	color: #9ca3af;
	font-size: 10px;
}

.admin-icon-picker__grid {
	display: grid;
	grid-template-columns: repeat(10, minmax(0, 1fr));
	gap: 6px;
	max-height: 360px;
	overflow-y: auto;
	margin-top: 10px;
	padding: 8px;
	border: 1px solid #e5e7eb;
	border-radius: 7px;
	background: #f9fafb;
}

.admin-icon-picker__item {
	display: flex;
	align-items: center;
	justify-content: center;
	width: 100%;
	height: 42px;
	padding: 0;
	box-sizing: border-box;
	border: 1px solid transparent;
	border-radius: 6px;
	background: #fff;
	color: #4b5563;
	cursor: pointer;
	font-size: 16px;
	transition:
		background-color .12s ease,
		border-color .12s ease,
		color .12s ease,
		transform .12s ease;
}

.admin-icon-picker__item:hover {
	background: #f3f4f6;
	border-color: #d1d5db;
	color: #111827;
	transform: translateY(-1px);
}

.admin-icon-picker__item.is-selected {
	background: #111827;
	border-color: #111827;
	color: #fff;
}

.admin-icon-picker__empty {
	display: none;
	padding: 24px;
	color: #9ca3af;
	font-size: 12px;
	text-align: center;
}

.admin-icon-picker__empty.is-visible {
	display: block;
}


/* ==========================================================
   LANGUAGE
   ========================================================== */

.admin-form-language {
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 12px;
	min-height: 40px;
	padding: 8px 11px;
	box-sizing: border-box;
	border: 1px solid #e5e7eb;
	border-radius: 6px;
	background: #f9fafb;
}

.admin-form-language__name {
	display: flex;
	align-items: center;
	gap: 9px;
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
	color: #374151;
	font-size: 10px;
	font-weight: 700;
}

.admin-form-language__badge {
	display: inline-flex;
	align-items: center;
	height: 24px;
	padding: 0 8px;
	border-radius: 5px;
	background: #f3f4f6;
	color: #6b7280;
	font-size: 10px;
	font-weight: 700;
}


/* ==========================================================
   TRANSLATIONS
   ========================================================== */

.admin-form-translations {
	display: flex;
	flex-direction: column;
	gap: 8px;
}

.admin-form-translation {
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 18px;
	padding: 12px 14px;
	border: 1px solid #e5e7eb;
	border-radius: 7px;
	background: #fff;
}

.admin-form-translation:hover {
	background: #fafafa;
}

.admin-form-translation__language {
	display: flex;
	align-items: center;
	gap: 11px;
	min-width: 0;
}

.admin-form-translation__code {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 34px;
	height: 28px;
	flex-shrink: 0;
	border: 1px solid #e5e7eb;
	border-radius: 5px;
	background: #f9fafb;
	color: #4b5563;
	font-size: 10px;
	font-weight: 700;
}

.admin-form-translation__name {
	color: #374151;
	font-size: 13px;
	font-weight: 600;
}

.admin-form-translation__native {
	display: block;
	margin-top: 2px;
	color: #9ca3af;
	font-size: 11px;
}

.admin-form-translation__status {
	display: flex;
	align-items: center;
	gap: 6px;
	flex-shrink: 0;
	color: #6b7280;
	font-size: 11px;
}

.admin-form-translation__status-dot {
	width: 7px;
	height: 7px;
	border-radius: 50%;
	background: #d1d5db;
}

.admin-form-translation--translated
.admin-form-translation__status-dot {
	background: #16a34a;
}

.admin-form-translation--translated
.admin-form-translation__status {
	color: #166534;
}

.admin-form-translation__action {
	display: inline-flex;
	align-items: center;
	gap: 6px;
	height: 32px;
	padding: 0 10px;
	box-sizing: border-box;
	border: 1px solid #d1d5db;
	border-radius: 6px;
	background: #fff;
	color: #374151 !important;
	font-size: 11px;
	font-weight: 600;
	text-decoration: none !important;
}

.admin-form-translation__action:hover {
	background: #f3f4f6;
	border-color: #9ca3af;
	color: #111827 !important;
	text-decoration: none !important;
}

.admin-form-translation__action--add {
	background: #111827;
	border-color: #111827;
	color: #fff !important;
}

.admin-form-translation__action--add:hover {
	background: #1f2937;
	border-color: #1f2937;
	color: #fff !important;
}


/* ==========================================================
   SWITCH
   ========================================================== */

.admin-form-switch-field {
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 16px;
	min-height: 40px;
	padding: 8px 11px;
	box-sizing: border-box;
	border: 1px solid #e5e7eb;
	border-radius: 7px;
	background: #f9fafb;
}

.admin-form-switch {
	position: relative;
	display: inline-flex;
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
	display: block;
	width: 42px;
	height: 24px;
	border-radius: 999px;
	background: #d1d5db;
	cursor: pointer;
	transition: background-color .15s ease;
}

.admin-form-switch__track:after {
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

.admin-form-switch input:checked + .admin-form-switch__track:after {
	transform: translateX(18px);
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
}

.admin-form-button--primary {
	background: #111827;
	border-color: #111827;
	color: #fff !important;
}

.admin-form-button--primary:hover {
	background: #1f2937;
	border-color: #1f2937;
}

.admin-form-button--secondary {
	background: #fff;
	border-color: #d1d5db;
	color: #374151 !important;
}

.admin-form-button--secondary:hover {
	background: #f3f4f6;
	border-color: #9ca3af;
}


/* ==========================================================
   RESPONSIVE
   ========================================================== */

@media (max-width: 900px) {

	.admin-icon-picker__grid {
		grid-template-columns: repeat(8, minmax(0, 1fr));
	}
}

@media (max-width: 768px) {

	.admin-form-fields {
		grid-template-columns: 1fr;
	}

	.admin-form-field--full {
		grid-column: auto;
	}

	.admin-form-card__header {
		align-items: flex-start;
		flex-direction: column;
	}

	.admin-form-image-upload {
		flex-direction: column;
	}

	.admin-form-image-preview {
		width: 100%;
		height: 180px;
	}

	.admin-icon-picker__grid {
		grid-template-columns: repeat(6, minmax(0, 1fr));
	}

	.admin-form-translation {
		align-items: flex-start;
		flex-direction: column;
	}

	.admin-form-translation__status {
		width: 100%;
		justify-content: space-between;
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
			'id' => 'businesses-form',
			'enableAjaxValidation' => false,
			'htmlOptions' => array(
				'class' => 'admin-form',
				'enctype' => 'multipart/form-data',
			),
		)
	);
	?>


	<!-- ==================================================
	     MAIN INFORMATION
	     ================================================== -->

	<div class="admin-form-card">

		<div class="admin-form-card__header">

			<div class="admin-form-card__heading">

				<div class="admin-form-card__icon">

					<?php
					echo $model->isNewRecord
						? '<i class="fas fa-plus"></i>'
						: '<i class="fas fa-pen"></i>';
					?>

				</div>

				<div>

					<h2 class="admin-form-card__title">
						Información
					</h2>

					<p class="admin-form-card__description">
						<?php
						echo $model->isNewRecord
							? 'Completa la información del business.'
							: 'Modifica la imagen y el icono del business.';
						?>
					</p>

				</div>

			</div>

		</div>


		<div class="admin-form-card__body">

			<?= $form->errorSummary(
				$model,
				'<strong>Por favor verifica la información:</strong>'
			); ?>


			<div class="admin-form-fields">


				<!-- ==================================================
				     IMAGE
				     ================================================== -->

				<div class="admin-form-field admin-form-field--full">

					<?= $form->labelEx(
						$model,
						'image'
					); ?>


					<div class="admin-form-image-upload">


						<div
							id="business-image-preview"
							class="admin-form-image-preview">

							<?php if (!empty($model->image)): ?>

								<img
									src="<?= Yii::app()->getBaseUrl() . CHtml::encode(
												$model->image
											); ?>"
									alt="Vista previa">

							<?php else: ?>

								<div class="admin-form-image-preview__empty">

									<i class="fas fa-image"></i>

									<span>
										No hay imagen
									</span>

								</div>

							<?php endif; ?>

						</div>


						<div class="admin-form-image-input">

							<?= $form->fileField(
								$model,
								'image',
								array(
									'class' => 'admin-form-file',
									'id' => 'business-image-input',
									'accept' => 'image/jpeg,image/png,image/webp',
								)
							); ?>

							<?= $form->error(
								$model,
								'image'
							); ?>

							<span class="hint">
								Selecciona una imagen JPG, PNG o WebP.
								La imagen será optimizada para web.
							</span>

							<?php if (!empty($model->image)): ?>

								<div class="admin-form-image-current">

									Imagen actual:
									<?= CHtml::encode(
										$model->image
									); ?>

								</div>

							<?php endif; ?>

						</div>

					</div>

				</div>


				<!-- ==================================================
				     ICON
				     ================================================== -->

				<div class="admin-form-field admin-form-field--full">

					<label>
						Icono
					</label>


					<div class="admin-icon-picker">


						<?php
						$currentIcon = !empty($model->icon)
							? $model->icon
							: 'fas fa-building';
						?>


						<?= CHtml::hiddenField(
							'Businesses[icon]',
							$currentIcon,
							array(
								'id' => 'business-icon-value',
							)
						); ?>


						<div class="admin-icon-picker__selected">

							<div
								id="business-icon-selected-preview"
								class="admin-icon-picker__selected-icon">

								<i
									class="<?= CHtml::encode(
												$currentIcon
											); ?>"></i>

							</div>


							<div class="admin-icon-picker__selected-info">

								<span class="admin-icon-picker__selected-label">
									Icono seleccionado
								</span>

								<span
									id="business-icon-selected-value"
									class="admin-icon-picker__selected-value">
									<?= CHtml::encode(
										$currentIcon
									); ?>
								</span>

							</div>

						</div>


						<div class="admin-icon-picker__search">

							<input
								type="text"
								id="business-icon-search"
								placeholder="Buscar icono..."
								autocomplete="off">

						</div>


						<div
							id="business-icon-count"
							class="admin-icon-picker__count">
							Cargando iconos...
						</div>


						<div
							id="business-icon-grid"
							class="admin-icon-picker__grid">
						</div>


						<div
							id="business-icon-empty"
							class="admin-icon-picker__empty">
							No se encontraron iconos.
						</div>

					</div>

				</div>


				<?php if ($model->isNewRecord): ?>


					<!-- SORT ORDER -->

					<div class="admin-form-field">

						<?= $form->labelEx(
							$model,
							'sort_order'
						); ?>

						<?= $form->textField(
							$model,
							'sort_order',
							array(
								'type' => 'number',
								'min' => 0,
							)
						); ?>

						<?= $form->error(
							$model,
							'sort_order'
						); ?>

						<span class="hint">
							Determina la posición del business en los listados.
						</span>

					</div>


					<!-- IS ACTIVE -->

					<div class="admin-form-field">

						<label>
							Estado
						</label>

						<div class="admin-form-switch-field">

							<div>

								<strong
									style="
									display:block;
									color:#374151;
									font-size:12px;
									">
									Business activo
								</strong>

								<span
									style="
									display:block;
									margin-top:2px;
									color:#9ca3af;
									font-size:11px;
									">
									Se mostrará en el sitio.
								</span>

							</div>

							<label class="admin-form-switch">

								<?= CHtml::activeCheckBox(
									$model,
									'is_active',
									array(
										'uncheckValue' => '0',
										'checked' => true,
									)
								); ?>

								<span class="admin-form-switch__track"></span>

							</label>

						</div>

					</div>

				<?php endif; ?>

			</div>

		</div>

		<?php if (!$model->isNewRecord): ?>
			<div class="admin-form-card__footer">

				<div class="admin-form-footer__note">

					<?php if ($model->isNewRecord): ?>

						<span class="required">*</span>
						Campos obligatorios

					<?php else: ?>

						Los textos se administran por idioma.

					<?php endif; ?>

				</div>


				<div class="admin-form-actions">

					<a
						href="<?php echo $this->createUrl('index'); ?>"
						class="admin-form-button admin-form-button--secondary">
						<i class="fas fa-arrow-left"></i>
						Volver
					</a>

					<button
						type="submit"
						class="admin-form-button admin-form-button--primary">

						<?php
						if ($model->isNewRecord) {
							echo '<i class="fas fa-plus"></i> Crear business';
						} else {
							echo '<i class="fas fa-save"></i> Guardar cambios';
						}
						?>

					</button>

				</div>

			</div>
		<?php endif; ?>

	</div>


	<?php if ($model->isNewRecord): ?>


		<!-- ==================================================
		     INITIAL TRANSLATION
		     ================================================== -->

		<div class="admin-form-card">

			<div class="admin-form-card__header">

				<div class="admin-form-card__heading">

					<div class="admin-form-card__icon">
						<i class="fas fa-language"></i>
					</div>

					<div>

						<h2 class="admin-form-card__title">
							Traducción inicial
						</h2>

						<p class="admin-form-card__description">
							El business debe tener una traducción en el idioma predeterminado.
						</p>

					</div>

				</div>

			</div>


			<div class="admin-form-card__body">

				<div class="admin-form-fields">


					<!-- DEFAULT LANGUAGE -->

					<div class="admin-form-field admin-form-field--full">

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

									<span>

										<?= CHtml::encode(
											$defaultLanguage->native_name
										); ?>

									</span>

								<?php else: ?>

									<span>
										Idioma predeterminado
									</span>

								<?php endif; ?>

							</div>

							<span class="admin-form-language__badge">
								Idioma predeterminado
							</span>

						</div>

						<?php
						if ($defaultLanguage) {

							echo CHtml::hiddenField(
								'BusinessTranslations[language_id]',
								$defaultLanguage->id
							);
						}
						?>

					</div>


					<!-- NAME -->

					<div class="admin-form-field">

						<?= $form->labelEx(
							$translation,
							'name'
						); ?>

						<?= $form->textField(
							$translation,
							'name',
							array(
								'maxlength' => 255,
							)
						); ?>

						<?= $form->error(
							$translation,
							'name'
						); ?>

					</div>


					<!-- NAME SIZE -->

					<div class="admin-form-field">

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

					</div>


					<!-- DESCRIPTION -->

					<div class="admin-form-field admin-form-field--full">

						<?= $form->labelEx(
							$translation,
							'description'
						); ?>

						<?= $form->textArea(
							$translation,
							'description',
							array(
								'rows' => 7,
							)
						); ?>

						<?= $form->error(
							$translation,
							'description'
						); ?>

					</div>


					<!-- DESCRIPTION SIZE -->

					<div class="admin-form-field">

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

					</div>

				</div>

			</div>

		</div>


	<?php else: ?>


		<!-- ==================================================
		     TRANSLATIONS
		     ================================================== -->

		<div
			class="admin-form-card"
			id="business-translations-card">

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
							Administra los idiomas disponibles para este business.
						</p>

					</div>

				</div>

			</div>


			<div class="admin-form-card__body">

				<?php if (!empty($languages)): ?>

					<div class="admin-form-translations">

						<?php foreach ($languages as $language): ?>

							<?php

							$languageId =
								(int) $language->id;

							$languageKey =
								(string) $language->id;

							$businessTranslation =
								isset(
									$translationsByLanguage[$languageKey]
								)
								? $translationsByLanguage[$languageKey]
								: null;

							$hasTranslation =
								$businessTranslation !== null;

							$classes =
								'admin-form-translation';

							if ($hasTranslation) {
								$classes .=
									' admin-form-translation--translated';
							}

							?>

							<div
								class="<?= $classes; ?>"
								data-language-id="<?= $languageId; ?>">

								<div class="admin-form-translation__language">

									<span class="admin-form-translation__code">

										<?= CHtml::encode(
											strtoupper(
												$language->code
											)
										); ?>

									</span>

									<div>

										<div class="admin-form-translation__name">

											<?= CHtml::encode(
												$language->name
											); ?>

										</div>

										<span class="admin-form-translation__native">

											<?= CHtml::encode(
												$language->native_name
											); ?>

										</span>

									</div>

								</div>


								<div class="admin-form-translation__status">

									<?php if ($hasTranslation): ?>

										<span class="admin-form-translation__status-dot"></span>

										<span>
											Traducido
										</span>

										<a
											class="admin-form-translation__action"
											href="<?php
													echo $this->createUrl(
														'translation',
														array(
															'business_id' => $model->id,
															'language_id' => $languageId,
														)
													);
													?>">
											<i class="fas fa-pen"></i>
											Editar
										</a>

									<?php else: ?>

										<span class="admin-form-translation__status-dot"></span>

										<span>
											Sin traducción
										</span>

										<a
											class="admin-form-translation__action admin-form-translation__action--add"
											href="<?php
													echo $this->createUrl(
														'translation',
														array(
															'business_id' => $model->id,
															'language_id' => $languageId,
														)
													);
													?>">
											<i class="fas fa-plus"></i>
											Agregar
										</a>

									<?php endif; ?>

								</div>

							</div>

						<?php endforeach; ?>

					</div>

				<?php else: ?>

					<div
						style="
						padding:40px 20px;
						text-align:center;
						color:#9ca3af;
						font-size:12px;
						">
						No hay idiomas configurados.
					</div>

				<?php endif; ?>

			</div>

		</div>

	<?php endif; ?>

	<?php if ($model->isNewRecord): ?>
		<div class="admin-form-card__footer">

			<div class="admin-form-footer__note">

				<?php if ($model->isNewRecord): ?>

					<span class="required">*</span>
					Campos obligatorios

				<?php else: ?>

					Los textos se administran por idioma.

				<?php endif; ?>

			</div>


			<div class="admin-form-actions">

				<a
					href="<?php echo $this->createUrl('index'); ?>"
					class="admin-form-button admin-form-button--secondary">
					<i class="fas fa-arrow-left"></i>
					Volver
				</a>

				<button
					type="submit"
					class="admin-form-button admin-form-button--primary">

					<?php
					if ($model->isNewRecord) {
						echo '<i class="fas fa-plus"></i> Crear business';
					} else {
						echo '<i class="fas fa-save"></i> Guardar cambios';
					}
					?>

				</button>

			</div>

		</div>
	<?php endif; ?>


	<?php $this->endWidget(); ?>

</div>


<?php

/*
 * ==========================================================
 * FONT AWESOME ICONS
 * ==========================================================
 *
 * Los iconos se obtienen directamente de:
 *
 * /bin/fontawesome/metadata/icons.yml
 *
 * El archivo de metadata de Font Awesome contiene los estilos
 * disponibles para cada icono. Construimos las clases CSS que
 * utiliza Font Awesome 5:
 *
 * solid   -> fas
 * regular -> far
 * brands  -> fab
 *
 * No mantenemos una lista manual de iconos en este formulario.
 */

$fontAwesomeIcons = array();

$fontAwesomeMetadataPath =
	Yii::getPathOfAlias('webroot') .
	'/bin/fonts/font-awesome/metadata/icons.yml';

if (is_file($fontAwesomeMetadataPath)) {

	$lines =
		file(
			$fontAwesomeMetadataPath,
			FILE_IGNORE_NEW_LINES
		);

	$currentIcon = null;
	$insideStyles = false;

	foreach ($lines as $line) {

		/*
		 * Un icono es una clave de primer nivel.
		 *
		 * Ejemplo:
		 *
		 * address-book:
		 *   changes:
		 *   - ...
		 *   styles:
		 *   - regular
		 */
		if (
			preg_match(
				'/^([A-Za-z0-9][A-Za-z0-9-]*):\\s*$/',
				$line,
				$matches
			)
		) {

			$currentIcon =
				$matches[1];

			$insideStyles =
				false;

			continue;
		}


		if ($currentIcon === null) {
			continue;
		}


		/*
		 * Detectar el bloque styles.
		 */
		if (
			preg_match(
				'/^\\s{2}styles:\\s*$/',
				$line
			)
		) {

			$insideStyles =
				true;

			continue;
		}


		/*
		 * Si encontramos otra propiedad de segundo nivel,
		 * dejamos de leer styles.
		 */
		if (
			$insideStyles &&
			preg_match(
				'/^\\s{2}[A-Za-z0-9_-]+:/',
				$line
			)
		) {

			$insideStyles =
				false;
		}


		if (!$insideStyles) {
			continue;
		}


		/*
		 * Los estilos aparecen como:
		 *
		 *   - solid
		 *   - regular
		 *   - brands
		 */
		if (
			preg_match(
				'/^\\s{4}-\\s*([A-Za-z0-9_-]+)\\s*$/',
				$line,
				$matches
			)
		) {

			$style =
				$matches[1];

			$prefix = null;

			switch ($style) {

				case 'solid':
					$prefix = 'fas';
					break;

				case 'regular':
					$prefix = 'far';
					break;

				case 'brands':
					$prefix = 'fab';
					break;
			}


			if ($prefix !== null) {

				$fontAwesomeIcons[] =
					$prefix .
					' fa-' .
					$currentIcon;
			}
		}
	}
}


/*
 * Eliminar duplicados y ordenar.
 */
$fontAwesomeIcons =
	array_values(
		array_unique(
			$fontAwesomeIcons
		)
	);

sort($fontAwesomeIcons);


/*
 * Pasar el array PHP a JavaScript de forma segura.
 */
$fontAwesomeIconsJson =
	CJSON::encode(
		$fontAwesomeIcons
	);


Yii::app()->clientScript->registerScript(
	'business-form',
	"
(function() {

	/* ======================================================
	   IMAGE PREVIEW
	   ====================================================== */

	$('#business-image-input').on(
		'change',
		function() {

			var input = this;

			var preview =
				$('#business-image-preview');

			if (
				!input.files ||
				!input.files[0]
			) {
				return;
			}

			var file =
				input.files[0];

			if (
				!file.type.match(
					/^image\\/(jpeg|png|webp)$/i
				)
			) {
				return;
			}

			var reader =
				new FileReader();

			reader.onload =
				function(e) {

					preview.html(
						'<img src=\"' +
						e.target.result +
						'\" alt=\"Vista previa\">'
					);
				};

			reader.readAsDataURL(file);
		}
	);


	/* ======================================================
	   FONT AWESOME ICONS
	   ====================================================== */

	var fontAwesomeIcons = " . $fontAwesomeIconsJson . ";


	var iconGrid =
		$('#business-icon-grid');

	var iconSearch =
		$('#business-icon-search');

	var iconEmpty =
		$('#business-icon-empty');

	var iconCount =
		$('#business-icon-count');

	var iconValue =
		$('#business-icon-value');


	/*
	 * Renderizar todos los iconos.
	 */
	function renderIcons(
		filter
	) {

		filter =
			(filter || '')
			.toLowerCase()
			.trim();

		iconGrid.empty();

		var visibleCount = 0;

		$.each(
			fontAwesomeIcons,
			function(index, icon) {

				if (
					filter &&
					icon
						.toLowerCase()
						.indexOf(filter) === -1
				) {
					return;
				}

				var button =
					$('<button>', {
						type: 'button',
						class:
							'admin-icon-picker__item',
						'data-icon': icon,
						title: icon
					});

				if (
					icon ===
					iconValue.val()
				) {

					button.addClass(
						'is-selected'
					);
				}

				button.append(
					$('<i>', {
						class: icon,
						'aria-hidden': 'true'
					})
				);

				iconGrid.append(
					button
				);

				visibleCount++;
			}
		);


		iconCount.text(
			visibleCount +
			' iconos disponibles'
		);


		if (visibleCount === 0) {

			iconEmpty.addClass(
				'is-visible'
			);

		} else {

			iconEmpty.removeClass(
				'is-visible'
			);
		}
	}


	/*
	 * Seleccionar icono.
	 */
	iconGrid.on(
		'click',
		'.admin-icon-picker__item',
		function() {

			var button =
				$(this);

			var icon =
				button.attr(
					'data-icon'
				);

			if (!icon) {
				return;
			}


			iconValue.val(
				icon
			);


			$('#business-icon-selected-value')
				.text(
					icon
				);


			$('#business-icon-selected-preview')
				.html(
					'<i class=\"' +
					icon +
					'\" aria-hidden=\"true\"></i>'
				);


			iconGrid
				.find(
					'.admin-icon-picker__item'
				)
				.removeClass(
					'is-selected'
				);


			button.addClass(
				'is-selected'
			);
		}
	);


	/*
	 * Buscar iconos.
	 */
	iconSearch.on(
		'input',
		function() {

			renderIcons(
				$(this).val()
			);
		}
	);


	/*
	 * Render inicial.
	 */
	renderIcons();

})();
"
);

?>