<?php
/*
|--------------------------------------------------------------------------
| HERO CAROUSEL
|--------------------------------------------------------------------------
| Bootstrap Carousel
|
| Todo el contenido editable está definido en $heroSlides.
| Posteriormente este arreglo puede reemplazarse directamente
| por información proveniente de la base de datos.
|--------------------------------------------------------------------------
*/

$themeUrl = Yii::app()->baseUrl;


/*
|--------------------------------------------------------------------------
| HERO SLIDES
|--------------------------------------------------------------------------
| Estructura preparada para futura conexión con DB.
|
| Campos:
|
| id          => Identificador del slide
| image       => Ruta relativa de la imagen (OBLIGATORIA)
| alt         => Texto alternativo
| alignment   => left / center / right
| eyebrow     => Texto pequeño superior (OPCIONAL)
| title       => Título principal (OPCIONAL)
| description => Descripción (OPCIONAL)
| active      => Define el slide inicial
| loading     => lazy / eager
|--------------------------------------------------------------------------
*/

$heroSlides = array(

	array(
		'id' => 1,

		'image' => '/images/hero/hero-01.jpg',

		'alt' => 'Representamos marcas que inspiran confianza',

		'alignment' => 'left',

		'eyebrow' => 'CONECTAMOS MARCAS CON MERCADOS',

		'title' => '
			Representamos
			<br>
			marcas que
			<em>inspiran</em>
			<br>
			confianza
		',

		'description' => '
			Llevamos grandes marcas a más personas,
			<br>
			creando experiencias memorables y resultados reales.
		',

		'active' => true,

		'loading' => 'eager'
	),


	array(
		'id' => 2,

		'image' => '/images/hero/hero-02.jpg',

		'alt' => 'Productos que inspiran experiencias',

		'alignment' => 'center',

		'eyebrow' => 'CONECTAMOS MARCAS CON MERCADOS',

		'title' => '
			Llevamos
			<br>
			productos que
			<em>inspiran</em>
			<br>
			experiencias
		',

		'description' => '
			Creamos conexiones entre grandes marcas
			<br>
			y las personas que las eligen.
		',

		'active' => false,

		'loading' => 'lazy'
	),


	array(
		'id' => 3,

		'image' => '/images/hero/hero-03.jpg',

		'alt' => 'Marcas que trascienden',

		'alignment' => 'right',

		'eyebrow' => 'MARCAS QUE TRASCIENDEN',

		'title' => '
			Construimos
			<br>
			marcas que
			<em>conectan</em>
			<br>
			con las personas
		',

		'description' => '
			Representación, distribución y crecimiento
			<br>
			para marcas con visión de futuro.
		',

		'active' => false,

		'loading' => 'lazy'
	),


	array(
		'id' => 4,

		'image' => '/images/hero/hero-04.jpg',

		'alt' => 'Experiencias que dejan huella',

		'alignment' => 'left',

		'eyebrow' => 'EXPERIENCIAS QUE DEJAN HUELLA',

		'title' => '
			Más que
			<br>
			productos,
			<em>creamos</em>
			<br>
			experiencias
		',

		'description' => '
			Acercamos marcas relevantes a nuevos
			<br>
			mercados y consumidores.
		',

		'active' => false,

		'loading' => 'lazy'
	),


	array(
		'id' => 5,

		'image' => '/images/hero/hero-05.jpg',

		'alt' => 'Marcas que generan confianza',

		'alignment' => 'center',

		'eyebrow' => 'NUESTRO COMPROMISO',

		'title' => '
			Marcas que
			<br>
			generan
			<em>confianza</em>
		',

		'description' => '
			Trabajamos para construir relaciones
			<br>
			duraderas y resultados reales.
		',

		'active' => false,

		'loading' => 'lazy'
	)

);

?>

