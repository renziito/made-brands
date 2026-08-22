<?php
/*
|--------------------------------------------------------------------------
| NUESTROS CLIENTES
|--------------------------------------------------------------------------
| Client / retail presence section.
|--------------------------------------------------------------------------
*/

$themeUrl = Yii::app()->baseUrl;


/*
|--------------------------------------------------------------------------
| FEATURED BRANDS
|--------------------------------------------------------------------------
| These are the brands shown in the featured brands cards.
|--------------------------------------------------------------------------
*/

$featuredBrands = array(

	array(
		'name' => 'La Tradición Molienda',
		'image' => 'molienda.png'
	),

	array(
		'name' => 'Mercado Verde',
		'image' => 'mercado-verde.png'
	),

	array(
		'name' => 'Disco',
		'image' => 'disco.png'
	)

);


/*
|--------------------------------------------------------------------------
| ALL CLIENT / BRAND LOGOS
|--------------------------------------------------------------------------
| The carousel automatically creates one slide per 5 brands.
|--------------------------------------------------------------------------
*/

$brands = array(

	array(
		'name' => 'Devoto',
		'image' => 'devoto.png'
	),

	array(
		'name' => 'Ta-Ta',
		'image' => 'ta-ta.png'
	),

	array(
		'name' => 'Géant',
		'image' => 'geant.png'
	),

	array(
		'name' => 'Macro Mercado',
		'image' => 'macro-mercado.png'
	),

	array(
		'name' => 'PedidosYa',
		'image' => 'pedidos-ya.png'
	),

	array(
		'name' => 'Club del Este',
		'image' => 'club-del-este.png'
	),

	array(
		'name' => 'Radisson',
		'image' => 'radisson.png'
	)
);


/*
|--------------------------------------------------------------------------
| CREATE CAROUSEL PAGES
|--------------------------------------------------------------------------
| 5 brands per page.
|--------------------------------------------------------------------------
*/

$brandPagesDesktop = array_chunk(
	$brands,
	5
);

$brandPagesMobile = array_chunk(
	$brands,
	3
);
?>

<section
	id="clientes"
	class="clients">


	<!--
    |--------------------------------------------------------------------------
    | MAIN CONTENT
    |--------------------------------------------------------------------------
    -->

	<div class="clients__content">


		<!--
        |--------------------------------------------------------------------------
        | IMAGE
        |--------------------------------------------------------------------------
        -->

		<div class="clients__image">

			<img
				src="<?php echo $themeUrl; ?>/images/team/clientes-collage.png"
				alt="Nuestros clientes"
				loading="lazy">

		</div>


		<!--
        |--------------------------------------------------------------------------
        | INFORMATION
        |--------------------------------------------------------------------------
        -->

		<div class="clients__info">

			<div class="clients__info-inner">


				<!--
                |--------------------------------------------------------------------------
                | EYEBROW
                |--------------------------------------------------------------------------
                -->

				<span class="section-label">
					Nuestros clientes
				</span>


				<!--
                |--------------------------------------------------------------------------
                | TITLE
                |--------------------------------------------------------------------------
                -->

				<h2 class="clients__title">
					Estamos donde
					<br>
					vos estás
				</h2>


				<!--
                |--------------------------------------------------------------------------
                | DESCRIPTION
                |--------------------------------------------------------------------------
                -->

				<p class="clients__description">

					Nuestras marcas llegan a miles de puntos de venta
					en todo el país, acompañando cada momento.

				</p>


				<!--
                |--------------------------------------------------------------------------
                | FEATURED BRANDS
                |--------------------------------------------------------------------------
                -->

				<div class="clients__featured">


					<span class="clients__featured-label">
						Marcas destacadas
					</span>


					<div class="clients__featured-grid">

						<?php foreach (
							$featuredBrands
							as $brand
						): ?>

							<div class="client-brand">

								<img
									src="<?php echo $themeUrl; ?>/images/brands/<?php echo CHtml::encode($brand['image']); ?>"
									alt="<?php echo CHtml::encode($brand['name']); ?>"
									loading="lazy">

							</div>

						<?php endforeach; ?>

					</div>

				</div>


			</div>

		</div>

	</div>


	<!--
    |--------------------------------------------------------------------------
    | BRAND CAROUSEL
    |--------------------------------------------------------------------------
    -->

	<!--
