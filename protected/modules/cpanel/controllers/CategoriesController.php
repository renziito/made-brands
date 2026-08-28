<?php

class CategoriesController extends Controller
{
	/**
	 * @var CategoryManager
	 */
	protected $categoryManager;


	/**
	 * @var CategoryTranslationManager
	 */
	protected $categoryTranslationManager;


	/**
	 * @var SubcategoryManager
	 */
	protected $subcategoryManager;


	/**
	 * @var AjaxResponseHelper
	 */
	protected $ajax;


	/**
	 * Initializes controller dependencies.
	 */
	public function init()
	{
		parent::init();


		$this->categoryManager =
			new CategoryManager;


		$this->categoryTranslationManager =
			new CategoryTranslationManager;


		$this->subcategoryManager =
			new SubcategoryManager;


		$this->ajax =
			new AjaxResponseHelper;
	}


	/**
	 * Creates a new category together with its
	 * default language translation.
	 */
	public function actionCreate()
	{
		$model =
			new Categories;


		$translation =
			new CategoryTranslations;


		$postCategory =
			Yii::app()->request->getPost(
				'Categories',
				false
			);


		$postTranslation =
			Yii::app()->request->getPost(
				'CategoryTranslations',
				false
			);


		if ($postCategory || $postTranslation) {

			$result =
				$this->categoryManager->create(
					$postCategory
						? $postCategory
						: array(),

					$postTranslation
						? $postTranslation
						: array()
				);


			$model =
				$result['model'];


			$translation =
				$result['translation'];


			$defaultLanguage =
				$result['defaultLanguage'];


			if ($result['saved']) {

				$this->redirect(array(
					'update',
					'id' =>
					$model->id,
					'created' =>
					1,
				));
			}
		}


		if (!isset($defaultLanguage)) {

			$defaultLanguage =
				$this->categoryManager
				->getDefaultLanguage();
		}


		$this->render(
			'create',
			array(
				'model' =>
				$model,

				'translation' =>
				$translation,

				'defaultLanguage' =>
				$defaultLanguage,
			)
		);
	}


	/**
	 * Updates a particular category.
	 */
	public function actionUpdate($id)
	{
		$model =
			$this->loadModel($id);


		$post =
			Yii::app()->request->getPost(
				'Categories',
				false
			);


		if ($post) {

			if (
				$this->categoryManager->update(
					$model,
					$post
				)
			) {

				$this->redirect(array(
					'update',
					'id' =>
					$model->id,
				));
			}
		}


		$data =
			$this->categoryManager
			->getUpdateData(
				$model
			);


		$this->render(
			'update',
			$data
		);
	}


	/**
	 * AJAX modal:
	 *
	 * Add / edit category translation.
	 *
	 * GET:
	 *     Returns the modal form.
	 *
	 * POST:
	 *     Validates and saves the translation.
	 */
	public function actionTranslation()
	{
		$categoryId =
			(int) Yii::app()->request->getQuery(
				'category_id',
				0
			);


		$languageId =
			(int) Yii::app()->request->getQuery(
				'language_id',
				0
			);


		$data =
			$this->categoryTranslationManager
			->getFormData(
				$categoryId,
				$languageId
			);


		$category =
			$data['category'];


		$language =
			$data['language'];


		$translation =
			$data['translation'];


		/*
		 * POST = save.
		 */
		if (
			Yii::app()->request->isPostRequest
		) {

			$post =
				Yii::app()->request->getPost(
					'CategoryTranslations',
					array()
				);


			$this->categoryTranslationManager
				->applyAttributes(
					$translation,
					$post
				);


			/*
			 * Keep relationship values controlled
			 * by the server.
			 */
			$translation->category_id =
				$category->id;


			$translation->language_id =
				$language->id;


			if (
				$this->categoryTranslationManager
				->save(
					$translation
				)
			) {

				$this->ajax->success(array(
					'message' =>
					'La traducción se guardó correctamente.',
					'refresh' =>
					true,
				));
			}


			$this->ajax->categoryForm(
				$this,
				'translation',
				array(
					'model' =>
					$translation,

					'category' =>
					$category,

					'language' =>
					$language,
				)
			);

			return;
		}


		/*
		 * GET = render modal form.
		 */
		$this->ajax->categoryForm(
			$this,
			'translation',
			array(
				'model' =>
				$translation,

				'category' =>
				$category,

				'language' =>
				$language,
			)
		);
	}


