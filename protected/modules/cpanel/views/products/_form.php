<?php
/* @var $this ProductsController */
/* @var $model Products */
/* @var $form CActiveForm */

/*
 * UPDATE FORM
 *
 * Expected controller variables:
 *
 * $translations
 * $defaultLanguage
 *
 * Existing category/subcategory relationships are loaded from:
 *
 * - product_categories
 * - product_subcategories
 *
 * Existing translations are edited using:
 *
 * ProductTranslations[TRANSLATION_ID][field]
 *
 * A new translation is submitted using:
 *
 * NewProductTranslation[field]
 *
 * Categories/subcategories are submitted using:
 *
 * ProductCategorySelection[category_ids][]
 * ProductCategorySelection[subcategory_ids][]
 */

$translations = isset($translations)
	? $translations
	: ProductTranslations::model()->findAllByAttributes(
		array(
			'product_id' => (int) $model->id,
		),
		array(
			'order' => 'language_id ASC, id ASC',
		)
	);

$defaultLanguage = isset($defaultLanguage)
	? $defaultLanguage
	: Languages::model()->findByAttributes(
		array(
			'is_default' => 1,
		)
	);

/*
 * ----------------------------------------------------------
 * EXISTING PRODUCT CATEGORIES / SUBCATEGORIES
 * ----------------------------------------------------------
 */

$selectedCategoryIds = array();
$selectedSubcategoryIds = array();

$productCategoryRows = ProductCategories::model()->findAllByAttributes(
	array(
		'product_id' => (int) $model->id,
	)
);

foreach ($productCategoryRows as $productCategory) {
	$selectedCategoryIds[] = (int) $productCategory->category_id;
}

$productSubcategoryRows = ProductSubcategories::model()->findAllByAttributes(
	array(
		'product_id' => (int) $model->id,
	)
);

foreach ($productSubcategoryRows as $productSubcategory) {
	$selectedSubcategoryIds[] = (int) $productSubcategory->subcategory_id;
}

$selectedCategoryIds = array_values(
	array_unique($selectedCategoryIds)
);

$selectedSubcategoryIds = array_values(
	array_unique($selectedSubcategoryIds)
);


/*
 * ----------------------------------------------------------
 * CATEGORIES / SUBCATEGORIES
 * ----------------------------------------------------------
 *
 * Their labels come from their own translation tables.
 */

$categories = Categories::model()->findAll(
	array(
		'order' => 'sort_order ASC, id ASC',
	)
);

$subcategories = Subcategories::model()->findAll(
	array(
		'order' => 'category_id ASC, sort_order ASC, id ASC',
	)
);

$categoryIds = array();

foreach ($categories as $category) {
	$categoryIds[] = (int) $category->id;
}

$subcategoryIds = array();

foreach ($subcategories as $subcategory) {
	$subcategoryIds[] = (int) $subcategory->id;
}


/*
 * ----------------------------------------------------------
 * CATEGORY TRANSLATIONS
 * ----------------------------------------------------------
 */

$categoryTranslationsByCategory = array();

if ($categoryIds) {

	$criteria = new CDbCriteria;

	$criteria->addInCondition(
		'category_id',
		$categoryIds
	);

	if ($defaultLanguage !== null) {

		$criteria->addCondition(
			'language_id = :category_default_language_id'
		);

		$criteria->params[':category_default_language_id'] = (int) $defaultLanguage->id;
	}

	$rows = CategoryTranslations::model()->findAll(
		$criteria
	);

	foreach ($rows as $row) {

		$categoryTranslationsByCategory[(int) $row->category_id] = $row;
	}
}


/*
 * ----------------------------------------------------------
 * SUBCATEGORY TRANSLATIONS
 * ----------------------------------------------------------
 */

$subcategoryTranslationsBySubcategory = array();

if ($subcategoryIds) {

	$criteria = new CDbCriteria;

	$criteria->addInCondition(
		'subcategory_id',
		$subcategoryIds
	);

	if ($defaultLanguage !== null) {

		$criteria->addCondition(
			'language_id = :subcategory_default_language_id'
		);

		$criteria->params[':subcategory_default_language_id'] = (int) $defaultLanguage->id;
	}

	$rows = SubcategoryTranslations::model()->findAll(
		$criteria
	);

	foreach ($rows as $row) {

		$subcategoryTranslationsBySubcategory[(int) $row->subcategory_id] = $row;
	}
}


/*
 * ----------------------------------------------------------
 * TAXONOMY DATA FOR JAVASCRIPT
 * ----------------------------------------------------------
 */

$taxonomyData = array(
	'categories' => array(),
	'subcategories' => array(),
);

foreach ($categories as $category) {

	$categoryId = (int) $category->id;

	$name = isset(
		$categoryTranslationsByCategory[$categoryId]
	)
		? trim(
			(string)
			$categoryTranslationsByCategory[$categoryId]->name
		)
		: '';

	if ($name === '') {
		$name = 'Categoría #' . $categoryId;
	}

	$taxonomyData['categories'][] = array(
		'id' => $categoryId,
		'name' => $name,
	);
}