|--------------------------------------------------------------------------
| BRAND CAROUSEL
|--------------------------------------------------------------------------
-->

	<?php if (!empty($brands)): ?>

		<div class="clients__brands">


			<!--
        |--------------------------------------------------------------------------
        | DESKTOP / TABLET CAROUSEL
        |--------------------------------------------------------------------------
        | 5 brands per page.
        |--------------------------------------------------------------------------
        -->

			<div
				id="clientsCarouselDesktop"
				class="carousel slide clients__carousel clients__carousel--desktop"
				data-ride="carousel"
				data-interval="5000"
				data-pause="hover">


				<?php if (count($brandPagesDesktop) > 1): ?>

					<ol class="carousel-indicators clients__indicators">

						<?php foreach (
							$brandPagesDesktop
							as $pageIndex => $page
						): ?>

							<li
								data-target="#clientsCarouselDesktop"
								data-slide-to="<?php echo $pageIndex; ?>"
								class="<?php echo $pageIndex === 0 ? 'active' : ''; ?>"></li>

						<?php endforeach; ?>

					</ol>

				<?php endif; ?>


				<div class="carousel-inner">

					<?php foreach (
						$brandPagesDesktop
						as $pageIndex => $page
					): ?>

						<div
							class="item <?php echo $pageIndex === 0 ? 'active' : ''; ?>">

							<div class="clients__logos">

								<?php foreach ($page as $brand): ?>

									<div class="clients__logo">

										<img
											src="<?php echo $themeUrl; ?>/images/brands/<?php echo CHtml::encode($brand['image']); ?>"
											alt="<?php echo CHtml::encode($brand['name']); ?>"
											loading="lazy">

									</div>

								<?php endforeach; ?>

							</div>

						</div>

					<?php endforeach; ?>

				</div>


				<?php if (count($brandPagesDesktop) > 1): ?>

					<a
						class="left carousel-control clients__control clients__control--prev"
						href="#clientsCarouselDesktop"
						data-slide="prev"
						aria-label="Marcas anteriores">

						<span
							class="fa fa-chevron-left"
							aria-hidden="true"></span>

						<span class="sr-only">
							Anterior
						</span>

					</a>


					<a
						class="right carousel-control clients__control clients__control--next"
						href="#clientsCarouselDesktop"
						data-slide="next"
						aria-label="Siguientes marcas">

						<span
							class="fa fa-chevron-right"
							aria-hidden="true"></span>

						<span class="sr-only">
							Siguiente
						</span>

					</a>

				<?php endif; ?>

			</div>


			<!--
        |--------------------------------------------------------------------------
        | MOBILE CAROUSEL
        |--------------------------------------------------------------------------
        | 3 brands per page.
        |--------------------------------------------------------------------------
        -->

			<div
				id="clientsCarouselMobile"
				class="carousel slide clients__carousel clients__carousel--mobile"
				data-ride="carousel"
				data-interval="5000"
				data-pause="hover">


				<?php if (count($brandPagesMobile) > 1): ?>

					<ol class="carousel-indicators clients__indicators">

						<?php foreach (
							$brandPagesMobile
							as $pageIndex => $page
						): ?>

							<li
								data-target="#clientsCarouselMobile"
								data-slide-to="<?php echo $pageIndex; ?>"
								class="<?php echo $pageIndex === 0 ? 'active' : ''; ?>"></li>

						<?php endforeach; ?>

					</ol>

				<?php endif; ?>


				<div class="carousel-inner">

					<?php foreach (
						$brandPagesMobile
						as $pageIndex => $page
					): ?>

						<div
							class="item <?php echo $pageIndex === 0 ? 'active' : ''; ?>">

							<div class="clients__logos">

								<?php foreach ($page as $brand): ?>

									<div class="clients__logo">

										<img
											src="<?php echo $themeUrl; ?>/images/brands/<?php echo CHtml::encode($brand['image']); ?>"
											alt="<?php echo CHtml::encode($brand['name']); ?>"
											loading="lazy">

									</div>

								<?php endforeach; ?>

							</div>

						</div>

					<?php endforeach; ?>

				</div>


				<?php if (count($brandPagesMobile) > 1): ?>

					<a
						class="left carousel-control clients__control clients__control--prev"
						href="#clientsCarouselMobile"
						data-slide="prev"
						aria-label="Marcas anteriores">

						<span
							class="fa fa-chevron-left"
							aria-hidden="true"></span>

						<span class="sr-only">
							Anterior
						</span>

					</a>


					<a
						class="right carousel-control clients__control clients__control--next"
						href="#clientsCarouselMobile"
						data-slide="next"
						aria-label="Siguientes marcas">

						<span
							class="fa fa-chevron-right"
							aria-hidden="true"></span>

						<span class="sr-only">
							Siguiente
						</span>

					</a>

				<?php endif; ?>


			</div>

		</div>

	<?php endif; ?>

</section>