	/**
	 * AJAX modal:
	 *
	 * Add / edit subcategory.
	 *
	 * GET:
	 *     Returns the modal form.
	 *
	 * POST:
	 *     Creates or updates the subcategory.
	 *
	 * CREATE:
	 *     Also creates the translation in the
	 *     configured default language.
	 */
	public function actionSubcategory()
	{
		$categoryId =
			(int) Yii::app()->request->getQuery(
				'category_id',
				0
			);


		$subcategoryId =
			(int) Yii::app()->request->getQuery(
				'id',
				0
			);


		$category =
			$this->loadModel(
				$categoryId
			);


		/*
		 * ======================================================
		 * EXISTING SUBCATEGORY
		 * ======================================================
		 */

		if ($subcategoryId > 0) {

			$subcategory =
				$this->subcategoryManager->find(
					$category->id,
					$subcategoryId,
					true
				);


			if ($subcategory === null) {

				throw new CHttpException(
					404,
					'La subcategoría solicitada no existe.'
				);
			}


			/*
			 * POST = update.
			 */
			if (
				Yii::app()->request->isPostRequest
			) {

				$post =
					Yii::app()->request->getPost(
						'Subcategories',
						array()
					);


				$this->subcategoryManager
					->applyAttributes(
						$subcategory,
						$post,
						$category->id
					);


				if (
					$this->subcategoryManager
					->save(
						$subcategory
					)
				) {

					$this->ajax->success(array(
						'message' =>
						'La subcategoría se guardó correctamente.',
						'refresh' =>
						true,
					));
				}


				$this->ajax->categoryForm(
					$this,
					'subcategory',
					array(
						'model' =>
						$subcategory,

						'category' =>
						$category,
					)
				);

				return;
			}


			/*
			 * GET = edit form.
			 */
			$this->ajax->categoryForm(
				$this,
				'subcategory',
				array(
					'model' =>
					$subcategory,

					'category' =>
					$category,
				)
			);

			return;
		}


		/*
		 * ======================================================
		 * NEW SUBCATEGORY
		 * ======================================================
		 */

		$subcategory =
			$this->subcategoryManager->create(
				$category->id
			);


		$translation =
			new SubcategoryTranslations;


		$defaultLanguage =
			$this->subcategoryManager
			->getDefaultLanguage();


		/*
		 * POST = create.
		 */
		if (
			Yii::app()->request->isPostRequest
		) {

			$postSubcategory =
				Yii::app()->request->getPost(
					'Subcategories',
					array()
				);


			$postTranslation =
				Yii::app()->request->getPost(
					'SubcategoryTranslations',
					array()
				);


			$result =
				$this->subcategoryManager
				->createWithDefaultTranslation(
					$category->id,
					$postSubcategory,
					$postTranslation
				);


			$subcategory =
				$result['subcategory'];


			$translation =
				$result['translation'];


			$defaultLanguage =
				$result['defaultLanguage'];


			if ($result['saved']) {

				$this->ajax->success(array(
					'message' =>
					'La subcategoría se guardó correctamente.',
					'refresh' =>
					true,
				));
			}


			$this->ajax->categoryForm(
				$this,
				'subcategory',
				array(
					'model' =>
					$subcategory,

					'category' =>
					$category,

					'translation' =>
					$translation,

					'defaultLanguage' =>
					$defaultLanguage,
				)
			);

			return;
		}


		/*
		 * GET = create form.
		 */
		$this->ajax->categoryForm(
			$this,
			'subcategory',
			array(
				'model' =>
				$subcategory,

				'category' =>
				$category,

				'translation' =>
				$translation,

				'defaultLanguage' =>
				$defaultLanguage,
			)
		);
	}


