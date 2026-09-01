<?php

/**
 * @var $this SiteController
 * @var $categories array
 * @var $subcategories array
 * @var $brands Brands[]
 * @var $dataProvider CArrayDataProvider
 * @var $selectedProduct array|null
 * @var $selectedCategoryId integer|null
 * @var $selectedSubcategoryId integer|null
 * @var $selectedBrandId integer|null
 * @var $categoryFilter string
 * @var $subcategoryFilter string
 * @var $brandFilter string
 * @var $orderFilter string
 * @var $productFilter string
 * @var $languageId integer|null
 */


/*
 * ==========================================================
 * CSS
 * ==========================================================
 */

Yii::app()->clientScript->registerCss(
    'public-products-catalog',
    '
/* ==========================================================
   CATALOG PAGE
   ========================================================== */

.catalog-page {
	width: 100%;
	max-width: 1500px;
	margin: 0 auto;
	padding: 42px 34px 70px;
	box-sizing: border-box;
	color: #17131d;
}

.catalog-layout {
	display: grid;
	grid-template-columns: 245px minmax(0, 1fr);
	gap: 42px;
	align-items: start;
}


/* ==========================================================
   SIDEBAR
   ========================================================== */

.catalog-sidebar {
	position: sticky;
	top: 24px;
	align-self: start;
}

.catalog-sidebar__header {
	display: flex;
	align-items: center;
	gap: 10px;
	margin-bottom: 28px;
	color: #2c205a;
	font-size: 13px;
	font-weight: 800;
	letter-spacing: .04em;
	text-transform: uppercase;
}

.catalog-sidebar__header i {
	font-size: 14px;
}

.catalog-filter-section {
	padding: 0 0 24px;
	margin-bottom: 24px;
	border-bottom: 1px solid #e7e4e9;
}

.catalog-filter-section:last-child {
	border-bottom: 0;
	margin-bottom: 0;
}

.catalog-filter-section__heading {
	display: flex;
	align-items: center;
	justify-content: space-between;
	width: 100%;
	margin: 0 0 16px;
	padding: 0;
	border: 0;
	background: transparent;
	color: #2c205a;
	cursor: pointer;
	font-family: inherit;
	font-size: 12px;
	font-weight: 800;
	letter-spacing: .05em;
	text-align: left;
	text-transform: uppercase;
}

.catalog-filter-section__heading i {
	font-size: 11px;
	transition: transform .2s ease;
}

.catalog-filter-section.is-collapsed
.catalog-filter-section__heading i {
	transform: rotate(-90deg);
}

.catalog-filter-section.is-collapsed
.catalog-filter-section__content {
	display: none;
}

.catalog-filter-category {
	margin-bottom: 13px;
}

.catalog-filter-category:last-child {
	margin-bottom: 0;
}

.catalog-filter-category__row {
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 10px;
}

.catalog-filter-category__link {
	display: flex;
	align-items: center;
	gap: 10px;
	min-width: 0;
	color: #242129;
	font-size: 13px;
	font-weight: 500;
	line-height: 1.4;
	text-decoration: none;
}

.catalog-filter-category__link:hover {
	color: #39216e;
	text-decoration: none;
}

.catalog-filter-category__dot {
	width: 10px;
	height: 10px;
	flex: 0 0 10px;
	border: 1.5px solid #aaa6b0;
	border-radius: 50%;
	box-sizing: border-box;
}

.catalog-filter-category.is-selected
.catalog-filter-category__dot {
	border: 3px solid #39216e;
}

.catalog-filter-category.is-selected
.catalog-filter-category__link {
	color: #39216e;
	font-weight: 700;
}

.catalog-filter-category__toggle {
	width: 24px;
	height: 24px;
	padding: 0;
	border: 0;
	background: transparent;
	color: #39216e;
	cursor: pointer;
	font-size: 13px;
}

.catalog-filter-subcategories {
	display: none;
	margin: 9px 0 0 19px;
	padding-left: 14px;
	border-left: 1px solid #ded9e5;
}

.catalog-filter-category.is-open
.catalog-filter-subcategories {
	display: block;
}

.catalog-filter-subcategory {
	display: block;
	margin: 9px 0;
	color: #5b5662;
	font-size: 12px;
	line-height: 1.35;
	text-decoration: none;
}

.catalog-filter-subcategory:hover {
	color: #39216e;
	text-decoration: none;
}

.catalog-filter-subcategory.is-selected {
	color: #39216e;
	font-weight: 700;
}

.catalog-filter-brand {
	display: flex;
	align-items: center;
	gap: 10px;
	margin: 0 0 13px;
	color: #3c3841;
	font-size: 13px;
	line-height: 1.35;
	text-decoration: none;
}

.catalog-filter-brand:last-child {
	margin-bottom: 0;
}

.catalog-filter-brand:hover {
	color: #39216e;
	text-decoration: none;
}

.catalog-filter-brand__box {
	width: 14px;
	height: 14px;
	flex: 0 0 14px;
	border: 1px solid #c8c4cb;
	border-radius: 3px;
	box-sizing: border-box;
	background: #fff;
}

.catalog-filter-brand.is-selected
.catalog-filter-brand__box {
	position: relative;
	border-color: #39216e;
	background: #39216e;
}

.catalog-filter-brand.is-selected
.catalog-filter-brand__box:after {
	content: "";
	position: absolute;
	left: 4px;
	top: 1px;
	width: 4px;
	height: 8px;
	border: solid #fff;
	border-width: 0 2px 2px 0;
	transform: rotate(45deg);
}

.catalog-filter-clear {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	gap: 8px;
	width: 100%;
	min-height: 42px;
	margin-top: 22px;
	padding: 10px 14px;
	box-sizing: border-box;
	border: 1px solid #39216e;
	border-radius: 5px;
	background: #fff;
	color: #39216e;
	font-size: 11px;
	font-weight: 800;
	letter-spacing: .04em;
	text-decoration: none;
	text-transform: uppercase;
	transition:
		background-color .2s ease,
		color .2s ease;
}

.catalog-filter-clear:hover {
	background: #39216e;
	color: #fff;
	text-decoration: none;
}

.catalog-download {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	gap: 8px;
	width: 100%;
	min-height: 42px;
	margin-top: 22px;
	padding: 10px 14px;
	box-sizing: border-box;
	border: 1px solid #39216e;
	border-radius: 5px;
	background: #fff;
	color: #39216e;
	font-size: 11px;
	font-weight: 800;
	letter-spacing: .04em;
	text-decoration: none;
	text-transform: uppercase;
	transition:
		background-color .2s ease,
		color .2s ease;
}

.catalog-download:hover {
	background: #39216e;
	color: #fff;
	text-decoration: none;
}


/* ==========================================================
   CONTENT HEADER
   ========================================================== */

.catalog-content {
	min-width: 0;
}

.catalog-heading {
	display: flex;
	align-items: flex-end;
	justify-content: space-between;
	gap: 28px;
	margin-bottom: 28px;
}

.catalog-heading__copy {
	min-width: 0;
}

.catalog-eyebrow {
	margin: 0 0 9px;
	color: #39216e;
	font-size: 12px;
	font-weight: 800;
	letter-spacing: .08em;
	line-height: 1.3;
	text-transform: uppercase;
}

.catalog-title {
	max-width: 680px;
	margin: 0;
	color: #17131d;
	font-family: Georgia, "Times New Roman", serif;
	font-size: clamp(34px, 3vw, 48px);
	font-weight: 400;
	letter-spacing: -.025em;
	line-height: .98;
}

.catalog-title em {
	font-style: italic;
}

.catalog-heading__tools {
	display: flex;
	align-items: center;
	gap: 22px;
	flex-shrink: 0;
	padding-bottom: 2px;
}

.catalog-count {
	color: #57515d;
	font-size: 12px;
	white-space: nowrap;
}

.catalog-sort {
	position: relative;
	display: inline-flex;
	align-items: center;
}

.catalog-sort select {
	min-width: 190px;
	height: 40px;
	padding: 0 36px 0 14px;
	border: 1px solid #d9d5dc;
	border-radius: 4px;
	outline: 0;
	appearance: none;
	background: #fff;
	color: #37323c;
	cursor: pointer;
	font-family: inherit;
	font-size: 12px;
}

.catalog-sort i {
	position: absolute;
	right: 13px;
	pointer-events: none;
	color: #39216e;
	font-size: 10px;
}


/* ==========================================================
   ACTIVE FILTERS
   ========================================================== */

.catalog-active-filters {
	display: flex;
	flex-wrap: wrap;
	gap: 8px;
	margin: 0 0 20px;
}

.catalog-active-filter {
	display: inline-flex;
	align-items: center;
	gap: 7px;
	padding: 7px 10px;
	border-radius: 999px;
	background: #f2eff6;
	color: #39216e;
	font-size: 11px;
	font-weight: 700;
}

.catalog-active-filter i {
	font-size: 9px;
}


/* ==========================================================
   PRODUCT GRID
   ========================================================== */

.catalog-grid {
	display: grid;
	grid-template-columns: repeat(3, minmax(0, 1fr));
	gap: 20px;
}

.catalog-product {
	position: relative;
	display: flex;
	flex-direction: column;
	min-width: 0;
	border: 1px solid #e7e4e8;
	border-radius: 5px;
	background: #fff;
	overflow: hidden;
	transition:
		transform .2s ease,
		box-shadow .2s ease,
		border-color .2s ease;
}

.catalog-product:hover {
	transform: translateY(-3px);
	border-color: #ddd7e2;
	box-shadow: 0 12px 28px rgba(34, 23, 49, .09);
}

.catalog-product__button {
	display: flex;
	flex-direction: column;
	flex: 1;
	width: 100%;
	padding: 0;
	border: 0;
	background: transparent;
	color: inherit;
	cursor: pointer;
	font-family: inherit;
	text-align: left;
}

.catalog-product__image-wrap {
	position: relative;
	display: flex;
	align-items: center;
	justify-content: center;
	aspect-ratio: 1 / 1.08;
	padding: 13px;
	box-sizing: border-box;
	background: #f7f6f5;
	overflow: hidden;
}

.catalog-product__image {
	display: block;
	width: 100%;
	height: 100%;
	object-fit: cover;
	transition: transform .35s ease;
}

.catalog-product:hover .catalog-product__image {
	transform: scale(1.025);
}

.catalog-product__placeholder {
	display: flex;
	align-items: center;
	justify-content: center;
	width: 100%;
	height: 100%;
	background:
		linear-gradient(135deg, #f4f1f5, #faf9fa);
	color: #8b8590;
	font-size: 12px;
	font-weight: 700;
	text-align: center;
	text-transform: uppercase;
}

.catalog-product__favorite {
	position: absolute;
	top: 10px;
	right: 10px;
	display: flex;
	align-items: center;
	justify-content: center;
	width: 28px;
	height: 28px;
	border-radius: 50%;
	background: rgba(255, 255, 255, .95);
	color: #5b5560;
	box-shadow: 0 2px 8px rgba(0, 0, 0, .06);
	font-size: 13px;
}

.catalog-product__body {
	display: flex;
	flex-direction: column;
	flex: 1;
	padding: 15px 16px 17px;
}

.catalog-product__brand {
	margin: 0 0 7px;
	color: #39216e;
	font-size: 10px;
	font-weight: 800;
	letter-spacing: .06em;
	line-height: 1.3;
	text-transform: uppercase;
}

.catalog-product__name {
	margin: 0;
	color: #201b23;
	font-family: Georgia, "Times New Roman", serif;
	font-size: 19px;
	font-weight: 400;
	line-height: 1.15;
}

.catalog-product__description {
	margin: 9px 0 0;
	color: #77717b;
	font-size: 11px;
	line-height: 1.5;
}

.catalog-product__taxonomy {
	margin-top: auto;
	padding-top: 17px;
	color: #77717b;
	font-size: 10px;
	line-height: 1.45;
}

.catalog-product__taxonomy strong {
	color: #4b4550;
	font-weight: 600;
}

.catalog-product__taxonomy-separator {
	padding: 0 3px;
	color: #aaa5ad;
}


/* ==========================================================
   EMPTY
   ========================================================== */

.catalog-empty {
	padding: 70px 30px;
	border: 1px dashed #d8d3dc;
	border-radius: 6px;
	background: #faf9fa;
	text-align: center;
}

.catalog-empty__icon {
	margin-bottom: 13px;
	color: #39216e;
	font-size: 25px;
}

.catalog-empty__title {
	margin: 0 0 6px;
	color: #27212c;
	font-family: Georgia, "Times New Roman", serif;
	font-size: 25px;
}

.catalog-empty__text {
	margin: 0;
	color: #77717b;
	font-size: 13px;
	line-height: 1.5;
}


/* ==========================================================
   PAGINATION
   ========================================================== */

.catalog-pagination {
	display: flex;
	align-items: center;
	justify-content: center;
	gap: 8px;
	margin-top: 34px;
}

.catalog-pagination a,
.catalog-pagination span {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	min-width: 34px;
	height: 34px;
	padding: 0 8px;
	box-sizing: border-box;
	border-radius: 4px;
	color: #342e39;
	font-size: 12px;
	font-weight: 700;
	text-decoration: none;
}

.catalog-pagination a:hover {
	background: #f0edf3;
	color: #39216e;
	text-decoration: none;
}

.catalog-pagination .selected {
	background: #39216e;
	color: #fff;
}

.catalog-pagination .hidden {
	display: none;
}


/* ==========================================================
   PRODUCT INFOGRAPHIC MODAL
   ========================================================== */

.catalog-modal {
	position: fixed;
	z-index: 9999;
	inset: 0;
	display: none;
	align-items: center;
	justify-content: center;
	padding: 28px;
	box-sizing: border-box;
	background: rgba(18, 13, 23, .66);
	backdrop-filter: blur(4px);
}

.catalog-modal.is-open {
	display: flex;
}

.catalog-modal-open {
	overflow: hidden;
}

.catalog-modal__dialog {
	position: relative;
	width: min(1100px, 100%);
	max-height: calc(100vh - 56px);
	overflow: hidden;
	border-radius: 12px;
	background: #fff;
	box-shadow: 0 28px 90px rgba(0, 0, 0, .28);
}

.catalog-modal__close {
	position: absolute;
	z-index: 3;
	top: 14px;
	right: 14px;
	display: flex;
	align-items: center;
	justify-content: center;
	width: 36px;
	height: 36px;
	padding: 0;
	border: 0;
	border-radius: 50%;
	background: #1c1a1d;
	color: #fff;
	cursor: pointer;
	font-size: 14px;
}

.catalog-modal__close:hover {
	background: #39216e;
}

.catalog-modal__image-wrap {
	display: flex;
	align-items: center;
	justify-content: center;
	width: 100%;
	max-height: calc(100vh - 56px);
	min-height: 320px;
	background: #f7f5f2;
	overflow: auto;
}

.catalog-modal__image {
	display: block;
	width: 100%;
	height: auto;
	max-height: calc(100vh - 56px);
	object-fit: contain;
}

.catalog-modal__placeholder {
	padding: 80px 30px;
	color: #77717b;
	font-size: 14px;
	text-align: center;
}

.catalog-modal__loading {
	display: none;
	padding: 60px;
	color: #77717b;
	font-size: 13px;
	text-align: center;
}

.catalog-modal.is-loading
.catalog-modal__loading {
	display: block;
}

.catalog-modal.is-loading
.catalog-modal__image-wrap {
	display: none;
}


/* ==========================================================
   MOBILE FILTER
   ========================================================== */

.catalog-mobile-filter-button {
	display: none;
	align-items: center;
	justify-content: center;
	gap: 8px;
	width: 100%;
	height: 44px;
	margin-bottom: 20px;
	padding: 0 14px;
	border: 1px solid #d9d5dc;
	border-radius: 5px;
	background: #fff;
	color: #39216e;
	cursor: pointer;
	font-family: inherit;
	font-size: 12px;
	font-weight: 800;
	letter-spacing: .04em;
	text-transform: uppercase;
}


/* ==========================================================
   RESPONSIVE
   ========================================================== */

@media (max-width: 1100px) {

	.catalog-page {
		padding-left: 24px;
		padding-right: 24px;
	}

	.catalog-layout {
		grid-template-columns: 210px minmax(0, 1fr);
		gap: 28px;
	}

	.catalog-grid {
		grid-template-columns: repeat(2, minmax(0, 1fr));
	}
}


@media (max-width: 820px) {

	.catalog-page {
		padding: 30px 18px 50px;
	}

	.catalog-layout {
		display: block;
	}

	.catalog-mobile-filter-button {
		display: flex;
	}

	.catalog-sidebar {
		position: static;
		display: none;
		margin-bottom: 24px;
		padding: 20px;
		border: 1px solid #e7e4e8;
		border-radius: 6px;
		background: #fff;
	}

	.catalog-sidebar.is-open {
		display: block;
	}

	.catalog-heading {
		align-items: flex-start;
		flex-direction: column;
		gap: 18px;
	}

	.catalog-heading__tools {
		width: 100%;
		justify-content: space-between;
	}

	.catalog-sort {
		flex: 0 0 auto;
	}

	.catalog-sort select {
		min-width: 165px;
	}
}


@media (max-width: 560px) {

	.catalog-title {
		font-size: 34px;
	}

	.catalog-heading__tools {
		align-items: stretch;
		flex-direction: column;
		gap: 12px;
	}

	.catalog-sort,
	.catalog-sort select {
		width: 100%;
	}

	.catalog-grid {
		grid-template-columns: 1fr;
		gap: 16px;
	}

	.catalog-product__image-wrap {
		aspect-ratio: 1 / .95;
	}

	.catalog-modal {
		padding: 10px;
	}

	.catalog-modal__dialog {
		border-radius: 8px;
	}

	.catalog-modal__close {
		top: 8px;
		right: 8px;
	}
}
'
);
/*
 * ==========================================================
 * BASE URL
 * ==========================================================
 */

$baseCatalogUrl =
    $this->createUrl('productos');


/*
 * ==========================================================
 * PAGINATION / PRODUCTS
 * ==========================================================
 */

$totalProducts =
    (int) $dataProvider->getTotalItemCount();


$currentProducts =
    $dataProvider->getData();


$pagination =
    $dataProvider->getPagination();


$firstItem =
    $totalProducts > 0
    ? (
        (int) $pagination->getCurrentPage() *
        (int) $pagination->getPageSize()
    ) + 1
    : 0;


$lastItem =
    $totalProducts > 0
    ? min(
        $firstItem +
            count($currentProducts) -
            1,
        $totalProducts
    )
    : 0;


/*
 * ==========================================================
 * URL HELPERS
 * ==========================================================
 */

$catalogUrl =
    function ($params = array()) use (
        $baseCatalogUrl
    ) {

        if (empty($params)) {

            return $baseCatalogUrl;
        }


        return $this->createUrl(
            'productos',
            $params
        );
    };


$imageUrl =
    function ($path) {

        $path =
            trim(
                (string) $path
            );


        if ($path === '') {

            return '';
        }


        if (
            preg_match(
                '#^https?://#i',
                $path
            )
        ) {

            return $path;
        }


        return Yii::app()->baseUrl .
            '/images/products/' .
            ltrim(
                $path,
                '/'
            );
    };


$categoryUrl =
    function ($category) use (
        $catalogUrl
    ) {

        return $catalogUrl(
            array(
                'categoria' =>
                $category['slug'],
            )
        );
    };


/*
 * ==========================================================
 * CATEGORY SLUG LOOKUP
 * ==========================================================
 */

$categorySlugById =
    array();


foreach (
    $categories
    as $category
) {

    $categorySlugById[(int) $category['id']] =
        $category['slug'];
}


$makeSubcategoryUrl =
    function ($subcategory) use (
        $catalogUrl,
        $categorySlugById
    ) {

        $params =
            array(
                'subcategoria' =>
                $subcategory['slug'],
            );


        $categoryId =
            (int)
            $subcategory['category_id'];


        if (
            isset(
                $categorySlugById[$categoryId]
            )
        ) {

            $params['categoria'] =
                $categorySlugById[$categoryId];
        }


        return $catalogUrl(
            $params
        );
    };


$clearFiltersUrl =
    $catalogUrl();


$brandUrl =
    function ($brand) use (
        $catalogUrl
    ) {

        return $catalogUrl(
            array(
                'marca' =>
                $brand->slug,
            )
        );
    };


/*
 * ==========================================================
 * SELECTED CATEGORY / SUBCATEGORY NAMES
 * ==========================================================
 */

$selectedCategoryName =
    '';


foreach (
    $categories
    as $category
) {

    if (
        $selectedCategoryId !== null &&
        (int) $category['id'] ===
        (int) $selectedCategoryId
    ) {

        $selectedCategoryName =
            $category['name'];

        break;
    }
}


$selectedSubcategoryName =
    '';


foreach (
    $subcategories
    as $subcategory
) {

    if (
        $selectedSubcategoryId !== null &&
        (int) $subcategory['id'] ===
        (int) $selectedSubcategoryId
    ) {

        $selectedSubcategoryName =
            $subcategory['name'];

        break;
    }
}


/*
 * ==========================================================
 * CURRENT FILTER URL
 * ==========================================================
 */

$currentFilterParams =
    array();


if (
    $categoryFilter !== ''
) {

    $currentFilterParams['categoria'] =
        $categoryFilter;
}


if (
    $subcategoryFilter !== ''
) {

    $currentFilterParams['subcategoria'] =
        $subcategoryFilter;
}


if (
    $brandFilter !== ''
) {

    $currentFilterParams['marca'] =
        $brandFilter;
}


if (
    $orderFilter !== ''
) {

    $currentFilterParams['orden'] =
        $orderFilter;
}


/*
 * ==========================================================
 * ORDER URLS
 * ==========================================================
 */

$recentUrl =
    $catalogUrl(
        array_merge(
            $currentFilterParams,
            array(
                'orden' =>
                'recientes',
            )
        )
    );


$nameUrl =
    $catalogUrl(
        array_merge(
            $currentFilterParams,
            array(
                'orden' =>
                'nombre',
            )
        )
    );


$oldestUrl =
    $catalogUrl(
        array_merge(
            $currentFilterParams,
            array(
                'orden' =>
                'antiguos',
            )
        )
    );


$orderUrlMap =
    array(
        'recientes' =>
        $recentUrl,

        'nombre' =>
        $nameUrl,

        'antiguos' =>
        $oldestUrl,
    );


/*
 * ==========================================================
 * FULL PRODUCT SHEET
 * ==========================================================
 */

$fullSheetUrl =
    trim(
        (string)
        WebUtils::getSiteSetting(
            'full_sheet'
        )
    );


/*
 * ==========================================================
 * INITIAL PRODUCT MODAL DATA
 * ==========================================================
 */

$initialProductId =
    $selectedProduct !== null
    ? (int)
    $selectedProduct['id']
    : 0;


$initialProductImage =
    $selectedProduct !== null
    ? $imageUrl(
        $selectedProduct['infographic_image']
    )
    : '';


$initialProductName =
    $selectedProduct !== null
    ? $selectedProduct['name']
    : '';

?>


<div class="catalog-layout">


    <!-- ==================================================
	     SIDEBAR
	================================================== -->

    <aside
        class="catalog-sidebar"
        id="catalog-sidebar">


        <div class="catalog-sidebar__header">

            <i
                class="fas fa-sliders-h"
                aria-hidden="true">
            </i>

            <span>

                <?= WebUtils::getMenuItemByKey(
                    'filters',
                    $languageId
                )['label']; ?>

            </span>

        </div>


        <!-- ==================================================
		     CATEGORIES
		================================================== -->

        <div
            class="catalog-filter-section"
            data-filter-section>

            <button
                type="button"
                class="catalog-filter-section__heading"
                data-filter-section-toggle>

                <span>

                    <?= WebUtils::getMenuItemByKey(
                        'categories',
                        $languageId
                    )['label']; ?>

                </span>

                <i
                    class="fas fa-minus"
                    aria-hidden="true">
                </i>

            </button>


            <div
                class="catalog-filter-section__content">


                <?php foreach (
                    $categories
                    as $category
                ): ?>

                    <?php

                    $isSelected =
                        $selectedCategoryId !== null &&
                        (int)
                        $selectedCategoryId ===
                        (int)
                        $category['id'];


                    $hasSubcategories =
                        !empty($category['subcategories']);


                    $isCategoryOpen =
                        $isSelected ||
                        (
                            $selectedSubcategoryId !== null &&
                            $hasSubcategories
                        );

                    ?>


                    <div
                        class="catalog-filter-category
							<?= $isSelected
                                ? 'is-selected'
                                : ''; ?>
							<?= $isCategoryOpen
                                ? 'is-open'
                                : ''; ?>">


                        <div
                            class="catalog-filter-category__row">


                            <a
                                class="catalog-filter-category__link"
                                href="<?= CHtml::encode(
                                            $categoryUrl(
                                                $category
                                            )
                                        ); ?>">

                                <span
                                    class="catalog-filter-category__dot"
                                    aria-hidden="true">
                                </span>


                                <span>

                                    <?= CHtml::encode(
                                        $category['name']
                                    ); ?>

                                </span>

                            </a>


                            <?php if (
                                $hasSubcategories
                            ): ?>

                                <button
                                    type="button"
                                    class="catalog-filter-category__toggle"
                                    data-category-toggle
                                    aria-label="<?= CHtml::encode(
                                                    WebUtils::getMenuItemByKey(
                                                        'show_subcategories',
                                                        $languageId
                                                    )['label']
                                                ); ?>">

                                    <i
                                        class="fas fa-plus"
                                        aria-hidden="true">
                                    </i>

                                </button>

                            <?php endif; ?>


                        </div>


                        <?php if (
                            $hasSubcategories
                        ): ?>

                            <div
                                class="catalog-filter-subcategories">


                                <?php foreach (
                                    $category['subcategories']
                                    as $subcategory
                                ): ?>

                                    <?php

                                    $isSubcategorySelected =
                                        $selectedSubcategoryId !== null &&
                                        (int)
                                        $selectedSubcategoryId ===
                                        (int)
                                        $subcategory['id'];

                                    ?>


                                    <a
                                        class="catalog-filter-subcategory
											<?= $isSubcategorySelected
                                                ? 'is-selected'
                                                : ''; ?>"
                                        href="<?= CHtml::encode(
                                                    $makeSubcategoryUrl(
                                                        $subcategory
                                                    )
                                                ); ?>">

                                        <?= CHtml::encode(
                                            $subcategory['name']
                                        ); ?>

                                    </a>


                                <?php endforeach; ?>


                            </div>

                        <?php endif; ?>


                    </div>


                <?php endforeach; ?>


            </div>

        </div>


        <!-- ==================================================
		     BRANDS
		================================================== -->

        <div
            class="catalog-filter-section"
            data-filter-section>

            <button
                type="button"
                class="catalog-filter-section__heading"
                data-filter-section-toggle>

                <span>

                    <?= WebUtils::getMenuItemByKey(
                        'brands',
                        $languageId
                    )['label']; ?>

                </span>


                <i
                    class="fas fa-minus"
                    aria-hidden="true">
                </i>

            </button>


            <div
                class="catalog-filter-section__content">


                <?php foreach (
                    $brands
                    as $brand
                ): ?>

                    <?php

                    $isSelected =
                        $selectedBrandId !== null &&
                        (int)
                        $selectedBrandId ===
                        (int)
                        $brand->id;

                    ?>


                    <a
                        class="catalog-filter-brand
							<?= $isSelected
                                ? 'is-selected'
                                : ''; ?>"
                        href="<?= CHtml::encode(
                                    $brandUrl(
                                        $brand
                                    )
                                ); ?>">

                        <span
                            class="catalog-filter-brand__box"
                            aria-hidden="true">
                        </span>


                        <span>

                            <?= CHtml::encode(
                                $brand->name
                            ); ?>

                        </span>

                    </a>


                <?php endforeach; ?>


            </div>

        </div>


        <!-- ==================================================
		     CLEAR FILTERS
		================================================== -->

        <?php if (
            $categoryFilter !== '' ||
            $subcategoryFilter !== '' ||
            $brandFilter !== ''
        ): ?>

            <a
                class="catalog-filter-clear"
                href="<?= CHtml::encode(
                            $clearFiltersUrl
                        ); ?>">

                <i
                    class="fas fa-times">
                </i>


                <?= WebUtils::getMenuItemByKey(
                    'clear_filters',
                    $languageId
                )['label']; ?>

            </a>

        <?php endif; ?>


        <!-- ==================================================
		     FULL CATALOG
		================================================== -->

        <?php if (
            $fullSheetUrl !== ''
        ): ?>

            <a
                class="catalog-download"
                href="<?= CHtml::encode(
                            $fullSheetUrl
                        ); ?>"
                target="_blank"
                rel="noopener noreferrer">

                <i
                    class="fas fa-external-link-alt">
                </i>


                <?= WebUtils::getMenuItemByKey(
                    'download_product_catalog',
                    $languageId
                )['label']; ?>

            </a>

        <?php endif; ?>


    </aside>


    <!-- ==================================================
	     MAIN CONTENT
	================================================== -->

    <main class="catalog-content">


        <!-- ==================================================
		     MOBILE FILTER BUTTON
		================================================== -->

        <button
            type="button"
            class="catalog-mobile-filter-button"
            id="catalog-mobile-filter-button">

            <i
                class="fas fa-sliders-h">
            </i>


            <?= WebUtils::getMenuItemByKey(
                'show_filters',
                $languageId
            )['label']; ?>

        </button>


        <!-- ==================================================
		     HEADING
		================================================== -->

        <div class="catalog-heading">


            <div class="catalog-heading__copy">

                <p class="catalog-eyebrow">

                    <?= WebUtils::getMenuItemByKey(
                        'our_products',
                        $languageId
                    )['label']; ?>

                </p>


                <h1 class="catalog-title">

                    <?= WebUtils::getMenuItemByKey(
                        'explore_catalog',
                        $languageId
                    )['label']; ?>

                </h1>

            </div>


            <div class="catalog-heading__tools">


                <div class="catalog-count">

                    <?php if (
                        $totalProducts > 0
                    ): ?>

                        <?= WebUtils::getMenuItemByKey(
                            'showing',
                            $languageId
                        )['label']; ?>


                        <strong>

                            <?= $firstItem; ?>–<?= $lastItem; ?>

                        </strong>


                        <?= WebUtils::getMenuItemByKey(
                            'of',
                            $languageId
                        )['label']; ?>


                        <strong>

                            <?= $totalProducts; ?>

                        </strong>


                        <?= WebUtils::getMenuItemByKey(
                            'products',
                            $languageId
                        )['label']; ?>


                    <?php else: ?>


                        0

                        <?= WebUtils::getMenuItemByKey(
                            'products',
                            $languageId
                        )['label']; ?>


                    <?php endif; ?>

                </div>


                <label
                    class="catalog-sort">

                    <select
                        id="catalog-order"
                        aria-label="<?= CHtml::encode(
                                        WebUtils::getMenuItemByKey(
                                            'sort_products',
                                            $languageId
                                        )['label']
                                    ); ?>">


                        <option
                            value="recientes"
                            <?= $orderFilter === 'recientes'
                                ? 'selected'
                                : ''; ?>>

                            <?= WebUtils::getMenuItemByKey(
                                'sort_most_recent',
                                $languageId
                            )['label']; ?>

                        </option>


                        <option
                            value="nombre"
                            <?= $orderFilter === 'nombre'
                                ? 'selected'
                                : ''; ?>>

                            <?= WebUtils::getMenuItemByKey(
                                'sort_name',
                                $languageId
                            )['label']; ?>

                        </option>


                        <option
                            value="antiguos"
                            <?= $orderFilter === 'antiguos'
                                ? 'selected'
                                : ''; ?>>

                            <?= WebUtils::getMenuItemByKey(
                                'sort_oldest',
                                $languageId
                            )['label']; ?>

                        </option>


                    </select>


                    <i
                        class="fas fa-chevron-down"
                        aria-hidden="true">
                    </i>

                </label>


            </div>

        </div>


        <!-- ==================================================
		     ACTIVE FILTERS
		================================================== -->

        <?php if (
            $selectedCategoryName !== '' ||
            $selectedSubcategoryName !== '' ||
            $selectedBrandId !== null
        ): ?>

            <div
                class="catalog-active-filters">


                <?php if (
                    $selectedCategoryName !== ''
                ): ?>

                    <span
                        class="catalog-active-filter">

                        <i
                            class="fas fa-tag">
                        </i>


                        <?= CHtml::encode(
                            $selectedCategoryName
                        ); ?>

                    </span>

                <?php endif; ?>


                <?php if (
                    $selectedSubcategoryName !== ''
                ): ?>

                    <span
                        class="catalog-active-filter">

                        <i
                            class="fas fa-layer-group">
                        </i>


                        <?= CHtml::encode(
                            $selectedSubcategoryName
                        ); ?>

                    </span>

                <?php endif; ?>


                <?php if (
                    $selectedBrandId !== null
                ): ?>

                    <?php foreach (
                        $brands
                        as $brand
                    ): ?>

                        <?php if (
                            (int) $brand->id ===
                            (int) $selectedBrandId
                        ): ?>

                            <span
                                class="catalog-active-filter">

                                <i
                                    class="fas fa-store">
                                </i>


                                <?= CHtml::encode(
                                    $brand->name
                                ); ?>

                            </span>


                            <?php break; ?>

                        <?php endif; ?>

                    <?php endforeach; ?>

                <?php endif; ?>


            </div>

        <?php endif; ?>


        <!-- ==================================================
		     PRODUCT GRID
		================================================== -->

        <?php if (
            !empty($currentProducts)
        ): ?>


            <div
                class="catalog-grid">


                <?php foreach (
                    $currentProducts
                    as $product
                ): ?>


                    <?php

                    $mainImage =
                        $imageUrl(
                            $product['main_image']
                        );


                    $infographicImage =
                        $imageUrl(
                            $product['infographic_image']
                        );


                    $brandName =
                        $product['brand']
                        ? $product['brand']->name
                        : '';


                    $categoryParts =
                        array();


                    foreach (
                        $product['categories']
                        as $productCategory
                    ) {

                        $categoryParts[] =
                            $productCategory['name'];
                    }


                    $subcategoryParts =
                        array();


                    foreach (
                        $product['subcategories']
                        as $productSubcategory
                    ) {

                        $subcategoryParts[] =
                            $productSubcategory['name'];
                    }


                    $taxonomyText =
                        '';


                    if (
                        $categoryParts
                    ) {

                        $taxonomyText =
                            implode(
                                ', ',
                                $categoryParts
                            );
                    }


                    if (
                        $subcategoryParts
                    ) {

                        if (
                            $taxonomyText !== ''
                        ) {

                            $taxonomyText .=
                                ' › ';
                        }


                        $taxonomyText .=
                            implode(
                                ', ',
                                $subcategoryParts
                            );
                    }

                    ?>


                    <article
                        class="catalog-product"
                        data-product-id="<?= (int) $product['id']; ?>"
                        data-product-slug="<?= CHtml::encode(
                                                $product['slug']
                                            ); ?>"
                        data-product-name="<?= CHtml::encode(
                                                $product['name']
                                            ); ?>"
                        data-infographic="<?= CHtml::encode(
                                                $infographicImage
                                            ); ?>">


                        <button
                            type="button"
                            class="catalog-product__button"
                            data-product-open>


                            <div
                                class="catalog-product__image-wrap">


                                <?php if (
                                    $mainImage !== ''
                                ): ?>


                                    <img
                                        class="catalog-product__image"
                                        src="<?= CHtml::encode(
                                                    $mainImage
                                                ); ?>"
                                        alt="<?= CHtml::encode(
                                                    $product['name']
                                                ); ?>"
                                        loading="lazy">


                                <?php else: ?>


                                    <div
                                        class="catalog-product__placeholder">

                                        <?= CHtml::encode(
                                            $product['name']
                                        ); ?>

                                    </div>


                                <?php endif; ?>


                                <span
                                    class="catalog-product__favorite"
                                    aria-hidden="true">

                                    <i
                                        class="far fa-heart">
                                    </i>

                                </span>


                            </div>


                            <div
                                class="catalog-product__body">


                                <?php if (
                                    $brandName !== ''
                                ): ?>

                                    <p
                                        class="catalog-product__brand">

                                        <?= CHtml::encode(
                                            $brandName
                                        ); ?>

                                    </p>

                                <?php endif; ?>


                                <h2
                                    class="catalog-product__name">

                                    <?= CHtml::encode(
                                        $product['name']
                                    ); ?>

                                </h2>


                                <?php if (
                                    $product['short_description'] !== ''
                                ): ?>

                                    <p
                                        class="catalog-product__description">

                                        <?= CHtml::encode(
                                            $product['short_description']
                                        ); ?>

                                    </p>

                                <?php endif; ?>


                                <?php if (
                                    $taxonomyText !== ''
                                ): ?>

                                    <div
                                        class="catalog-product__taxonomy">

                                        <strong>

                                            <?= CHtml::encode(
                                                $taxonomyText
                                            ); ?>

                                        </strong>

                                    </div>

                                <?php endif; ?>


                            </div>


                        </button>


                    </article>


                <?php endforeach; ?>


            </div>


            <!-- ==================================================
			     PAGINATION
			================================================== -->

            <?php if (
                $dataProvider
                ->getPagination()
                ->getPageCount()
                > 1
            ): ?>


                <div
                    class="catalog-pagination">


                    <?php

                    $this->widget(
                        'CLinkPager',
                        array(
                            'pages' =>
                            $dataProvider
                                ->getPagination(),

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

                            'htmlOptions' =>
                            array(
                                'class' =>
                                'catalog-pagination',
                            ),
                        )
                    );

                    ?>


                </div>


            <?php endif; ?>


        <?php else: ?>


            <!-- ==================================================
			     EMPTY
			================================================== -->

            <div
                class="catalog-empty">


                <div
                    class="catalog-empty__icon">

                    <i
                        class="fas fa-box-open">
                    </i>

                </div>


                <h2
                    class="catalog-empty__title">

                    <?= WebUtils::getMenuItemByKey(
                        'no_products_found',
                        $languageId
                    )['label']; ?>

                </h2>


                <p
                    class="catalog-empty__text">

                    <?= WebUtils::getMenuItemByKey(
                        'remove_filters_message',
                        $languageId
                    )['label']; ?>

                </p>


                <a
                    class="catalog-filter-clear"
                    style="max-width:220px;"
                    href="<?= CHtml::encode(
                                $clearFiltersUrl
                            ); ?>">

                    <?= WebUtils::getMenuItemByKey(
                        'view_all_products',
                        $languageId
                    )['label']; ?>

                </a>


            </div>


        <?php endif; ?>


    </main>


