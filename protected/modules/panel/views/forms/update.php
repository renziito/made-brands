<?php
$this->breadcrumbs = array(
	'Faq Forms' => array('index'),
	$model->title => array('view', 'id' => $model->id),
	'Actualizar',
);

/* @var $this FormsController */
/* @var $model FaqForms */
/* @var $fields FaqFormFields[] */

$this->renderPartial('_form', array(
	'model' => $model,
));
?>

<?php
Yii::app()->clientScript->registerCss('faq-form-fields', '

.faq-fields-section {
	width: 100%;
	max-width: 1100px;
	margin: 28px auto 0;
}

.faq-fields-section__header {
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 20px;
	margin-bottom: 16px;
}

.faq-fields-section__heading {
	min-width: 0;
}

.faq-fields-section__title {
	margin: 0;
	color: #111827;
	font-size: 16px;
	font-weight: 600;
	line-height: 1.4;
}

.faq-fields-section__description {
	margin: 4px 0 0;
	color: #9ca3af;
	font-size: 12px;
	line-height: 1.4;
}

.faq-fields-section__add {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	gap: 7px;
	height: 38px;
	padding: 0 14px;
	box-sizing: border-box;
	border: 1px solid #111827;
	border-radius: 7px;
	background: #111827;
	color: #fff !important;
	font-size: 13px;
	font-weight: 600;
	text-decoration: none !important;
	transition:
		background-color .15s ease,
		border-color .15s ease;
}

.faq-fields-section__add:hover {
	background: #1f2937;
	border-color: #1f2937;
	color: #fff !important;
	text-decoration: none !important;
}

/* ==========================================================
   FIELD CARD
   ========================================================== */

.faq-field-card {
	margin-bottom: 12px;
	overflow: hidden;
	border: 1px solid #e5e7eb;
	border-radius: 9px;
	background: #fff;
	box-shadow: 0 1px 2px rgba(0, 0, 0, .02);
}

.faq-field-card__header {
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 20px;
	padding: 15px 18px;
	border-bottom: 1px solid #e5e7eb;
}

.faq-field-card__main {
	display: flex;
	align-items: center;
	gap: 12px;
	min-width: 0;
}

.faq-field-card__icon {
	display: flex;
	align-items: center;
	justify-content: center;
	width: 34px;
	height: 34px;
	flex-shrink: 0;
	border-radius: 7px;
	background: #f3f4f6;
	color: #374151;
	font-size: 13px;
}

.faq-field-card__info {
	min-width: 0;
}

.faq-field-card__title {
	margin: 0;
	overflow: hidden;
	color: #111827;
	font-size: 14px;
	font-weight: 600;
	line-height: 1.4;
	text-overflow: ellipsis;
	white-space: nowrap;
}

.faq-field-card__name {
	margin: 3px 0 0;
	color: #9ca3af;
	font-size: 11px;
	line-height: 1.3;
}

.faq-field-card__actions {
	display: flex;
	align-items: center;
	gap: 7px;
	flex-shrink: 0;
}

.faq-field-action {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	gap: 6px;
	height: 32px;
	padding: 0 10px;
	box-sizing: border-box;
	border: 1px solid #d1d5db;
	border-radius: 6px;
	background: #fff;
	color: #374151 !important;
	font-size: 12px;
	font-weight: 600;
	text-decoration: none !important;
}

.faq-field-action:hover {
	background: #f3f4f6;
	border-color: #9ca3af;
	color: #111827 !important;
	text-decoration: none !important;
}

.faq-field-action--delete {
	border-color: #fecaca;
	color: #b91c1c !important;
}

.faq-field-action--delete:hover {
	background: #fef2f2;
	border-color: #fca5a5;
	color: #991b1b !important;
}

/* ==========================================================
   FIELD BODY
   ========================================================== */

.faq-field-card__body {
	display: grid;
	grid-template-columns: repeat(4, minmax(0, 1fr));
	gap: 16px;
	padding: 16px 18px;
}

.faq-field-property {
	min-width: 0;
}

.faq-field-property__label {
	display: block;
	margin-bottom: 4px;
	color: #9ca3af;
	font-size: 10px;
	font-weight: 600;
	text-transform: uppercase;
	letter-spacing: .04em;
}

.faq-field-property__value {
	display: block;
	overflow: hidden;
	color: #374151;
	font-size: 12px;
	line-height: 1.5;
	text-overflow: ellipsis;
	white-space: nowrap;
}

.faq-field-required {
	display: inline-flex;
	align-items: center;
	height: 22px;
	padding: 0 7px;
	box-sizing: border-box;
	border-radius: 999px;
	background: #f3f4f6;
	color: #374151;
	font-size: 10px;
	font-weight: 600;
}

.faq-field-required--yes {
	background: #f0fdf4;
	color: #166534;
}

.faq-field-status {
	display: inline-flex;
	align-items: center;
	height: 22px;
	padding: 0 7px;
	box-sizing: border-box;
	border-radius: 999px;
	background: #f3f4f6;
	color: #6b7280;
	font-size: 10px;
	font-weight: 600;
}

.faq-field-status--active {
	background: #f0fdf4;
	color: #166534;
}

/* ==========================================================
   EMPTY
   ========================================================== */

.faq-fields-empty {
	padding: 35px 20px;
	border: 1px dashed #d1d5db;
	border-radius: 9px;
	background: #fafafa;
	text-align: center;
	color: #9ca3af;
	font-size: 12px;
}

.faq-fields-empty__icon {
	margin-bottom: 10px;
	color: #d1d5db;
	font-size: 24px;
}

.faq-fields-empty__title {
	margin: 0 0 4px;
	color: #374151;
	font-size: 13px;
	font-weight: 600;
}

.faq-fields-empty__description {
	margin: 0;
	color: #9ca3af;
	font-size: 11px;
}

/* ==========================================================
   RESPONSIVE
   ========================================================== */

@media (max-width: 768px) {

	.faq-fields-section__header {
		align-items: stretch;
		flex-direction: column;
	}

	.faq-fields-section__add {
		width: 100%;
	}

	.faq-field-card__header {
		align-items: flex-start;
		flex-direction: column;
	}

	.faq-field-card__actions {
		width: 100%;
	}

	.faq-field-action {
		flex: 1;
	}

	.faq-field-card__body {
		grid-template-columns: repeat(2, minmax(0, 1fr));
	}
}

@media (max-width: 480px) {

	.faq-field-card__body {
		grid-template-columns: 1fr;
	}
}

');
?>

<div class="faq-fields-section">

	<div class="faq-fields-section__header">

		<div class="faq-fields-section__heading">

			<h2 class="faq-fields-section__title">
				Campos del formulario
			</h2>

			<p class="faq-fields-section__description">
				Administra los campos que tendrá este formulario.
			</p>

		</div>

		<a
			href="<?php echo $this->createUrl('createField', array(
						'form_id' => $model->id,
					)); ?>"
			class="faq-fields-section__add">
			<i class="fas fa-plus"></i>
			Añadir campo
		</a>

	</div>


	<?php if (!empty($fields)): ?>

		<?php foreach ($fields as $field): ?>

			<?php

			$fieldIcons = array(
				'text' => 'fa-font',
				'email' => 'fa-envelope',
				'tel' => 'fa-phone',
				'number' => 'fa-hashtag',
				'textarea' => 'fa-align-left',
				'select' => 'fa-list',
				'radio' => 'fa-circle-dot',
				'checkbox' => 'fa-square-check',
				'date' => 'fa-calendar',
				'file' => 'fa-file',
			);

			$fieldIcon = isset($fieldIcons[$field->type])
				? $fieldIcons[$field->type]
				: 'fa-input-text';

			?>

			<div class="faq-field-card">

				<div class="faq-field-card__header">

					<div class="faq-field-card__main">

						<div class="faq-field-card__icon">
							<i class="fas <?php echo $fieldIcon; ?>"></i>
						</div>

						<div class="faq-field-card__info">

							<h3 class="faq-field-card__title">
								<?php echo CHtml::encode($field->label); ?>
							</h3>

							<p class="faq-field-card__name">
								<?php echo CHtml::encode($field->name); ?>
							</p>

						</div>

					</div>


					<div class="faq-field-card__actions">

						<a
							href="<?php echo $this->createUrl('updateField', array(
										'id' => $field->id,
									)); ?>"
							class="faq-field-action">
							<i class="fas fa-pen"></i>
							Editar
						</a>

						<a
							href="<?php echo $this->createUrl('deleteField', array(
										'id' => $field->id,
									)); ?>"
							class="faq-field-action faq-field-action--delete"
							onclick="return confirm('¿Deseas desactivar este campo?');">
							<i class="fas fa-trash"></i>
							Eliminar
						</a>

					</div>

				</div>


				<div class="faq-field-card__body">

					<div class="faq-field-property">

						<span class="faq-field-property__label">
							Tipo
						</span>

						<span class="faq-field-property__value">
							<?php echo CHtml::encode($field->type); ?>
						</span>

					</div>


					<div class="faq-field-property">

						<span class="faq-field-property__label">
							Placeholder
						</span>

						<span class="faq-field-property__value">

							<?php
							if ($field->placeholder !== null && $field->placeholder !== '') {
								echo CHtml::encode($field->placeholder);
							} else {
								echo '—';
							}
							?>

						</span>

					</div>


					<div class="faq-field-property">

						<span class="faq-field-property__label">
							Orden
						</span>

						<span class="faq-field-property__value">
							<?php echo (int) $field->sort_order; ?>
						</span>

					</div>


					<div class="faq-field-property">

						<span class="faq-field-property__label">
							Estado
						</span>

						<?php if ((int) $field->is_active === 1): ?>

							<span class="faq-field-status faq-field-status--active">
								Activo
							</span>

						<?php else: ?>

							<span class="faq-field-status">
								Inactivo
							</span>

						<?php endif; ?>

					</div>


					<div class="faq-field-property">

						<span class="faq-field-property__label">
							Requerido
						</span>

						<?php if ((int) $field->is_required === 1): ?>

							<span class="faq-field-required faq-field-required--yes">
								Sí
							</span>

						<?php else: ?>

							<span class="faq-field-required">
								No
							</span>

						<?php endif; ?>

					</div>


					<div class="faq-field-property">

						<span class="faq-field-property__label">
							Valor por defecto
						</span>

						<span class="faq-field-property__value">

							<?php
							if ($field->default_value !== null && $field->default_value !== '') {
								echo CHtml::encode($field->default_value);
							} else {
								echo '—';
							}
							?>

						</span>

					</div>


					<div class="faq-field-property">

						<span class="faq-field-property__label">
							Opciones
						</span>

						<span class="faq-field-property__value">

							<?php
							if ($field->options !== null && $field->options !== '') {
								echo CHtml::encode($field->options);
							} else {
								echo '—';
							}
							?>

						</span>

					</div>

				</div>

			</div>

		<?php endforeach; ?>

	<?php else: ?>

		<div class="faq-fields-empty">

			<div class="faq-fields-empty__icon">
				<i class="fas fa-layer-group"></i>
			</div>

			<h3 class="faq-fields-empty__title">
				No hay campos todavía
			</h3>

			<p class="faq-fields-empty__description">
				Utiliza "Añadir campo" para agregar el primer campo.
			</p>

		</div>

	<?php endif; ?>

</div>