	/**
	 * AJAX:
	 *
	 * Soft deletes a subcategory.
	 */
	public function actionRemoveSubcategory()
	{
		$categoryId =
			(int) Yii::app()->request->getPost(
				'category_id',
				0
			);


		$subcategoryId =
			(int) Yii::app()->request->getPost(
				'subcategory_id',
				0
			);


		if (
			$categoryId <= 0 ||
			$subcategoryId <= 0
		) {

			$this->ajax->error(
				'Los datos de la subcategoría no son válidos.'
			);

			return;
		}


		$subcategory =
			$this->subcategoryManager->find(
				$categoryId,
				$subcategoryId,
				false
			);


		if ($subcategory === null) {

			$this->ajax->error(
				'La subcategoría solicitada no existe.'
			);

			return;
		}


		if (
			!$this->subcategoryManager
				->remove(
					$subcategory
				)
		) {

			$this->ajax->error(
				'No fue posible remover la subcategoría.'
			);

			return;
		}


		$this->ajax->success(array(
			'message' =>
			'La subcategoría fue removida correctamente.',
		));
	}


	/**
	 * AJAX modal:
	 *
	 * Add / edit a subcategory translation.
	 */
	public function actionSubcategoryTranslation()
	{
		$categoryId =
			(int) Yii::app()->request->getQuery(
				'category_id',
				0
			);


		$subcategoryId =
			(int) Yii::app()->request->getQuery(
				'subcategory_id',
				0
			);


		$languageId =
			(int) Yii::app()->request->getQuery(
				'language_id',
				0
			);


		$data =
			$this->subcategoryManager
			->getTranslationFormData(
				$categoryId,
				$subcategoryId,
				$languageId
			);


		$category =
			$data['category'];


		$subcategory =
			$data['subcategory'];


		$language =
			$data['language'];


		$translation =
			$data['translation'];


		/*
		 * POST = save.
		 */
		if (
			Yii::app()->request->isPostRequest
		) {

			$post =
				Yii::app()->request->getPost(
					'SubcategoryTranslations',
					array()
				);


			$this->subcategoryManager
				->applyTranslationAttributes(
					$translation,
					$post,
					$subcategory->id,
					$language->id
				);


			if (
				$this->subcategoryManager
				->saveTranslation(
					$translation
				)
			) {

				$this->ajax->success(array(
					'message' =>
					'La traducción de la subcategoría se guardó correctamente.',
					'refresh' =>
					true,
				));
			}


			$this->ajax->categoryForm(
				$this,
				'subcategoryTranslation',
				array(
					'model' =>
					$translation,

					'category' =>
					$category,

					'subcategory' =>
					$subcategory,

					'language' =>
					$language,
				)
			);

			return;
		}


		/*
		 * GET = render modal form.
		 */
		$this->ajax->categoryForm(
			$this,
			'subcategoryTranslation',
			array(
				'model' =>
				$translation,

				'category' =>
				$category,

				'subcategory' =>
				$subcategory,

				'language' =>
				$language,
			)
		);
	}


	/**
	 * Soft deletes a category.
	 */
	public function actionDelete($id)
	{
		$model =
			$this->loadModel($id);


		if (
			$this->categoryManager->delete(
				$model
			)
		) {

			$this->redirect(array(
				'index',
			));
		}
	}


	/**
	 * Manages all categories.
	 */
	public function actionIndex()
	{
		$model =
			new Categories('search');


		$model->unsetAttributes();


		$attributes =
			Yii::app()->request->getQuery(
				'Categories',
				false
			);


		if ($attributes) {

			$model->attributes =
				$attributes;
		}


		$this->render(
			'index',
			array(
				'model' =>
				$model,
			)
		);
	}


	/**
	 * Returns the data model based on the primary key.
	 *
	 * @param integer $id
	 * @return Categories
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model =
			Categories::model()->findByPk(
				(int) $id
			);


		if ($model === null) {

			throw new CHttpException(
				404,
				'La página solicitada no existe.'
			);
		}


		return $model;
	}
}
