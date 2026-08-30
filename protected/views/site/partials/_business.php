<?php
/*
|--------------------------------------------------------------------------
| NUESTROS NEGOCIOS
|--------------------------------------------------------------------------
|
| Todo el contenido editable está definido en $businesses.
|
| Posteriormente este arreglo puede reemplazarse directamente
| por información proveniente de la base de datos.
|
|--------------------------------------------------------------------------
*/

$themeUrl = Yii::app()->baseUrl;


/*
|--------------------------------------------------------------------------
| BUSINESSES
|--------------------------------------------------------------------------
| Estructura preparada para futura conexión con DB.
|
| Campos:
|
| id          => Identificador del negocio
| title       => Nombre del negocio
| description => Descripción
| image       => Imagen principal
| alt         => Texto alternativo de la imagen
| icon        => Clase Font Awesome
| status      => 1 activo / 0 inactivo
| sort_order  => Orden de aparición
|--------------------------------------------------------------------------
*/



$businesses2 = array(

	array(
		'id' => 1,

		'title' => 'Consumo masivo',

		'description' => '
			Llevamos productos de calidad a las
			góndolas de todo el país.
		',

		'image' => '/images/business/consumo-masivo.png',

		'alt' => 'Consumo masivo',

		'icon' => 'fa-shopping-cart',

		'status' => 1,

		'sort_order' => 1
	),


	array(
		'id' => 2,

		'title' => 'Soluciones B2B',

		'description' => '
			Soluciones a medida para empresas,
			instituciones y canales profesionales.
		',

		'image' => '/images/business/soluciones-b2b.png',

		'alt' => 'Soluciones B2B',

		'icon' => 'fa-cutlery',

		'status' => 1,

		'sort_order' => 2
	)

);



/*
|--------------------------------------------------------------------------
| FILTER ACTIVE BUSINESSES
|--------------------------------------------------------------------------
*/

$activeBusinesses = array_filter(
	$businesses,
	function ($business) {

		return !empty($business['status']);
	}
);


/*
|--------------------------------------------------------------------------
| SORT BUSINESSES
|--------------------------------------------------------------------------
*/

usort(
	$activeBusinesses,
	function ($a, $b) {

		return $a['sort_order'] - $b['sort_order'];
	}
);

?>

<section
	id="negocios"
	class="business">

	<div class="container">


		<!--
		|--------------------------------------------------------------------------
		| SECTION HEADER
		|--------------------------------------------------------------------------
		-->

		<div class="business__header">

			<h2 class="business__title">
				Nuestros negocios
			</h2>


			<div class="business__title-line"></div>

		</div>


		<!--
		|--------------------------------------------------------------------------
		| BUSINESS GRID
		|--------------------------------------------------------------------------
		-->

		<div class="row business__grid">

			<?php foreach ($activeBusinesses as $business): ?>

				<div class="col-sm-6">

					<article class="business-card">


						<!--
						|--------------------------------------------------------------------------
						| IMAGE
						|--------------------------------------------------------------------------
						-->

						<div class="business-card__image">

							<img
								src="<?php echo $themeUrl . $business['image']; ?>"
								alt="<?php echo CHtml::encode($business['alt']); ?>"
								loading="lazy">

						</div>


						<!--
						|--------------------------------------------------------------------------
						| ICON
						|--------------------------------------------------------------------------
						-->

						<div class="business-card__icon">

							<i
								class="<?php echo CHtml::encode($business['icon']); ?>"
								aria-hidden="true"></i>

						</div>


						<!--
						|--------------------------------------------------------------------------
						| CONTENT
						|--------------------------------------------------------------------------
						-->

						<div class="business-card__content">

							<h3 class="business-card__title">
								<?php echo CHtml::encode($business['title']); ?>
							</h3>


							<p class="business-card__description">
								<?php echo $business['description']; ?>
							</p>

						</div>

					</article>

				</div>

			<?php endforeach; ?>

		</div>


	</div>

</section>