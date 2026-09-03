<?php

$themeUrl = Yii::app()->baseUrl;

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
	class="business" style="background-color:<?= WebUtils::getSiteSetting('section_alt_background_color') ?>">

	<div class="container">


		<!--
		|--------------------------------------------------------------------------
		| SECTION HEADER
		|--------------------------------------------------------------------------
		-->

		<div class="business__header">

			<h2 class="business__title" style="font-family:<?= WebUtils::getSiteSetting('heading_font_family') ?>;padding-top: 60px;">
				<?= WebUtils::getMenuItemByKey('our_businesses', $languageId)['label'] ?>
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

							<h3 class="business-card__title" style="font-family:<?= WebUtils::getSiteSetting('heading_font_family') ?>">
								<?php echo CHtml::encode($business['title']); ?>
							</h3>


							<p class="business-card__description" style="font-family:<?= WebUtils::getSiteSetting('body_font_family') ?>">
								<?php echo $business['description']; ?>
							</p>

						</div>

					</article>

				</div>

			<?php endforeach; ?>

		</div>


	</div>

</section>