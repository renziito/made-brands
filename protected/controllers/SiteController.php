<?php

class SiteController extends Controller
{
	public function actionIndex()
	{
		$languageCode = Yii::app()->session->get('language', 'es');
		$language = Languages::model()->find('code = :code', array(':code' => $languageCode));
		$languageId = $language ? $language->id : null;

		$heroSlides = WebUtils::getHero($languageId);
		$introContent = WebUtils::getIntro($languageId);
		$businesses = WebUtils::getBusinesses($languageId);
		$featuredCategories = WebUtils::getProductCategories($languageId);
		$brandSection = WebUtils::getBrandSection($languageId);
		$featuredBrands = WebUtils::getFeaturedBrands();
		$brands = WebUtils::getBrands();
		$faqItems = WebUtils::getFaqItems($languageId);

		$this->render('index', array(
			'languageId' => $languageId,
			'heroSlides' => $heroSlides,
			'introContent' => $introContent,
			'businesses' => $businesses,
			'featuredCategories' => $featuredCategories,
			'brandSection' => $brandSection,
			'featuredBrands' => $featuredBrands,
			'brands' => $brands,
			'faqItems' => $faqItems
		));
	}


	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if ($error = Yii::app()->errorHandler->error) {
			if (Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model = new LoginForm;
		if (isset($_POST['LoginForm'])) {
			$model->attributes = $_POST['LoginForm'];
			if ($model->validate() && $model->login())
				$this->redirect(Yii::app()->createAbsoluteUrl('cpanel'));
		}
		$this->render('login', array('model' => $model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

	/**
	 * Displays the public product catalog.
	 *
	 * Supported URL filters:
	 *
	 * /site/productos
	 * /site/productos?categoria=chocolates
	 * /site/productos?subcategoria=chocolate-con-leche
	 * /site/productos?marca=mercado-verde
	 * /site/productos?categoria=chocolates&subcategoria=chocolate-con-leche
	 * /site/productos?producto=chocolate-clasico
	 *
	 * The product parameter opens the infographic modal.
	 */
	public function actionProductos()
	{
		/*
		 * ==========================================================
		 * LANGUAGE
		 * ==========================================================
		 */

		$languageCode = Yii::app()->session->get(
			'language',
			'es'
		);

		$language = Languages::model()->find(
			'code = :code',
			array(
				':code' => $languageCode,
			)
		);

		$languageId = $language
			? (int) $language->id
			: null;

		$defaultLanguage = Languages::model()->findByAttributes(
			array(
				'is_default' => 1,
			)
		);

		$defaultLanguageId = $defaultLanguage
			? (int) $defaultLanguage->id
			: $languageId;


		/*
		 * ==========================================================
		 * QUERY PARAMETERS
		 * ==========================================================
		 */

		$categoryFilter = trim((string) Yii::app()->request->getQuery(
			'categoria',
			''
		));

		$subcategoryFilter = trim((string) Yii::app()->request->getQuery(
			'subcategoria',
			''
		));

		$brandFilter = trim((string) Yii::app()->request->getQuery(
			'marca',
			''
		));

		$orderFilter = trim((string) Yii::app()->request->getQuery(
			'orden',
			'recientes'
		));

		$productFilter = trim((string) Yii::app()->request->getQuery(
			'producto',
			''
		));


		/*
		 * ==========================================================
		 * CATEGORIES
		 * ==========================================================
		 *
		 * Categories do not have their own slug column.
		 * The public URL therefore uses either:
		 *
		 * - numeric ID
		 * - translated name converted to a URL slug
		 *
		 */

		$categories = Categories::model()->findAll(array(
			'condition' => 'is_active = 1',
			'order' => 'sort_order ASC, id ASC',
		));

		$categoryIds = array();

		foreach ($categories as $category) {
			$categoryIds[] = (int) $category->id;
		}


		/*
		 * Category translations.
		 */
		$categoryTranslations = array();

		if ($categoryIds) {

			$criteria = new CDbCriteria;

			$criteria->addInCondition(
				'category_id',
				$categoryIds
			);

			$criteria->addCondition(
				'language_id IN (:current_language, :default_language)'
			);

			$criteria->params[':current_language'] =
				(int) $languageId;

			$criteria->params[':default_language'] =
				(int) $defaultLanguageId;

			$rows = CategoryTranslations::model()->findAll(
				$criteria
			);

			foreach ($rows as $row) {

				$categoryId = (int) $row->category_id;
				$translationLanguageId = (int) $row->language_id;

				if (!isset(
					$categoryTranslations[$categoryId]
				)) {
					$categoryTranslations[$categoryId] = array();
				}

				$categoryTranslations[$categoryId][$translationLanguageId] = $row;
			}
		}


		/*
		 * Prepare public category names.
		 */
		$categoryData = array();
		$categoryNameById = array();
		$categorySlugById = array();

		foreach ($categories as $category) {

			$id = (int) $category->id;

			$name = '';

			if (
				isset($categoryTranslations[$id]) &&
				isset($categoryTranslations[$id][$languageId])
			) {
				$name =
					trim(
						(string) $categoryTranslations[$id][$languageId]->name
					);
			}

			if (
				$name === '' &&
				isset($categoryTranslations[$id]) &&
				isset($categoryTranslations[$id][$defaultLanguageId])
			) {
				$name =
					trim(
						(string) $categoryTranslations[$id][$defaultLanguageId]->name
					);
			}

			if ($name === '') {
				$name = 'Categoría #' . $id;
			}

			$slug = $this->publicSlug($name);

			$categoryData[] = array(
				'model' => $category,
				'id' => $id,
				'name' => $name,
				'slug' => $slug,
				'subcategories' => array(),
			);

			$categoryNameById[$id] = $name;
			$categorySlugById[$id] = $slug;
		}


		/*
		 * ==========================================================
		 * SUBCATEGORIES
		 * ==========================================================
		 */

		$subcategories = Subcategories::model()->findAll(array(
			'condition' => 'is_active = 1',
			'order' => 'category_id ASC, sort_order ASC, id ASC',
		));

		$subcategoryIds = array();

		foreach ($subcategories as $subcategory) {
			$subcategoryIds[] = (int) $subcategory->id;
		}

		$subcategoryTranslations = array();

		if ($subcategoryIds) {

			$criteria = new CDbCriteria;

			$criteria->addInCondition(
				'subcategory_id',
				$subcategoryIds
			);

			$criteria->addCondition(
				'language_id IN (:current_language, :default_language)'
			);

			$criteria->params[':current_language'] =
				(int) $languageId;

			$criteria->params[':default_language'] =
				(int) $defaultLanguageId;

			$rows = SubcategoryTranslations::model()->findAll(
				$criteria
			);

			foreach ($rows as $row) {

				$subcategoryId =
					(int) $row->subcategory_id;

				$translationLanguageId =
					(int) $row->language_id;

				if (!isset(
					$subcategoryTranslations[$subcategoryId]
				)) {
					$subcategoryTranslations[$subcategoryId] =
						array();
				}

				$subcategoryTranslations[$subcategoryId][$translationLanguageId] = $row;
			}
		}


		$subcategoryData = array();
		$subcategoryNameById = array();
		$subcategorySlugById = array();
		$subcategoryCategoryById = array();

		foreach ($subcategories as $subcategory) {

			$id = (int) $subcategory->id;
			$categoryId = (int) $subcategory->category_id;

			$name = '';

			if (
				isset($subcategoryTranslations[$id]) &&
				isset($subcategoryTranslations[$id][$languageId])
			) {
				$name =
					trim(
						(string) $subcategoryTranslations[$id][$languageId]->name
					);
			}

			if (
				$name === '' &&
				isset($subcategoryTranslations[$id]) &&
				isset($subcategoryTranslations[$id][$defaultLanguageId])
			) {
				$name =
					trim(
						(string) $subcategoryTranslations[$id][$defaultLanguageId]->name
					);
			}

			if ($name === '') {
				$name = 'Subcategoría #' . $id;
			}

			$slug = $this->publicSlug($name);

			$data = array(
				'model' => $subcategory,
				'id' => $id,
				'category_id' => $categoryId,
				'name' => $name,
				'slug' => $slug,
			);

			$subcategoryData[] = $data;

			$subcategoryNameById[$id] = $name;
			$subcategorySlugById[$id] = $slug;
			$subcategoryCategoryById[$id] = $categoryId;

			foreach ($categoryData as &$categoryItem) {

				if ((int) $categoryItem['id'] === $categoryId) {

					$categoryItem['subcategories'][] =
						$data;

					break;
				}
			}

			unset($categoryItem);
		}


		/*
		 * ==========================================================
		 * RESOLVE CATEGORY FILTER
		 * ==========================================================
		 */

		$selectedCategoryId = null;

		if ($categoryFilter !== '') {

			if (ctype_digit($categoryFilter)) {

				$candidateId = (int) $categoryFilter;

				if (isset($categoryNameById[$candidateId])) {
					$selectedCategoryId = $candidateId;
				}
			} else {

				foreach ($categorySlugById as $id => $slug) {

					if ($slug === $this->publicSlug($categoryFilter)) {

						$selectedCategoryId = (int) $id;

						break;
					}
				}
			}
		}


		/*
		 * ==========================================================
		 * RESOLVE SUBCATEGORY FILTER
		 * ==========================================================
		 */

		$selectedSubcategoryId = null;

		if ($subcategoryFilter !== '') {

			if (ctype_digit($subcategoryFilter)) {

				$candidateId = (int) $subcategoryFilter;

				if (isset($subcategoryNameById[$candidateId])) {

					$selectedSubcategoryId =
						$candidateId;
				}
			} else {

				foreach ($subcategorySlugById as $id => $slug) {

					if (
						$slug ===
						$this->publicSlug($subcategoryFilter)
					) {

						$selectedSubcategoryId =
							(int) $id;

						break;
					}
				}
			}

			/*
			 * If a subcategory was selected together with a
			 * category, make sure both filters are consistent.
			 */
			if (
				$selectedSubcategoryId !== null &&
				$selectedCategoryId !== null &&
				isset(
					$subcategoryCategoryById[$selectedSubcategoryId]
				) &&
				(int) $subcategoryCategoryById[$selectedSubcategoryId] !== $selectedCategoryId
			) {

				$selectedSubcategoryId = null;
			}

			/*
			 * Selecting a subcategory also selects its parent
			 * category automatically.
			 */
			if (
				$selectedSubcategoryId !== null &&
				$selectedCategoryId === null &&
				isset(
					$subcategoryCategoryById[$selectedSubcategoryId]
				)
			) {

				$selectedCategoryId =
					(int) $subcategoryCategoryById[$selectedSubcategoryId];
			}
		}


		/*
		 * ==========================================================
		 * BRANDS
		 * ==========================================================
		 */

		$brands = Brands::model()->findAll(array(
			'condition' => 'is_active = 1',
			'order' => 'sort_order ASC, name ASC, id ASC',
		));

		$brandIdsBySlug = array();

		foreach ($brands as $brand) {

			$brandIdsBySlug[$this->publicSlug($brand->slug)] = (int) $brand->id;
		}

		$selectedBrandId = null;

		if ($brandFilter !== '') {

			if (ctype_digit($brandFilter)) {

				foreach ($brands as $brand) {

					if ((int) $brand->id === (int) $brandFilter) {

						$selectedBrandId =
							(int) $brand->id;

						break;
					}
				}
			} else {

				$brandSlug =
					$this->publicSlug($brandFilter);

				if (isset($brandIdsBySlug[$brandSlug])) {

					$selectedBrandId =
						$brandIdsBySlug[$brandSlug];
				}
			}
		}


		/*
		 * ==========================================================
		 * PRODUCTS
		 * ==========================================================
		 */

		$criteria = new CDbCriteria;

		$criteria->addCondition(
			"(status = 'published' OR status = 'publicado')"
		);

	

		if ($selectedCategoryId !== null) {

			$criteria->addCondition(
				'id IN (
					SELECT product_id
					FROM product_categories
					WHERE category_id = :catalog_category_id
				)'
			);

			$criteria->params[':catalog_category_id'] =
				$selectedCategoryId;
		}


		if ($selectedSubcategoryId !== null) {

			$criteria->addCondition(
				'id IN (
					SELECT product_id
					FROM product_subcategories
					WHERE subcategory_id = :catalog_subcategory_id
				)'
			);

			$criteria->params[':catalog_subcategory_id'] =
				$selectedSubcategoryId;
		}


		if ($selectedBrandId !== null) {

			$criteria->addCondition(
				'brand_id = :catalog_brand_id'
			);

			$criteria->params[':catalog_brand_id'] =
				$selectedBrandId;
		}


		switch ($orderFilter) {

			case 'nombre':
				$criteria->order =
					'LOWER(slug) ASC, id ASC';
				break;

			case 'antiguos':
				$criteria->order =
					'published_at ASC, id ASC';
				break;

			case 'orden':
				$criteria->order =
					'sort_order ASC, id ASC';
				break;

			case 'recientes':
			default:
				$orderFilter = 'recientes';

				$criteria->order =
					'published_at DESC, sort_order DESC, id DESC';
				break;
		}


		$products = Products::model()->findAll(
			$criteria
		);


		/*
		 * ==========================================================
		 * PRODUCT TRANSLATIONS
		 * ==========================================================
		 */

		$productIds = array();

		foreach ($products as $product) {
			$productIds[] = (int) $product->id;
		}

		$productTranslations = array();

		if ($productIds) {

			$criteriaTranslations = new CDbCriteria;

			$criteriaTranslations->addInCondition(
				'product_id',
				$productIds
			);

			$criteriaTranslations->addCondition(
				'language_id IN (:product_current_language, :product_default_language)'
			);

			$criteriaTranslations->params[':product_current_language'] = (int) $languageId;

			$criteriaTranslations->params[':product_default_language'] = (int) $defaultLanguageId;

			$rows = ProductTranslations::model()->findAll(
				$criteriaTranslations
			);

			foreach ($rows as $row) {

				$productId = (int) $row->product_id;
				$translationLanguageId =
					(int) $row->language_id;

				if (!isset(
					$productTranslations[$productId]
				)) {
					$productTranslations[$productId] =
						array();
				}

				$productTranslations[$productId][$translationLanguageId] = $row;
			}
		}


		/*
		 * ==========================================================
		 * PRODUCT TAXONOMY
		 * ==========================================================
		 */

		$productCategories = array();
		$productSubcategories = array();

		if ($productIds) {

			$criteriaProductCategories =
				new CDbCriteria;

			$criteriaProductCategories->addInCondition(
				'product_id',
				$productIds
			);

			$rows =
				ProductCategories::model()->findAll(
					$criteriaProductCategories
				);

			foreach ($rows as $row) {

				$productId = (int) $row->product_id;
				$categoryId = (int) $row->category_id;

				if (!isset($productCategories[$productId])) {
					$productCategories[$productId] =
						array();
				}

				$productCategories[$productId][] =
					$categoryId;
			}


			$criteriaProductSubcategories =
				new CDbCriteria;

			$criteriaProductSubcategories->addInCondition(
				'product_id',
				$productIds
			);

			$rows =
				ProductSubcategories::model()->findAll(
					$criteriaProductSubcategories
				);

			foreach ($rows as $row) {

				$productId = (int) $row->product_id;
				$subcategoryId =
					(int) $row->subcategory_id;

				if (!isset(
					$productSubcategories[$productId]
				)) {
					$productSubcategories[$productId] =
						array();
				}

				$productSubcategories[$productId][] =
					$subcategoryId;
			}
		}


		/*
		 * ==========================================================
		 * PRODUCT DISPLAY DATA
		 * ==========================================================
		 */

		$productData = array();

		foreach ($products as $product) {

			$productId = (int) $product->id;

			$translation = null;

			if (
				isset($productTranslations[$productId]) &&
				isset(
					$productTranslations[$productId][$languageId]
				)
			) {

				$translation =
					$productTranslations[$productId][$languageId];
			} elseif (
				isset($productTranslations[$productId]) &&
				isset(
					$productTranslations[$productId][$defaultLanguageId]
				)
			) {

				$translation =
					$productTranslations[$productId][$defaultLanguageId];
			}


			$name = $translation
				? trim((string) $translation->name)
				: '';

			if ($name === '') {
				$name =
					'Producto #' . $productId;
			}


			$shortDescription =
				$translation
				? trim(
					(string) $translation->short_description
				)
				: '';


			$description =
				$translation
				? trim(
					(string) $translation->description
				)
				: '';


			$categoriesForProduct = array();

			if (isset($productCategories[$productId])) {

				foreach (
					$productCategories[$productId]
					as $categoryId
				) {

					if (isset($categoryNameById[$categoryId])) {

						$categoriesForProduct[] =
							array(
								'id' =>
								$categoryId,
								'name' =>
								$categoryNameById[$categoryId],
								'slug' =>
								$categorySlugById[$categoryId],
							);
					}
				}
			}


			$subcategoriesForProduct = array();

			if (
				isset(
					$productSubcategories[$productId]
				)
			) {

				foreach (
					$productSubcategories[$productId]
					as $subcategoryId
				) {

					if (
						isset(
							$subcategoryNameById[$subcategoryId]
						)
					) {

						$subcategoriesForProduct[] =
							array(
								'id' =>
								$subcategoryId,
								'name' =>
								$subcategoryNameById[$subcategoryId],
								'slug' =>
								$subcategorySlugById[$subcategoryId],
								'category_id' =>
								$subcategoryCategoryById[$subcategoryId],
							);
					}
				}
			}


			$brand = $product->brand;

			$productData[] = array(
				'model' => $product,
				'id' => $productId,
				'name' => $name,
				'slug' => $product->slug,
				'short_description' =>
				$shortDescription,
				'description' =>
				$description,
				'main_image' =>
				$product->main_image,
				'infographic_image' =>
				$product->infographic_image,
				'brand' => $brand,
				'categories' =>
				$categoriesForProduct,
				'subcategories' =>
				$subcategoriesForProduct,
			);
		}


		/*
		 * ==========================================================
		 * PAGINATION
		 * ==========================================================
		 */

		$dataProvider = new CArrayDataProvider(
			$productData,
			array(
				'keyField' => 'id',
				'pagination' => array(
					'pageSize' => 12,
					'pageVar' => 'pagina',
				),
			)
		);


		/*
		 * ==========================================================
		 * SELECTED PRODUCT / INFOGRAPHIC
		 * ==========================================================
		 */

		$selectedProduct = null;

		if ($productFilter !== '') {

			foreach ($productData as $item) {

				if (
					$this->publicSlug($item['slug']) ===
					$this->publicSlug($productFilter)
				) {

					$selectedProduct = $item;

					break;
				}
			}
		}


		/*
		 * ==========================================================
		 * VIEW
		 * ==========================================================
		 */

		$this->render(
			'productos',
			array(
				'languageId' =>
				$languageId,
				'language' =>
				$language,
				'categories' =>
				$categoryData,
				'subcategories' =>
				$subcategoryData,
				'brands' =>
				$brands,
				'products' =>
				$productData,
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
			)
		);
	}


	/**
	 * Converts a public name into a URL-friendly slug.
	 */
	protected function publicSlug($value)
	{
		$value = trim((string) $value);

		$value = strtolower($value);

		$value = strtr(
			$value,
			array(
				'á' => 'a',
				'é' => 'e',
				'í' => 'i',
				'ó' => 'o',
				'ú' => 'u',
				'ü' => 'u',
				'ñ' => 'n',
			)
		);

		$value = preg_replace(
			'/[^a-z0-9]+/',
			'-',
			$value
		);

		$value = trim($value, '-');

		return $value;
	}
}
