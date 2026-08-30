<?php

/* @var $this FaqController */
/* @var $model Faqs */
/* @var $languages Languages[] */

$this->breadcrumbs = array(
	'Faqs' => array('index'),
);

$hasActiveFilters = false;

$filterValues = isset($_GET[CHtml::modelName($model)])
	? $_GET[CHtml::modelName($model)]
	: array();

$filterAttributes = array(
	'id',
	'icon',
	'sort_order',
	'is_active',
	'created_at',
	'updated_at',
);

foreach ($filterAttributes as $filterAttribute) {

	if (
		isset($filterValues[$filterAttribute]) &&
		$filterValues[$filterAttribute] !== ''
	) {
		$hasActiveFilters = true;
		break;
	}
}

$dataProvider = $model->search();

/*
 * Load translations for the FAQs shown in the current page.
 */
$faqItems = $dataProvider->getData();

$faqIds = array();

foreach ($faqItems as $faqItem) {
	$faqIds[] = (int) $faqItem->id;
}

$translationsByFaq = array();

if (!empty($faqIds)) {

	$criteria = new CDbCriteria;

	$criteria->addInCondition(
		'faq_id',
		$faqIds
	);

	$translations = FaqTranslations::model()->findAll(
		$criteria
	);

	foreach ($translations as $translation) {

		$faqId = (int) $translation->faq_id;

		if (!isset($translationsByFaq[$faqId])) {
			$translationsByFaq[$faqId] = array();
		}

		$translationsByFaq[$faqId][(int) $translation->language_id] = $translation;
	}
}


