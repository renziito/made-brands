<?php
/*
|--------------------------------------------------------------------------
| PRODUCTS / CATEGORÍAS
|--------------------------------------------------------------------------
| Product categories / featured products section.
|
| Todo el contenido editable está definido arriba.
| Posteriormente estos datos pueden reemplazarse directamente
| por información proveniente de la base de datos.
|--------------------------------------------------------------------------
*/

$themeUrl = Yii::app()->baseUrl;


/*
|--------------------------------------------------------------------------
| SECTION CONTENT
|--------------------------------------------------------------------------
*/

$productsSection = array(

	'title' => 'Nuestras categorías',

	'button_text' => 'Ver todos los productos',

	'button_url' => 'product/index'

);


/*
|--------------------------------------------------------------------------
| PRODUCT CATEGORIES
|--------------------------------------------------------------------------
|
| Campos:
|
| id          => Identificador de la categoría
| name        => Nombre visible
| slug        => Identificador utilizado en la URL
| image       => Imagen de la categoría
| alt         => Texto alternativo
| status      => 1 activo / 0 inactivo
| featured    => 1 destacada / 0 no destacada
| sort_order  => Orden de aparición
|--------------------------------------------------------------------------
*/

$productCategories = array(

	array(
		'id' => 1,

		'name' => 'Chocolates',

		'slug' => 'chocolates',

		'image' => '/images/categories/chocolate.png',

		'alt' => 'Chocolates',

		'status' => 1,

		'featured' => 1,

		'sort_order' => 1
	),


	array(
		'id' => 2,

		'name' => 'Salsas',

		'slug' => 'salsas',

		'image' => '/images/categories/salsas.png',

		'alt' => 'Salsas',

		'status' => 1,

		'featured' => 1,

		'sort_order' => 2
	),


	array(
		'id' => 3,

		'name' => 'Galletas',

		'slug' => 'galletas',

		'image' => '/images/categories/galletas.png',

		'alt' => 'Galletas',

		'status' => 1,

		'featured' => 1,

		'sort_order' => 3
	),


	array(
		'id' => 4,

		'name' => 'Hogar',

		'slug' => 'hogar',

		'image' => '/images/categories/hogar.png',

		'alt' => 'Hogar',

		'status' => 1,

		'featured' => 1,

		'sort_order' => 4
	),


	array(
		'id' => 5,

		'name' => 'Snacks',

		'slug' => 'snacks',

		'image' => '/images/categories/snacks.png',

		'alt' => 'Snacks',

		'status' => 1,

		'featured' => 1,

		'sort_order' => 5
	),


	array(
		'id' => 6,

		'name' => 'Cuidado personal',

		'slug' => 'cuidado-personal',

		'image' => '/images/categories/cuidado.png',

		'alt' => 'Cuidado personal',

		'status' => 1,

		'featured' => 1,

		'sort_order' => 6
	)

);


/*
|--------------------------------------------------------------------------
| FILTER CATEGORIES
|--------------------------------------------------------------------------
| Only active and featured categories are displayed in this section.
|--------------------------------------------------------------------------
*/

$featuredCategories = array_filter(
	$productCategories,
	function ($category) {

		return !empty($category['status'])
			&& !empty($category['featured']);
	}
);


/*
|--------------------------------------------------------------------------
| SORT CATEGORIES
|--------------------------------------------------------------------------
*/

usort(
	$featuredCategories,
	function ($a, $b) {

		return $a['sort_order'] - $b['sort_order'];
	}
);

?>

<section
	id="productos"
	class="products">

	<div class="container">


		<!--
		|--------------------------------------------------------------------------
		| SECTION HEADER
		|--------------------------------------------------------------------------
		-->

		<div class="products__header">

			<h2 class="products__title">
				<?php echo CHtml::encode($productsSection['title']); ?>
			</h2>


			<div class="products__title-line"></div>

		</div>


		<!--
		|--------------------------------------------------------------------------
		| PRODUCTS GRID
		|--------------------------------------------------------------------------
		-->

		<div class="row products__grid">

			<?php foreach ($featuredCategories as $category): ?>

				<div class="col-md-3 col-sm-6">

					<a
						href="<?php echo $this->createUrl(
									'product/index',
									array(
										'category' => $category['slug']
									)
								); ?>"
						class="product-card">


						<!--
						|--------------------------------------------------------------------------
						| CATEGORY IMAGE
						|--------------------------------------------------------------------------
						-->

						<div class="product-card__image">

							<img
								src="<?php echo $themeUrl . $category['image']; ?>"
								alt="<?php echo CHtml::encode($category['alt']); ?>"
								loading="lazy">

						</div>


						<!--
						|--------------------------------------------------------------------------
						| CATEGORY NAME
						|--------------------------------------------------------------------------
						-->

						<div class="product-card__name">

							<?php echo CHtml::encode($category['name']); ?>

						</div>

					</a>

				</div>

			<?php endforeach; ?>

		</div>


		<!--
		|--------------------------------------------------------------------------
		| ALL PRODUCTS
		|--------------------------------------------------------------------------
		-->

		<div class="products__action">

			<a
				href="<?php echo $this->createUrl(
							$productsSection['button_url']
						); ?>"
				class="products__button">

				<?php echo CHtml::encode(
					$productsSection['button_text']
				); ?>

			</a>

		</div>


	</div>

</section>