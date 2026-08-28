<?php
/* @var $this BrandsController */
/* @var $model Brands */
/* @var $form CActiveForm */
?>
<?php
Yii::app()->clientScript->registerCss('admin-form-brands', '
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
.admin-form-field select {
	height: 40px;
}
.admin-form-field textarea {
	min-height: 120px;
	resize: vertical;
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
   SWITCH FIELD
   ========================================================== */
.admin-form-field--switch {
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 16px;
	min-height: 40px;
	padding: 10px 12px;
	box-sizing: border-box;
	border: 1px solid #e5e7eb;
	border-radius: 7px;
	background: #f9fafb;
}
.admin-form-field--switch .admin-form-field__label {
	margin: 0;
}
.admin-form-field__switch {
	display: inline-flex;
	flex-shrink: 0;
}
.admin-form-field__switch .admin-form-switch {
	width: 42px;
	height: 24px;
}
.admin-form-field__switch .admin-form-switch__input {
	position: absolute;
	width: 1px;
	height: 1px;
	op: 0;
	left: 0;
	op: 0;
	op: 0;
	op: 0;
	op: 0;
	op: 0;
	margin: 0;
	op: 0;
	left: 0;
	op: 0;
	opacity: 0;
}
.admin-form-field--switch .error {
	grid-column: 1 / -1;
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
');
?>
<div class="admin-form-page">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id' => 'brands-form',
		'enableAjaxValidation' => false,
		'htmlOptions' => array(
			'class' => 'admin-form',
		),
	)); ?>
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
						Completa los campos correspondientes.
					</p>
				</div>
			</div>
							<div class="admin-form-status">
																	<div class="admin-form-status__item">
							<div class="admin-form-status__text">
								<span class="admin-form-status__label">
									Is Active								</span>
								<span class="admin-form-status__description">
									<?php
									echo $model->isNewRecord
										? 'Activo por defecto'
										: ($model->is_active ? 'Activo' : 'Inactivo');
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
										'class' => 'admin-form-status__input',
										'checked' => $model->isNewRecord
											? true
											: (bool) $model->is_active,
									)
								);
								?>
								<span class="admin-form-switch__track"></span>
							</label>
						</div>
									</div>
					</div>
		<div class="admin-form-card__body">
			<?= $form->errorSummary($model, '<strong>Por favor verifica la información:</strong>'); ?>
			<div class="admin-form-fields">
															<div class="admin-form-field">
							<?= $form->labelEx($model,'name'); ?>
<?= $form->textField($model,'name',['class'=>'form-control','size'=>60,'maxlength'=>255]); ?>
						</div>
																				<div class="admin-form-field">
							<?= $form->labelEx($model,'slug'); ?>
<?= $form->textField($model,'slug',['class'=>'form-control','size'=>60,'maxlength'=>150]); ?>
						</div>
																				<div class="admin-form-field">
							<?= $form->labelEx($model,'logo'); ?>
<?= $form->textField($model,'logo',['class'=>'form-control','size'=>60,'maxlength'=>255]); ?>
						</div>
																				<div class="admin-form-field">
							<?= $form->labelEx($model,'website_url'); ?>
<?= $form->textField($model,'website_url',['class'=>'form-control','size'=>60,'maxlength'=>255]); ?>
						</div>
																				<div class="admin-form-field admin-form-field--switch">
							<div>
								<?= $form->labelEx($model,'is_featured'); ?>
							</div>
							<div class="admin-form-field__switch">
								<label class="admin-form-switch">
									<?php
									echo CHtml::activeCheckBox(
										$model,
										'is_featured',
										array(
											'uncheckValue' => '0',
											'class' => 'admin-form-switch__input',
										)
									);
									?>
									<span class="admin-form-switch__track"></span>
								</label>
							</div>
						</div>
																				<div class="admin-form-field">
							<?= $form->labelEx($model,'sort_order'); ?>
<?= $form->textField($model,'sort_order'); ?>
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
					href="<?php echo $this->createUrl("index"); ?>"
					class="admin-form-button admin-form-button--secondary"
				>
					<i class="fas fa-times"></i>
					Cancelar
				</a>
				<button
					type="submit"
					class="admin-form-button admin-form-button--primary"
				>
					<?php
					if ($model->isNewRecord) {
						echo '<i class="fas fa-plus"></i> Crear';
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