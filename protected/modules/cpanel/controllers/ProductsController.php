<?php
class ProductsController extends Controller
{
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'index' page.
	 */
	public function actionCreate()
	{
		$model = new Products;

		$translation = new ProductTranslations;

		$defaultLanguage = Languages::model()->findByAttributes(array(
			'is_default' => 1,
		));

		if ($defaultLanguage !== null) {
			$translation->language_id = (int) $defaultLanguage->id;
		}

		$post = Yii::app()->request->getPost(
			'Products',
			false
		);

		$translationPost = Yii::app()->request->getPost(
			'ProductTranslations',
			array()
		);

		$categorySelection = Yii::app()->request->getPost(
			'ProductCategorySelection',
			array()
		);

		if ($post !== false) {

			$model->attributes = $post;

			$translation->attributes =
				is_array($translationPost)
				? $translationPost
				: array();

			/*
		 * The product must always use the
		 * configured default language.
		 */
			if ($defaultLanguage !== null) {
				$translation->language_id =
					(int) $defaultLanguage->id;
			}

			/*
		 * The slug comes from the product name
		 * in the default-language translation.
		 */
			$model->slug = Utils::Slugify(
				$translation->name,
				true
			);

			if ($model->status == 'publicado') {
				$model->published_at = date('Y-m-d H:i:s');
			}

			$model->created_at = date('Y-m-d H:i:s');
			$model->updated_at = date('Y-m-d H:i:s');

			/*
		 * ---------------------------------------------
		 * CATEGORY / SUBCATEGORY IDS
		 * ---------------------------------------------
		 */

			$categoryIds =
				isset($categorySelection['category_ids']) &&
				is_array($categorySelection['category_ids'])
				? $categorySelection['category_ids']
				: array();

			$subcategoryIds =
				isset($categorySelection['subcategory_ids']) &&
				is_array($categorySelection['subcategory_ids'])
				? $categorySelection['subcategory_ids']
				: array();

			$categoryIds = array_values(
				array_unique(
					array_filter(
						array_map('intval', $categoryIds)
					)
				)
			);

			$subcategoryIds = array_values(
				array_unique(
					array_filter(
						array_map('intval', $subcategoryIds)
					)
				)
			);

			/*
		 * ---------------------------------------------
		 * ADD PARENT CATEGORIES FROM SUBCATEGORIES
		 * ---------------------------------------------
		 */

			if (!empty($subcategoryIds)) {

				$criteria = new CDbCriteria;

				$criteria->addInCondition(
					'id',
					$subcategoryIds
				);

				$subcategories =
					Subcategories::model()->findAll($criteria);

				foreach ($subcategories as $subcategory) {

					$parentCategoryId =
						(int) $subcategory->category_id;

					if (
						$parentCategoryId > 0 &&
						!in_array(
							$parentCategoryId,
							$categoryIds,
							true
						)
					) {
						$categoryIds[] =
							$parentCategoryId;
					}
				}
			}

			/*
		 * ---------------------------------------------
		 * DATABASE TRANSACTION
		 * ---------------------------------------------
		 */

			$transaction =
				Yii::app()->db->beginTransaction();

			try {

				/*
			 * PRODUCTS
			 */

				if (!$model->save()) {

					throw new CException(
						'No se pudo guardar el producto.'
					);
				}

				/*
			 * PRODUCT TRANSLATION
			 */

				$translation->product_id =
					(int) $model->id;

				if ($defaultLanguage !== null) {

					$translation->language_id =
						(int) $defaultLanguage->id;
				}
				$translation->created_at = date('Y-m-d H:i:s');
				$translation->updated_at = date('Y-m-d H:i:s');

				if (!$translation->save()) {

					throw new CException(
						'No se pudo guardar la traducción del producto.'
					);
				}

				/*
			 * PRODUCT CATEGORIES
			 */

				foreach ($categoryIds as $categoryId) {

					$productCategory =
						new ProductCategories;

					$productCategory->product_id =
						(int) $model->id;

					$productCategory->category_id =
						(int) $categoryId;

					if (!$productCategory->save()) {

						throw new CException(
							'No se pudo guardar la categoría del producto.'
						);
					}
				}

				/*
			 * PRODUCT SUBCATEGORIES
			 */

				foreach ($subcategoryIds as $subcategoryId) {

					$productSubcategory =
						new ProductSubcategories;

					$productSubcategory->product_id =
						(int) $model->id;

					$productSubcategory->subcategory_id =
						(int) $subcategoryId;

					if (!$productSubcategory->save()) {

						throw new CException(
							'No se pudo guardar la subcategoría del producto.'
						);
					}
				}

				/*
			 * EVERYTHING WAS SAVED
			 */

				$transaction->commit();

				$this->redirect(array(
					'index',
				));
			} catch (Exception $e) {

				$transaction->rollback();

				$model->addError(
					'brand_id',
					$e->getMessage()
				);
			}
		}

		$this->render(
			'create',
			array(
				'model' => $model,
				'translation' => $translation,
				'defaultLanguage' => $defaultLanguage,
			)
		);
	}
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'index' page.
	 *
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);

		/*
	 * ----------------------------------------------------------
	 * DEFAULT LANGUAGE
	 * ----------------------------------------------------------
	 */

		$defaultLanguage = Languages::model()->findByAttributes(array(
			'is_default' => 1,
		));


		/*
	 * ----------------------------------------------------------
	 * POST DATA
	 * ----------------------------------------------------------
	 */

		$post = Yii::app()->request->getPost(
			'Products',
			false
		);

		$translationPost = Yii::app()->request->getPost(
			'ProductTranslations',
			array()
		);

		$newTranslationPost = Yii::app()->request->getPost(
			'NewProductTranslation',
			array()
		);

		$categorySelection = Yii::app()->request->getPost(
			'ProductCategorySelection',
			array()
		);


		/*
	 * ----------------------------------------------------------
	 * UPDATE
	 * ----------------------------------------------------------
	 */

		if ($post !== false) {

			$model->attributes = $post;

			$model->updated_at = date(
				'Y-m-d H:i:s'
			);


			/*
		 * ------------------------------------------------------
		 * CATEGORY / SUBCATEGORY IDS
		 * ------------------------------------------------------
		 */

			$categoryIds =
				isset($categorySelection['category_ids']) &&
				is_array($categorySelection['category_ids'])
				? $categorySelection['category_ids']
				: array();

			$subcategoryIds =
				isset($categorySelection['subcategory_ids']) &&
				is_array($categorySelection['subcategory_ids'])
				? $categorySelection['subcategory_ids']
				: array();


			/*
		 * Normalize IDs.
		 */

			$categoryIds = array_values(
				array_unique(
					array_filter(
						array_map(
							'intval',
							$categoryIds
						)
					)
				)
			);

			$subcategoryIds = array_values(
				array_unique(
					array_filter(
						array_map(
							'intval',
							$subcategoryIds
						)
					)
				)
			);


			/*
		 * ------------------------------------------------------
		 * ADD PARENT CATEGORIES
		 * ------------------------------------------------------
		 *
		 * If a subcategory is selected, its parent category
		 * must also be associated with the product.
		 */

			if (!empty($subcategoryIds)) {

				$criteria = new CDbCriteria;

				$criteria->addInCondition(
					'id',
					$subcategoryIds
				);

				$subcategories =
					Subcategories::model()->findAll(
						$criteria
					);

				foreach ($subcategories as $subcategory) {

					$parentCategoryId =
						(int) $subcategory->category_id;

					if (
						$parentCategoryId > 0 &&
						!in_array(
							$parentCategoryId,
							$categoryIds,
							true
						)
					) {
						$categoryIds[] =
							$parentCategoryId;
					}
				}
			}


			/*
		 * ------------------------------------------------------
		 * GET DEFAULT TRANSLATION
		 * ------------------------------------------------------
		 *
		 * We need the default translation before saving the
		 * product because the slug comes from its name.
		 */

			$defaultTranslation = null;

			if ($defaultLanguage !== null) {

				$defaultTranslation =
					ProductTranslations::model()->findByAttributes(
						array(
							'product_id' => (int) $model->id,
							'language_id' => (int) $defaultLanguage->id,
						)
					);
			}


			/*
		 * If the default translation is part of the POST,
		 * use the posted name for the new slug.
		 */

			if (
				$defaultTranslation !== null &&
				isset(
					$translationPost[(int) $defaultTranslation->id]
				)
			) {

				$postedDefaultTranslation =
					$translationPost[(int) $defaultTranslation->id];

				if (
					isset(
						$postedDefaultTranslation['name']
					)
				) {

					$model->slug =
						Utils::Slugify(
							trim(
								(string)
								$postedDefaultTranslation['name']
							),
							true
						);
				}
			}


			/*
		 * ------------------------------------------------------
		 * DATABASE TRANSACTION
		 * ------------------------------------------------------
		 */

			$model->updated_at = date('Y-m-d H:i:s');

			$transaction =
				Yii::app()->db->beginTransaction();

			try {

				/*
			 * --------------------------------------------------
			 * PRODUCT
			 * --------------------------------------------------
			 */

				if (!$model->save()) {

					throw new CException(
						'No se pudo actualizar el producto.'
					);
				}


				/*
			 * --------------------------------------------------
			 * EXISTING TRANSLATIONS
			 * --------------------------------------------------
			 *
			 * Only existing translation IDs belonging to this
			 * product can be updated.
			 */

				if (
					is_array($translationPost) &&
					!empty($translationPost)
				) {

					foreach (
						$translationPost
						as $translationId => $translationAttributes
					) {

						$translationId =
							(int) $translationId;

						if ($translationId <= 0) {
							continue;
						}

						$translation =
							ProductTranslations::model()->findByAttributes(
								array(
									'id' => $translationId,
									'product_id' => (int) $model->id,
								)
							);

						if ($translation === null) {

							throw new CException(
								'No se encontró una traducción válida del producto.'
							);
						}


						/*
					 * Do not allow product_id or language_id
					 * to be changed through the POST.
					 */

						if (
							isset(
								$translationAttributes['name']
							)
						) {
							$translation->name =
								$translationAttributes['name'];
						}

						if (
							isset(
								$translationAttributes['name_size']
							)
						) {
							$translation->name_size =
								$translationAttributes['name_size'];
						}

						if (
							isset(
								$translationAttributes['short_description']
							)
						) {
							$translation->short_description =
								$translationAttributes['short_description'];
						}

						if (
							isset(
								$translationAttributes['short_description_size']
							)
						) {
							$translation->short_description_size =
								$translationAttributes['short_description_size'];
						}

						if (
							isset(
								$translationAttributes['description']
							)
						) {
							$translation->description =
								$translationAttributes['description'];
						}

						if (
							isset(
								$translationAttributes['description_size']
							)
						) {
							$translation->description_size =
								$translationAttributes['description_size'];
						}


						$translation->updated_at = date('Y-m-d H:i:s');

						/*
					 * Keep the existing language.
					 */

						if (
							!$translation->save()
						) {

							throw new CException(
								'No se pudo actualizar una traducción del producto.'
							);
						}
					}
				}


				/*
			 * --------------------------------------------------
			 * NEW TRANSLATION
			 * --------------------------------------------------
			 */

				if (
					is_array($newTranslationPost) &&
					!empty($newTranslationPost) &&
					!empty($newTranslationPost['language_id'])
				) {

					$newLanguageId =
						(int)
						$newTranslationPost['language_id'];


					/*
				 * Verify that the language exists.
				 */

					$language =
						Languages::model()->findByPk(
							$newLanguageId
						);

					if ($language === null) {

						throw new CException(
							'El idioma seleccionado no existe.'
						);
					}


					/*
				 * Prevent duplicate translations.
				 */

					$existingTranslation =
						ProductTranslations::model()->findByAttributes(
							array(
								'product_id' =>
								(int) $model->id,

								'language_id' =>
								$newLanguageId,
							)
						);

					if ($existingTranslation !== null) {

						throw new CException(
							'El producto ya tiene una traducción para el idioma seleccionado.'
						);
					}


					$newTranslation =
						new ProductTranslations;

					$newTranslation->product_id =
						(int) $model->id;

					$newTranslation->language_id =
						$newLanguageId;

					if (
						isset(
							$newTranslationPost['name']
						)
					) {
						$newTranslation->name =
							$newTranslationPost['name'];
					}

					if (
						isset(
							$newTranslationPost['name_size']
						)
					) {
						$newTranslation->name_size =
							$newTranslationPost['name_size'];
					}

					if (
						isset(
							$newTranslationPost['short_description']
						)
					) {
						$newTranslation->short_description =
							$newTranslationPost['short_description'];
					}

					if (
						isset(
							$newTranslationPost['short_description_size']
						)
					) {
						$newTranslation->short_description_size =
							$newTranslationPost['short_description_size'];
					}

					if (
						isset(
							$newTranslationPost['description']
						)
					) {
						$newTranslation->description =
							$newTranslationPost['description'];
					}

					if (
						isset(
							$newTranslationPost['description_size']
						)
					) {
						$newTranslation->description_size =
							$newTranslationPost['description_size'];
					}

					$newTranslation->created_at = date('Y-m-d H:i:s');
					$newTranslation->updated_at = date('Y-m-d H:i:s');

					if (!$newTranslation->save()) {

						throw new CException(
							'No se pudo guardar la nueva traducción del producto.'
						);
					}
				}


				/*
			 * --------------------------------------------------
			 * PRODUCT CATEGORIES
			 * --------------------------------------------------
			 *
			 * The update form represents the complete current
			 * selection, so we synchronize the relations by
			 * deleting the old ones and recreating them.
			 */

				ProductCategories::model()->deleteAll(
					'product_id = :product_id',
					array(
						':product_id' =>
						(int) $model->id,
					)
				);


				foreach ($categoryIds as $categoryId) {

					$productCategory =
						new ProductCategories;

					$productCategory->product_id =
						(int) $model->id;

					$productCategory->category_id =
						(int) $categoryId;

					if (!$productCategory->save()) {

						throw new CException(
							'No se pudo guardar una categoría del producto.'
						);
					}
				}


				/*
			 * --------------------------------------------------
			 * PRODUCT SUBCATEGORIES
			 * --------------------------------------------------
			 */

				ProductSubcategories::model()->deleteAll(
					'product_id = :product_id',
					array(
						':product_id' =>
						(int) $model->id,
					)
				);


				foreach ($subcategoryIds as $subcategoryId) {

					$productSubcategory =
						new ProductSubcategories;

					$productSubcategory->product_id =
						(int) $model->id;

					$productSubcategory->subcategory_id =
						(int) $subcategoryId;

					if (!$productSubcategory->save()) {

						throw new CException(
							'No se pudo guardar una subcategoría del producto.'
						);
					}
				}


				/*
			 * --------------------------------------------------
			 * EVERYTHING WAS SAVED
			 * --------------------------------------------------
			 */

				$transaction->commit();

				$this->redirect(
					array(
						'index',
					)
				);
			} catch (Exception $e) {

				$transaction->rollback();

				$model->addError(
					'brand_id',
					$e->getMessage()
				);
			}
		}


		/*
	 * ----------------------------------------------------------
	 * LOAD DATA FOR UPDATE FORM
	 * ----------------------------------------------------------
	 */

		$translations =
			ProductTranslations::model()->findAllByAttributes(
				array(
					'product_id' => (int) $model->id,
				),
				array(
					'order' =>
					'language_id ASC, id ASC',
				)
			);


		$this->render(
			'update',
			array(
				'model' => $model,
				'translations' => $translations,
				'defaultLanguage' => $defaultLanguage,
			)
		);
	}
	/**
	 * Soft deletes a particular model.
	 * Instead of physically deleting the record, all tinyint fields are set to 0.
	 * If the update is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$model = $this->loadModel($id);
		$model->status = 0;
		if ($model->save()) {
			$this->redirect(array('index'));
		}
	}
	/**
	 * Manages all models.
	 */
	public function actionIndex()
	{
		$model = new Products('search');
		$model->unsetAttributes();
		$attributes = Yii::app()->request->getQuery('Products', false);
		if ($attributes) {
			$model->attributes = $attributes;
		}
		$this->render('index', array('model' => $model));
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Products the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model = Products::model()->findByPk($id);
		if ($model === null) {
			throw new CHttpException(404, 'La página solicitada no existe.');
		}
		return $model;
	}
}
