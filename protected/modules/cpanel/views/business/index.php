<?php
/* @var $this BusinessController */
/* @var $model Businesses */

$this->breadcrumbs = array(
	'Businesses' => array('index'),
	'Administrar',
);

$hasActiveFilters = false;

$filterValues = isset($_GET[CHtml::modelName($model)])
	? $_GET[CHtml::modelName($model)]
	: array();

$filterAttributes = array(
	'id',
	'image',
	'icon',
	'sort_order',
	'is_active',
	'created_at',
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


/*
 * Load businesses manually.
 *
 * We intentionally do not use CGridView here.
 */
$dataProvider = $model->search();

$businesses = $dataProvider->getData();


/*
 * Load the default language.
 */
$defaultLanguage = Languages::model()->findByAttributes(
	array(
		'is_default' => 1,
	)
);


/*
 * Load translations for the businesses currently displayed.
 */
$translationsByBusiness = array();

if (!empty($businesses)) {

	$businessIds = array();

	foreach ($businesses as $business) {
		$businessIds[] = (int) $business->id;
	}

	$criteria = new CDbCriteria;

	$criteria->addInCondition(
		'business_id',
		$businessIds
	);

	$translations =
		BusinessTranslations::model()->findAll(
			$criteria
		);

	foreach ($translations as $translation) {

		$businessId =
			(int) $translation->business_id;

		if (!isset($translationsByBusiness[$businessId])) {
			$translationsByBusiness[$businessId] = array();
		}

		$translationsByBusiness[$businessId][(int) $translation->language_id] = $translation;
	}
}


/*
 * Load all languages so we can display the available
 * translation codes.
 */
$languages = Languages::model()->findAll(
	array(
		'order' => 'sort_order ASC, id ASC',
	)
);

$languageById = array();

foreach ($languages as $language) {

	$languageById[(int) $language->id] = $language;
}


Yii::app()->clientScript->registerCss(
	'admin-crud-businesses',
	'
/* ==========================================================
   PAGE
   ========================================================== */

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
	box-sizing: border-box;
	border: 1px solid transparent;
	border-radius: 7px;
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
	min-width: 900px;
	margin: 0;
	border-collapse: separate;
	border-spacing: 0;
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
	height: 62px;
	padding: 8px 14px;
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
   BUSINESS IMAGE
   ========================================================== */

.admin-crud-business-image {
	display: flex;
	align-items: center;
	justify-content: center;
	width: 52px;
	height: 38px;
	overflow: hidden;
	border: 1px solid #e5e7eb;
	border-radius: 6px;
	background: #f9fafb;
}

.admin-crud-business-image img {
	display: block;
	max-width: 100%;
	max-height: 100%;
	object-fit: contain;
}

.admin-crud-business-image__empty {
	color: #d1d5db;
	font-size: 13px;
}


/* ==========================================================
   BUSINESS NAME
   ========================================================== */

.admin-crud-business-name {
	color: #111827;
	font-size: 13px;
	font-weight: 600;
	line-height: 1.4;
}

.admin-crud-business-id {
	margin-top: 2px;
	color: #9ca3af;
	font-size: 10px;
}


/* ==========================================================
   ICON
   ========================================================== */

.admin-crud-business-icon {
	display: inline-flex;
	align-items: center;
	gap: 7px;
	color: #4b5563;
	font-size: 12px;
}

.admin-crud-business-icon i {
	color: #6b7280;
}


/* ==========================================================
   LANGUAGES
   ========================================================== */

.admin-crud-languages {
	display: flex;
	align-items: center;
	flex-wrap: wrap;
	gap: 5px;
	max-width: 240px;
}

.admin-crud-language {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	min-width: 30px;
	height: 24px;
	padding: 0 7px;
	box-sizing: border-box;
	border: 1px solid #e5e7eb;
	border-radius: 5px;
	background: #f9fafb;
	color: #9ca3af;
	font-size: 9px;
	font-weight: 700;
	text-decoration: none !important;
}

.admin-crud-language--translated {
	background: #f3f4f6;
	border-color: #d1d5db;
	color: #374151;
}

.admin-crud-language--default {
	border-color: #9ca3af;
	color: #111827;
}

.admin-crud-language:hover {
	background: #e5e7eb;
	color: #111827;
	text-decoration: none !important;
}


/* ==========================================================
   STATUS
   ========================================================== */

.admin-crud-status {
	display: inline-flex;
	align-items: center;
	gap: 6px;
	height: 25px;
	padding: 0 8px;
	box-sizing: border-box;
	border-radius: 5px;
	background: #f3f4f6;
	color: #6b7280;
	font-size: 10px;
	font-weight: 600;
}

.admin-crud-status__dot {
	width: 6px;
	height: 6px;
	border-radius: 50%;
	background: #9ca3af;
}

.admin-crud-status--active {
	background: #f0fdf4;
	color: #166534;
}

.admin-crud-status--active .admin-crud-status__dot {
	background: #16a34a;
}


/* ==========================================================
   ORDER
   ========================================================== */

.admin-crud-order {
	color: #6b7280;
	font-size: 12px;
	font-weight: 600;
}


/* ==========================================================
   ACTIONS
   ========================================================== */

.admin-crud-actions-column {
	width: 100px;
	min-width: 100px;
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
}

.admin-crud-action--delete:hover {
	background: #fef2f2;
	color: #dc2626 !important;
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
}

.admin-crud-pagination ul.yiiPager li.selected a,
.admin-crud-pagination ul.yiiPager li.selected span {
	background: #111827;
	border-color: #111827;
	color: #fff;
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
}
'
);


Yii::app()->clientScript->registerScript(
	'crud-index-businesses',
	"
$('#businesses-filter-toggle').on('click', function(e) {

	e.preventDefault();

	var button = $(this);
	var panel = $('#businesses-filter-panel');
	var icon = button.find('.filter-toggle-icon');

	panel.toggleClass('is-visible');
	button.toggleClass('is-active');

	icon
		.toggleClass('fa-chevron-down')
		.toggleClass('fa-chevron-up');

	return false;
});


var businessesDeleteUrl = null;


function openBusinessesDeleteModal(url)
{
	businessesDeleteUrl = url;

	$('#businesses-delete-modal')
		.addClass('is-visible')
		.attr('aria-hidden', 'false');

	$('body').css('overflow', 'hidden');
}


function closeBusinessesDeleteModal()
{
	businessesDeleteUrl = null;

	$('#businesses-delete-modal')
		.removeClass('is-visible')
		.attr('aria-hidden', 'true');

	$('body').css('overflow', '');
}


$(document).on(
	'click',
	'#businesses-table .admin-crud-action--delete',
	function(e) {

		e.preventDefault();
		e.stopImmediatePropagation();

		var url = $(this).attr('href');

		if (!url) {
			return false;
		}

		openBusinessesDeleteModal(url);

		return false;
	}
);


$('#businesses-delete-cancel').on(
	'click',
	function(e) {

		e.preventDefault();

		closeBusinessesDeleteModal();

		return false;
	}
);


$('#businesses-delete-confirm').on(
	'click',
	function(e) {

		e.preventDefault();

		var url = businessesDeleteUrl;

		if (!url) {

			closeBusinessesDeleteModal();

			return false;
		}

		window.location.href = url;

		return false;
	}
);


$('#businesses-delete-modal').on(
	'click',
	function(e) {

		if (e.target === this) {
			closeBusinessesDeleteModal();
		}
	}
);


$(document).on(
	'keydown',
	function(e) {

		if (e.key === 'Escape') {
			closeBusinessesDeleteModal();
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
				Businesses
			</h1>

			<p class="admin-crud-description">
				Gestiona y administra businesses.
			</p>

		</div>


		<div class="admin-crud-actions">

			<a
				class="admin-crud-button admin-crud-button--primary"
				href="<?php
						echo $this->createUrl('create');
						?>"
				title="Nuevo business"
				aria-label="Nuevo business">

				<i
					class="fas fa-plus"
					aria-hidden="true"></i>

				<span>
					Nuevo business
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

					<i class="fas fa-building"></i>

				</div>

				<div>

					<h2 class="admin-crud-card__heading">
						Listado
					</h2>

					<p class="admin-crud-card__description">
						Businesses registrados en el sistema.
					</p>

				</div>

			</div>

		</div>


		<div class="admin-crud-table-wrapper">

			<table
				id="businesses-table"
				class="admin-crud-table">

				<thead>

					<tr>

						<th>
							Imagen
						</th>

						<th>
							Nombre
						</th>

						<th>
							Icono
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

					<?php if (!empty($businesses)): ?>

						<?php foreach ($businesses as $business): ?>

							<?php

							$businessId =
								(int) $business->id;

							$businessTranslations =
								isset(
									$translationsByBusiness[$businessId]
								)
								? $translationsByBusiness[$businessId]
								: array();


							$defaultTranslation = null;

							if ($defaultLanguage) {

								$defaultLanguageId =
									(int) $defaultLanguage->id;

								if (
									isset(
										$businessTranslations[$defaultLanguageId]
									)
								) {

									$defaultTranslation =
										$businessTranslations[$defaultLanguageId];
								}
							}

							?>

							<tr>


								<!-- IMAGE -->

								<td>

									<?php if (!empty($business->image)): ?>

										<div class="admin-crud-business-image">

											<img
												src="<?= Yii::app()->getBaseUrl() . CHtml::encode(
															$business->image
														);
														?>"
												alt="">

										</div>

									<?php else: ?>

										<div class="admin-crud-business-image">

											<span class="admin-crud-business-image__empty">

												<i class="fas fa-image"></i>

											</span>

										</div>

									<?php endif; ?>

								</td>


								<!-- NAME -->

								<td>

									<?php if ($defaultTranslation): ?>

										<div class="admin-crud-business-name">

											<?= CHtml::encode(
												$defaultTranslation->name
											); ?>

										</div>

									<?php else: ?>

										<div
											class="admin-crud-business-name"
											style="color:#9ca3af;">
											Sin traducción
										</div>

									<?php endif; ?>


									<div class="admin-crud-business-id">

										#<?= $businessId; ?>

										<?php if ($defaultLanguage): ?>

											&nbsp;·&nbsp;

											<?= CHtml::encode(
												strtoupper(
													$defaultLanguage->code
												)
											); ?>

										<?php endif; ?>

									</div>

								</td>


								<!-- ICON -->

								<td>

									<?php if (!empty($business->icon)): ?>

										<div class="admin-crud-business-icon">

											<i
												class="<?= CHtml::encode(
															$business->icon
														); ?>"></i>

											<span>
												<?= CHtml::encode(
													$business->icon
												); ?>
											</span>

										</div>

									<?php else: ?>

										<span style="color:#9ca3af;">
											—
										</span>

									<?php endif; ?>

								</td>

								<!-- ORDER -->

								<td>

									<span class="admin-crud-order">

										<?= (int) $business->sort_order; ?>

									</span>

								</td>


								<!-- STATUS -->

								<td>

									<?php if ((int) $business->is_active): ?>

										<span
											class="
											admin-crud-status
											admin-crud-status--active
											">

											<span
												class="admin-crud-status__dot"></span>

											Activo

										</span>

									<?php else: ?>

										<span class="admin-crud-status">

											<span
												class="admin-crud-status__dot"></span>

											Inactivo

										</span>

									<?php endif; ?>

								</td>


								<!-- ACTIONS -->

								<td class="admin-crud-actions-column">

									<a
										class="admin-crud-action"
										href="<?php
												echo $this->createUrl(
													'update',
													array(
														'id' => $businessId,
													)
												);
												?>"
										title="Editar"
										aria-label="Editar">

										<i
											class="fas fa-pen"
											aria-hidden="true"></i>

									</a>


									<a
										class="
										admin-crud-action
										admin-crud-action--delete
										"
										href="<?php
												echo $this->createUrl(
													'delete',
													array(
														'id' => $businessId,
													)
												);
												?>"
										title="Eliminar"
										aria-label="Eliminar">

										<i
											class="fas fa-trash-alt"
											aria-hidden="true"></i>

									</a>

								</td>

							</tr>

						<?php endforeach; ?>

					<?php else: ?>

						<tr>

							<td
								colspan="7"
								class="admin-crud-empty">

								<div class="admin-crud-empty__icon">

									<i class="fas fa-inbox"></i>

								</div>

								<div class="admin-crud-empty__title">
									No hay registros
								</div>

								<div class="admin-crud-empty__text">
									No se encontraron registros para mostrar.
								</div>

							</td>

						</tr>

					<?php endif; ?>

				</tbody>

			</table>

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


<!-- ========================================================
     DELETE MODAL
     ======================================================== -->

<div
	id="businesses-delete-modal"
	class="admin-crud-modal"
	aria-hidden="true"
	role="dialog"
	aria-modal="true"
	aria-labelledby="businesses-delete-modal-title"
	style="
		position:fixed;
		z-index:99999;
		top:0;
		left:0;
		display:none;
		align-items:center;
		justify-content:center;
		width:100%;
		height:100%;
		padding:20px;
		box-sizing:border-box;
		background:rgba(17,24,39,.48);
	">

	<div
		style="
			width:100%;
			max-width:420px;
			overflow:hidden;
			background:#fff;
			border:1px solid #e5e7eb;
			border-radius:10px;
			box-shadow:0 20px 50px rgba(0,0,0,.18);
		">

		<div
			style="
				display:flex;
				align-items:flex-start;
				gap:12px;
				padding:20px;
			">

			<div
				style="
					display:flex;
					align-items:center;
					justify-content:center;
					width:38px;
					height:38px;
					flex-shrink:0;
					border-radius:8px;
					background:#fef2f2;
					color:#dc2626;
					font-size:15px;
				">

				<i class="fas fa-exclamation-triangle"></i>

			</div>


			<div>

				<h3
					id="businesses-delete-modal-title"
					style="
						margin:0;
						color:#111827;
						font-size:16px;
						font-weight:600;
						line-height:1.4;
					">
					Eliminar registro
				</h3>

				<p
					style="
						margin:5px 0 0;
						color:#6b7280;
						font-size:13px;
						line-height:1.5;
					">
					¿Está seguro de que desea eliminar este registro?
					Esta acción no se puede deshacer.
				</p>

			</div>

		</div>


		<div
			style="
				display:flex;
				align-items:center;
				justify-content:flex-end;
				gap:8px;
				padding:14px 20px;
				border-top:1px solid #e5e7eb;
				background:#f9fafb;
			">

			<button
				type="button"
				id="businesses-delete-cancel"
				style="
					display:inline-flex;
					align-items:center;
					justify-content:center;
					gap:7px;
					height:36px;
					padding:0 13px;
					box-sizing:border-box;
					border:1px solid #d1d5db;
					border-radius:6px;
					background:#fff;
					color:#374151;
					cursor:pointer;
					font-family:inherit;
					font-size:12px;
					font-weight:600;
				">

				<i class="fas fa-times"></i>

				Cancelar

			</button>


			<button
				type="button"
				id="businesses-delete-confirm"
				style="
					display:inline-flex;
					align-items:center;
					justify-content:center;
					gap:7px;
					height:36px;
					padding:0 13px;
					box-sizing:border-box;
					border:1px solid #dc2626;
					border-radius:6px;
					background:#dc2626;
					color:#fff;
					cursor:pointer;
					font-family:inherit;
					font-size:12px;
					font-weight:600;
				">

				<i class="fas fa-trash-alt"></i>

				Eliminar

			</button>

		</div>

	</div>

</div>


<?php

Yii::app()->clientScript->registerCss(
	'admin-crud-businesses-modal',
	'
#businesses-delete-modal.is-visible {
	display: flex !important;
}

#businesses-delete-modal.is-visible > div {
	transform: translateY(0);
	opacity: 1;
}
'
);

?>