Yii::app()->clientScript->registerCss(
	'admin-crud-faqs',
	"
.admin-crud-page {
	width: 100%;
}

/* ==========================================================
   HEADER
   ========================================================== */

.admin-crud-header {
	display: flex;
	align-items: flex-end;
	justify-content: space-between;
	gap: 24px;
	margin-bottom: 24px;
}

.admin-crud-heading {
	min-width: 0;
}

.admin-crud-eyebrow {
	margin-bottom: 6px;
	color: #6b7280;
	font-size: 11px;
	font-weight: 700;
	letter-spacing: .08em;
	line-height: 1.4;
	text-transform: uppercase;
}

.admin-crud-title {
	margin: 0;
	color: #111827;
	font-size: 30px;
	font-weight: 600;
	line-height: 1.2;
}

.admin-crud-description {
	margin: 7px 0 0;
	color: #6b7280;
	font-size: 14px;
	line-height: 1.5;
}

.admin-crud-actions {
	display: flex;
	align-items: center;
	gap: 8px;
	flex-shrink: 0;
}


/* ==========================================================
   BUTTONS
   ========================================================== */

.admin-crud-button {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	gap: 8px;
	height: 38px;
	padding: 0 14px;
	border: 1px solid transparent;
	border-radius: 7px;
	box-sizing: border-box;
	cursor: pointer;
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

.admin-crud-button:hover {
	text-decoration: none !important;
}

.admin-crud-button--primary {
	background: #111827;
	border-color: #111827;
	color: #fff !important;
}

.admin-crud-button--primary:hover {
	background: #1f2937;
	border-color: #1f2937;
	color: #fff !important;
}

.admin-crud-button--secondary {
	background: #fff;
	border-color: #d1d5db;
	color: #374151 !important;
}

.admin-crud-button--secondary:hover,
.admin-crud-button--secondary.is-active {
	background: #f3f4f6;
	border-color: #9ca3af;
	color: #111827 !important;
}


/* ==========================================================
   CARD
   ========================================================== */

.admin-crud-card {
	overflow: hidden;
	background: #fff;
	border: 1px solid #e5e7eb;
	border-radius: 10px;
	box-shadow: 0 1px 2px rgba(0, 0, 0, .03);
}

.admin-crud-card__header {
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 20px;
	padding: 16px 20px;
	border-bottom: 1px solid #e5e7eb;
}

.admin-crud-card__title {
	display: flex;
	align-items: center;
	gap: 11px;
	min-width: 0;
}

.admin-crud-card__icon {
	display: flex;
	align-items: center;
	justify-content: center;
	width: 34px;
	height: 34px;
	flex-shrink: 0;
	border-radius: 7px;
	background: #f3f4f6;
	color: #374151;
	font-size: 14px;
}

.admin-crud-card__heading {
	margin: 0;
	color: #111827;
	font-size: 15px;
	font-weight: 600;
	line-height: 1.3;
}

.admin-crud-card__description {
	margin: 2px 0 0;
	color: #9ca3af;
	font-size: 12px;
	line-height: 1.4;
}


/* ==========================================================
   FILTER PANEL
   ========================================================== */

.admin-crud-filter {
	display: none;
	padding: 18px 20px;
	background: #f9fafb;
	border-bottom: 1px solid #e5e7eb;
}

.admin-crud-filter.is-visible {
	display: block;
}

.admin-crud-filter__header {
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 16px;
	margin-bottom: 14px;
}

.admin-crud-filter__title {
	display: flex;
	align-items: center;
	gap: 8px;
	color: #374151;
	font-size: 13px;
	font-weight: 600;
}

.admin-crud-filter__title i {
	color: #6b7280;
}

.admin-crud-filter__hint {
	margin: 4px 0 0;
	color: #9ca3af;
	font-size: 12px;
}

.admin-crud-filter__fields {
	display: grid;
	grid-template-columns: repeat(3, minmax(0, 1fr));
	gap: 14px;
}

.admin-crud-filter__field {
	min-width: 0;
}

.admin-crud-filter__label {
	display: block;
	margin-bottom: 6px;
	color: #4b5563;
	font-size: 11px;
	font-weight: 600;
}

.admin-crud-filter__input {
	display: block;
	width: 100%;
	height: 36px;
	padding: 0 10px;
	box-sizing: border-box;
	border: 1px solid #d1d5db;
	border-radius: 6px;
	outline: none;
	background: #fff;
	color: #374151;
	font-size: 13px;
	transition:
		border-color .15s ease,
		box-shadow .15s ease;
}

.admin-crud-filter__input:focus {
	border-color: #9ca3af;
	box-shadow: 0 0 0 3px rgba(17, 24, 39, .06);
}

.admin-crud-filter__footer {
	display: flex;
	align-items: center;
	justify-content: flex-end;
	gap: 8px;
	margin-top: 16px;
}


/* ==========================================================
   TABLE
   ========================================================== */

.admin-crud-table-wrapper {
	width: 100%;
	overflow-x: auto;
	overflow-y: visible;
	-webkit-overflow-scrolling: touch;
}

.admin-crud-table {
	width: 100%;
	margin: 0 !important;
	border: 0 !important;
	border-collapse: separate;
	border-spacing: 0;
	background: #fff;
}

.admin-crud-table thead th {
	height: 42px;
	padding: 0 14px;
	background: #f9fafb;
	border-bottom: 1px solid #e5e7eb;
	color: #6b7280;
	font-size: 11px;
	font-weight: 700;
	letter-spacing: .04em;
	line-height: 1;
	text-align: left;
	text-transform: uppercase;
	white-space: nowrap;
}

.admin-crud-table tbody td {
	padding: 14px;
	background: #fff;
	border-bottom: 1px solid #f0f1f3;
	color: #374151;
	font-size: 13px;
	vertical-align: middle;
}

.admin-crud-table tbody tr:hover td {
	background: #fafafa;
}

.admin-crud-table tbody tr:last-child td {
	border-bottom: 0;
}


/* ==========================================================
   FAQ CONTENT
   ========================================================== */

.faq-question-cell {
	min-width: 240px;
	max-width: 420px;
}

.faq-question {
	display: block;
	color: #111827;
	font-weight: 600;
	line-height: 1.4;
}

.faq-answer-preview {
	display: block;
	margin-top: 4px;
	max-width: 420px;
	overflow: hidden;
	color: #9ca3af;
	font-size: 12px;
	line-height: 1.4;
	text-overflow: ellipsis;
	white-space: nowrap;
}

.faq-language-list {
	display: flex;
	flex-wrap: wrap;
	gap: 5px;
}

.faq-language {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	min-width: 28px;
	height: 24px;
	padding: 0 7px;
	border-radius: 5px;
	background: #f3f4f6;
	color: #4b5563;
	font-size: 10px;
	font-weight: 700;
	text-transform: uppercase;
}

.faq-language--missing {
	background: #fef2f2;
	color: #b91c1c;
}

.faq-icon {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 32px;
	height: 32px;
	border-radius: 6px;
	background: #f3f4f6;
	color: #374151;
}

.faq-sort {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	min-width: 30px;
	height: 26px;
	padding: 0 7px;
	border-radius: 5px;
	background: #f9fafb;
	color: #6b7280;
	font-size: 11px;
	font-weight: 600;
}

.faq-status {
	display: inline-flex;
	align-items: center;
	gap: 6px;
	padding: 5px 9px;
	border-radius: 999px;
	font-size: 10px;
	font-weight: 700;
}

.faq-status--active {
	background: #ecfdf3;
	color: #15803d;
}

.faq-status--inactive {
	background: #f3f4f6;
	color: #6b7280;
}


/* ==========================================================
   ACTIONS
   ========================================================== */

.admin-crud-actions-column {
	width: 90px;
	min-width: 90px;
	text-align: right !important;
	white-space: nowrap;
}

.admin-crud-action {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 30px;
	height: 30px;
	margin-left: 3px;
	border-radius: 5px;
	color: #6b7280 !important;
	font-size: 12px;
	text-decoration: none !important;
	transition:
		background-color .15s ease,
		color .15s ease;
}

.admin-crud-action:hover {
	background: #f3f4f6;
	color: #111827 !important;
	text-decoration: none !important;
}

.admin-crud-action--delete:hover {
	background: #fef2f2;
	color: #dc2626 !important;
}


/* ==========================================================
   MODAL
   ========================================================== */

.admin-crud-modal {
	position: fixed;
	z-index: 99999;
	top: 0;
	left: 0;
	display: none;
	align-items: center;
	justify-content: center;
	width: 100%;
	height: 100%;
	padding: 20px;
	box-sizing: border-box;
	background: rgba(17, 24, 39, .48);
}

.admin-crud-modal.is-visible {
	display: flex;
}

.admin-crud-modal__dialog {
	width: 100%;
	max-width: 420px;
	overflow: hidden;
	background: #fff;
	border: 1px solid #e5e7eb;
	border-radius: 10px;
	box-shadow: 0 20px 50px rgba(0, 0, 0, .18);
	transform: translateY(8px);
	opacity: 0;
	transition:
		transform .15s ease,
		opacity .15s ease;
}

.admin-crud-modal.is-visible .admin-crud-modal__dialog {
	transform: translateY(0);
	opacity: 1;
}

.admin-crud-modal__header {
	display: flex;
	align-items: flex-start;
	gap: 12px;
	padding: 20px;
}

.admin-crud-modal__icon {
	display: flex;
	align-items: center;
	justify-content: center;
	width: 38px;
	height: 38px;
	flex-shrink: 0;
	border-radius: 8px;
	background: #fef2f2;
	color: #dc2626;
	font-size: 15px;
}

.admin-crud-modal__title {
	margin: 0;
	color: #111827;
	font-size: 16px;
	font-weight: 600;
	line-height: 1.4;
}

.admin-crud-modal__message {
	margin: 5px 0 0;
	color: #6b7280;
	font-size: 13px;
	line-height: 1.5;
}

.admin-crud-modal__footer {
	display: flex;
	align-items: center;
	justify-content: flex-end;
	gap: 8px;
	padding: 14px 20px;
	border-top: 1px solid #e5e7eb;
	background: #f9fafb;
}

.admin-crud-modal__button {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	gap: 7px;
	height: 36px;
	padding: 0 13px;
	box-sizing: border-box;
	border: 1px solid transparent;
	border-radius: 6px;
	cursor: pointer;
	font-family: inherit;
	font-size: 12px;
	font-weight: 600;
	text-decoration: none;
}

.admin-crud-modal__button--cancel {
	background: #fff;
	border-color: #d1d5db;
	color: #374151;
}

.admin-crud-modal__button--cancel:hover {
	background: #f3f4f6;
}

.admin-crud-modal__button--delete {
	background: #dc2626;
	border-color: #dc2626;
	color: #fff;
}

.admin-crud-modal__button--delete:hover {
	background: #b91c1c;
	border-color: #b91c1c;
}


/* ==========================================================
   EMPTY
   ========================================================== */

.admin-crud-empty {
	padding: 64px 20px !important;
	text-align: center !important;
}

.admin-crud-empty__icon {
	display: flex;
	align-items: center;
	justify-content: center;
	width: 46px;
	height: 46px;
	margin: 0 auto 12px;
	border-radius: 50%;
	background: #f3f4f6;
	color: #9ca3af;
	font-size: 18px;
}

.admin-crud-empty__title {
	margin-bottom: 4px;
	color: #374151;
	font-size: 14px;
	font-weight: 600;
}

.admin-crud-empty__text {
	color: #9ca3af;
	font-size: 12px;
}


/* ==========================================================
   PAGINATION
   ========================================================== */

.admin-crud-footer {
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 16px;
	padding: 12px 20px;
	border-top: 1px solid #e5e7eb;
	background: #fff;
}

.admin-crud-summary {
	color: #6b7280;
	font-size: 12px;
}

.admin-crud-pagination {
	margin: 0;
}

.admin-crud-pagination ul.yiiPager {
	display: flex;
	align-items: center;
	gap: 4px;
	margin: 0;
	padding: 0;
	list-style: none;
}

.admin-crud-pagination ul.yiiPager li {
	display: inline-flex;
	margin: 0;
	padding: 0;
}

.admin-crud-pagination ul.yiiPager li a,
.admin-crud-pagination ul.yiiPager li span {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	min-width: 30px;
	height: 30px;
	padding: 0 8px;
	box-sizing: border-box;
	border: 1px solid #e5e7eb;
	border-radius: 5px;
	background: #fff;
	color: #4b5563;
	font-size: 12px;
	text-decoration: none;
}

.admin-crud-pagination ul.yiiPager li a:hover {
	background: #f9fafb;
	color: #111827;
	text-decoration: none;
}

.admin-crud-pagination ul.yiiPager li.selected a,
.admin-crud-pagination ul.yiiPager li.selected span {
	background: #111827;
	border-color: #111827;
	color: #fff;
}

.admin-crud-pagination ul.yiiPager li.hidden a,
.admin-crud-pagination ul.yiiPager li.hidden span {
	opacity: .45;
	cursor: default;
}


/* ==========================================================
   RESPONSIVE
   ========================================================== */

@media (max-width: 900px) {

	.admin-crud-filter__fields {
		grid-template-columns: repeat(2, minmax(0, 1fr));
	}
}

@media (max-width: 768px) {

	.admin-crud-header {
		align-items: stretch;
		flex-direction: column;
	}

	.admin-crud-actions {
		width: 100%;
	}

	.admin-crud-button {
		flex: 1;
	}

	.admin-crud-filter__fields {
		grid-template-columns: 1fr;
	}

	.admin-crud-footer {
		align-items: flex-start;
		flex-direction: column;
	}

	.admin-crud-modal {
		padding: 16px;
	}
}
"
);


Yii::app()->clientScript->registerScript(
	'crud-index-faqs',
	"
	$('#faqs-filter-toggle').on('click', function(e) {

		e.preventDefault();

		var button = $(this);
		var panel = $('#faqs-filter-panel');
		var icon = button.find('.filter-toggle-icon');

		panel.toggleClass('is-visible');
		button.toggleClass('is-active');

		icon
			.toggleClass('fa-chevron-down')
			.toggleClass('fa-chevron-up');

		return false;
	});


	var faqDeleteUrl = null;


	function openFaqDeleteModal(url)
	{
		faqDeleteUrl = url;

		$('#faqs-delete-modal')
			.addClass('is-visible')
			.attr('aria-hidden', 'false');

		$('body').css('overflow', 'hidden');
	}


	function closeFaqDeleteModal()
	{
		faqDeleteUrl = null;

		$('#faqs-delete-modal')
			.removeClass('is-visible')
			.attr('aria-hidden', 'true');

		$('body').css('overflow', '');
	}


	$(document).on(
		'click',
		'.faq-delete-action',
		function(e) {

			e.preventDefault();
			e.stopPropagation();

			var url = $(this).attr('href');

			if (!url) {
				return false;
			}

			openFaqDeleteModal(url);

			return false;
		}
	);


	$('#faqs-delete-cancel').on(
		'click',
		function(e) {

			e.preventDefault();

			closeFaqDeleteModal();

			return false;
		}
	);


	$('#faqs-delete-confirm').on(
		'click',
		function(e) {

			e.preventDefault();

			var url = faqDeleteUrl;

			if (!url) {

				closeFaqDeleteModal();

				return false;
			}

			window.location.href = url;

			return false;
		}
	);


	$('#faqs-delete-modal').on(
		'click',
		function(e) {

			if (e.target === this) {

				closeFaqDeleteModal();
			}
		}
	);


	$(document).on(
		'keydown',
		function(e) {

			if (e.key === 'Escape') {

				closeFaqDeleteModal();
			}
		}
	);
	"
);

?>

<div class="admin-crud-page">


	<!-- ======================================================
	     HEADER
	     ====================================================== -->

	<div class="admin-crud-header">

		<div class="admin-crud-heading">

			<div class="admin-crud-eyebrow">
				Contenido
			</div>

			<h1 class="admin-crud-title">
				FAQs
			</h1>

			<p class="admin-crud-description">
				Gestiona y administra las preguntas frecuentes.
			</p>

		</div>


		<div class="admin-crud-actions">

			<a
				class="admin-crud-button admin-crud-button--primary"
				href="<?php echo $this->createUrl('create'); ?>"
				title="Nueva FAQ"
				aria-label="Nueva FAQ">

				<i
					class="fas fa-plus"
					aria-hidden="true"></i>

				<span>
					Nueva FAQ
				</span>

			</a>

		</div>

	</div>



	<!-- ======================================================
	     LIST
	     ====================================================== -->

	<div class="admin-crud-card">


		<div class="admin-crud-card__header">

			<div class="admin-crud-card__title">

				<div class="admin-crud-card__icon">

					<i class="fas fa-list"></i>

				</div>

				<div>

					<h2 class="admin-crud-card__heading">
						Listado
					</h2>

					<p class="admin-crud-card__description">
						Preguntas frecuentes disponibles en el sistema.
					</p>

				</div>

			</div>

		</div>


		<div class="admin-crud-table-wrapper">


			<?php if (!empty($faqItems)): ?>


				<table class="admin-crud-table">


					<thead>

						<tr>

							<th>
								ID
							</th>

							<th>
								Icono
							</th>

							<th>
								Pregunta
							</th>

							<th>
								Idiomas
							</th>

							<th>
								Orden
							</th>

							<th>
								Estado
							</th>


							<th class="admin-crud-actions-column">
								Acciones
							</th>

						</tr>

					</thead>


					<tbody>


						<?php foreach ($faqItems as $faq): ?>


							<?php

							$faqId =
								(int) $faq->id;

							$faqTranslations =
								isset(
									$translationsByFaq[$faqId]
								)
								? $translationsByFaq[$faqId]
								: array();

							$firstTranslation = null;

							foreach (
								$languages as $language
							) {

								$languageId =
									(int) $language->id;

								if (
									isset(
										$faqTranslations[$languageId]
									)
								) {

									$firstTranslation =
										$faqTranslations[$languageId];

									break;
								}
							}

							if ($firstTranslation === null) {

								foreach (
									$faqTranslations
									as $faqTranslation
								) {

									$firstTranslation =
										$faqTranslation;

									break;
								}
							}

							?>


							<tr>


								<td>

									<strong>
										#<?php echo $faqId; ?>
									</strong>

								</td>


								<td>

									<?php if (!empty($faq->icon)): ?>

										<span
											class="faq-icon"
											title="<?php echo CHtml::encode($faq->icon); ?>">

											<i
												class="<?php echo CHtml::encode($faq->icon); ?>"
												aria-hidden="true"></i>

										</span>

									<?php else: ?>

										<span class="faq-icon">

											<i
												class="fas fa-question"
												aria-hidden="true"></i>

										</span>

									<?php endif; ?>

								</td>


								<td class="faq-question-cell">


									<?php if ($firstTranslation !== null): ?>


										<span class="faq-question">

											<?php

											echo CHtml::encode(
												$firstTranslation->question
											);

											?>

										</span>


										<?php if (
											!empty($firstTranslation->answer)
										): ?>

											<span class="faq-answer-preview">

												<?php

												echo CHtml::encode(
													strip_tags(
														$firstTranslation->answer
													)
												);

												?>

											</span>

										<?php endif; ?>


									<?php else: ?>


										<span
											style="
												color:#9ca3af;
												font-style:italic;
											">
											Sin traducción
										</span>


									<?php endif; ?>


								</td>


								<td>

									<div class="faq-language-list">


										<?php foreach (
											$languages
											as $language
										): ?>


											<?php

											$languageId =
												(int) $language->id;

											$hasTranslation =
												isset(
													$faqTranslations[$languageId]
												);

											$languageCode =
												isset(
													$language->code
												)
												? $language->code
												: substr(
													(string)
													$language->name,
													0,
													2
												);

											?>


											<span
												class="faq-language<?php echo !$hasTranslation ? ' faq-language--missing' : ''; ?>"
												title="<?php echo CHtml::encode(
															$language->name
														); ?>">

												<?php

												echo CHtml::encode(
													strtoupper(
														$languageCode
													)
												);

												?>

											</span>


										<?php endforeach; ?>


									</div>

								</td>


								<td>

									<span class="faq-sort">

										<?php

										echo (int)
										$faq->sort_order;

										?>

									</span>

								</td>


								<td>


									<?php if ($faq->is_active): ?>


										<span
											class="faq-status faq-status--active">

											<i
												class="fas fa-check-circle"
												aria-hidden="true"></i>

											Activo

										</span>


									<?php else: ?>


										<span
											class="faq-status faq-status--inactive">

											<i
												class="fas fa-minus-circle"
												aria-hidden="true"></i>

											Inactivo

										</span>


									<?php endif; ?>


								</td>


								<td class="admin-crud-actions-column">


									<a
										class="admin-crud-action"
										href="<?php echo $this->createUrl(
													'update',
													array(
														'id' => $faqId,
													)
												); ?>"
										title="Editar"
										aria-label="Editar">

										<i
											class="fas fa-pen"
											aria-hidden="true"></i>

									</a>


									<a
										class="admin-crud-action admin-crud-action--delete faq-delete-action"
										href="<?php echo $this->createUrl(
													'delete',
													array(
														'id' => $faqId,
													)
												); ?>"
										title="Desactivar"
										aria-label="Desactivar">

										<i
											class="fas fa-trash-alt"
											aria-hidden="true"></i>

									</a>


								</td>


							</tr>


						<?php endforeach; ?>


					</tbody>


				</table>


			<?php else: ?>


				<div class="admin-crud-empty">

					<div class="admin-crud-empty__icon">

						<i class="fas fa-inbox"></i>

					</div>

					<div class="admin-crud-empty__title">
						No hay registros
					</div>

					<div class="admin-crud-empty__text">
						No se encontraron FAQs para mostrar.
					</div>

				</div>


			<?php endif; ?>


		</div>


		<!-- ==================================================
		     FOOTER
		     ================================================== -->

		<div class="admin-crud-footer">


			<div class="admin-crud-summary">

				<?php

				$pagination =
					$dataProvider->getPagination();

				$itemCount =
					$dataProvider->getItemCount();

				$totalCount =
					$dataProvider->getTotalItemCount();

				$start =
					$itemCount > 0
					? $pagination->getOffset() + 1
					: 0;

				$end =
					$pagination->getOffset() +
					$itemCount;

				echo 'Mostrando ' .
					$start .
					'–' .
					$end .
					' de ' .
					$totalCount .
					' registros';

				?>

			</div>


			<div class="admin-crud-pagination">

				<?php

				$this->widget(
					'CLinkPager',
					array(
						'pages' =>
						$dataProvider->getPagination(),
						'header' => '',
						'firstPageLabel' => '«',
						'prevPageLabel' => '‹',
						'nextPageLabel' => '›',
						'lastPageLabel' => '»',
						'maxButtonCount' => 5,
					)
				);

				?>

			</div>


		</div>


	</div>

