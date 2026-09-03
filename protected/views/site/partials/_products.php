<?php
$themeUrl = Yii::app()->baseUrl;

$productsSection = array(
	'title' =>  WebUtils::getMenuItemByKey('our_categories', $languageId)['label'],
	'button_text' => WebUtils::getMenuItemByKey('view_all_products', $languageId)['label'],
	'button_url' => 'productos'
);

?>

<section
	id="productos"
	class="products" style="background-color:<?= WebUtils::getSiteSetting('section_background_color') ?>;padding-top: 60px;">

	<div class="container">

		<div class="products__header">

			<h2 class="products__title" style="font-family:<?= WebUtils::getSiteSetting('heading_font_family') ?>">
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
									'productos',
									array(
										'categoria' => strtolower($category['name'])
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
								src="<?php echo $themeUrl . '/' .  $category['image']; ?>"
								alt="<?php echo CHtml::encode($category['alt']); ?>"
								loading="lazy">

						</div>


						<!--
						|--------------------------------------------------------------------------
						| CATEGORY NAME
						|--------------------------------------------------------------------------
						-->

						<div class="product-card__name" style="background-color:<?= WebUtils::getSiteSetting('category_button_background_color') ?>!important ;color:<?= WebUtils::getSiteSetting('category_button_text_color') ?> !important;font-family:<?= WebUtils::getSiteSetting('body_font_family') ?>">

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
				class="products__button" style="font-family:<?= WebUtils::getSiteSetting('button_font_family') ?>">

				<?php echo CHtml::encode(
					$productsSection['button_text']
				); ?>

			</a>

		</div>


	</div>

</section>