<section
	id="inicio"
	class="hero">

	<div
		id="heroCarousel"
		class="carousel slide hero__carousel"
		data-ride="carousel"
		data-interval="6000"
		data-pause="hover">


		<!--
		|--------------------------------------------------------------------------
		| INDICATORS
		|--------------------------------------------------------------------------
		-->

		<ol class="carousel-indicators hero__indicators">

			<?php foreach ($heroSlides as $index => $slide): ?>

				<li
					data-target="#heroCarousel"
					data-slide-to="<?php echo $index; ?>"
					class="<?php echo !empty($slide['active']) ? 'active' : ''; ?>">
				</li>

			<?php endforeach; ?>

		</ol>


		<!--
		|--------------------------------------------------------------------------
		| SLIDES
		|--------------------------------------------------------------------------
		-->

		<div class="carousel-inner">

			<?php foreach ($heroSlides as $slide): ?>

				<?php

				/*
				 * --------------------------------------------------------------
				 * ALIGNMENT
				 * --------------------------------------------------------------
				 *
				 * Solo permitimos valores conocidos para evitar que
				 * información incorrecta termine directamente en una
				 * clase CSS.
				 */

				$alignment = !empty($slide['alignment'])
					? strtolower(trim($slide['alignment']))
					: 'left';

				$allowedAlignments = array(
					'left',
					'center',
					'right'
				);

				if (!in_array($alignment, $allowedAlignments, true)) {
					$alignment = 'left';
				}


				/*
				 * --------------------------------------------------------------
				 * CONTENT
				 * --------------------------------------------------------------
				 *
				 * Cada elemento de contenido es opcional.
				 */

				$hasEyebrow = !empty($slide['eyebrow']);
				$hasTitle = !empty($slide['title']);
				$hasDescription = !empty($slide['description']);

				$hasContent = (
					$hasEyebrow ||
					$hasTitle ||
					$hasDescription
				);


				/*
				 * --------------------------------------------------------------
				 * SLIDE CLASSES
				 * --------------------------------------------------------------
				 *
				 * Agregamos una clase específica para distinguir entre:
				 *
				 * hero__slide--has-content
				 * hero__slide--image-only
				 */

				$slideClasses = array(
					'item',
					'hero__slide',
					'hero__slide--' . $alignment
				);

				if ($hasContent) {
					$slideClasses[] = 'hero__slide--has-content';
				} else {
					$slideClasses[] = 'hero__slide--image-only';
				}

				if (!empty($slide['active'])) {
					$slideClasses[] = 'active';
				}


				/*
				 * --------------------------------------------------------------
				 * LOADING
				 * --------------------------------------------------------------
				 *
				 * Solo permitimos lazy / eager.
				 */

				$loading = !empty($slide['loading'])
					? strtolower(trim($slide['loading']))
					: 'lazy';

				if (!in_array($loading, array('lazy', 'eager'), true)) {
					$loading = 'lazy';
				}

				?>

				<div
					class="<?php echo implode(' ', $slideClasses); ?>">


					<!--
					|--------------------------------------------------------------------------
					| IMAGE
					|--------------------------------------------------------------------------
					| La imagen es el único elemento obligatorio del Hero.
					|--------------------------------------------------------------------------
					-->

					<img
						src="<?php echo $themeUrl . $slide['image']; ?>"
						alt="<?php echo CHtml::encode(isset($slide['alt']) ? $slide['alt'] : ''); ?>"
						class="hero__image"
						loading="<?php echo $loading; ?>">


					<!--
					|--------------------------------------------------------------------------
					| OVERLAY
					|--------------------------------------------------------------------------
					-->

					<div class="hero__overlay"></div>


					<?php if ($hasContent): ?>

						<!--
						|--------------------------------------------------------------------------
						| CONTENT
						|--------------------------------------------------------------------------
						-->

						<div class="container hero__container">

							<div class="hero__content">


								<?php if ($hasEyebrow): ?>

									<span class="hero__eyebrow">
										<?php echo $slide['eyebrow']; ?>
									</span>

								<?php endif; ?>


								<?php if ($hasTitle): ?>

									<h2 class="hero__title">
										<?php echo $slide['title']; ?>
									</h2>

								<?php endif; ?>


								<?php if ($hasDescription): ?>

									<p class="hero__description">
										<?php echo $slide['description']; ?>
									</p>

								<?php endif; ?>


							</div>

						</div>

					<?php endif; ?>

				</div>

			<?php endforeach; ?>

		</div>


		<!--
		|--------------------------------------------------------------------------
		| PREVIOUS
		|--------------------------------------------------------------------------
		-->

		<a
			class="left carousel-control hero__control hero__control--prev"
			href="#heroCarousel"
			data-slide="prev"
			aria-label="Slide anterior">

			<span
				class="fa fa-chevron-left"
				aria-hidden="true"></span>

			<span class="sr-only">
				Anterior
			</span>

		</a>


		<!--
		|--------------------------------------------------------------------------
		| NEXT
		|--------------------------------------------------------------------------
		-->

		<a
			class="right carousel-control hero__control hero__control--next"
			href="#heroCarousel"
			data-slide="next"
			aria-label="Siguiente slide">

			<span
				class="fa fa-chevron-right"
				aria-hidden="true"></span>

			<span class="sr-only">
				Siguiente
			</span>

		</a>

	</div>

</section>