</div>


<!-- ======================================================
     DELETE MODAL
     ====================================================== -->

<div
	id="faqs-delete-modal"
	class="admin-crud-modal"
	aria-hidden="true"
	role="dialog"
	aria-modal="true"
	aria-labelledby="faqs-delete-modal-title">

	<div class="admin-crud-modal__dialog">


		<div class="admin-crud-modal__header">


			<div class="admin-crud-modal__icon">

				<i
					class="fas fa-exclamation-triangle"
					aria-hidden="true"></i>

			</div>


			<div>

				<h3
					id="faqs-delete-modal-title"
					class="admin-crud-modal__title">
					Desactivar FAQ
				</h3>

				<p class="admin-crud-modal__message">

					¿Está seguro de que desea desactivar
					esta FAQ?

					La información no será eliminada
					de la base de datos.

				</p>

			</div>


		</div>


		<div class="admin-crud-modal__footer">


			<button
				type="button"
				id="faqs-delete-cancel"
				class="admin-crud-modal__button admin-crud-modal__button--cancel">

				<i
					class="fas fa-times"
					aria-hidden="true"></i>

				Cancelar

			</button>


			<button
				type="button"
				id="faqs-delete-confirm"
				class="admin-crud-modal__button admin-crud-modal__button--delete">

				<i
					class="fas fa-trash-alt"
					aria-hidden="true"></i>

				Desactivar

			</button>


		</div>


	</div>

</div>