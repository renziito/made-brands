<?php
/* @var $this FormsController */
/* @var $model FaqForms */

Yii::app()->clientScript->registerCss('faq-forms-index', '
.faq-forms-page {
	width: 100%;
	max-width: 1100px;
	margin: 0 auto;
}

/* ==========================================================
   HEADER
   ========================================================== */

.faq-forms-header {
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 20px;
	margin-bottom: 20px;
}

.faq-forms-heading {
	min-width: 0;
}

.faq-forms-title {
	margin: 0;
	color: #111827;
	font-size: 20px;
	font-weight: 600;
	line-height: 1.3;
}

.faq-forms-description {
	margin: 5px 0 0;
	color: #9ca3af;
	font-size: 12px;
	line-height: 1.4;
}

.faq-forms-add {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	gap: 8px;
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

.faq-forms-add:hover {
	background: #1f2937;
	border-color: #1f2937;
	color: #fff !important;
	text-decoration: none !important;
}

/* ==========================================================
   CARDS
   ========================================================== */

.faq-form-card {
	margin-bottom: 14px;
	overflow: hidden;
	border: 1px solid #e5e7eb;
	border-radius: 10px;
	background: #fff;
	box-shadow: 0 1px 2px rgba(0, 0, 0, .03);
}

.faq-form-card__header {
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 20px;
	padding: 18px 20px;
	border-bottom: 1px solid #e5e7eb;
}

.faq-form-card__main {
	display: flex;
	align-items: center;
	gap: 12px;
	min-width: 0;
}

.faq-form-card__icon {
	display: flex;
	align-items: center;
	justify-content: center;
	width: 38px;
	height: 38px;
	flex-shrink: 0;
	border-radius: 8px;
	background: #f3f4f6;
	color: #374151;
	font-size: 14px;
}

.faq-form-card__info {
	min-width: 0;
}

.faq-form-card__title {
	margin: 0;
	overflow: hidden;
	color: #111827;
	font-size: 15px;
	font-weight: 600;
	line-height: 1.4;
	text-overflow: ellipsis;
	white-space: nowrap;
}

.faq-form-card__description {
	margin: 3px 0 0;
	overflow: hidden;
	max-width: 700px;
	color: #9ca3af;
	font-size: 12px;
	line-height: 1.4;
	text-overflow: ellipsis;
	white-space: nowrap;
}

/* ==========================================================
   STATUS
   ========================================================== */

.faq-form-card__status {
	display: inline-flex;
	align-items: center;
	gap: 6px;
	flex-shrink: 0;
	height: 26px;
	padding: 0 9px;
	border-radius: 999px;
	font-size: 10px;
	font-weight: 600;
}

.faq-form-card__status--active {
	background: #f0fdf4;
	color: #166534;
}

.faq-form-card__status--inactive {
	background: #f3f4f6;
	color: #6b7280;
}

.faq-form-card__status-dot {
	width: 6px;
	height: 6px;
	border-radius: 50%;
	background: currentColor;
}

/* ==========================================================
   BODY
   ========================================================== */

.faq-form-card__body {
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 20px;
	padding: 15px 20px;
}

.faq-form-card__meta {
	display: flex;
	align-items: center;
	gap: 22px;
}

.faq-form-card__meta-item {
	display: flex;
	flex-direction: column;
	gap: 3px;
}

.faq-form-card__meta-label {
	color: #9ca3af;
	font-size: 10px;
	font-weight: 600;
	text-transform: uppercase;
	letter-spacing: .04em;
}

.faq-form-card__meta-value {
	color: #374151;
	font-size: 12px;
}

/* ==========================================================
   ACTIONS
   ========================================================== */

.faq-form-card__actions {
	display: flex;
	align-items: center;
	gap: 7px;
	flex-shrink: 0;
}

.faq-form-action {
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
	transition:
		background-color .15s ease,
		border-color .15s ease,
		color .15s ease;
}

.faq-form-action:hover {
	background: #f3f4f6;
	border-color: #9ca3af;
	color: #111827 !important;
	text-decoration: none !important;
}

.faq-form-action--delete {
	border-color: #fecaca;
	color: #b91c1c !important;
}

.faq-form-action--delete:hover {
	background: #fef2f2;
	border-color: #fca5a5;
	color: #991b1b !important;
}

/* ==========================================================
   EMPTY
   ========================================================== */

.faq-forms-empty {
	padding: 45px 20px;
	border: 1px dashed #d1d5db;
	border-radius: 10px;
	background: #fafafa;
	text-align: center;
	color: #9ca3af;
	font-size: 13px;
}

.faq-forms-empty__icon {
	margin-bottom: 12px;
	color: #d1d5db;
	font-size: 28px;
}

.faq-forms-empty__title {
	margin: 0 0 5px;
	color: #374151;
	font-size: 14px;
	font-weight: 600;
}

.faq-forms-empty__description {
	margin: 0;
	color: #9ca3af;
	font-size: 12px;
}

/* ==========================================================
   RESPONSIVE
   ========================================================== */

@media (max-width: 768px) {

	.faq-forms-header {
		align-items: stretch;
		flex-direction: column;
	}

	.faq-forms-add {
		width: 100%;
	}

	.faq-form-card__header {
		align-items: flex-start;
		flex-direction: column;
	}

	.faq-form-card__body {
		align-items: stretch;
		flex-direction: column;
	}

	.faq-form-card__meta {
		flex-wrap: wrap;
	}

	.faq-form-card__actions {
		width: 100%;
	}

	.faq-form-action {
		flex: 1;
	}

	.faq-form-card__description {
		max-width: 100%;
	}
}
');
?>

<div class="faq-forms-page">

	<!-- ======================================================
	     HEADER
	     ======================================================= -->

	<div class="faq-forms-header">

		<div class="faq-forms-heading">

			<h1 class="faq-forms-title">
				FAQ Forms
			</h1>

			<p class="faq-forms-description">
				Administra los formularios y sus campos.
			</p>

		</div>

		<a
			href="<?php echo $this->createUrl('create'); ?>"
			class="faq-forms-add">
			<i class="fas fa-plus"></i>
			Crear formulario
		</a>

	</div>


	<!-- ======================================================
	     LIST
	     ======================================================= -->

	<?php

	$forms = FaqForms::model()->findAll(array(
		'order' => 'id DESC',
	));

	?>

	<?php if (!empty($forms)): ?>

		<?php foreach ($forms as $formModel): ?>

			<div class="faq-form-card">

				<!-- ==================================================
				     CARD HEADER
				     =================================================== -->

				<div class="faq-form-card__header">

					<div class="faq-form-card__main">

						<div class="faq-form-card__icon">
							<i class="fas fa-list"></i>
						</div>

						<div class="faq-form-card__info">

							<h2 class="faq-form-card__title">
								<?php echo CHtml::encode($formModel->title); ?>
							</h2>

							<?php if ($formModel->description !== null && $formModel->description !== ''): ?>

								<p class="faq-form-card__description">
									<?php echo CHtml::encode($formModel->description); ?>
								</p>

							<?php else: ?>

								<p class="faq-form-card__description">
									Sin descripción
								</p>

							<?php endif; ?>

						</div>

					</div>

					<div class="faq-form-card__actions">

						<a
							href="<?php echo $this->createUrl('update', array(
										'id' => $formModel->id,
									)); ?>"
							class="faq-form-action">
							<i class="fas fa-pen"></i>
							Editar
						</a>
					</div>


					<!-- STATUS -->

					<?php if ((int) $formModel->is_active === 1): ?>

						<div class="faq-form-card__status faq-form-card__status--active">
							<span class="faq-form-card__status-dot"></span>
							Activo
						</div>

					<?php else: ?>

						<div class="faq-form-card__status faq-form-card__status--inactive">
							<span class="faq-form-card__status-dot"></span>
							Inactivo
						</div>

					<?php endif; ?>

				</div>


			</div>

		<?php endforeach; ?>

	<?php else: ?>

		<!-- ======================================================
		     EMPTY STATE
		     ======================================================= -->

		<div class="faq-forms-empty">

			<div class="faq-forms-empty__icon">
				<i class="fas fa-rectangle-list"></i>
			</div>

			<h2 class="faq-forms-empty__title">
				No hay formularios
			</h2>

			<p class="faq-forms-empty__description">
				Todavía no se ha creado ningún formulario.
			</p>

		</div>

	<?php endif; ?>

</div>