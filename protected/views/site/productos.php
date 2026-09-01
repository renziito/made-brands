<?php

/**
 * @var $this SiteController
 * @var $languageId integer|null
 * @var $language Languages|null
 * @var $categories array
 * @var $subcategories array
 * @var $brands Brands[]
 * @var $products array
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
 */


/*
|--------------------------------------------------------------------------
| LANGUAGE
|--------------------------------------------------------------------------
*/

$languageCode =
	Yii::app()->session->get(
		'language',
		'es'
	);


/*
|--------------------------------------------------------------------------
| THEME URL
|--------------------------------------------------------------------------
*/

$themeUrl =
	Yii::app()->theme->baseUrl;


/*
|--------------------------------------------------------------------------
| CURRENT CATALOG URL
|--------------------------------------------------------------------------
*/

$catalogUrl =
	$this->createUrl(
		'productos'
	);


/*
|--------------------------------------------------------------------------
| CURRENT QUERY PARAMETERS
|--------------------------------------------------------------------------
*/

$currentQuery =
	$_GET;


/*
|--------------------------------------------------------------------------
| CHANGE ORDER URL
|--------------------------------------------------------------------------
*/

$orderUrl =
	function ($order) use (
		$catalogUrl,
		$currentQuery
	) {

		$query =
			$currentQuery;


		$query['orden'] =
			$order;


		return $catalogUrl .
			'?' .
			http_build_query(
				$query
			);
	};


/*
|--------------------------------------------------------------------------
| INITIAL SELECTED PRODUCT
|--------------------------------------------------------------------------
*/

$selectedProductId =
	$selectedProduct !== null
	? (int)
	$selectedProduct['id']
	: 0;


/*
|--------------------------------------------------------------------------
| PAGE TITLE
|--------------------------------------------------------------------------
*/

$this->pageTitle =
	WebUtils::getMenuItemByKey(
		'products',
		$languageId
	)['label'];


/*
|--------------------------------------------------------------------------
| CSS
|--------------------------------------------------------------------------
*/

Yii::app()->clientScript->registerCssFile(
	$themeUrl .
		'/assets/css/products.css'
);


/*
|--------------------------------------------------------------------------
| PAGE-SPECIFIC CSS
|--------------------------------------------------------------------------
*/

