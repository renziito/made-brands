<?php
/* @var $this ProductsController */
/* @var $model Products */
/* @var $form CActiveForm */

/*
 * CREATE FORM
 *
 * Expected controller variables:
 * - $translation: ProductTranslations
 * - $defaultLanguage: Languages
 *
 * Additional submitted data:
 * ProductCategorySelection[category_ids][]
 * ProductCategorySelection[subcategory_ids][]
 */

$translation = isset($translation)
    ? $translation
    : new ProductTranslations;

$defaultLanguage = isset($defaultLanguage)
    ? $defaultLanguage
    : Languages::model()->findByAttributes(array(
        'is_default' => 1,
    ));

/*
 * Categories and subcategories use their own translation tables.
 * ProductTranslations is only used for the product itself.
 */
$categories = Categories::model()->findAll(array(
    'order' => 'sort_order ASC, id ASC',
));

$subcategories = Subcategories::model()->findAll(array(
    'order' => 'category_id ASC, sort_order ASC, id ASC',
));

$categoryIds = array();
foreach ($categories as $category) {
    $categoryIds[] = (int) $category->id;
}

$subcategoryIds = array();
foreach ($subcategories as $subcategory) {
    $subcategoryIds[] = (int) $subcategory->id;
}

$categoryTranslationsByCategory = array();

if ($categoryIds) {

    $criteria = new CDbCriteria;
    $criteria->addInCondition('category_id', $categoryIds);

    if ($defaultLanguage !== null) {
        $criteria->addCondition(
            'language_id = :category_default_language_id'
        );
        $criteria->params[':category_default_language_id'] =
            (int) $defaultLanguage->id;
    }

    $rows = CategoryTranslations::model()->findAll($criteria);

    foreach ($rows as $row) {
        $categoryTranslationsByCategory[(int) $row->category_id] = $row;
    }
}

$subcategoryTranslationsBySubcategory = array();

if ($subcategoryIds) {

    $criteria = new CDbCriteria;
    $criteria->addInCondition('subcategory_id', $subcategoryIds);

    if ($defaultLanguage !== null) {
        $criteria->addCondition(
            'language_id = :subcategory_default_language_id'
        );
        $criteria->params[':subcategory_default_language_id'] =
            (int) $defaultLanguage->id;
    }

    $rows = SubcategoryTranslations::model()->findAll($criteria);

    foreach ($rows as $row) {
        $subcategoryTranslationsBySubcategory[(int) $row->subcategory_id] = $row;
    }
}

$taxonomyData = array(
    'categories' => array(),
    'subcategories' => array(),
);

foreach ($categories as $category) {

    $id = (int) $category->id;

    $name = isset($categoryTranslationsByCategory[$id])
        ? trim((string) $categoryTranslationsByCategory[$id]->name)
        : '';

    if ($name === '') {
        $name = 'Categoría #' . $id;
    }

    $taxonomyData['categories'][] = array(
        'id' => $id,
        'name' => $name,
    );
}

