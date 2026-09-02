<?php
/* @var $this CategoriesController */
/* @var $model Categories */

$this->breadcrumbs = array(
	'Categories' => array('index'),
	'Administrar',
);

$filterValues = isset($_GET[CHtml::modelName($model)])
	? $_GET[CHtml::modelName($model)]
	: array();

/*
 * Custom filters that are not direct Categories attributes.
 *
 * These will be consumed by Categories::search() once the model
 * search criteria are extended to support translations and
 * subcategories.
 */
$languageId = isset($filterValues['language_id'])
	? $filterValues['language_id']
	: '';

$subcategoryId = isset($filterValues['subcategory_id'])
	? $filterValues['subcategory_id']
	: '';

$hasActiveFilters = false;

$filterAttributes = array(
	'id',
	'is_featured',
	'sort_order',
	'is_active',
	'created_at',
	'language_id',
	'subcategory_id',
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
 * Languages
 *
 * We intentionally load all languages, including inactive ones,
 * because the administrator may need to find categories that
 * contain an old/inactive translation.
 */
$languages = Languages::model()->findAll(array(
	'order' => 'sort_order ASC, id ASC',
));


/*
 * Subcategories
 *
 * We intentionally load all subcategories, including inactive ones,
 * so the administrator can search for any existing association.
 */
$subcategories = Subcategories::model()->findAll(array(
	'order' => 'sort_order ASC, id ASC',
));


/*
 * Default language.
 *
 * The category name displayed in the main column will use this language.
 */
$defaultLanguage = Languages::model()->findByAttributes(array(
	'is_default' => 1,
));


/*
 * Cache subcategory translations by subcategory ID.
 *
 * This is used only for presentation in the current index.
 */
$subcategoryTranslationCache = array();

if ($defaultLanguage !== null) {

	$subcategoryTranslationRows = SubcategoryTranslations::model()->findAllByAttributes(
		array(
			'language_id' => $defaultLanguage->id
		)
	);

	foreach ($subcategoryTranslationRows as $subcategoryTranslation) {

		$subcategoryTranslationCache[$subcategoryTranslation->subcategory_id] =
			$subcategoryTranslation;
	}
}


Yii::app()->clientScript->registerCss('admin-crud-categories', "
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

.admin-crud-filter__input,
.admin-crud-filter__select {
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

.admin-crud-filter__input:focus,
.admin-crud-filter__select:focus {
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

.admin-crud-grid {
	width: 100%;
	margin: 0 !important;
	border: 0 !important;
	background: #fff;
}

.admin-crud-grid table {
	width: 100%;
	min-width: 980px;
	margin: 0 !important;
	border: 0 !important;
	border-collapse: separate !important;
	border-spacing: 0 !important;
}

.admin-crud-grid thead th {
	height: 42px;
	padding: 0 14px;
	background: #f9fafb;
	border-top: 0 !important;
	border-right: 0 !important;
	border-left: 0 !important;
	border-bottom: 1px solid #e5e7eb !important;
	color: #6b7280;
	font-size: 11px;
	font-weight: 700;
	letter-spacing: .04em;
	line-height: 1;
	text-align: left;
	text-transform: uppercase;
	white-space: nowrap;
}

.admin-crud-grid thead th a {
	color: #6b7280;
	text-decoration: none;
}

.admin-crud-grid thead th a:hover {
	color: #111827;
	text-decoration: none;
}

.admin-crud-grid tbody td {
	min-height: 52px;
	padding: 10px 14px;
	background: #fff;
	border-top: 0 !important;
	border-right: 0 !important;
	border-left: 0 !important;
	border-bottom: 1px solid #f0f1f3 !important;
	color: #374151;
	font-size: 13px;
	vertical-align: middle;
}

.admin-crud-grid tbody tr:hover td {
	background: #fafafa;
}

.admin-crud-grid tbody tr:last-child td {
	border-bottom: 0 !important;
}

/* ==========================================================
CATEGORY
========================================================== */

.admin-crud-category-name {
	display: flex;
	align-items: center;
	gap: 10px;
}

.admin-crud-category-name__image {
	display: flex;
	align-items: center;
	justify-content: center;
	width: 36px;
	height: 36px;
	flex-shrink: 0;
	overflow: hidden;
	border-radius: 6px;
	background: #f3f4f6;
	color: #9ca3af;
}

.admin-crud-category-name__image img {
	display: block;
	width: 100%;
	height: 100%;
	object-fit: cover;
}

.admin-crud-category-name__text {
	min-width: 0;
}

.admin-crud-category-name__title {
	max-width: 220px;
	overflow: hidden;
	color: #111827;
	font-weight: 600;
	text-overflow: ellipsis;
	white-space: nowrap;
}

.admin-crud-category-name__id {
	margin-top: 2px;
	color: #9ca3af;
	font-size: 11px;
}

/* ==========================================================
LANGUAGES
========================================================== */

.admin-crud-languages {
	display: flex;
	flex-wrap: wrap;
	gap: 5px;
	max-width: 260px;
}

.admin-crud-language {
	display: inline-flex;
	align-items: center;
	gap: 5px;
	height: 24px;
	padding: 0 7px;
	border: 1px solid #e5e7eb;
	border-radius: 5px;
	background: #f9fafb;
	color: #4b5563;
	font-size: 11px;
	font-weight: 600;
	line-height: 1;
	white-space: nowrap;
}

.admin-crud-language__code {
	color: #111827;
	font-weight: 700;
}

.admin-crud-language--inactive {
	opacity: .55;
}

.admin-crud-language__missing {
	color: #9ca3af;
	font-style: italic;
	font-weight: 500;
}

/* ==========================================================
SUBCATEGORIES
========================================================== */

.admin-crud-subcategories {
	display: flex;
	flex-direction: column;
	gap: 4px;
	max-width: 280px;
}

.admin-crud-subcategory {
	display: flex;
	align-items: center;
	gap: 6px;
	min-width: 0;
}

.admin-crud-subcategory__bullet {
	width: 5px;
	height: 5px;
	flex-shrink: 0;
	border-radius: 50%;
	background: #9ca3af;
}

.admin-crud-subcategory__name {
	overflow: hidden;
	color: #4b5563;
	text-overflow: ellipsis;
	white-space: nowrap;
}

.admin-crud-subcategory__more {
	color: #9ca3af;
	font-size: 11px;
}

/* ==========================================================
STATUS
========================================================== */

.admin-crud-status {
	display: inline-flex;
	align-items: center;
	gap: 6px;
	font-size: 12px;
	font-weight: 600;
	white-space: nowrap;
}

.admin-crud-status__dot {
	width: 7px;
	height: 7px;
	border-radius: 50%;
	background: #9ca3af;
}

.admin-crud-status--active {
	color: #374151;
}

.admin-crud-status--active .admin-crud-status__dot {
	background: #22c55e;
}

.admin-crud-status--inactive {
	color: #9ca3af;
}

.admin-crud-status--inactive .admin-crud-status__dot {
	background: #d1d5db;
}

/* ==========================================================
FEATURED
========================================================== */

.admin-crud-featured {
	display: inline-flex;
	align-items: center;
	gap: 5px;
	font-size: 12px;
	font-weight: 600;
	white-space: nowrap;
}

.admin-crud-featured--yes {
	color: #374151;
}

.admin-crud-featured--yes i {
	color: #f59e0b;
}

.admin-crud-featured--no {
	color: #9ca3af;
}

/* ==========================================================
ACTIONS
========================================================== */

.admin-crud-actions-column {
	width: 108px;
	min-width: 108px;
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
EMPTY STATE
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
");


Yii::app()->clientScript->registerScript(
	'crud-index-categories',
	"
$('#categories-filter-toggle').on('click', function(e) {

	e.preventDefault();

	var button = $(this);
	var panel = $('#categories-filter-panel');
	var icon = button.find('.filter-toggle-icon');

	panel.toggleClass('is-visible');
	button.toggleClass('is-active');

	icon
		.toggleClass('fa-chevron-down')
		.toggleClass('fa-chevron-up');

	return false;
});


var crud_categories_delete_url = null;


function openCrudCategoriesDeleteModal(url) {

	crud_categories_delete_url = url;

	$('#categories-delete-modal')
		.addClass('is-visible')
		.attr('aria-hidden', 'false');

	$('body').css('overflow', 'hidden');
}


function closeCrudCategoriesDeleteModal() {

	crud_categories_delete_url = null;

	$('#categories-delete-modal')
		.removeClass('is-visible')
		.attr('aria-hidden', 'true');

	$('body').css('overflow', '');
}


$(document).on(
	'click',
	'#categories-grid .admin-crud-action--delete',
	function(e) {

		e.preventDefault();
		e.stopImmediatePropagation();

		var url = $(this).attr('href');

		if (!url) {
			return false;
		}

		openCrudCategoriesDeleteModal(url);

		return false;
	}
);


$('#categories-delete-cancel').on(
	'click',
	function(e) {

		e.preventDefault();

		closeCrudCategoriesDeleteModal();

		return false;
	}
);


$('#categories-delete-confirm').on(
	'click',
	function(e) {

		e.preventDefault();

		var url = crud_categories_delete_url;

		if (!url) {

			closeCrudCategoriesDeleteModal();

			return false;
		}

		window.location.href = url;

		return false;
	}
);


$('#categories-delete-modal').on(
	'click',
	function(e) {

		if (e.target === this) {
			closeCrudCategoriesDeleteModal();
		}

	}
);


$(document).on(
	'keydown',
	function(e) {

		if (e.key === 'Escape') {
			closeCrudCategoriesDeleteModal();
		}

	}
);
"
);
?>


<div class="admin-crud-page">


	<div class="admin-crud-header">

		<div class="admin-crud-heading">

			<h1 class="admin-crud-title">
				Categorías
			</h1>

			<p class="admin-crud-description">
				Gestiona y administra las categorías del sistema.
			</p>

		</div>


		<div class="admin-crud-actions">

			<a
				id="categories-filter-toggle"
				class="admin-crud-button admin-crud-button--secondary"
				href="#"
				title="Filtrar"
				aria-label="Filtrar">

				<i
					class="fas fa-filter"
					aria-hidden="true"></i>

				<span>
					Filtrar
				</span>

				<i
					class="fas fa-chevron-down filter-toggle-icon"
					aria-hidden="true"></i>

			</a>


			<a
				class="admin-crud-button admin-crud-button--primary"
				href="<?php echo $this->createUrl('create'); ?>"
				title="Nueva categoría"
				aria-label="Nueva categoría">

				<i
					class="fas fa-plus"
					aria-hidden="true"></i>

				<span>
					Nueva categoría
				</span>

			</a>

		</div>

	</div>


	<div
		id="categories-filter-panel"
		class="admin-crud-filter<?php echo $hasActiveFilters ? ' is-visible' : ''; ?>">

		<div class="admin-crud-filter__header">

			<div>

				<div class="admin-crud-filter__title">

					<i class="fas fa-sliders-h"></i>

					Filtrar registros

				</div>

				<p class="admin-crud-filter__hint">

					Busca categorías por sus datos, idiomas o subcategorías asociadas.

				</p>

			</div>

		</div>


		<form
			method="get"
			action="<?php echo $this->createUrl('index'); ?>">


			<div class="admin-crud-filter__fields">


				<div class="admin-crud-filter__field">

					<label
						class="admin-crud-filter__label"
						for="categories-filter-name">

						Categoría

					</label>

					<?php

					echo CHtml::textField(
						CHtml::modelName($model) . '[name]',
						isset($filterValues['name'])
							? $filterValues['name']
							: '',
						array(
							'id' => 'categories-filter-name',
							'class' => 'admin-crud-filter__input',
							'autocomplete' => 'off',
						)
					);

					?>

				</div>


				<div class="admin-crud-filter__field">

					<label
						class="admin-crud-filter__label"
						for="categories-filter-is_featured">

						Featured

					</label>

					<?php

					echo CHtml::dropDownList(
						CHtml::modelName($model) . '[is_featured]',
						isset($filterValues['is_featured'])
							? $filterValues['is_featured']
							: '',
						array(
							'' => 'Todos',
							'1' => 'Featured',
							'0' => 'No featured',
						),
						array(
							'id' => 'categories-filter-is_featured',
							'class' => 'admin-crud-filter__select',
						)
					);

					?>

				</div>


				<div class="admin-crud-filter__field">

					<label
						class="admin-crud-filter__label"
						for="categories-filter-is_active">

						Estado

					</label>

					<?php

					echo CHtml::dropDownList(
						CHtml::modelName($model) . '[is_active]',
						isset($filterValues['is_active'])
							? $filterValues['is_active']
							: '',
						array(
							'' => 'Todos',
							'1' => 'Activo',
							'0' => 'Inactivo',
						),
						array(
							'id' => 'categories-filter-is_active',
							'class' => 'admin-crud-filter__select',
						)
					);

					?>

				</div>


			</div>


			<div class="admin-crud-filter__footer">

				<a
					class="admin-crud-button admin-crud-button--secondary"
					href="<?php echo $this->createUrl('index'); ?>"
					title="Limpiar filtros"
					aria-label="Limpiar filtros">

					<i
						class="fas fa-undo"
						aria-hidden="true"></i>

					Limpiar

				</a>


				<button
					type="submit"
					class="admin-crud-button admin-crud-button--primary"
					title="Buscar"
					aria-label="Buscar">

					<i
						class="fas fa-search"
						aria-hidden="true"></i>

					Buscar

				</button>

			</div>


		</form>

	</div>


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
						Categorías disponibles en el sistema.
					</p>

				</div>

			</div>

		</div>


		<div class="admin-crud-table-wrapper">


			<?php

			$this->widget('zii.widgets.grid.CGridView', array(

				'id' => 'categories-grid',

				'dataProvider' => $dataProvider,

				'filter' => null,

				'ajaxUpdate' => false,

				'enableSorting' => true,

				'enablePagination' => true,

				'itemsCssClass' => 'admin-crud-table',

				'htmlOptions' => array(
					'class' => 'admin-crud-grid',
				),

				'template' => '{items}',

				'emptyText' => '

					<div class="admin-crud-empty">

						<div class="admin-crud-empty__icon">

							<i class="fas fa-inbox"></i>

						</div>

						<div class="admin-crud-empty__title">

							No hay categorías

						</div>

						<div class="admin-crud-empty__text">

							No se encontraron categorías para mostrar.

						</div>

					</div>

				',

				'columns' => array(


					array(

						'header' => 'Categoría',

						'type' => 'raw',

						'value' => function ($data) use ($defaultLanguage) {

							$translation = null;

							if ($defaultLanguage !== null) {

								foreach ($data->categoryTranslations as $categoryTranslation) {

									if (
										(string) $categoryTranslation->language_id ===
										(string) $defaultLanguage->id
									) {

										$translation = $categoryTranslation;

										break;
									}
								}
							}

							$name = $translation
								? $translation->name
								: 'Sin traducción';

							if ($data->image) {

								$imageHtml =
									'<img src="' .
									Yii::app()->baseUrl . '/' . CHtml::encode($data->image) .
									'" alt="' .
									CHtml::encode($name) .
									'">';
							} else {

								$imageHtml =
									'<i class="fas fa-folder"></i>';
							}

							return
								'<div class="admin-crud-category-name">' .

								'<div class="admin-crud-category-name__image">' .
								$imageHtml .
								'</div>' .

								'<div class="admin-crud-category-name__text">' .

								'<div class="admin-crud-category-name__title">' .
								CHtml::encode($name) .
								'</div>' .

								'<div class="admin-crud-category-name__id">' .
								'ID #' .
								CHtml::encode($data->id) .
								'</div>' .

								'</div>' .

								'</div>';
						},
					),

					array(

						'header' => 'Subcategorías',

						'type' => 'raw',

						'value' => function ($data) use ($subcategoryTranslationCache) {

							$rows = $data->subcategories;

							if (!$rows) {

								return
									'<span class="admin-crud-language__missing">' .
									'Sin subcategorías' .
									'</span>';
							}

							$html =
								'<div class="admin-crud-subcategories">';

							$visibleCount = 3;
							$count = 0;

							foreach ($rows as $subcategory) {

								if ((int) $subcategory->is_active !== 1) {
									break;
								}

								if ($count >= $visibleCount) {
									break;
								}

								$translation =
									isset($subcategoryTranslationCache[$subcategory->id])
									? $subcategoryTranslationCache[$subcategory->id]
									: null;

								$name =
									$translation
									? $translation->name
									: 'Subcategoría #' . $subcategory->id;

								$html .=

									'<div class="admin-crud-subcategory">' .

									'<span class="admin-crud-subcategory__bullet"></span>' .

									'<span class="admin-crud-subcategory__name">' .
									CHtml::encode($name) .
									'</span>' .

									'</div>';

								$count++;
							}

							if ($count > $visibleCount) {

								$remaining =
									$count - $visibleCount;

								$html .=

									'<div class="admin-crud-subcategory__more">' .
									'+' .
									$remaining .
									' más' .
									'</div>';
							}

							$html .=
								'</div>';

							return $html;
						},
					),


					array(

						'name' => 'is_featured',

						'header' => 'Featured',

						'type' => 'raw',

						'value' => function ($data) {

							if ((int) $data->is_featured === 1) {

								return
									'<span class="admin-crud-featured admin-crud-featured--yes">' .

									'<i class="fas fa-star"></i>' .

									'Sí' .

									'</span>';
							}

							return
								'<span class="admin-crud-featured admin-crud-featured--no">' .
								'No' .
								'</span>';
						},
					),

					array(

						'name' => 'is_active',

						'header' => 'Estado',

						'type' => 'raw',

						'value' => function ($data) {

							if ((int) $data->is_active === 1) {

								return
									'<span class="admin-crud-status admin-crud-status--active">' .

									'<span class="admin-crud-status__dot"></span>' .

									'Activo' .

									'</span>';
							}

							return
								'<span class="admin-crud-status admin-crud-status--inactive">' .

								'<span class="admin-crud-status__dot"></span>' .

								'Inactivo' .

								'</span>';
						},
					),

					array(

						'class' => 'CButtonColumn',

						'header' => 'Acciones',

						'headerHtmlOptions' => array(
							'class' => 'admin-crud-actions-column',
						),

						'htmlOptions' => array(
							'class' => 'admin-crud-actions-column',
						),

						'template' => '{update}{delete}',

						'buttons' => array(

							'update' => array(

								'label' =>
								'<i class="fas fa-pen" aria-hidden="true"></i>',

								'title' => 'Editar',

								'imageUrl' => false,

								'options' => array(
									'class' => 'admin-crud-action',
									'alt' => 'Editar',
									'aria-label' => 'Editar',
								),
							),

							'delete' => array(

								'label' =>
								'<i class="fas fa-trash-alt" aria-hidden="true"></i>',

								'title' => 'Eliminar',

								'imageUrl' => false,

								'options' => array(
									'class' => 'admin-crud-action admin-crud-action--delete',
									'alt' => 'Eliminar',
									'aria-label' => 'Eliminar',
								),
							),
						),

						'deleteConfirmation' => null,
					),
				),
			));

			?>


		</div>


		<div class="admin-crud-footer">


			<div class="admin-crud-summary">

				<?php

				$pagination = $dataProvider->getPagination();

				$itemCount = $dataProvider->getItemCount();

				$totalCount = $dataProvider->getTotalItemCount();

				$start = $itemCount > 0
					? $pagination->getOffset() + 1
					: 0;

				$end = $pagination->getOffset() + $itemCount;

				echo
				'Mostrando ' .
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

				$this->widget('CLinkPager', array(
					'pages' => $dataProvider->getPagination(),
					'header' => '',
					'firstPageLabel' => '«',
					'prevPageLabel' => '‹',
					'nextPageLabel' => '›',
					'lastPageLabel' => '»',
					'maxButtonCount' => 5,
				));

				?>

			</div>


		</div>

	</div>

</div>


<div
	id="categories-delete-modal"
	class="admin-crud-modal"
	aria-hidden="true"
	role="dialog"
	aria-modal="true"
	aria-labelledby="categories-delete-modal-title">


	<div class="admin-crud-modal__dialog">


		<div class="admin-crud-modal__header">


			<div class="admin-crud-modal__icon">

				<i
					class="fas fa-exclamation-triangle"
					aria-hidden="true"></i>

			</div>


			<div>

				<h3
					id="categories-delete-modal-title"
					class="admin-crud-modal__title">

					Eliminar categoría

				</h3>


				<p class="admin-crud-modal__message">

					¿Está seguro de que desea eliminar esta categoría?
					Esta acción desactivará el registro.

				</p>

			</div>


		</div>


		<div class="admin-crud-modal__footer">


			<button
				type="button"
				id="categories-delete-cancel"
				class="admin-crud-modal__button admin-crud-modal__button--cancel"
				title="Cancelar"
				aria-label="Cancelar">

				<i
					class="fas fa-times"
					aria-hidden="true"></i>

				Cancelar

			</button>


			<button
				type="button"
				id="categories-delete-confirm"
				class="admin-crud-modal__button admin-crud-modal__button--delete"
				title="Eliminar"
				aria-label="Eliminar">

				<i
					class="fas fa-trash-alt"
					aria-hidden="true"></i>

				Eliminar

			</button>


		</div>

	</div>

</div>