Yii::app()->clientScript->registerCss(
	'catalog-page-inline',
	<<<CSS

.catalog-page {
    width: 100%;
}

.catalog-page__inner {
    width: 100%;
}

.catalog-layout {
    display: grid;
    grid-template-columns: 280px minmax(0, 1fr);
    gap: 40px;
    width: 100%;
}

.catalog-sidebar {
    position: sticky;
    top: calc(var(--made-header-height, 80px) + 24px);
    align-self: start;
    max-height: calc(100vh - var(--made-header-height, 80px) - 48px);
    overflow-y: auto;
}

.catalog-content {
    min-width: 0;
}

.catalog-mobile-filter-button {
    display: none;
}

.catalog-filter-section__heading {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    border: 0;
    background: transparent;
    padding: 14px 0;
    cursor: pointer;
}

.catalog-filter-category__row {
    display: flex;
    align-items: center;
    gap: 8px;
}

.catalog-filter-category__link {
    flex: 1;
}

.catalog-filter-category__toggle {
    flex: 0 0 auto;
    width: 30px;
    height: 30px;
    border: 0;
    background: transparent;
    cursor: pointer;
}

.catalog-filter-subcategories {
    display: none;
    padding-left: 22px;
}

.catalog-filter-category.is-open
.catalog-filter-subcategories {
    display: block;
}

.catalog-filter-category__toggle i {
    transition: transform .2s ease;
}

.catalog-filter-category.is-open
.catalog-filter-category__toggle i {
    transform: rotate(45deg);
}

.catalog-filter-brand,
.catalog-filter-subcategory {
    display: flex;
    align-items: center;
    gap: 9px;
}

.catalog-filter-brand__box {
    width: 15px;
    height: 15px;
    border: 1px solid currentColor;
    flex: 0 0 15px;
}

.catalog-filter-brand.is-selected
.catalog-filter-brand__box {
    position: relative;
}

.catalog-filter-brand.is-selected
.catalog-filter-brand__box::after {
    content: "";
    position: absolute;
    left: 3px;
    top: 0;
    width: 6px;
    height: 10px;
    border-right: 2px solid currentColor;
    border-bottom: 2px solid currentColor;
    transform: rotate(45deg);
}

.catalog-sort {
    position: relative;
}

.catalog-sort select {
    appearance: none;
    -webkit-appearance: none;
    padding-right: 34px;
}

.catalog-sort > i {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    pointer-events: none;
}

.catalog-product__button {
    display: block;
    width: 100%;
    padding: 0;
    border: 0;
    background: transparent;
    text-align: left;
    cursor: pointer;
}

.catalog-product__image-wrap {
    position: relative;
}

.catalog-product__image {
    display: block;
    width: 100%;
    height: auto;
}

.catalog-product__favorite {
    position: absolute;
    right: 14px;
    top: 14px;
}

.catalog-modal {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 2000;
    align-items: center;
    justify-content: center;
    padding: 30px;
    background: rgba(0, 0, 0, .75);
}

.catalog-modal.is-open {
    display: flex;
}

.catalog-modal__dialog {
    position: relative;
    width: min(100%, 1000px);
    max-height: calc(100vh - 60px);
    overflow: auto;
    background: #fff;
}

.catalog-modal__close {
    position: absolute;
    right: 15px;
    top: 15px;
    z-index: 2;
    width: 42px;
    height: 42px;
    border: 0;
    border-radius: 50%;
    background: #fff;
    cursor: pointer;
    box-shadow: 0 2px 12px rgba(0, 0, 0, .15);
}

.catalog-modal__image {
    display: block;
    width: 100%;
    height: auto;
}

.catalog-modal__loading {
    display: none;
    padding: 30px;
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

body.catalog-modal-open {
    overflow: hidden;
}

@media (max-width: 991px) {

    .catalog-layout {
        grid-template-columns: 1fr;
        gap: 25px;
    }

    .catalog-sidebar {
        position: fixed;
        left: 0;
        top: var(--made-header-height, 80px);
        bottom: 0;
        z-index: 1500;
        width: min(340px, 90vw);
        max-height: none;
        padding: 25px;
        overflow-y: auto;
        background: #fff;
        box-shadow: 8px 0 30px rgba(0, 0, 0, .12);
        transform: translateX(-105%);
        transition: transform .25s ease;
    }

    .catalog-sidebar.is-open {
        transform: translateX(0);
    }

    .catalog-mobile-filter-button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
        padding: 10px 16px;
        border: 1px solid #d9dde3;
        background: #fff;
        cursor: pointer;
    }

}

@media (max-width: 767px) {

    .catalog-heading {
        display: block;
    }

    .catalog-heading__tools {
        margin-top: 20px;
    }

    .catalog-modal {
        padding: 15px;
    }

    .catalog-modal__dialog {
        max-height: calc(100vh - 30px);
    }

}

CSS
);


/*
|--------------------------------------------------------------------------
| CATALOG CONTAINER
|--------------------------------------------------------------------------
*/

?>

<div
	class="catalog-page"
	id="productos-page">


	<div
		class="catalog-page__inner"
		id="productos-content">


		<?php

		echo $this->renderPartial(
			'partials/_productos_catalog',
			array(
				'languageId' =>
				$languageId,

				'language' =>
				$language,

				'categories' =>
				$categories,

				'subcategories' =>
				$subcategories,

				'brands' =>
				$brands,

				'products' =>
				$products,

				'dataProvider' =>
				$dataProvider,

				'selectedProduct' =>
				$selectedProduct,

				'selectedCategoryId' =>
				$selectedCategoryId,

				'selectedSubcategoryId' =>
				$selectedSubcategoryId,

				'selectedBrandId' =>
				$selectedBrandId,

				'categoryFilter' =>
				$categoryFilter,

				'subcategoryFilter' =>
				$subcategoryFilter,

				'brandFilter' =>
				$brandFilter,

				'orderFilter' =>
				$orderFilter,

				'productFilter' =>
				$productFilter,
			),
			true
		);

		?>


	</div>


</div>


<?php


/*
|--------------------------------------------------------------------------
| PRODUCT CATALOG JAVASCRIPT
|--------------------------------------------------------------------------
|
| This script is intentionally initialized through a function.
|
| main.php replaces #productos-content when the language
| changes through AJAX. Therefore all event handlers need
| to be registered again after the new HTML is inserted.
|
*/

