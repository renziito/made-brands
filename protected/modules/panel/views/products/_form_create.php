<?php
/* @var $this ProductsController */
/* @var $model Products */
/* @var $form CActiveForm */
/* @var $translation ProductTranslations */
/* @var $defaultLanguage Languages */


/*
 * ----------------------------------------------------------
 * DEFAULT LANGUAGE
 * ----------------------------------------------------------
 */

$defaultLanguage = isset($defaultLanguage)
	? $defaultLanguage
	: Languages::model()->findByAttributes(
		array(
			'is_default' => 1,
		)
	);


/*
 * ----------------------------------------------------------
 * CATEGORIES / SUBCATEGORIES
 * ----------------------------------------------------------
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

		$criteria->params[':category_default_language_id'] =
			(int) $defaultLanguage->id;
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

		$criteria->params[':subcategory_default_language_id'] =
			(int) $defaultLanguage->id;
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
 * TAXONOMY DATA
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
 */

$languages = Languages::model()->findAll(
	array(
		'order' => 'sort_order ASC, id ASC',
	)
);


/*
 * ----------------------------------------------------------
 * CSS
 * ----------------------------------------------------------
 */

// Select2 para buscar marcas conservando el campo original de Yii.
Yii::app()->clientScript->registerCssFile('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css');
Yii::app()->clientScript->registerScriptFile('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/es.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScript(
	'product-brand-select2',
	"jQuery(function($) {
        var \$brand = $('#Products_brand_id');

        if (\$brand.length && $.fn.select2) {
            var \$brandField = \$brand.closest('.admin-form-field');

            \$brand.select2({
                width: '100%',
                language: 'es',
                placeholder: 'Seleccione una marca',
                allowClear: true,
                minimumResultsForSearch: 0,
                dropdownAutoWidth: false,
                dropdownParent: \$brandField.length ? \$brandField : \$brand.parent()
            });
        }
    });",
	CClientScript::POS_READY
);

// Ordena primero las marcas que ya tienen productos asociados.
$productTable = Products::model()->tableName();
$brandsWithProductsFirst = Brands::model()->findAll(
	array(
		'order' => "(SELECT COUNT(*) FROM {$productTable} p WHERE p.brand_id = t.id) DESC, t.name ASC",
	)
);

