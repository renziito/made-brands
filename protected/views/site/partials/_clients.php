<?php

$themeUrl = Yii::app()->baseUrl;

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
	class="clients" style="background-color:<?= WebUtils::getSiteSetting('section_background_color') ?>">

	<div class="clients__content">

		<div class="clients__image">

			<img
				src="<?= Yii::app()->getBaseUrl() . $brandSection['image']   ?>"
				alt="<?= $brandSection['eyebrow'] ?>"
				loading="lazy">

		</div>


		<!--
        |--------------------------------------------------------------------------
        | INFORMATION
        |--------------------------------------------------------------------------
        -->

		<div class="clients__info" style="padding-top: 60px;">

			<div class="clients__info-inner">

				<span class="section-label" style="font-family:<?= WebUtils::getSiteSetting('eyebrow_font_family') ?>"><?= $brandSection['eyebrow'] ?></span>

				<h2 class="clients__title" style="font-family:<?= WebUtils::getSiteSetting('heading_font_family') ?>"><?= $brandSection['title'] ?></h2>

				<p class="clients__description" style="font-family:<?= WebUtils::getSiteSetting('body_font_family') ?>"><?= $brandSection['text'] ?></p>


				<!--
                |--------------------------------------------------------------------------
                | FEATURED BRANDS
                |--------------------------------------------------------------------------
                -->

				<div class="clients__featured">


					<span class="clients__featured-label">
						<?= $brandSection['featuredText'] ?>
					</span>


					<div class="clients__featured-grid">

						<?php foreach ($featuredBrands as $brand): ?>

							<?php $brandUrl = trim($brand['website_url']);
							if ($brandUrl && !preg_match('#^https?://#i', $brandUrl)) {
								$brandUrl = 'https://' . ltrim($brandUrl, '/');
							} ?>

							<?php if ($brandUrl): ?>

								<a
									href="<?php echo CHtml::encode($brandUrl); ?>"
									target="_blank"
									rel="noopener noreferrer"
									class="client-brand">

									<img
										src="<?php echo $themeUrl . '/' . CHtml::encode($brand['image']); ?>"
										alt="<?php echo CHtml::encode($brand['name']); ?>"
										loading="lazy">

								</a>

							<?php else: ?>

								<div class="client-brand">

									<img
										src="<?php echo $themeUrl . '/' . CHtml::encode($brand['image']); ?>"
										alt="<?php echo CHtml::encode($brand['name']); ?>"
										loading="lazy">

								</div>

							<?php endif; ?>

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
											src="<?php echo $themeUrl . CHtml::encode($brand['image']); ?>"
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
							<
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
							>
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
							<
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
							>
						</span>

					</a>

				<?php endif; ?>


			</div>

		</div>

	<?php endif; ?>

</section>