foreach ($subcategories as $subcategory) {

	$subcategoryId = (int) $subcategory->id;
	$categoryId = (int) $subcategory->category_id;

	$name = isset(
		$subcategoryTranslationsBySubcategory[$subcategoryId]
	)
		? trim(
			(string)
			$subcategoryTranslationsBySubcategory[$subcategoryId]->name
		)
		: '';

	if ($name === '') {
		$name = 'Subcategoría #' . $subcategoryId;
	}

	$categoryName = isset(
		$categoryTranslationsByCategory[$categoryId]
	)
		? trim(
			(string)
			$categoryTranslationsByCategory[$categoryId]->name
		)
		: '';

	if ($categoryName === '') {
		$categoryName = 'Categoría #' . $categoryId;
	}

	$taxonomyData['subcategories'][] = array(
		'id' => $subcategoryId,
		'name' => $name,
		'category_id' => $categoryId,
		'category_name' => $categoryName,
	);
}


/*
 * ----------------------------------------------------------
 * LANGUAGES
 * ----------------------------------------------------------
 *
 * Used by the "Add translation" selector.
 */

$languages = Languages::model()->findAll(
	array(
		'order' => 'sort_order ASC, id ASC',
	)
);

$translationLanguageIds = array();

foreach ($translations as $productTranslation) {

	$translationLanguageIds[(int) $productTranslation->language_id] = true;
}


/*
 * ----------------------------------------------------------
 * CSS
 * ----------------------------------------------------------
 */