Yii::app()->clientScript->registerCss(
	'admin-form-products-create',
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
.select2-container .select2-choice {padding: 12px; !important }

/* Select2: estilo aislado para evitar conflictos con el CSS global del panel. */
.admin-form-field .select2-container { padding: 0px !important; display: block !important; width: 100% !important; min-width: 0; font-family: inherit !important; font-size: 13px !important; }
.admin-form-field .select2-container .select2-selection--single { position: relative; display: block; box-sizing: border-box; width: 100%; height: 40px !important; padding: 0 34px 0 0 !important; border: 1px solid #d1d5db !important; border-radius: 6px !important; background: #fff !important; box-shadow: none !important; }
.admin-form-field .select2-container .select2-selection--single .select2-selection__rendered { display: block; box-sizing: border-box; padding: 0 11px !important; color: #374151 !important; font-family: inherit !important; font-size: 13px !important; font-weight: 400 !important; line-height: 38px !important; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.admin-form-field .select2-container .select2-selection--single .select2-selection__placeholder { color: #6b7280 !important; font-weight: 400 !important; }
.admin-form-field .select2-container .select2-selection--single .select2-selection__clear { position: relative; z-index: 2; margin-right: 8px; color: #6b7280; font-size: 16px; }
.admin-form-field .select2-container .select2-selection--single .select2-selection__arrow { position: absolute !important; top: 1px !important; right: 1px !important; width: 30px !important; height: 36px !important; border: 0 !important; background: transparent !important; }
.admin-form-field .select2-container .select2-selection--single .select2-selection__arrow b { position: absolute !important; top: 50% !important; left: 50% !important; width: 0 !important; height: 0 !important; margin: -2px 0 0 -5px !important; border: 5px solid transparent !important; border-top-color: #6b7280 !important; border-bottom: 0 !important; transform: none !important; }
/* El dropdown se monta normalmente al final de BODY; normalizamos sus estilos globalmente para evitar el CSS del tema. */
body .select2-container,
body .select2-container *,
body .select2-dropdown,
body .select2-dropdown * {
    box-sizing: border-box !important;
    font-family: inherit !important;
    font-size: 13px !important;
    font-weight: 400 !important;
    font-style: normal !important;
    letter-spacing: normal !important;
    line-height: 1.5 !important;
    text-transform: none !important;
    text-shadow: none !important;
}
body .select2-dropdown {
    z-index: 99999 !important;
    border: 1px solid #d1d5db !important;
    border-radius: 6px !important;
    overflow: hidden !important;
    background: #fff !important;
    color: #374151 !important;
    box-shadow: 0 4px 12px rgba(17, 24, 39, .12) !important;
}
body .select2-container--open .select2-dropdown--below { border-top: 1px solid #d1d5db !important; }
body .select2-container--open .select2-dropdown--above { border-bottom: 1px solid #d1d5db !important; }
body .select2-container--default .select2-search--dropdown { padding: 8px !important; background: #fff !important; }
body .select2-container--default .select2-search--dropdown .select2-search__field {
    display: block !important;
    width: 100% !important;
    height: 36px !important;
    margin: 0 !important;
    padding: 7px 10px !important;
    border: 1px solid #d1d5db !important;
    border-radius: 5px !important;
    outline: none !important;
    background: #fff !important;
    color: #374151 !important;
    box-shadow: none !important;
}
body .select2-container--default .select2-search--dropdown .select2-search__field:focus { border-color: #9ca3af !important; box-shadow: 0 0 0 3px rgba(17, 24, 39, .06) !important; }
body .select2-container--default .select2-results > .select2-results__options { max-height: 260px !important; overflow-y: auto !important; background: #fff !important; }
body .select2-container--default .select2-results__option {
    min-height: 0 !important;
    margin: 0 !important;
    padding: 8px 10px !important;
    border: 0 !important;
    border-radius: 0 !important;
    background: #fff !important;
    color: #374151 !important;
    cursor: pointer !important;
}
body .select2-container--default .select2-results__option[aria-selected=true] { background: #f3f4f6 !important; color: #374151 !important; }
body .select2-container--default .select2-results__option--highlighted[aria-selected] { background: #2563eb !important; color: #fff !important; }
body .select2-container--default .select2-results__message { padding: 8px 10px !important; color: #6b7280 !important; }
/* Refuerzo de estilos dentro del campo: el dropdownParent evita que el tema global le aplique tipografía serif. */
.admin-form-field .select2-dropdown,
.admin-form-field .select2-dropdown * { font-family: Arial, Helvetica, sans-serif !important; font-size: 13px !important; font-weight: 400 !important; font-style: normal !important; letter-spacing: normal !important; line-height: 1.5 !important; text-transform: none !important; text-shadow: none !important; }
.admin-form-field .select2-dropdown { border: 1px solid #d1d5db !important; border-radius: 6px !important; background: #fff !important; color: #374151 !important; box-shadow: 0 4px 12px rgba(17, 24, 39, .12) !important; }
.admin-form-field .select2-results__options { max-height: 260px !important; background: #fff !important; }
.admin-form-field .select2-results__option { margin: 0 !important; padding: 8px 10px !important; border: 0 !important; border-radius: 0 !important; background: #fff !important; color: #374151 !important; font-family: Arial, Helvetica, sans-serif !important; font-size: 13px !important; font-weight: 400 !important; cursor: pointer !important; }
.admin-form-field .select2-results__option[aria-selected=true] { background: #f3f4f6 !important; color: #374151 !important; }
.admin-form-field .select2-results__option--highlighted[aria-selected] { background: #2563eb !important; color: #fff !important; }
.admin-form-field .select2-search--dropdown { padding: 8px !important; background: #fff !important; }
.admin-form-field .select2-search--dropdown .select2-search__field { width: 100% !important; height: 36px !important; margin: 0 !important; padding: 7px 10px !important; border: 1px solid #d1d5db !important; border-radius: 5px !important; background: #fff !important; color: #374151 !important; font-family: Arial, Helvetica, sans-serif !important; font-size: 13px !important; font-weight: 400 !important; box-shadow: none !important; }
.admin-form-field .select2-container--default.select2-container--focus .select2-selection--single,
.admin-form-field .select2-container--default.select2-container--open .select2-selection--single { border-color: #9ca3af !important; box-shadow: 0 0 0 3px rgba(17, 24, 39, .06) !important; }

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

.dropdown-list-new option {
	padding: 8px 10px;
	background: #fff;
	color: #1f2937;
	font-size: 13px;
}


/* ==========================================================
   IMAGE UPLOAD
   ========================================================== */

.admin-form-image-upload {
	display: grid;
	grid-template-columns: minmax(0, 1fr) 280px;
	gap: 18px;
	align-items: start;
}

.admin-form-image-input {
	min-width: 0;
}

.admin-form-file {
	display: block;
	width: 100%;
	box-sizing: border-box;
	padding: 10px;
	border: 1px solid #d1d5db;
	border-radius: 7px;
	background: #fff;
	color: #374151;
	font-family: inherit;
	font-size: 13px;
	cursor: pointer;
}

.admin-form-image-input .hint {
	display: block;
	margin-top: 7px;
	color: #9ca3af;
	font-size: 11px;
	line-height: 1.4;
}

.admin-form-image-preview {
	display: flex;
	align-items: center;
	justify-content: center;
	min-height: 190px;
	padding: 12px;
	box-sizing: border-box;
	border: 1px dashed #d1d5db;
	border-radius: 8px;
	background: #f9fafb;
	overflow: hidden;
}

.admin-form-image-preview img {
	display: block;
	max-width: 100%;
	max-height: 240px;
	width: auto;
	height: auto;
	object-fit: contain;
	border-radius: 6px;
}

.admin-form-image-preview__empty {
	display: flex;
	flex-direction: column;
	align-items: center;
	justify-content: center;
	gap: 8px;
	color: #9ca3af;
	font-size: 11px;
	text-align: center;
}

.admin-form-image-preview__empty i {
	font-size: 28px;
	color: #d1d5db;
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
	gap: 8px;
	color: #374151;
	font-size: 12px;
	font-weight: 600;
}

.admin-product-translation__badge {
	padding: 4px 7px;
	border-radius: 999px;
	background: #eef2ff;
	color: #4338ca;
	font-size: 10px;
	font-weight: 700;
	line-height: 1;
	text-transform: uppercase;
}

.admin-product-translation__body {
	padding: 18px 14px;
}

.admin-product-add-translation {
	margin-top: 18px;
	padding-top: 18px;
	border-top: 1px solid #e5e7eb;
}

.admin-product-add-translation__header {
	margin-bottom: 14px;
}

.admin-product-add-translation__title {
	margin: 0;
	color: #111827;
	font-size: 13px;
	font-weight: 600;
}

.admin-product-add-translation__hint {
	margin: 3px 0 0;
	color: #9ca3af;
	font-size: 11px;
}

.admin-product-add-translation__fields {
	display: grid;
	grid-template-columns: minmax(0, 300px) minmax(0, 1fr);
	gap: 12px;
	align-items: end;
}

#new-product-translation-fields {
	display: none;
}


/* ==========================================================
   TAXONOMY
   ========================================================== */

.admin-product-taxonomy {
	overflow: hidden;
	border: 1px solid #e5e7eb;
	border-radius: 8px;
	background: #fff;
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
	transform: translateY(-50%);
	color: #9ca3af;
	font-size: 12px;
	pointer-events: none;
}

.admin-product-taxonomy__search-input {
	display: block;
	width: 100%;
	height: 40px;
	padding: 0 12px 0 34px;
	box-sizing: border-box;
	border: 1px solid #d1d5db;
	border-radius: 7px;
	outline: none;
	background: #fff;
	color: #374151;
	font-family: inherit;
	font-size: 13px;
}

.admin-product-taxonomy__search-input:focus {
	border-color: #9ca3af;
	box-shadow: 0 0 0 3px rgba(17, 24, 39, .06);
}

.admin-product-taxonomy__hint {
	margin: 8px 0 0;
	color: #9ca3af;
	font-size: 10px;
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

.admin-product-taxonomy__result-info {
	min-width: 0;
}

.admin-product-taxonomy__result-name {
	display: block;
	color: #111827;
	font-size: 13px;
	font-weight: 600;
}

.admin-product-taxonomy__result-category,
.admin-product-taxonomy__result-type {
	display: block;
	color: #9ca3af;
	font-size: 10px;
	line-height: 1.4;
}

.admin-product-taxonomy__add {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	gap: 6px;
	flex-shrink: 0;
	padding: 7px 10px;
	border: 1px solid #d1d5db;
	border-radius: 6px;
	background: #fff;
	color: #374151;
	font-family: inherit;
	font-size: 11px;
	cursor: pointer;
}

.admin-product-taxonomy__add:hover {
	background: #f3f4f6;
	border-color: #9ca3af;
}

.admin-product-taxonomy__add.is-added,
.admin-product-taxonomy__add:disabled {
	background: #f3f4f6;
	color: #9ca3af;
	cursor: default;
}

.admin-product-taxonomy__empty {
	padding: 30px 20px;
	color: #9ca3af;
	font-size: 12px;
	text-align: center;
}

.admin-product-taxonomy__selected {
	padding: 14px;
	border-top: 1px solid #e5e7eb;
	background: #fff;
}

.admin-product-taxonomy__selected-title {
	margin: 0 0 9px;
	color: #374151;
	font-size: 11px;
	font-weight: 600;
}

.admin-product-taxonomy__tag {
	display: inline-flex;
	align-items: center;
	gap: 7px;
	margin: 3px;
	padding: 6px 9px;
	border: 1px solid #dbe1e8;
	border-radius: 999px;
	background: #f8fafc;
	color: #374151;
	font-size: 10px;
	line-height: 1;
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
	background: #e5e7eb;
	color: #6b7280;
	font-size: 11px;
	line-height: 1;
	cursor: pointer;
}

.admin-product-taxonomy__remove:hover {
	background: #d1d5db;
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

	.admin-form-image-upload {
		grid-template-columns: 1fr;
	}

	.admin-form-image-preview {
		order: -1;
	}
}

.select2-result-label{
	font-weight: 400 !important;
	font-size: 13px !important;
	font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", "Roboto", "Oxygen", "Ubuntu", "Cantarell", "Fira Sans", "Droid Sans", "Helvetica Neue", sans-serif; !important;
}
'
);


/*
 * ----------------------------------------------------------
 * JAVASCRIPT
 * ----------------------------------------------------------
 */

Yii::app()->clientScript->registerScript(
	'admin-product-create-form',
	"
var productCreateTaxonomy = " . CJSON::encode(
		$taxonomyData
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
			i < productCreateTaxonomy.categories.length;
			i++
		) {

			if (
				String(
					productCreateTaxonomy.categories[i].id
				) === id
			) {

				return productCreateTaxonomy.categories[i];
			}
		}

		return null;
	}


	function getSubcategory(id) {

		id = String(id);

		for (
			var i = 0;
			i < productCreateTaxonomy.subcategories.length;
			i++
		) {

			if (
				String(
					productCreateTaxonomy.subcategories[i].id
				) === id
			) {

				return productCreateTaxonomy.subcategories[i];
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

				delete selectedCategories[
					parentId
				];
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

		var html = '';


		$.each(
			selectedCategories,
			function(id) {

				var category =
					getCategory(id);

				if (!category) {
					return;
				}

				html +=
					'<span class=\"admin-product-taxonomy__tag\">' +
						escapeHtml(category.name) +
						'<button type=\"button\" ' +
						'class=\"admin-product-taxonomy__remove\" ' +
						'data-type=\"category\" ' +
						'data-id=\"' +
						escapeHtml(id) +
						'\">' +
						'&times;' +
						'</button>' +
					'</span>';
			}
		);


		$.each(
			selectedSubcategories,
			function(id) {

				var subcategory =
					getSubcategory(id);

				if (!subcategory) {
					return;
				}

				html +=
					'<span class=\"admin-product-taxonomy__tag\">' +
						escapeHtml(subcategory.name) +
						'<button type=\"button\" ' +
						'class=\"admin-product-taxonomy__remove\" ' +
						'data-type=\"subcategory\" ' +
						'data-id=\"' +
						escapeHtml(id) +
						'\">' +
						'&times;' +
						'</button>' +
					'</span>';
			}
		);


		if (html === '') {

			html =
				'<span style=\"color:#9ca3af;font-size:11px;\">' +
					'No hay categorías seleccionadas.' +
				'</span>';
		}


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
			productCreateTaxonomy.categories,
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

							'<span class=\"admin-product-taxonomy__result-category\">' +
								'Categoría' +
							'</span>' +

							'<span class=\"admin-product-taxonomy__result-name\">' +
								escapeHtml(category.name) +
							'</span>' +

							'<span class=\"admin-product-taxonomy__result-type\">' +
								'Categoría principal' +
							'</span>' +

						'</div>' +

						'<button type=\"button\" ' +
							'class=\"admin-product-taxonomy__add' +
							(isAdded ? ' is-added' : '') +
							'\" ' +
							'data-type=\"category\" ' +
							'data-id=\"' +
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
			productCreateTaxonomy.subcategories,
			function(index, subcategory) {

				var searchable =
					(
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
								escapeHtml(
									subcategory.category_name
								) +
							'</span>' +

							'<span class=\"admin-product-taxonomy__result-name\">' +
								escapeHtml(
									subcategory.name
								) +
							'</span>' +

							'<span class=\"admin-product-taxonomy__result-type\">' +
								'Subcategoría' +
							'</span>' +

						'</div>' +

						'<button type=\"button\" ' +
							'class=\"admin-product-taxonomy__add' +
							(isAdded ? ' is-added' : '') +
							'\" ' +
							'data-type=\"subcategory\" ' +
							'data-id=\"' +
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
	 * Taxonomy events.
	 */

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
	 * ----------------------------------------------------------
	 * IMAGE PREVIEW
	 * ----------------------------------------------------------
	 */

	function previewImage(
		input,
		previewSelector
	) {

		if (
			!input.files ||
			!input.files[0]
		) {

			return;
		}


		var file =
			input.files[0];


		if (
			[
				'image/jpeg',
				'image/png',
				'image/webp'
			].indexOf(file.type) === -1
		) {

			input.value = '';

			alert(
				'Solo se permiten imágenes JPG, PNG o WebP.'
			);

			return;
		}


		var reader =
			new FileReader();


		reader.onload =
			function(e) {

				$(previewSelector).html(
					'<img src=\"' +
						e.target.result +
						'\" alt=\"Vista previa\">'
				);
			};


		reader.readAsDataURL(
			file
		);
	}


	$('#product-main-image-input').on(
		'change',
		function() {

			previewImage(
				this,
				'#product-main-image-preview'
			);
		}
	);


	$('#product-infographic-image-input').on(
		'change',
		function() {

			previewImage(
				this,
				'#product-infographic-image-preview'
			);
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
			'id' => 'products-create-form',
			'enableAjaxValidation' => false,
			'htmlOptions' => array(
				'class' => 'admin-form',
				'enctype' => 'multipart/form-data',
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
					<i class="fas fa-box"></i>
				</div>

				<div>

					<h2 class="admin-form-card__title">
						Información del producto
					</h2>

					<p class="admin-form-card__description">
						Completa la información principal del nuevo producto.
					</p>

				</div>

			</div>


			<div class="admin-form-status">

				<div class="admin-form-status__item">

					<div class="admin-form-status__text">

						<span class="admin-form-status__label">
							<?= $form->labelEx(
								$model,
								'status'
							); ?>
						</span>

					</div>


					<?= $form->dropDownList(
						$model,
						'status',
						array(
							'publicado' =>
							'Publicado',

							'borrador' =>
							'Borrador',

							'deshabilitado' =>
							'Deshabilitado',

							'eliminado' =>
							'Eliminado',
						),
						array(
							'class' =>
							'dropdown-list-new',

							'options' => array(
								'borrador' =>
								array(
									'selected' =>
									$model->status === null ||
										$model->status === ''
								),
							),
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

					<?= $form->labelEx(
						$model,
						'brand_id'
					); ?>


					<?= $form->dropDownList(
						$model,
						'brand_id',
						CHtml::listData(
							$brandsWithProductsFirst,
							'id',
							'name'
						),
						array(
							'class' =>
							'form-control js-brand-select2',

							'prompt' =>
							'Seleccione una marca',
						)
					); ?>


					<?= $form->error(
						$model,
						'brand_id'
					); ?>

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
							'class' =>
							'form-control',

							'value' =>
							$model->sort_order !== null
								? $model->sort_order
								: 0,
						)
					); ?>


					<?= $form->error(
						$model,
						'sort_order'
					); ?>

				</div>


				<!-- ==================================================
				     MAIN IMAGE
				     ================================================== -->

				<div class="admin-form-field admin-form-field--full">

					<?= $form->labelEx(
						$model,
						'main_image'
					); ?>


					<div class="admin-form-image-upload">

						<div class="admin-form-image-input">

							<?= $form->fileField(
								$model,
								'main_image',
								array(
									'class' =>
									'admin-form-file',

									'id' =>
									'product-main-image-input',

									'accept' =>
									'image/jpeg,image/png,image/webp',
								)
							); ?>


							<?= $form->error(
								$model,
								'main_image'
							); ?>


							<span class="hint">
								Selecciona una imagen JPG, PNG o WebP.
								La imagen se optimizará sin cambiar
								su ancho ni su alto.
							</span>

						</div>


						<div
							id="product-main-image-preview"
							class="admin-form-image-preview">

							<div class="admin-form-image-preview__empty">

								<i class="fas fa-image"></i>

								<span>
									La vista previa aparecerá aquí
								</span>

							</div>

						</div>

					</div>

				</div>


				<!-- ==================================================
				     INFOGRAPHIC IMAGE
				     ================================================== -->

				<div class="admin-form-field admin-form-field--full">

					<?= $form->labelEx(
						$model,
						'infographic_image'
					); ?>


					<div class="admin-form-image-upload">

						<div class="admin-form-image-input">

							<?= $form->fileField(
								$model,
								'infographic_image',
								array(
									'class' =>
									'admin-form-file',

									'id' =>
									'product-infographic-image-input',

									'accept' =>
									'image/jpeg,image/png,image/webp',
								)
							); ?>


							<?= $form->error(
								$model,
								'infographic_image'
							); ?>


							<span class="hint">
								Selecciona una imagen JPG, PNG o WebP.
								La imagen se optimizará sin cambiar
								su ancho ni su alto.
							</span>

						</div>


						<div
							id="product-infographic-image-preview"
							class="admin-form-image-preview">

							<div class="admin-form-image-preview__empty">

								<i class="fas fa-image"></i>

								<span>
									La vista previa aparecerá aquí
								</span>

							</div>

						</div>

					</div>

				</div>


				<!-- ==================================================
				     SLUG
				     ================================================== -->

				<div class="admin-form-field admin-form-field--full">

					<?= $form->labelEx(
						$model,
						'slug'
					); ?>


					<?= $form->textField(
						$model,
						'slug',
						array(
							'class' =>
							'form-control',

							'maxlength' =>
							255,
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
	     DEFAULT LANGUAGE TRANSLATION
	     ====================================================== -->

	<div class="admin-form-card">

		<div class="admin-form-card__header">

			<div class="admin-form-card__heading">

				<div class="admin-form-card__icon">
					<i class="fas fa-language"></i>
				</div>

				<div>

					<h2 class="admin-form-card__title">
						Traducción
					</h2>

					<p class="admin-form-card__description">
						Información del producto en el idioma predeterminado.
					</p>

				</div>

			</div>

		</div>


		<div class="admin-form-card__body">

			<div class="admin-product-translation">

				<div class="admin-product-translation__header">

					<div class="admin-product-translation__language">

						<i class="fas fa-language"></i>

						<span>

							<?php
							if ($defaultLanguage !== null) {

								echo CHtml::encode(
									$defaultLanguage->name
								);
							} else {

								echo 'Idioma predeterminado';
							}
							?>

						</span>


						<span class="admin-product-translation__badge">
							Predeterminado
						</span>

					</div>

				</div>


				<div class="admin-product-translation__body">

					<div
						class="admin-form-fields"
						style="grid-template-columns: repeat(3, minmax(0, 1fr));">


						<div class="admin-form-field admin-form-field--full">

							<?= $form->labelEx(
								$translation,
								'name'
							); ?>


							<?= $form->textField(
								$translation,
								'name',
								array(
									'class' =>
									'form-control',

									'maxlength' =>
									255,
								)
							); ?>


							<?= $form->error(
								$translation,
								'name'
							); ?>

						</div>


						<div class="admin-form-field admin-form-field--full">

							<?= $form->labelEx(
								$translation,
								'short_description'
							); ?>


							<?= $form->textArea(
								$translation,
								'short_description',
								array(
									'class' =>
									'form-control',
								)
							); ?>


							<?= $form->error(
								$translation,
								'short_description'
							); ?>

						</div>


						<div class="admin-form-field admin-form-field--full">

							<?= $form->labelEx(
								$translation,
								'description'
							); ?>


							<?= $form->textArea(
								$translation,
								'description',
								array(
									'class' =>
									'form-control',
								)
							); ?>


							<?= $form->error(
								$translation,
								'description'
							); ?>

						</div>


						<div class="admin-form-field">

							<?= $form->labelEx(
								$translation,
								'name_size'
							); ?>


							<?= $form->textField(
								$translation,
								'name_size',
								array(
									'class' =>
									'form-control',

									'maxlength' =>
									20,
								)
							); ?>


							<?= $form->error(
								$translation,
								'name_size'
							); ?>

						</div>


						<div class="admin-form-field">

							<?= $form->labelEx(
								$translation,
								'short_description_size'
							); ?>


							<?= $form->textField(
								$translation,
								'short_description_size',
								array(
									'class' =>
									'form-control',

									'maxlength' =>
									20,
								)
							); ?>


							<?= $form->error(
								$translation,
								'short_description_size'
							); ?>

						</div>


						<div class="admin-form-field">

							<?= $form->labelEx(
								$translation,
								'description_size'
							); ?>


							<?= $form->textField(
								$translation,
								'description_size',
								array(
									'class' =>
									'form-control',

									'maxlength' =>
									20,
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
						Selecciona las categorías y subcategorías del producto.
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
						Al agregar una subcategoría, su categoría padre
						se asigna automáticamente.
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

					Crear producto

				</button>

			</div>

		</div>

	</div>


	<?php $this->endWidget(); ?>

</div>