Yii::app()->clientScript->registerScript(
	'productos-page-script',
	<<<JS

(function($) {

    'use strict';


    /*
    |--------------------------------------------------------------------------
    | GLOBAL NAMESPACE
    |--------------------------------------------------------------------------
    */

    window.MadeProducts =
        window.MadeProducts || {};


    /*
    |--------------------------------------------------------------------------
    | INITIALIZE
    |--------------------------------------------------------------------------
    */

    function initProductsPage() {

        /*
        |--------------------------------------------------------------------------
        | CATEGORY TOGGLES
        |--------------------------------------------------------------------------
        */

        $(document)
            .off(
                'click.madeProducts',
                '[data-category-toggle]'
            )
            .on(
                'click.madeProducts',
                '[data-category-toggle]',
                function(e) {

                    e.preventDefault();

                    e.stopPropagation();


                    var category =
                        $(this)
                            .closest(
                                '.catalog-filter-category'
                            );


                    category.toggleClass(
                        'is-open'
                    );

                }
            );


        /*
        |--------------------------------------------------------------------------
        | FILTER SECTION TOGGLES
        |--------------------------------------------------------------------------
        */

        $(document)
            .off(
                'click.madeProducts',
                '[data-filter-section-toggle]'
            )
            .on(
                'click.madeProducts',
                '[data-filter-section-toggle]',
                function(e) {

                    e.preventDefault();


                    var section =
                        $(this)
                            .closest(
                                '[data-filter-section]'
                            );


                    section.toggleClass(
                        'is-collapsed'
                    );


                    var icon =
                        $(this).find('i');


                    if (
                        section.hasClass(
                            'is-collapsed'
                        )
                    ) {

                        icon
                            .removeClass(
                                'fa-minus'
                            )
                            .addClass(
                                'fa-plus'
                            );

                    } else {

                        icon
                            .removeClass(
                                'fa-plus'
                            )
                            .addClass(
                                'fa-minus'
                            );
                    }

                }
            );


        /*
        |--------------------------------------------------------------------------
        | ORDER
        |--------------------------------------------------------------------------
        */

        $(document)
            .off(
                'change.madeProducts',
                '#catalog-order'
            )
            .on(
                'change.madeProducts',
                '#catalog-order',
                function() {

                    var order =
                        $(this).val();


                    var url =
                        new URL(
                            window.location.href
                        );


                    url.searchParams.set(
                        'orden',
                        order
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Remove product parameter
                    |--------------------------------------------------------------------------
                    */

                    url.searchParams.delete(
                        'producto'
                    );


                    window.location.href =
                        url.toString();

                }
            );


        /*
        |--------------------------------------------------------------------------
        | MOBILE FILTER
        |--------------------------------------------------------------------------
        */

        $(document)
            .off(
                'click.madeProducts',
                '#catalog-mobile-filter-button'
            )
            .on(
                'click.madeProducts',
                '#catalog-mobile-filter-button',
                function(e) {

                    e.preventDefault();


                    $('#catalog-sidebar')
                        .toggleClass(
                            'is-open'
                        );

                }
            );


        /*
        |--------------------------------------------------------------------------
        | CLOSE MOBILE FILTER WHEN CLICKING A LINK
        |--------------------------------------------------------------------------
        */

        $(document)
            .off(
                'click.madeProducts',
                '#catalog-sidebar a'
            )
            .on(
                'click.madeProducts',
                '#catalog-sidebar a',
                function() {

                    $('#catalog-sidebar')
                        .removeClass(
                            'is-open'
                        );

                }
            );


        /*
        |--------------------------------------------------------------------------
        | OPEN PRODUCT MODAL
        |--------------------------------------------------------------------------
        */

        $(document)
            .off(
                'click.madeProducts',
                '[data-product-open]'
            )
            .on(
                'click.madeProducts',
                '[data-product-open]',
                function(e) {

                    e.preventDefault();


                    var product =
                        $(this)
                            .closest(
                                '.catalog-product'
                            );


                    var productId =
                        parseInt(
                            product.data(
                                'product-id'
                            ),
                            10
                        ) || 0;


                    var productName =
                        String(
                            product.data(
                                'product-name'
                            ) || ''
                        );


                    var infographic =
                        String(
                            product.data(
                                'infographic'
                            ) || ''
                        );


                    openProductModal(
                        productId,
                        productName,
                        infographic
                    );

                }
            );


        /*
        |--------------------------------------------------------------------------
        | CLOSE PRODUCT MODAL
        |--------------------------------------------------------------------------
        */

        $(document)
            .off(
                'click.madeProducts',
                '#catalog-modal-close'
            )
            .on(
                'click.madeProducts',
                '#catalog-modal-close',
                function(e) {

                    e.preventDefault();

                    closeProductModal();

                }
            );


        /*
        |--------------------------------------------------------------------------
        | CLOSE MODAL BY BACKDROP
        |--------------------------------------------------------------------------
        */

        $(document)
            .off(
                'click.madeProducts',
                '#catalog-product-modal'
            )
            .on(
                'click.madeProducts',
                '#catalog-product-modal',
                function(e) {

                    if (
                        e.target === this
                    ) {

                        closeProductModal();
                    }

                }
            );


        /*
        |--------------------------------------------------------------------------
        | ESCAPE
        |--------------------------------------------------------------------------
        */

        $(document)
            .off(
                'keydown.madeProducts'
            )
            .on(
                'keydown.madeProducts',
                function(e) {

                    if (
                        e.key === 'Escape' ||
                        e.keyCode === 27
                    ) {

                        closeProductModal();
                    }

                }
            );


        /*
        |--------------------------------------------------------------------------
        | INITIAL PRODUCT FROM URL
        |--------------------------------------------------------------------------
        */

        openInitialProduct();

    }


    /*
    |--------------------------------------------------------------------------
    | OPEN PRODUCT MODAL
    |--------------------------------------------------------------------------
    */

    function openProductModal(
        productId,
        productName,
        infographic
    ) {

        var modal =
            $('#catalog-product-modal');


        if (
            !modal.length
        ) {

            return;
        }


        var image =
            $('#catalog-modal-image');


        var placeholder =
            $('#catalog-modal-placeholder');


        /*
        |--------------------------------------------------------------------------
        | PRODUCT URL
        |--------------------------------------------------------------------------
        */

        if (
            productName !== ''
        ) {

            var url =
                new URL(
                    window.location.href
                );


            var productSlug =
                $('[data-product-id="' +
                    productId +
                    '"]')
                    .data(
                        'product-slug'
                    );


            if (
                productSlug
            ) {

                url.searchParams.set(
                    'producto',
                    productSlug
                );

                window.history.pushState(
                    {
                        producto:
                        productSlug
                    },
                    '',
                    url.toString()
                );

            }

        }


        /*
        |--------------------------------------------------------------------------
        | IMAGE
        |--------------------------------------------------------------------------
        */

        image
            .attr(
                'alt',
                productName
            );


        if (
            infographic !== ''
        ) {

            image
                .attr(
                    'src',
                    infographic
                )
                .show();


            placeholder.hide();

        } else {

            image
                .removeAttr(
                    'src'
                )
                .hide();


            placeholder.show();

        }


        /*
        |--------------------------------------------------------------------------
        | OPEN
        |--------------------------------------------------------------------------
        */

        modal
            .addClass(
                'is-open'
            )
            .attr(
                'aria-hidden',
                'false'
            );


        $('body')
            .addClass(
                'catalog-modal-open'
            );

    }


    /*
    |--------------------------------------------------------------------------
    | CLOSE PRODUCT MODAL
    |--------------------------------------------------------------------------
    */

    function closeProductModal() {

        var modal =
            $('#catalog-product-modal');


        if (
            !modal.length
        ) {

            return;
        }


        modal
            .removeClass(
                'is-open'
            )
            .attr(
                'aria-hidden',
                'true'
            );


        $('body')
            .removeClass(
                'catalog-modal-open'
            );


        /*
        |--------------------------------------------------------------------------
        | REMOVE PRODUCT FROM URL
        |--------------------------------------------------------------------------
        */

        var url =
            new URL(
                window.location.href
            );


        if (
            url.searchParams.has(
                'producto'
            )
        ) {

            url.searchParams.delete(
                'producto'
            );


            window.history.replaceState(
                {},
                '',
                url.toString()
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | OPEN PRODUCT FROM URL
    |--------------------------------------------------------------------------
    */

    function openInitialProduct() {

        var modal =
            $('#catalog-product-modal');


        if (
            !modal.length
        ) {

            return;
        }


        var productFilter =
            new URLSearchParams(
                window.location.search
            ).get(
                'producto'
            );


        if (
            !productFilter
        ) {

            return;
        }


        var products =
            $('.catalog-product');


        var found =
            null;


        products.each(
            function() {

                var product =
                    $(this);


                var slug =
                    String(
                        product.data(
                            'product-slug'
                        ) || ''
                    );


                if (
                    slug ===
                    productFilter
                ) {

                    found =
                        product;

                    return false;
                }

            }
        );


        if (
            !found
        ) {

            return;
        }


        var button =
            found.find(
                '[data-product-open]'
            );


        if (
            button.length
        ) {

            button.trigger(
                'click'
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | PUBLIC REINITIALIZATION
    |--------------------------------------------------------------------------
    */

    window.MadeProducts.init =
        function() {

            initProductsPage();

        };


    /*
    |--------------------------------------------------------------------------
    | INITIAL PAGE LOAD
    |--------------------------------------------------------------------------
    */

    $(function() {

        initProductsPage();

    });


})(jQuery);

JS
);


/*
|--------------------------------------------------------------------------
| PAGE READY EVENT FOR AJAX REINITIALIZATION
|--------------------------------------------------------------------------
|
| main.php can call:
|
| MadeProducts.init()
|
| after replacing #productos-content.
|
*/

?>