<?php

/* @var $this BrandsController */
/* @var $model Brands */

$this->breadcrumbs = array(
	'Brands' => array('index'),
	'Administrar',
);


/*
 * ==========================================================
 * ORIGINAL FILTER
 * ==========================================================
 */

$hasActiveFilters = false;

$filterValues = isset($_GET[CHtml::modelName($model)])
	? $_GET[CHtml::modelName($model)]
	: array();

$filterAttributes = array(
	'id',
	'name',
	'is_featured',
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
 * ==========================================================
 * LANGUAGES
 * ==========================================================
 */

$languages = Yii::app()->db->createCommand()
	->select('*')
	->from('languages')
	->order('id ASC')
	->queryAll();


/*
 * ==========================================================
 * BRANDS SECTION
 * ==========================================================
 *
 * Un registro de brands_section por idioma.
 *
 */

$brandsSectionRows = BrandsSection::model()
	->findAll(array(
		'order' => 'language_id ASC',
	));

$brandsSectionsByLanguage = array();

foreach ($brandsSectionRows as $brandsSectionRow) {

	$brandsSectionsByLanguage[(int) $brandsSectionRow->language_id] = $brandsSectionRow;
}


/*
 * ==========================================================
 * CSS
 * ==========================================================
 */

Yii::app()->clientScript->registerCss('admin-crud-brands', "

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

.admin-crud-grid {
	width: 100%;
	margin: 0 !important;
	border: 0 !important;
	background: #fff;
}

.admin-crud-grid table {
	width: 100%;
	min-width: 760px;
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
	height: 52px;
	padding: 0 14px;
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
   BRANDS SECTION FORM
========================================================== */

.brands-section-card {
	overflow: hidden;
	margin-bottom: 24px;
	background: #fff;
	border: 1px solid #e5e7eb;
	border-radius: 10px;
	box-shadow: 0 1px 2px rgba(0, 0, 0, .03);
}

.brands-section-card__header {
	display: flex;
	align-items: center;
	gap: 12px;
	padding: 16px 20px;
	border-bottom: 1px solid #e5e7eb;
}

.brands-section-card__icon {
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

.brands-section-card__heading {
	margin: 0;
	color: #111827;
	font-size: 15px;
	font-weight: 600;
	line-height: 1.3;
}

.brands-section-card__description {
	margin: 2px 0 0;
	color: #9ca3af;
	font-size: 12px;
	line-height: 1.4;
}


/* ==========================================================
   TABS
========================================================== */

.brands-section-tabs {
	display: flex;
	align-items: center;
	gap: 0;
	overflow-x: auto;
	padding: 0 20px;
	border-bottom: 1px solid #e5e7eb;
	background: #f9fafb;
}

.brands-section-tab {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	gap: 8px;
	min-height: 48px;
	padding: 0 16px;
	border: 0;
	border-bottom: 2px solid transparent;
	background: transparent;
	color: #6b7280;
	cursor: pointer;
	font-family: inherit;
	font-size: 13px;
	font-weight: 600;
	white-space: nowrap;
}

.brands-section-tab:hover {
	color: #111827;
}

.brands-section-tab.is-active {
	border-bottom-color: #111827;
	color: #111827;
}

.brands-section-tab__code {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	min-width: 28px;
	height: 22px;
	padding: 0 6px;
	box-sizing: border-box;
	border: 1px solid #e5e7eb;
	border-radius: 5px;
	background: #fff;
	color: #6b7280;
	font-size: 10px;
	font-weight: 700;
	text-transform: uppercase;
}

.brands-section-tab.is-active .brands-section-tab__code {
	background: #111827;
	border-color: #111827;
	color: #fff;
}


/* ==========================================================
   PANELS
========================================================== */

.brands-section-panel {
	display: none;
}

.brands-section-panel.is-active {
	display: block;
}

.brands-section-form {
	margin: 0;
}


/* ==========================================================
   LANGUAGE HEADER
========================================================== */

.brands-section-language-header {
	display: flex;
	align-items: center;
	gap: 10px;
	padding: 16px 20px;
	border-bottom: 1px solid #f0f1f3;
}

.brands-section-language-code {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 34px;
	height: 30px;
	border-radius: 6px;
	background: #f3f4f6;
	color: #374151;
	font-size: 10px;
	font-weight: 700;
	text-transform: uppercase;
}

.brands-section-language-name {
	color: #111827;
	font-size: 14px;
	font-weight: 600;
}


/* ==========================================================
   FIELDS
========================================================== */

.brands-section-form-body {
	padding: 24px 20px;
}

.brands-section-form-grid {
	display: grid;
	grid-template-columns: minmax(0, 1fr) 300px;
	gap: 28px;
}

.brands-section-fields {
	min-width: 0;
}

.brands-section-field {
	margin-bottom: 20px;
}

.brands-section-field:last-child {
	margin-bottom: 0;
}

.brands-section-label {
	display: block;
	margin-bottom: 7px;
	color: #374151;
	font-size: 11px;
	font-weight: 700;
	letter-spacing: .03em;
	text-transform: uppercase;
}

.brands-section-required {
	color: #dc2626;
}

.brands-section-input,
.brands-section-textarea {
	display: block;
	width: 100%;
	box-sizing: border-box;
	border: 1px solid #d1d5db;
	border-radius: 6px;
	outline: none;
	background: #fff;
	color: #374151;
	font-family: inherit;
	font-size: 13px;
	transition:
		border-color .15s ease,
		box-shadow .15s ease;
}

.brands-section-input {
	height: 40px;
	padding: 0 11px;
}

.brands-section-textarea {
	min-height: 130px;
	padding: 10px 11px;
	resize: vertical;
	line-height: 1.5;
}

.brands-section-input:focus,
.brands-section-textarea:focus {
	border-color: #9ca3af;
	box-shadow: 0 0 0 3px rgba(17, 24, 39, .06);
}


/* ==========================================================
   IMAGE
========================================================== */

.brands-section-image {
	min-width: 0;
}

.brands-section-image-label {
	display: block;
	margin-bottom: 7px;
	color: #374151;
	font-size: 11px;
	font-weight: 700;
	letter-spacing: .03em;
	text-transform: uppercase;
}

.brands-section-image-preview {
	width: 100%;
	aspect-ratio: 4 / 3;
	overflow: hidden;
	margin-bottom: 12px;
	border: 1px solid #e5e7eb;
	border-radius: 8px;
	background: #f3f4f6;
}

.brands-section-image-preview img {
	display: block;
	width: 100%;
	height: 100%;
	object-fit: cover;
}

.brands-section-image-empty {
	display: flex;
	align-items: center;
	justify-content: center;
	width: 100%;
	height: 100%;
	color: #9ca3af;
	font-size: 26px;
}

.brands-section-image-file {
	display: block;
	width: 100%;
	box-sizing: border-box;
	padding: 8px;
	border: 1px solid #d1d5db;
	border-radius: 6px;
	background: #fff;
	color: #374151;
	font-family: inherit;
	font-size: 12px;
}

.brands-section-image-hint {
	margin-top: 7px;
	color: #9ca3af;
	font-size: 11px;
	line-height: 1.4;
}

.brands-section-image-current {
	margin-top: 7px;
	color: #9ca3af;
	font-size: 11px;
	line-height: 1.4;
	word-break: break-all;
}


/* ==========================================================
   FORM FOOTER
========================================================== */

.brands-section-form-footer {
	display: flex;
	align-items: center;
	justify-content: flex-end;
	gap: 8px;
	padding: 14px 20px;
	border-top: 1px solid #e5e7eb;
	background: #f9fafb;
}

.brands-section-save {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	gap: 8px;
	height: 38px;
	padding: 0 16px;
	border: 1px solid #111827;
	border-radius: 7px;
	background: #111827;
	color: #fff;
	cursor: pointer;
	font-family: inherit;
	font-size: 13px;
	font-weight: 600;
	line-height: 1;
}

.brands-section-save:hover {
	background: #1f2937;
	border-color: #1f2937;
	color: #fff;
}


/* ==========================================================
   RESPONSIVE
========================================================== */

@media (max-width: 900px) {

	.admin-crud-filter__fields {
		grid-template-columns: repeat(2, minmax(0, 1fr));
	}

	.brands-section-form-grid {
		grid-template-columns: 1fr;
	}

	.brands-section-image {
		max-width: 420px;
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

@media (max-width: 600px) {

	.brands-section-form-body {
		padding: 20px 16px;
	}

	.brands-section-language-header {
		padding: 14px 16px;
	}

	.brands-section-form-footer {
		padding: 14px 16px;
	}

	.brands-section-tabs {
		padding: 0 12px;
	}

	.brands-section-tab {
		padding: 0 12px;
	}

	.brands-section-save {
		width: 100%;
	}

}
");


/*
 * ==========================================================
 * ORIGINAL JAVASCRIPT + LANGUAGE TABS
 * ==========================================================
 */

Yii::app()->clientScript->registerScript(
	'crud-index-brands',
	"
$('#brands-filter-toggle').on('click', function(e) {

	e.preventDefault();

	var button = $(this);
	var panel = $('#brands-filter-panel');
	var icon = button.find('.filter-toggle-icon');

	panel.toggleClass('is-visible');

	button.toggleClass('is-active');

	icon
		.toggleClass('fa-chevron-down')
		.toggleClass('fa-chevron-up');

	return false;

});


var crud_633fb5d52cDeleteUrl = null;


function opencrud_633fb5d52cDeleteModal(url) {

	crud_633fb5d52cDeleteUrl = url;

	$('#brands-delete-modal')
		.addClass('is-visible')
		.attr('aria-hidden', 'false');

	$('body').css('overflow', 'hidden');

}


function closecrud_633fb5d52cDeleteModal() {

	crud_633fb5d52cDeleteUrl = null;

	$('#brands-delete-modal')
		.removeClass('is-visible')
		.attr('aria-hidden', 'true');

	$('body').css('overflow', '');

}


$(document).on(
	'click',
	'#brands-grid .admin-crud-action--delete',
	function(e) {

		e.preventDefault();

		e.stopImmediatePropagation();

		var url = $(this).attr('href');

		if (!url) {
			return false;
		}

		opencrud_633fb5d52cDeleteModal(url);

		return false;

	}
);


$('#brands-delete-cancel').on(
	'click',
	function(e) {

		e.preventDefault();

		closecrud_633fb5d52cDeleteModal();

		return false;

	}
);


$('#brands-delete-confirm').on(
	'click',
	function(e) {

		e.preventDefault();

		var url = crud_633fb5d52cDeleteUrl;

		if (!url) {

			closecrud_633fb5d52cDeleteModal();

			return false;

		}

		window.location.href = url;

		return false;

	}
);


$('#brands-delete-modal').on(
	'click',
	function(e) {

		if (e.target === this) {

			closecrud_633fb5d52cDeleteModal();

		}

	}
);


$(document).on('keydown', function(e) {

	if (e.key === 'Escape') {

		closecrud_633fb5d52cDeleteModal();

	}

});


/*
 * ==========================================================
 * BRANDS SECTION TABS
 * ==========================================================
 */

$('.brands-section-tab').on(
	'click',
	function(e) {

		e.preventDefault();

		var button =
			$(this);

		var languageId =
			button.attr(
				'data-language-id'
			);

		if (!languageId) {
			return false;
		}

		$('.brands-section-tab')
			.removeClass('is-active')
			.attr(
				'aria-selected',
				'false'
			);

		button
			.addClass('is-active')
			.attr(
				'aria-selected',
				'true'
			);

		$('.brands-section-panel')
			.removeClass('is-active');

		$('#brands-section-panel-' + languageId)
			.addClass('is-active');

		return false;

	}
);

"
);

?>


<div class="admin-crud-page">


	<!-- ======================================================
	     HEADER ORIGINAL
	====================================================== -->

	<div class="admin-crud-header">

		<div class="admin-crud-heading">

			<h1 class="admin-crud-title">

				Brands

			</h1>

			<p class="admin-crud-description">

				Gestiona y administra brands.

			</p>

		</div>


		<div class="admin-crud-actions">


			<a
				id="brands-filter-toggle"
				class="admin-crud-button admin-crud-button--secondary"
				href="#"
				title="Filtrar"
				aria-label="Filtrar">

				<i
					class="fas fa-filter"
					aria-hidden="true">
				</i>

				<span>
					Filtrar
				</span>

				<i
					class="fas fa-chevron-down filter-toggle-icon"
					aria-hidden="true">
				</i>

			</a>


			<a
				class="admin-crud-button admin-crud-button--primary"
				href="<?php
						echo $this->createUrl("create");
						?>"
				title="Nuevo brands"
				aria-label="Nuevo brands">

				<i
					class="fas fa-plus"
					aria-hidden="true">
				</i>

				<span>
					Nuevo brands
				</span>

			</a>


		</div>

	</div>


	<!-- ======================================================
	     FILTER ORIGINAL
	     NO MODIFICADO
	====================================================== -->

	<div
		id="brands-filter-panel"
		class="admin-crud-filter<?php
								echo $hasActiveFilters
									? " is-visible"
									: "";
								?>">

		<div class="admin-crud-filter__header">

			<div>

				<div class="admin-crud-filter__title">

					<i class="fas fa-sliders-h"></i>

					Filtrar registros

				</div>

				<p class="admin-crud-filter__hint">

					Completa uno o varios campos para filtrar los resultados.

				</p>

			</div>

		</div>


		<form
			method="get"
			action="<?php
					echo $this->createUrl("index");
					?>">

			<div class="admin-crud-filter__fields">


				<div class="admin-crud-filter__field">

					<label
						class="admin-crud-filter__label"
						for="brands-filter-id">

						Id

					</label>

					<?php

					echo CHtml::activeTextField(
						$model,
						'id',
						array(
							'id' =>
							'brands-filter-id',
							'class' =>
							'admin-crud-filter__input',
							'autocomplete' =>
							'off',
						)
					);

					?>

				</div>


				<div class="admin-crud-filter__field">

					<label
						class="admin-crud-filter__label"
						for="brands-filter-name">

						Name

					</label>

					<?php

					echo CHtml::activeTextField(
						$model,
						'name',
						array(
							'id' =>
							'brands-filter-name',
							'class' =>
							'admin-crud-filter__input',
							'autocomplete' =>
							'off',
						)
					);

					?>

				</div>


				<div class="admin-crud-filter__field">

					<label
						class="admin-crud-filter__label"
						for="brands-filter-is_featured">

						Is Featured

					</label>

					<?php

					echo CHtml::activeTextField(
						$model,
						'is_featured',
						array(
							'id' =>
							'brands-filter-is_featured',
							'class' =>
							'admin-crud-filter__input',
							'autocomplete' =>
							'off',
						)
					);

					?>

				</div>


			</div>


			<div class="admin-crud-filter__footer">


				<a
					class="admin-crud-button admin-crud-button--secondary"
					href="<?php
							echo $this->createUrl("index");
							?>"
					title="Limpiar filtros"
					aria-label="Limpiar filtros">

					<i
						class="fas fa-undo"
						aria-hidden="true">
					</i>

					Limpiar

				</a>


				<button
					type="submit"
					class="admin-crud-button admin-crud-button--primary"
					title="Buscar"
					aria-label="Buscar">

					<i
						class="fas fa-search"
						aria-hidden="true">
					</i>

					Buscar

				</button>


			</div>

		</form>

	</div>


	<!-- ======================================================
	     BRANDS SECTION
	     NUEVO BLOQUE
	====================================================== -->

	<div class="brands-section-card">


		<div class="brands-section-card__header">

			<div class="brands-section-card__icon">

				<i
					class="fas fa-globe"
					aria-hidden="true">
				</i>

			</div>


			<div>

				<h2 class="brands-section-card__heading">

					Contenido por idioma

				</h2>

				<p class="brands-section-card__description">

					Completa el contenido de la sección para cada idioma.

				</p>

			</div>

		</div>


		<?php if (!empty($languages)): ?>


			<!-- ==================================================
			     LANGUAGE TABS
			================================================== -->

			<div
				class="brands-section-tabs"
				role="tablist"
				aria-label="Idiomas">

				<?php foreach ($languages as $index => $language): ?>

					<?php

					$languageId =
						(int) $language['id'];

					$languageCode =
						isset($language['code'])
						? strtoupper(
							$language['code']
						)
						: '';

					$languageName =
						isset($language['native_name']) &&
						$language['native_name'] !== ''
						? $language['native_name']
						: (
							isset($language['name'])
							? $language['name']
							: 'Idioma #' .
							$languageId
						);

					$isActive =
						$index === 0;

					?>

					<button
						type="button"
						class="brands-section-tab<?php
													echo $isActive
														? ' is-active'
														: '';
													?>"
						data-language-id="<?php
											echo $languageId;
											?>"
						role="tab"
						aria-selected="<?php
										echo $isActive
											? 'true'
											: 'false';
										?>">

						<span class="brands-section-tab__code">

							<?php

							echo CHtml::encode(
								$languageCode
							);

							?>

						</span>


						<span>

							<?php

							echo CHtml::encode(
								$languageName
							);

							?>

						</span>

					</button>

				<?php endforeach; ?>

			</div>


			<!-- ==================================================
			     LANGUAGE PANELS
			================================================== -->

			<?php foreach ($languages as $index => $language): ?>

				<?php

				$languageId =
					(int) $language['id'];

				$languageCode =
					isset($language['code'])
					? strtoupper(
						$language['code']
					)
					: '';

				$languageName =
					isset($language['native_name']) &&
					$language['native_name'] !== ''
					? $language['native_name']
					: (
						isset($language['name'])
						? $language['name']
						: 'Idioma #' .
						$languageId
					);

				$isActive =
					$index === 0;

				$section =
					isset(
						$brandsSectionsByLanguage[$languageId]
					)
					? $brandsSectionsByLanguage[$languageId]
					: null;

				?>

				<div
					id="brands-section-panel-<?php
												echo $languageId;
												?>"
					class="brands-section-panel<?php
												echo $isActive
													? ' is-active'
													: '';
												?>"
					role="tabpanel">


					<form
						class="brands-section-form"
						method="post"
						enctype="multipart/form-data"
						action="<?php

								if ($section) {

									echo Yii::app()->createUrl(
										'/cpanel/brandsSection/update',
										array(
											'id' =>
											$section->id,
										)
									);
								} else {

									echo Yii::app()->createUrl(
										'/cpanel/brandsSection/create'
									);
								}

								?>">


						<input
							type="hidden"
							name="BrandsSection[language_id]"
							value="<?php
									echo $languageId;
									?>">


						<?php if ($section): ?>

							<input
								type="hidden"
								name="BrandsSection[id]"
								value="<?php
										echo $section->id;
										?>">

						<?php endif; ?>


						<!-- ==========================================
						     LANGUAGE HEADER
						========================================== -->

						<div class="brands-section-language-header">

							<span class="brands-section-language-code">

								<?php

								echo CHtml::encode(
									$languageCode
								);

								?>

							</span>


							<span class="brands-section-language-name">

								<?php

								echo CHtml::encode(
									$languageName
								);

								?>

							</span>

						</div>


						<!-- ==========================================
						     FORM BODY
						========================================== -->

						<div class="brands-section-form-body">

							<div class="brands-section-form-grid">


								<!-- ==================================
								     TEXT FIELDS
								================================== -->

								<div class="brands-section-fields">


									<div class="brands-section-field">

										<label
											class="brands-section-label"
											for="brands-section-<?php
																echo $languageId;
																?>-eyebrow">

											Eyebrow

											<span class="brands-section-required">
												*
											</span>

										</label>


										<input
											type="text"
											id="brands-section-<?php
																echo $languageId;
																?>-eyebrow"
											name="BrandsSection[eyebrow]"
											value="<?php

													echo $section
														? CHtml::encode(
															$section->eyebrow
														)
														: '';

													?>"
											class="brands-section-input"
											required=required
											maxlength="255"
											autocomplete="off"
											placeholder="Ej. NUESTROS CLIENTES">

									</div>


									<div class="brands-section-field">

										<label
											class="brands-section-label"
											for="brands-section-<?php
																echo $languageId;
																?>-title">

											Título

											<span class="brands-section-required">
												*
											</span>

										</label>


										<input
											type="text"
											id="brands-section-<?php
																echo $languageId;
																?>-title"
											name="BrandsSection[title]"
											value="<?php

													echo $section
														? CHtml::encode(
															$section->title
														)
														: '';

													?>"
											class="brands-section-input"
											required=required
											maxlength="500"
											autocomplete="off"
											placeholder="Ej. Estamos donde vos estás">

									</div>


									<div class="brands-section-field">

										<label
											class="brands-section-label"
											for="brands-section-<?php
																echo $languageId;
																?>-text">

											Texto

											<span class="brands-section-required">
												*
											</span>

										</label>


										<textarea
											id="brands-section-<?php
																echo $languageId;
																?>-text"
											name="BrandsSection[text]"
											required=required
											class="brands-section-textarea"
											placeholder="Ingresa el texto de la sección."><?php

																							echo $section
																								? CHtml::encode(
																									$section->text
																								)
																								: '';

																							?></textarea>

									</div>


								</div>


								<!-- ==================================
								     IMAGE
								================================== -->

								<div class="brands-section-image">


									<label class="brands-section-image-label">

										Imagen

									</label>


									<div class="brands-section-image-preview">


										<?php if (
											$section &&
											!empty($section->image)
										): ?>


											<img
												src="<?= Yii::app()->getBaseUrl() . CHtml::encode(
															$section->image
														);

														?>"
												alt="<?= CHtml::encode(
															$section->title
														);

														?>">


										<?php else: ?>


											<div class="brands-section-image-empty">

												<i
													class="fas fa-image"
													aria-hidden="true">
												</i>

											</div>


										<?php endif; ?>


									</div>


									<input
										type="file"
										name="BrandsSection[image]"
										class="brands-section-image-file"
										accept="image/jpeg,image/png,image/webp,image/gif">


									<div class="brands-section-image-hint">

										JPG, PNG, WebP o GIF.
										La imagen será optimizada para web.

									</div>


									<?php if (
										$section &&
										!empty($section->image)
									): ?>


										<div class="brands-section-image-current">

											Imagen actual:

											<?= Yii::app()->getBaseUrl() . CHtml::encode(
												$section->image
											);

											?>

										</div>


									<?php endif; ?>


								</div>


							</div>

						</div>


						<!-- ==========================================
						     SAVE BUTTON
						========================================== -->

						<div class="brands-section-form-footer">

							<button
								type="submit"
								class="brands-section-save">

								<i
									class="fas fa-save"
									aria-hidden="true">
								</i>

								Guardar

							</button>

						</div>


					</form>

				</div>

			<?php endforeach; ?>


		<?php else: ?>


			<div class="admin-crud-empty">

				<div class="admin-crud-empty__icon">

					<i
						class="fas fa-language"
						aria-hidden="true">
					</i>

				</div>

				<div class="admin-crud-empty__title">

					No hay idiomas configurados

				</div>

				<div class="admin-crud-empty__text">

					Configura al menos un idioma para administrar
					esta sección.

				</div>

			</div>


		<?php endif; ?>


	</div>


	<!-- ======================================================
	     GRID ORIGINAL
	     NO MODIFICADO
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

						Registros disponibles en el sistema.

					</p>

				</div>

			</div>

		</div>


		<div class="admin-crud-table-wrapper">

			<?php

			$this->widget(
				'zii.widgets.grid.CGridView',
				array(

					'id' =>
					'brands-grid',

					'dataProvider' =>
					$dataProvider,

					'filter' =>
					null,

					'ajaxUpdate' =>
					false,

					'enableSorting' =>
					true,

					'enablePagination' =>
					true,

					'itemsCssClass' =>
					'admin-crud-table',

					'htmlOptions' =>
					array(
						'class' =>
						'admin-crud-grid',
					),

					'template' =>
					'{items}',

					'emptyText' => '
				<div class="admin-crud-empty">

					<div class="admin-crud-empty__icon">

						<i class="fas fa-inbox"></i>

					</div>

					<div class="admin-crud-empty__title">

						No hay registros

					</div>

					<div class="admin-crud-empty__text">

						No se encontraron registros para mostrar.

					</div>

				</div>
				',

					'columns' =>
					array(

						'id',

						'name',

						'is_featured',

						'slug',

						'logo',

						'website_url',

						array(

							'class' =>
							'CButtonColumn',

							'header' =>
							'Acciones',

							'headerHtmlOptions' =>
							array(
								'class' =>
								'admin-crud-actions-column',
							),

							'htmlOptions' =>
							array(
								'class' =>
								'admin-crud-actions-column',
							),

							'template' =>
							'{update}{delete}',

							'buttons' =>
							array(

								'update' =>
								array(

									'label' =>
									'<i class="fas fa-pen" aria-hidden="true"></i>',

									'title' =>
									'Editar',

									'imageUrl' =>
									false,

									'options' =>
									array(
										'class' =>
										'admin-crud-action',

										'alt' =>
										'Editar',

										'aria-label' =>
										'Editar',
									),

								),

								'delete' =>
								array(

									'label' =>
									'<i class="fas fa-trash-alt" aria-hidden="true"></i>',

									'title' =>
									'Eliminar',

									'imageUrl' =>
									false,

									'options' =>
									array(
										'class' =>
										'admin-crud-action admin-crud-action--delete',

										'alt' =>
										'Eliminar',

										'aria-label' =>
										'Eliminar',
									),

								),

							),

							'deleteConfirmation' =>
							null,

						),

					),

				)
			);

			?>

		</div>


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

				$this->widget(
					'CLinkPager',
					array(

						'pages' =>
						$dataProvider->getPagination(),

						'header' =>
						'',

						'firstPageLabel' =>
						'«',

						'prevPageLabel' =>
						'‹',

						'nextPageLabel' =>
						'›',

						'lastPageLabel' =>
						'»',

						'maxButtonCount' =>
						5,

					)
				);

				?>

			</div>

		</div>

	</div>

</div>


<!-- ==========================================================
     DELETE MODAL ORIGINAL
========================================================== -->

<div
	id="brands-delete-modal"
	class="admin-crud-modal"
	aria-hidden="true"
	role="dialog"
	aria-modal="true"
	aria-labelledby="brands-delete-modal-title">


	<div class="admin-crud-modal__dialog">


		<div class="admin-crud-modal__header">


			<div class="admin-crud-modal__icon">

				<i
					class="fas fa-exclamation-triangle"
					aria-hidden="true">
				</i>

			</div>


			<div>

				<h3
					id="brands-delete-modal-title"
					class="admin-crud-modal__title">

					Eliminar registro

				</h3>


				<p class="admin-crud-modal__message">

					¿Está seguro de que desea eliminar este registro?
					Esta acción no se puede deshacer.

				</p>

			</div>


		</div>


		<div class="admin-crud-modal__footer">


			<button
				type="button"
				id="brands-delete-cancel"
				class="admin-crud-modal__button admin-crud-modal__button--cancel"
				title="Cancelar"
				aria-label="Cancelar">

				<i
					class="fas fa-times"
					aria-hidden="true">
				</i>

				Cancelar

			</button>


			<button
				type="button"
				id="brands-delete-confirm"
				class="admin-crud-modal__button admin-crud-modal__button--delete"
				title="Eliminar"
				aria-label="Eliminar">

				<i
					class="fas fa-trash-alt"
					aria-hidden="true">
				</i>

				Eliminar

			</button>


		</div>


	</div>


</div>