foreach ($subcategories as $subcategory) {

    $id = (int) $subcategory->id;
    $categoryId = (int) $subcategory->category_id;

    $name = isset($subcategoryTranslationsBySubcategory[$id])
        ? trim((string) $subcategoryTranslationsBySubcategory[$id]->name)
        : '';

    if ($name === '') {
        $name = 'Subcategoría #' . $id;
    }

    $categoryName = isset($categoryTranslationsByCategory[$categoryId])
        ? trim((string) $categoryTranslationsByCategory[$categoryId]->name)
        : '';

    if ($categoryName === '') {
        $categoryName = 'Categoría #' . $categoryId;
    }

    $taxonomyData['subcategories'][] = array(
        'id' => $id,
        'name' => $name,
        'category_id' => $categoryId,
        'category_name' => $categoryName,
    );
}
?>
<?php
Yii::app()->clientScript->registerCss('admin-form-products', '
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
   PRODUCT DEFAULT LANGUAGE
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
   CATEGORY / SUBCATEGORY SELECTOR
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

.dropdown-list-new {
	display: block;
	width: 100%;
	min-height: 40px;
	padding: 0 38px 0 12px;
	box-sizing: border-box;

	border: 1px solid #b8c0cc;
	border-radius: 7px;

	background-color: #ffffff;
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
	background-color: #ffffff;

	box-shadow:
		0 0 0 3px rgba(17, 24, 39, .08),
		0 1px 2px rgba(0, 0, 0, .05);
}

.dropdown-list-new option {
	padding: 8px 10px;
	background: #ffffff;
	color: #1f2937;
	font-size: 13px;
}
');

/*
 * Product category/subcategory selector.
 */
Yii::app()->clientScript->registerScript(
    'admin-product-create-taxonomy',
    "
var productCreateTaxonomy = " . CJSON::encode($taxonomyData) . ";

(function($) {

	var selectedCategories = {};
	var selectedSubcategories = {};

	var searchInput = $('#product-taxonomy-search');
	var results = $('#product-taxonomy-results');
	var selected = $('#product-taxonomy-selected');
	var hidden = $('#product-category-selection');

	function escapeHtml(value) {
		return $('<div>').text(value == null ? '' : String(value)).html();
	}

	function getCategory(id) {
		id = String(id);

		for (var i = 0; i < productCreateTaxonomy.categories.length; i++) {
			if (String(productCreateTaxonomy.categories[i].id) === id) {
				return productCreateTaxonomy.categories[i];
			}
		}

		return null;
	}

	function getSubcategory(id) {
		id = String(id);

		for (var i = 0; i < productCreateTaxonomy.subcategories.length; i++) {
			if (String(productCreateTaxonomy.subcategories[i].id) === id) {
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

		/*
		 * Removing a category also removes its selected subcategories.
		 * This prevents a subcategory from remaining without its parent.
		 */
		$.each(selectedSubcategories, function(subcategoryId) {

			var subcategory = getSubcategory(subcategoryId);

			if (
				subcategory &&
				String(subcategory.category_id) === id
			) {
				delete selectedSubcategories[subcategoryId];
			}
		});

		render();
	}

	function addSubcategory(id) {
		id = String(id);

		var subcategory = getSubcategory(id);

		if (!subcategory) {
			return;
		}

		selectedSubcategories[id] = true;

		/*
		 * Selecting a subcategory automatically selects its parent.
		 */
		selectedCategories[String(subcategory.category_id)] = true;

		render();
	}

	function removeSubcategory(id) {
		id = String(id);

		delete selectedSubcategories[id];

		/*
		 * If no other selected subcategory belongs to the parent,
		 * remove the parent category as well.
		 */
		var removed = getSubcategory(id);

		if (removed) {

			var parentId = String(removed.category_id);
			var hasSibling = false;

			$.each(selectedSubcategories, function(otherId) {

				var other = getSubcategory(otherId);

				if (
					other &&
					String(other.category_id) === parentId
				) {
					hasSibling = true;
					return false;
				}
			});

			if (!hasSibling) {
				delete selectedCategories[parentId];
			}
		}

		render();
	}

	function syncHiddenFields() {

		hidden.empty();

		$.each(selectedCategories, function(id) {

			$('<input>', {
				type: 'hidden',
				name: 'ProductCategorySelection[category_ids][]',
				value: id
			}).appendTo(hidden);
		});

		$.each(selectedSubcategories, function(id) {

			$('<input>', {
				type: 'hidden',
				name: 'ProductCategorySelection[subcategory_ids][]',
				value: id
			}).appendTo(hidden);
		});
	}

	function renderSelected() {

		var groups = {};

		$.each(selectedCategories, function(categoryId) {

			var category = getCategory(categoryId);

			if (!category) {
				return;
			}

			groups[categoryId] = {
				category: category,
				subcategories: []
			};
		});

		$.each(selectedSubcategories, function(subcategoryId) {

			var subcategory = getSubcategory(subcategoryId);

			if (!subcategory) {
				return;
			}

			var categoryId = String(subcategory.category_id);

			if (!groups[categoryId]) {

				var category = getCategory(categoryId);

				if (!category) {
					return;
				}

				groups[categoryId] = {
					category: category,
					subcategories: []
				};
			}

			groups[categoryId].subcategories.push(subcategory);
		});

		var html = '';

		if ($.isEmptyObject(groups)) {

			selected.html(
				'<div class=\"admin-product-taxonomy__selected-empty\">' +
				'No hay categorías ni subcategorías seleccionadas.' +
				'</div>'
			);

			return;
		}

		$.each(groups, function(categoryId, group) {

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

			$.each(group.subcategories, function(index, subcategory) {

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
			});

			html += '</div></div>';
		});

		selected.html(html);
	}

	function renderResults() {

		var term = $.trim(searchInput.val()).toLowerCase();
		var html = '';
		var count = 0;

		$.each(productCreateTaxonomy.categories, function(index, category) {

			if (
				term !== '' &&
				category.name.toLowerCase().indexOf(term) === -1
			) {
				return;
			}

			count++;

			var id = String(category.id);
			var isAdded = !!selectedCategories[id];

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
				escapeHtml(id) + '\"' +
				(isAdded ? ' disabled' : '') +
				'>' +
				(
					isAdded
						? '<i class=\"fas fa-check\"></i> Agregado'
						: '<i class=\"fas fa-plus\"></i> Agregar'
				) +
				'</button>' +
				'</div>';
		});

		$.each(productCreateTaxonomy.subcategories, function(index, subcategory) {

			var searchable = (
				subcategory.name + ' ' +
				subcategory.category_name
			).toLowerCase();

			if (
				term !== '' &&
				searchable.indexOf(term) === -1
			) {
				return;
			}

			count++;

			var id = String(subcategory.id);
			var isAdded = !!selectedSubcategories[id];

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
				escapeHtml(id) + '\"' +
				(isAdded ? ' disabled' : '') +
				'>' +
				(
					isAdded
						? '<i class=\"fas fa-check\"></i> Agregado'
						: '<i class=\"fas fa-plus\"></i> Agregar'
				) +
				'</button>' +
				'</div>';
		});

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

			var type = $(this).attr('data-type');
			var id = $(this).attr('data-id');

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

			var type = $(this).attr('data-type');
			var id = $(this).attr('data-id');

			if (type === 'category') {
				removeCategory(id);
			}

			if (type === 'subcategory') {
				removeSubcategory(id);
			}

			return false;
		}
	);

	render();

})(jQuery);
"
);

?>
<div class="admin-form-page">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'products-create-form',
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
                            <?= $form->labelEx($model, 'status'); ?> </span>
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
                            Brands::model()->findAll(array(
                                'order' => 'name ASC',
                            )),
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
                    <?= $form->labelEx($model, 'sort_order'); ?>

                    <?= $form->textField(
                        $model,
                        'sort_order',
                        array(
                            'class' => 'form-control',
                        )
                    ); ?>

                    <?= $form->error($model, 'sort_order'); ?>
                </div>

                <div class="admin-form-field admin-form-field--full">
                    <?= $form->labelEx($model, 'main_image'); ?>

                    <?= $form->textField(
                        $model,
                        'main_image',
                        array(
                            'class' => 'form-control',
                            'maxlength' => 255,
                        )
                    ); ?>

                    <?= $form->error($model, 'main_image'); ?>
                </div>

                <div class="admin-form-field admin-form-field--full">
                    <?= $form->labelEx($model, 'infographic_image'); ?>

                    <?= $form->textField(
                        $model,
                        'infographic_image',
                        array(
                            'class' => 'form-control',
                            'maxlength' => 255,
                        )
                    ); ?>

                    <?= $form->error($model, 'infographic_image'); ?>
                </div>

            </div>

        </div>


        <!-- ======================================================
	     DEFAULT TRANSLATION
	     ====================================================== -->

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
                            La información del producto se guardará en el idioma predeterminado.
                        </p>
                    </div>

                </div>

            </div>

            <div class="admin-form-card__body">

                <div class="admin-form-fields" style="grid-template-columns: repeat(3, minmax(0, 1fr));">

                    <div class="admin-form-field admin-form-field--full">

                        <label class="admin-form-field__label">
                            Idioma
                        </label>

                        <div class="admin-product-language">

                            <span class="admin-product-language__name">
                                <?php
                                echo $defaultLanguage !== null
                                    ? CHtml::encode($defaultLanguage->name)
                                    : 'No configurado';
                                ?>
                            </span>

                            <span class="admin-product-language__badge">
                                Predeterminado
                            </span>

                        </div>

                        <?php
                        if ($defaultLanguage !== null) {
                            echo CHtml::hiddenField(
                                'ProductTranslations[language_id]',
                                (int) $defaultLanguage->id
                            );
                        }
                        ?>

                    </div>

                    <div class="admin-form-field admin-form-field--full">

                        <?= CHtml::activeLabelEx($translation, 'name'); ?>

                        <?= CHtml::activeTextField(
                            $translation,
                            'name',
                            array(
                                'class' => 'form-control',
                                'maxlength' => 255,
                            )
                        ); ?>

                        <?= CHtml::error($translation, 'name'); ?>

                    </div>

                    <div class="admin-form-field admin-form-field--full">

                        <?= CHtml::activeLabelEx(
                            $translation,
                            'short_description'
                        ); ?>

                        <?= CHtml::activeTextArea(
                            $translation,
                            'short_description',
                            array(
                                'class' => 'form-control',
                            )
                        ); ?>

                        <?= CHtml::error(
                            $translation,
                            'short_description'
                        ); ?>

                    </div>


                    <div class="admin-form-field admin-form-field--full">

                        <?= CHtml::activeLabelEx(
                            $translation,
                            'description'
                        ); ?>

                        <?= CHtml::activeTextArea(
                            $translation,
                            'description',
                            array(
                                'class' => 'form-control',
                            )
                        ); ?>

                        <?= CHtml::error(
                            $translation,
                            'description'
                        ); ?>

                    </div>



                    <div class="admin-form-field ">

                        <?= CHtml::activeLabelEx($translation, 'name_size'); ?>

                        <?= CHtml::activeTextField(
                            $translation,
                            'name_size',
                            array(
                                'class' => 'form-control',
                                'maxlength' => 10,
                            )
                        ); ?>

                        <?= CHtml::error($translation, 'name_size'); ?>

                    </div>

                    <div class="admin-form-field ">

                        <?= CHtml::activeLabelEx(
                            $translation,
                            'short_description_size'
                        ); ?>

                        <?= CHtml::activeTextField(
                            $translation,
                            'short_description_size',
                            array(
                                'class' => 'form-control',
                                'maxlength' => 10,
                            )
                        ); ?>

                        <?= CHtml::error(
                            $translation,
                            'short_description_size'
                        ); ?>

                    </div>

                    <div class="admin-form-field ">

                        <?= CHtml::activeLabelEx(
                            $translation,
                            'description_size'
                        ); ?>

                        <?= CHtml::activeTextField(
                            $translation,
                            'description_size',
                            array(
                                'class' => 'form-control',
                                'maxlength' => 10,
                            )
                        ); ?>

                        <?= CHtml::error(
                            $translation,
                            'description_size'
                        ); ?>


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
                            Busca y agrega las categorías o subcategorías del producto.
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
                            Al agregar una subcategoría, su categoría padre se asigna automáticamente.
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

        <div class="admin-form-card__footer">
            <div class="admin-form-footer__note">
                <span class="required">*</span>
                Campos obligatorios
            </div>
            <div class="admin-form-actions">
                <a
                    href="<?php echo $this->createUrl("index"); ?>"
                    class="admin-form-button admin-form-button--secondary">
                    <i class="fas fa-times"></i>
                    Cancelar
                </a>
                <button
                    type="submit"
                    class="admin-form-button admin-form-button--primary">
                    <i class="fas fa-plus"></i>
                    Crear producto
                </button>
            </div>
        </div>
    </div>
    <?php $this->endWidget(); ?>
</div>