</div>


<!-- ==========================================================
     PRODUCT INFOGRAPHIC MODAL
========================================================== -->

<div
    id="catalog-product-modal"
    class="catalog-modal"
    aria-hidden="true"
    role="dialog"
    aria-modal="true"
    aria-label="<?= CHtml::encode(
                    WebUtils::getMenuItemByKey(
                        'product_information',
                        $languageId
                    )['label']
                ); ?>">


    <div
        class="catalog-modal__dialog">


        <button
            type="button"
            class="catalog-modal__close"
            id="catalog-modal-close"
            title="<?= CHtml::encode(
                        WebUtils::getMenuItemByKey(
                            'close',
                            $languageId
                        )['label']
                    ); ?>"
            aria-label="<?= CHtml::encode(
                            WebUtils::getMenuItemByKey(
                                'close',
                                $languageId
                            )['label']
                        ); ?>">

            <i
                class="fas fa-times">
            </i>

        </button>


        <div
            class="catalog-modal__loading">

            <?= WebUtils::getMenuItemByKey(
                'loading_product_information',
                $languageId
            )['label']; ?>

        </div>


        <div
            class="catalog-modal__image-wrap">


            <img
                id="catalog-modal-image"
                class="catalog-modal__image"
                src="<?= CHtml::encode(
                            $initialProductImage
                        ); ?>"
                alt="<?= CHtml::encode(
                            $initialProductName
                        ); ?>"
                <?= $initialProductImage === ''
                    ? 'style="display:none;"'
                    : ''; ?>>


            <div
                id="catalog-modal-placeholder"
                class="catalog-modal__placeholder"
                <?= $initialProductImage !== ''
                    ? 'style="display:none;"'
                    : ''; ?>>

                <?= WebUtils::getMenuItemByKey(
                    'product_infographic_unavailable',
                    $languageId
                )['label']; ?>

            </div>


        </div>


    </div>

</div>