Yii::app()->clientScript->registerCss(
	'admin-form-products-update',
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
	margin-top: 20px;
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

.admin-form-field .error {
	display: block;
	margin-top: 6px;
	color: #dc2626;
	font-size: 11px;
	line-height: 1.4;
}

.dropdown-list-new {
	display: block;
	width: 100%;
	min-height: 40px;
	padding: 0 38px 0 12px;
	box-sizing: border-box;
	border: 1px solid #b8c0cc;
	border-radius: 7px;
	background-color: #fff;
	color: #1f2937;
	font-family: inherit;
	font-size: 13px;
	font-weight: 500;
	line-height: 40px;
	cursor: pointer;
	outline: none;
	box-shadow:
		0 1px 2px rgba(0, 0, 0, .05),
		0 0 0 1px rgba(17, 24, 39, .02);
	transition:
		border-color .15s ease,
		box-shadow .15s ease,
		background-color .15s ease;
}

.dropdown-list-new:hover {
	border-color: #9ca3af;
	background-color: #fafafa;
}

.dropdown-list-new:focus {
	border-color: #6b7280;
	background-color: #fff;
	box-shadow:
		0 0 0 3px rgba(17, 24, 39, .08),
		0 1px 2px rgba(0, 0, 0, .05);
}


/* ==========================================================
   TRANSLATIONS
   ========================================================== */

.admin-product-translations {
	display: flex;
	flex-direction: column;
	gap: 14px;
}

.admin-product-translation {
	border: 1px solid #e5e7eb;
	border-radius: 8px;
	background: #fff;
	overflow: hidden;
}

.admin-product-translation__header {
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 16px;
	padding: 13px 14px;
	border-bottom: 1px solid #e5e7eb;
	background: #f9fafb;
}

.admin-product-translation__language {
	display: flex;
	align-items: center;
	gap: 9px;
	color: #111827;
	font-size: 13px;
	font-weight: 600;
}

.admin-product-translation__language i {
	color: #6b7280;
	font-size: 12px;
}

.admin-product-translation__badge {
	display: inline-flex;
	align-items: center;
	height: 22px;
	padding: 0 8px;
	border-radius: 999px;
	background: #eef2ff;
	color: #4338ca;
	font-size: 10px;
	font-weight: 700;
	letter-spacing: .04em;
	text-transform: uppercase;
}

.admin-product-translation__body {
	padding: 18px 14px;
}

.admin-product-translation__actions {
	display: flex;
	align-items: center;
	gap: 8px;
}

.admin-product-translation__remove {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	gap: 6px;
	height: 30px;
	padding: 0 9px;
	border: 1px solid #e5e7eb;
	border-radius: 6px;
	background: #fff;
	color: #6b7280;
	cursor: pointer;
	font-family: inherit;
	font-size: 11px;
	font-weight: 600;
}

.admin-product-translation__remove:hover {
	border-color: #fecaca;
	background: #fef2f2;
	color: #dc2626;
}

.admin-product-translation__empty {
	padding: 18px;
	border: 1px dashed #d1d5db;
	border-radius: 7px;
	color: #9ca3af;
	font-size: 12px;
	text-align: center;
}


/* ==========================================================
   ADD TRANSLATION
   ========================================================== */

.admin-product-add-translation {
	margin-top: 18px;
	padding-top: 18px;
	border-top: 1px solid #e5e7eb;
}

.admin-product-add-translation__header {
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 14px;
	margin-bottom: 12px;
}

.admin-product-add-translation__title {
	margin: 0;
	color: #374151;
	font-size: 12px;
	font-weight: 700;
}

.admin-product-add-translation__hint {
	margin: 3px 0 0;
	color: #9ca3af;
	font-size: 11px;
}

.admin-product-add-translation__fields {
	display: grid;
	grid-template-columns: minmax(0, 300px) 1fr;
	gap: 12px;
	align-items: end;
}

.admin-product-add-translation__button {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	gap: 7px;
	height: 40px;
	padding: 0 13px;
	border: 1px solid #d1d5db;
	border-radius: 7px;
	background: #fff;
	color: #374151;
	cursor: pointer;
	font-family: inherit;
	font-size: 12px;
	font-weight: 600;
}

.admin-product-add-translation__button:hover {
	background: #f3f4f6;
	border-color: #9ca3af;
	color: #111827;
}


/* ==========================================================
   DEFAULT LANGUAGE
   ========================================================== */

.admin-product-language {
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 14px;
	min-height: 42px;
	padding: 10px 12px;
	box-sizing: border-box;
	border: 1px solid #d1d5db;
	border-radius: 7px;
	background: #f9fafb;
}

.admin-product-language__name {
	color: #111827;
	font-size: 13px;
	font-weight: 600;
}

.admin-product-language__badge {
	display: inline-flex;
	align-items: center;
	height: 22px;
	padding: 0 8px;
	border-radius: 999px;
	background: #eef2ff;
	color: #4338ca;
	font-size: 10px;
	font-weight: 700;
	letter-spacing: .04em;
	text-transform: uppercase;
}


/* ==========================================================
   TAXONOMY
   ========================================================== */

.admin-product-taxonomy {
	border: 1px solid #e5e7eb;
	border-radius: 8px;
	background: #fff;
	overflow: hidden;
}

.admin-product-taxonomy__search {
	padding: 14px;
	border-bottom: 1px solid #e5e7eb;
	background: #f9fafb;
}

.admin-product-taxonomy__search-wrap {
	position: relative;
}

.admin-product-taxonomy__search-icon {
	position: absolute;
	top: 50%;
	left: 12px;
	color: #9ca3af;
	font-size: 12px;
	transform: translateY(-50%);
	pointer-events: none;
}

.admin-product-taxonomy__search-input {
	display: block;
	width: 100%;
	height: 40px;
	padding: 0 12px 0 34px;
	box-sizing: border-box;
	border: 1px solid #cbd5e1;
	border-radius: 7px;
	outline: none;
	background: #fff;
	color: #1f2937;
	font-family: inherit;
	font-size: 13px;
}

.admin-product-taxonomy__search-input:focus {
	border-color: #9ca3af;
	box-shadow: 0 0 0 3px rgba(17, 24, 39, .06);
}

.admin-product-taxonomy__hint {
	margin: 7px 0 0;
	color: #9ca3af;
	font-size: 11px;
	line-height: 1.4;
}

.admin-product-taxonomy__results {
	max-height: 300px;
	overflow-y: auto;
}

.admin-product-taxonomy__result {
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 14px;
	padding: 11px 14px;
	border-bottom: 1px solid #f0f1f3;
}

.admin-product-taxonomy__result:last-child {
	border-bottom: 0;
}

.admin-product-taxonomy__result:hover {
	background: #fafafa;
}

.admin-product-taxonomy__result-info {
	min-width: 0;
}

.admin-product-taxonomy__result-category {
	display: block;
	margin-bottom: 2px;
	color: #6b7280;
	font-size: 10px;
	font-weight: 600;
	letter-spacing: .04em;
	text-transform: uppercase;
}

.admin-product-taxonomy__result-name {
	display: block;
	color: #111827;
	font-size: 13px;
	font-weight: 600;
}

.admin-product-taxonomy__result-type {
	display: block;
	margin-top: 2px;
	color: #9ca3af;
	font-size: 10px;
}

.admin-product-taxonomy__add {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	gap: 6px;
	height: 30px;
	padding: 0 10px;
	flex-shrink: 0;
	border: 1px solid #d1d5db;
	border-radius: 6px;
	background: #fff;
	color: #374151;
	cursor: pointer;
	font-family: inherit;
	font-size: 11px;
	font-weight: 600;
}

.admin-product-taxonomy__add:hover {
	border-color: #9ca3af;
	background: #f3f4f6;
	color: #111827;
}

.admin-product-taxonomy__add.is-added {
	border-color: #d1d5db;
	background: #f3f4f6;
	color: #9ca3af;
	cursor: default;
}

.admin-product-taxonomy__empty {
	padding: 30px 20px;
	text-align: center;
	color: #9ca3af;
	font-size: 12px;
}

.admin-product-taxonomy__selected {
	padding: 14px;
	border-top: 1px solid #e5e7eb;
	background: #fff;
}

.admin-product-taxonomy__selected-title {
	margin: 0 0 10px;
	color: #374151;
	font-size: 11px;
	font-weight: 700;
	letter-spacing: .04em;
	text-transform: uppercase;
}

.admin-product-taxonomy__selected-empty {
	padding: 12px;
	border: 1px dashed #d1d5db;
	border-radius: 6px;
	color: #9ca3af;
	font-size: 11px;
	text-align: center;
}

.admin-product-taxonomy__selected-group {
	margin-bottom: 10px;
}

.admin-product-taxonomy__selected-group:last-child {
	margin-bottom: 0;
}

.admin-product-taxonomy__selected-category {
	margin-bottom: 5px;
	color: #374151;
	font-size: 12px;
	font-weight: 700;
}

.admin-product-taxonomy__selected-items {
	display: flex;
	flex-wrap: wrap;
	gap: 6px;
}

.admin-product-taxonomy__tag {
	display: inline-flex;
	align-items: center;
	gap: 7px;
	min-height: 28px;
	padding: 0 8px 0 10px;
	border: 1px solid #dbe1e8;
	border-radius: 999px;
	background: #f8fafc;
	color: #374151;
	font-size: 11px;
	font-weight: 600;
}

.admin-product-taxonomy__tag--category {
	background: #f3f4f6;
}

.admin-product-taxonomy__remove {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 18px;
	height: 18px;
	padding: 0;
	border: 0;
	border-radius: 50%;
	background: transparent;
	color: #9ca3af;
	cursor: pointer;
	font-size: 10px;
}

.admin-product-taxonomy__remove:hover {
	background: #e5e7eb;
	color: #dc2626;
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

	.admin-form-status {
		width: 100%;
		justify-content: space-between;
		padding-top: 12px;
		border-top: 1px solid #f0f1f3;
	}

	.admin-form-fields,
	.admin-product-add-translation__fields {
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
'
);


/*
 * ----------------------------------------------------------
 * JAVASCRIPT
 * ----------------------------------------------------------
 *
 * Existing taxonomy selections are initialized from the
 * product's current relations.
 */

Yii::app()->clientScript->registerScript(
	'admin-product-update-form',
	"
var productUpdateTaxonomy = " . CJSON::encode(
		array(
			'categories' => $taxonomyData['categories'],
			'subcategories' => $taxonomyData['subcategories'],
			'selectedCategories' => $selectedCategoryIds,
			'selectedSubcategories' => $selectedSubcategoryIds,
		)
	) . ";

(function($) {

	var selectedCategories = {};
	var selectedSubcategories = {};

	var searchInput =
		$('#product-taxonomy-search');

	var results =
		$('#product-taxonomy-results');

	var selected =
		$('#product-taxonomy-selected');

	var hidden =
		$('#product-category-selection');

	function escapeHtml(value) {

		return $('<div>')
			.text(
				value == null
					? ''
					: String(value)
			)
			.html();
	}

	function getCategory(id) {

		id = String(id);

		for (
			var i = 0;
			i < productUpdateTaxonomy.categories.length;
			i++
		) {

			if (
				String(
					productUpdateTaxonomy.categories[i].id
				) === id
			) {
				return productUpdateTaxonomy.categories[i];
			}
		}

		return null;
	}

	function getSubcategory(id) {

		id = String(id);

		for (
			var i = 0;
			i < productUpdateTaxonomy.subcategories.length;
			i++
		) {

			if (
				String(
					productUpdateTaxonomy.subcategories[i].id
				) === id
			) {
				return productUpdateTaxonomy.subcategories[i];
			}
		}

		return null;
	}

	function addCategory(id) {

		id = String(id);

		if (!getCategory(id)) {
			return;
		}

		selectedCategories[id] = true;

		render();
	}

	function removeCategory(id) {

		id = String(id);

		delete selectedCategories[id];

		/*
		 * Remove all subcategories belonging to this
		 * category because the parent relation is removed.
		 */
		$.each(
			selectedSubcategories,
			function(subcategoryId) {

				var subcategory =
					getSubcategory(subcategoryId);

				if (
					subcategory &&
					String(subcategory.category_id) === id
				) {
					delete selectedSubcategories[
						subcategoryId
					];
				}
			}
		);

		render();
	}

	function addSubcategory(id) {

		id = String(id);

		var subcategory =
			getSubcategory(id);

		if (!subcategory) {
			return;
		}

		selectedSubcategories[id] = true;

		/*
		 * Selecting a subcategory automatically selects
		 * its parent category.
		 */
		selectedCategories[
			String(subcategory.category_id)
		] = true;

		render();
	}

	function removeSubcategory(id) {

		id = String(id);

		delete selectedSubcategories[id];

		var removed =
			getSubcategory(id);

		if (removed) {

			var parentId =
				String(removed.category_id);

			var hasSibling = false;

			$.each(
				selectedSubcategories,
				function(otherId) {

					var other =
						getSubcategory(otherId);

					if (
						other &&
						String(other.category_id) === parentId
					) {
						hasSibling = true;
						return false;
					}
				}
			);

			if (!hasSibling) {
				delete selectedCategories[parentId];
			}
		}

		render();
	}

	function syncHiddenFields() {

		hidden.empty();

		$.each(
			selectedCategories,
			function(id) {

				$('<input>', {
					type: 'hidden',
					name:
						'ProductCategorySelection[category_ids][]',
					value: id
				}).appendTo(hidden);
			}
		);

		$.each(
			selectedSubcategories,
			function(id) {

				$('<input>', {
					type: 'hidden',
					name:
						'ProductCategorySelection[subcategory_ids][]',
					value: id
				}).appendTo(hidden);
			}
		);
	}

	function renderSelected() {

		var groups = {};

		$.each(
			selectedCategories,
			function(categoryId) {

				var category =
					getCategory(categoryId);

				if (!category) {
					return;
				}

				groups[categoryId] = {
					category: category,
					subcategories: []
				};
			}
		);

		$.each(
			selectedSubcategories,
			function(subcategoryId) {

				var subcategory =
					getSubcategory(subcategoryId);

				if (!subcategory) {
					return;
				}

				var categoryId =
					String(subcategory.category_id);

				if (!groups[categoryId]) {

					var category =
						getCategory(categoryId);

					if (!category) {
						return;
					}

					groups[categoryId] = {
						category: category,
						subcategories: []
					};
				}

				groups[categoryId]
					.subcategories
					.push(subcategory);
			}
		);

		var html = '';

		if ($.isEmptyObject(groups)) {

			selected.html(
				'<div class=\"admin-product-taxonomy__selected-empty\">' +
				'No hay categorías ni subcategorías seleccionadas.' +
				'</div>'
			);

			return;
		}

		$.each(
			groups,
			function(categoryId, group) {

				html +=
					'<div class=\"admin-product-taxonomy__selected-group\">';

				html +=
					'<div class=\"admin-product-taxonomy__selected-category\">' +
					escapeHtml(group.category.name) +
					'</div>';

				html +=
					'<div class=\"admin-product-taxonomy__selected-items\">';

				html +=
					'<span class=\"admin-product-taxonomy__tag admin-product-taxonomy__tag--category\">' +
					escapeHtml(group.category.name) +
					'<button type=\"button\" class=\"admin-product-taxonomy__remove\" ' +
					'data-type=\"category\" data-id=\"' +
					escapeHtml(categoryId) +
					'\" title=\"Quitar categoría\" aria-label=\"Quitar categoría\">' +
					'<i class=\"fas fa-times\"></i>' +
					'</button>' +
					'</span>';

				$.each(
					group.subcategories,
					function(index, subcategory) {

						html +=
							'<span class=\"admin-product-taxonomy__tag\">' +
							escapeHtml(subcategory.name) +
							'<button type=\"button\" class=\"admin-product-taxonomy__remove\" ' +
							'data-type=\"subcategory\" data-id=\"' +
							escapeHtml(subcategory.id) +
							'\" title=\"Quitar subcategoría\" aria-label=\"Quitar subcategoría\">' +
							'<i class=\"fas fa-times\"></i>' +
							'</button>' +
							'</span>';
					}
				);

				html += '</div></div>';
			}
		);

		selected.html(html);
	}

	function renderResults() {

		var term =
			$.trim(
				searchInput.val()
			).toLowerCase();

		var html = '';
		var count = 0;

		$.each(
			productUpdateTaxonomy.categories,
			function(index, category) {

				if (
					term !== '' &&
					category.name
						.toLowerCase()
						.indexOf(term) === -1
				) {
					return;
				}

				count++;

				var id =
					String(category.id);

				var isAdded =
					!!selectedCategories[id];

				html +=
					'<div class=\"admin-product-taxonomy__result\">' +
					'<div class=\"admin-product-taxonomy__result-info\">' +
					'<span class=\"admin-product-taxonomy__result-category\">Categoría</span>' +
					'<span class=\"admin-product-taxonomy__result-name\">' +
					escapeHtml(category.name) +
					'</span>' +
					'<span class=\"admin-product-taxonomy__result-type\">Categoría principal</span>' +
					'</div>' +
					'<button type=\"button\" class=\"admin-product-taxonomy__add' +
					(isAdded ? ' is-added' : '') +
					'\" data-type=\"category\" data-id=\"' +
					escapeHtml(id) +
					'\"' +
					(isAdded ? ' disabled' : '') +
					'>' +
					(
						isAdded
							? '<i class=\"fas fa-check\"></i> Agregado'
							: '<i class=\"fas fa-plus\"></i> Agregar'
					) +
					'</button>' +
					'</div>';
			}
		);

		$.each(
			productUpdateTaxonomy.subcategories,
			function(index, subcategory) {

				var searchable = (
					subcategory.name +
					' ' +
					subcategory.category_name
				).toLowerCase();

				if (
					term !== '' &&
					searchable.indexOf(term) === -1
				) {
					return;
				}

				count++;

				var id =
					String(subcategory.id);

				var isAdded =
					!!selectedSubcategories[id];

				html +=
					'<div class=\"admin-product-taxonomy__result\">' +
					'<div class=\"admin-product-taxonomy__result-info\">' +
					'<span class=\"admin-product-taxonomy__result-category\">' +
					escapeHtml(subcategory.category_name) +
					'</span>' +
					'<span class=\"admin-product-taxonomy__result-name\">' +
					escapeHtml(subcategory.name) +
					'</span>' +
					'<span class=\"admin-product-taxonomy__result-type\">Subcategoría</span>' +
					'</div>' +
					'<button type=\"button\" class=\"admin-product-taxonomy__add' +
					(isAdded ? ' is-added' : '') +
					'\" data-type=\"subcategory\" data-id=\"' +
					escapeHtml(id) +
					'\"' +
					(isAdded ? ' disabled' : '') +
					'>' +
					(
						isAdded
							? '<i class=\"fas fa-check\"></i> Agregado'
							: '<i class=\"fas fa-plus\"></i> Agregar'
					) +
					'</button>' +
					'</div>';
			}
		);

		if (count === 0) {

			html =
				'<div class=\"admin-product-taxonomy__empty\">' +
				'No se encontraron categorías ni subcategorías.' +
				'</div>';
		}

		results.html(html);
	}

	function render() {

		syncHiddenFields();
		renderSelected();
		renderResults();
	}

	/*
	 * Initialize existing relationships.
	 */
	$.each(
		productUpdateTaxonomy.selectedCategories,
		function(index, id) {

			selectedCategories[
				String(id)
			] = true;
		}
	);

	$.each(
		productUpdateTaxonomy.selectedSubcategories,
		function(index, id) {

			selectedSubcategories[
				String(id)
			] = true;
		}
	);

	$(document).on(
		'input',
		'#product-taxonomy-search',
		renderResults
	);

	$(document).on(
		'click',
		'#product-taxonomy-results .admin-product-taxonomy__add',
		function(e) {

			e.preventDefault();

			var type =
				$(this).attr('data-type');

			var id =
				$(this).attr('data-id');

			if (type === 'category') {
				addCategory(id);
			}

			if (type === 'subcategory') {
				addSubcategory(id);
			}

			return false;
		}
	);

	$(document).on(
		'click',
		'#product-taxonomy-selected .admin-product-taxonomy__remove',
		function(e) {

			e.preventDefault();

			var type =
				$(this).attr('data-type');

			var id =
				$(this).attr('data-id');

			if (type === 'category') {
				removeCategory(id);
			}

			if (type === 'subcategory') {
				removeSubcategory(id);
			}

			return false;
		}
	);

	/*
	 * New translation language.
	 *
	 * This only exposes the language selector. The actual
	 * translation record is submitted to the controller.
	 */
	$('#new-product-translation-language').on(
		'change',
		function() {

			var languageId =
				$(this).val();

			if (!languageId) {
				$('#new-product-translation-fields')
					.addClass('is-hidden');
				return;
			}

			$('#new-product-translation-fields')
				.removeClass('is-hidden');
		}
	);

	$('#add-product-translation-button').on(
		'click',
		function(e) {

			e.preventDefault();

			var button = $(this);

			var languageSelect =
				$('#new-product-translation-language');

			var languageId =
				languageSelect.val();

			if (!languageId) {
				languageSelect.focus();
				return false;
			}

			var languageName =
				languageSelect
					.find('option:selected')
					.text();

			$('#new-product-translation-language-value')
				.val(languageId);

			$('#new-product-translation-language-name')
				.text($.trim(languageName));

			$('#new-product-translation-fields')
				.stop(true, true)
				.slideDown(150);

			button.prop('disabled', true);
			languageSelect.prop('disabled', true);

			return false;
		}
	);

	render();

})(jQuery);
"
);
?>

<div class="admin-form-page">

	<?php
	$form = $this->beginWidget(
		'CActiveForm',
		array(
			'id' => 'products-update-form',
			'enableAjaxValidation' => false,
			'htmlOptions' => array(
				'class' => 'admin-form',
			),
		)
	);
	?>

	<!-- ======================================================
	     PRODUCT INFORMATION
	     ====================================================== -->

	<div class="admin-form-card">

		<div class="admin-form-card__header">

			<div class="admin-form-card__heading">

				<div class="admin-form-card__icon">
					<i class="fas fa-pen"></i>
				</div>

				<div>

					<h2 class="admin-form-card__title">
						Información del producto
					</h2>

					<p class="admin-form-card__description">
						Actualiza la información principal del producto.
					</p>

				</div>

			</div>

			<div class="admin-form-status">

				<div class="admin-form-status__item">

					<div class="admin-form-status__text">

						<span class="admin-form-status__label">
							<?= $form->labelEx($model, 'status'); ?>
						</span>

					</div>

					<?= $form->dropDownList(
						$model,
						'status',
						array(
							'publicado' => 'Publicado',
							'borrador' => 'Borrador',
							'deshabilitado' => 'Deshabilitado',
							'eliminado' => 'Eliminado',
						),
						array(
							'class' => 'dropdown-list-new',
						)
					); ?>

				</div>

			</div>

		</div>

		<div class="admin-form-card__body">

			<?= $form->errorSummary(
				$model,
				'<strong>Por favor verifica la información:</strong>'
			); ?>

			<div class="admin-form-fields">

				<div class="admin-form-field">

					<?= $form->labelEx($model, 'brand_id'); ?>

					<?= $form->dropDownList(
						$model,
						'brand_id',
						CHtml::listData(
							Brands::model()->findAll(
								array(
									'order' => 'name ASC',
								)
							),
							'id',
							'name'
						),
						array(
							'class' => 'form-control',
							'prompt' => 'Seleccione una marca',
						)
					); ?>

					<?= $form->error($model, 'brand_id'); ?>

				</div>


				<div class="admin-form-field">

					<?= $form->labelEx(
						$model,
						'sort_order'
					); ?>

					<?= $form->textField(
						$model,
						'sort_order',
						array(
							'class' => 'form-control',
						)
					); ?>

					<?= $form->error(
						$model,
						'sort_order'
					); ?>

				</div>


				<div class="admin-form-field admin-form-field--full">

					<?= $form->labelEx(
						$model,
						'main_image'
					); ?>

					<?= $form->textField(
						$model,
						'main_image',
						array(
							'class' => 'form-control',
							'maxlength' => 255,
						)
					); ?>

					<?= $form->error(
						$model,
						'main_image'
					); ?>

				</div>


				<div class="admin-form-field admin-form-field--full">

					<?= $form->labelEx(
						$model,
						'infographic_image'
					); ?>

					<?= $form->textField(
						$model,
						'infographic_image',
						array(
							'class' => 'form-control',
							'maxlength' => 255,
						)
					); ?>

					<?= $form->error(
						$model,
						'infographic_image'
					); ?>

				</div>


				<div class="admin-form-field admin-form-field--full">

					<?= $form->labelEx(
						$model,
						'slug'
					); ?>

					<?= $form->textField(
						$model,
						'slug',
						array(
							'class' => 'form-control',
							'maxlength' => 255,
						)
					); ?>

					<?= $form->error(
						$model,
						'slug'
					); ?>

				</div>

			</div>

		</div>

	</div>


	<!-- ======================================================
	     TRANSLATIONS
	     ====================================================== -->

	<div class="admin-form-card">

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
						Edita las traducciones existentes o agrega un nuevo idioma.
					</p>

				</div>

			</div>

		</div>

		<div class="admin-form-card__body">

			<div class="admin-product-translations">

				<?php if ($translations): ?>

					<?php foreach ($translations as $translation): ?>

						<?php
						$translationLanguage =
							Languages::model()->findByPk(
								(int) $translation->language_id
							);

						$isDefaultTranslation =
							$defaultLanguage !== null &&
							(int) $translation->language_id ===
							(int) $defaultLanguage->id;
						?>

						<div class="admin-product-translation">

							<div class="admin-product-translation__header">

								<div class="admin-product-translation__language">

									<i class="fas fa-language"></i>

									<span>
										<?php
										echo $translationLanguage !== null
											? CHtml::encode(
												$translationLanguage->name
											)
											: 'Idioma #' .
											(int) $translation->language_id;
										?>
									</span>

									<?php if ($isDefaultTranslation): ?>

										<span class="admin-product-translation__badge">
											Predeterminado
										</span>

									<?php endif; ?>

								</div>

							</div>

							<div class="admin-product-translation__body">

								<input
									type="hidden"
									name="ProductTranslations[<?= (int) $translation->id; ?>][id]"
									value="<?= (int) $translation->id; ?>">

								<input
									type="hidden"
									name="ProductTranslations[<?= (int) $translation->id; ?>][language_id]"
									value="<?= (int) $translation->language_id; ?>">

								<div class="admin-form-fields" style="grid-template-columns: repeat(3, minmax(0, 1fr));">

									<div class="admin-form-field admin-form-field--full">

										<label>
											Name
										</label>

										<input
											type="text"
											class="form-control"
											name="ProductTranslations[<?= (int) $translation->id; ?>][name]"
											value="<?= CHtml::encode($translation->name); ?>"
											maxlength="255">

									</div>


									<div class="admin-form-field admin-form-field--full">

										<label>
											Short Description
										</label>

										<textarea
											class="form-control"
											name="ProductTranslations[<?= (int) $translation->id; ?>][short_description]"><?= CHtml::encode($translation->short_description); ?></textarea>

									</div>

									<div class="admin-form-field admin-form-field--full">

										<label>
											Description
										</label>

										<textarea
											class="form-control"
											name="ProductTranslations[<?= (int) $translation->id; ?>][description]"><?= CHtml::encode($translation->description); ?></textarea>

									</div>

									<div class="admin-form-field">

										<label>
											Name Size
										</label>

										<input
											type="text"
											class="form-control"
											name="ProductTranslations[<?= (int) $translation->id; ?>][name_size]"
											value="<?= CHtml::encode($translation->name_size); ?>"
											maxlength="20">

									</div>

									<div class="admin-form-field">

										<label>
											Short Description Size
										</label>

										<input
											type="text"
											class="form-control"
											name="ProductTranslations[<?= (int) $translation->id; ?>][short_description_size]"
											value="<?= CHtml::encode($translation->short_description_size); ?>"
											maxlength="20">

									</div>

									<div class="admin-form-field">

										<label>
											Description Size
										</label>

										<input
											type="text"
											class="form-control"
											name="ProductTranslations[<?= (int) $translation->id; ?>][description_size]"
											value="<?= CHtml::encode($translation->description_size); ?>"
											maxlength="20">

									</div>

								</div>

							</div>

						</div>

					<?php endforeach; ?>

				<?php else: ?>

					<div class="admin-product-translation__empty">
						Este producto todavía no tiene traducciones.
					</div>

				<?php endif; ?>

			</div>


			<!-- ==================================================
			     ADD TRANSLATION
			     ================================================== -->

			<div class="admin-product-add-translation">

				<div class="admin-product-add-translation__header">

					<div>

						<h3 class="admin-product-add-translation__title">
							Agregar traducción
						</h3>

						<p class="admin-product-add-translation__hint">
							Selecciona un idioma que todavía no tenga traducción.
						</p>

					</div>

				</div>

				<div class="admin-product-add-translation__fields">

					<div class="admin-form-field">

						<label
							for="new-product-translation-language">
							Idioma
						</label>

						<select
							id="new-product-translation-language"
							class="dropdown-list-new"
							name="NewProductTranslation[language_id]">

							<option value="">
								Seleccione un idioma
							</option>

							<?php foreach ($languages as $language): ?>

								<?php
								$languageId =
									(int) $language->id;

								if (
									isset(
										$translationLanguageIds[$languageId]
									)
								) {
									continue;
								}
								?>

								<option value="<?= $languageId; ?>">
									<?= CHtml::encode(
										$language->name
									); ?>
								</option>

							<?php endforeach; ?>

						</select>

					</div>

					<div>

						<button
							type="button"
							id="add-product-translation-button"
							class="admin-product-add-translation__button">

							<i class="fas fa-plus"></i>
							Preparar traducción

						</button>

					</div>

				</div>

				<div
					id="new-product-translation-fields"
					class="admin-product-translation"
					style="display: none; margin-top: 14px;">

					<div class="admin-product-translation__header">

						<div class="admin-product-translation__language">

							<i class="fas fa-language"></i>

							<span
								id="new-product-translation-language-name">
							</span>

						</div>

					</div>

					<div class="admin-product-translation__body">

						<input
							type="hidden"
							id="new-product-translation-language-value"
							name="NewProductTranslation[language_id]"
							value="">

						<div class="admin-form-fields" style="grid-template-columns: repeat(3, minmax(0, 1fr));">

							<div class="admin-form-field admin-form-field--full">

								<label>
									Name
								</label>

								<input
									type="text"
									class="form-control"
									name="NewProductTranslation[name]"
									maxlength="255">

							</div>

							<div class="admin-form-field admin-form-field--full">

								<label>
									Short Description
								</label>

								<textarea
									class="form-control"
									name="NewProductTranslation[short_description]"></textarea>

							</div>

							<div class="admin-form-field admin-form-field--full">

								<label>
									Description
								</label>

								<textarea
									class="form-control"
									name="NewProductTranslation[description]"></textarea>

							</div>

							<div class="admin-form-field">

								<label>
									Name Size
								</label>

								<input
									type="text"
									class="form-control"
									name="NewProductTranslation[name_size]"
									maxlength="20">

							</div>

							<div class="admin-form-field">

								<label>
									Short Description Size
								</label>

								<input
									type="text"
									class="form-control"
									name="NewProductTranslation[short_description_size]"
									maxlength="20">

							</div>

							<div class="admin-form-field">

								<label>
									Description Size
								</label>

								<input
									type="text"
									class="form-control"
									name="NewProductTranslation[description_size]"
									maxlength="20">

							</div>

						</div>

					</div>

				</div>

			</div>

		</div>

	</div>


	<!-- ======================================================
	     CATEGORIES / SUBCATEGORIES
	     ====================================================== -->

	<div class="admin-form-card">

		<div class="admin-form-card__header">

			<div class="admin-form-card__heading">

				<div class="admin-form-card__icon">
					<i class="fas fa-sitemap"></i>
				</div>

				<div>

					<h2 class="admin-form-card__title">
						Categorías y subcategorías
					</h2>

					<p class="admin-form-card__description">
						Actualiza las categorías y subcategorías del producto.
					</p>

				</div>

			</div>

		</div>

		<div class="admin-form-card__body">

			<div class="admin-product-taxonomy">

				<div class="admin-product-taxonomy__search">

					<div class="admin-product-taxonomy__search-wrap">

						<i
							class="fas fa-search admin-product-taxonomy__search-icon"
							aria-hidden="true"></i>

						<input
							type="search"
							id="product-taxonomy-search"
							class="admin-product-taxonomy__search-input"
							placeholder="Buscar categoría o subcategoría..."
							autocomplete="off">

					</div>

					<p class="admin-product-taxonomy__hint">
						Las relaciones actuales aparecen seleccionadas. Al agregar una subcategoría, su categoría padre se asigna automáticamente.
					</p>

				</div>

				<div
					id="product-taxonomy-results"
					class="admin-product-taxonomy__results">
				</div>

				<div class="admin-product-taxonomy__selected">

					<h3 class="admin-product-taxonomy__selected-title">
						Seleccionados
					</h3>

					<div id="product-taxonomy-selected"></div>

				</div>

			</div>

			<div id="product-category-selection"></div>

		</div>

	</div>


	<!-- ======================================================
	     FOOTER
	     ====================================================== -->

	<div class="admin-form-card">

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
					Guardar cambios

				</button>

			</div>

		</div>

	</div>

	<?php $this->endWidget(); ?>

</div>