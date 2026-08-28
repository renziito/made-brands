<?php

/**
 * Handles category business logic.
 *
 * This class keeps category creation, updating and
 * update-page data preparation outside CategoriesController.
 *
 * Yii 1.x compatible.
 */
class CategoryManager
{
	/**
	 * Creates a new category together with its default
	 * language translation.
	 *
	 * @param array $categoryAttributes
	 * @param array $translationAttributes
	 * @return Categories
	 * @throws Exception
	 */
	public function create(
		$categoryAttributes = array(),
		$translationAttributes = array()
	) {
		$model = new Categories;
		$translation = new CategoryTranslations;

		if ($categoryAttributes) {

			$model->attributes =
				$categoryAttributes;
		}

		if ($translationAttributes) {

			$translation->attributes =
				$translationAttributes;
		}

		/*
		 * The first translation must always use
		 * the configured default language.
		 */
		$defaultLanguage =
			$this->getDefaultLanguage();

		if ($defaultLanguage === null) {

			$model->addError(
				'is_active',
				'No existe un idioma predeterminado configurado.'
			);

			return array(
				'model' => $model,
				'translation' => $translation,
				'defaultLanguage' => null,
				'saved' => false,
			);
		}

		$now = date('Y-m-d H:i:s');

		$model->created_at = $now;
		$model->updated_at = $now;

		$translation->language_id =
			$defaultLanguage->id;

		$translation->created_at = $now;
		$translation->updated_at = $now;

		/*
		 * Validate both models before starting
		 * the transaction.
		 */
		$categoryValid =
			$model->validate();

		$translationValid =
			$translation->validate();

		if (
			!$categoryValid ||
			!$translationValid
		) {

			return array(
				'model' => $model,
				'translation' => $translation,
				'defaultLanguage' => $defaultLanguage,
				'saved' => false,
			);
		}

		$transaction =
			Yii::app()->db->beginTransaction();

		try {

			/*
			 * Save category first so we obtain its ID.
			 */
			if (!$model->save(false)) {

				throw new Exception(
					'No se pudo guardar la categoría.'
				);
			}

			/*
			 * Associate translation with category.
			 */
			$translation->category_id =
				$model->id;

			if (!$translation->save(false)) {

				throw new Exception(
					'No se pudo guardar la traducción de la categoría.'
				);
			}

			$transaction->commit();

			return array(
				'model' => $model,
				'translation' => $translation,
				'defaultLanguage' => $defaultLanguage,
				'saved' => true,
			);

		} catch (Exception $e) {

			$transaction->rollback();

			$model->addError(
				'id',
				$e->getMessage()
			);

			return array(
				'model' => $model,
				'translation' => $translation,
				'defaultLanguage' => $defaultLanguage,
				'saved' => false,
			);
		}
	}


	/**
	 * Updates an existing category.
	 *
	 * @param Categories $model
	 * @param array $attributes
	 * @return boolean
	 */
	public function update(
		Categories $model,
		$attributes = array()
	) {
		if ($attributes) {

			$model->attributes =
				$attributes;
		}

		$model->updated_at =
			date('Y-m-d H:i:s');

		return $model->save();
	}


	/**
	 * Soft deletes a category.
	 *
	 * The category is marked as inactive
	 * instead of being physically deleted.
	 *
	 * @param Categories $model
	 * @return boolean
	 */
	public function delete(
		Categories $model
	) {
		$model->is_featured = 0;
		$model->is_active = 0;
		$model->updated_at =
			date('Y-m-d H:i:s');

		return $model->save();
	}


	/**
	 * Returns all data required by update.php / _form.php.
	 *
	 * This method contains the data-loading logic that
	 * previously lived inside CategoriesController::getUpdateData().
	 *
	 * @param Categories $model
	 * @return array
	 */
	public function getUpdateData(
		Categories $model
	) {
		/*
		 * Load every language, including inactive languages.
		 */
		$languages =
			Languages::model()->findAll(array(
				'order' =>
					'sort_order ASC, id ASC',
			));


		/*
		 * Category translations.
		 */
		$translations =
			CategoryTranslations::model()->findAllByAttributes(
				array(
					'category_id' =>
						$model->id,
				),
				array(
					'order' =>
						'language_id ASC',
				)
			);


		/*
		 * Index category translations by language.
		 */
		$translationsByLanguage =
			array();

		foreach (
			$translations
			as $translation
		) {

			$translationsByLanguage[
				(string) $translation->language_id
			] = $translation;
		}


		/*
		 * Active subcategories.
		 */
		$subcategories =
			Subcategories::model()->findAllByAttributes(
				array(
					'category_id' =>
						$model->id,

					'is_active' => 1,
				),
				array(
					'order' =>
						'sort_order ASC, id ASC',
				)
			);


		/*
		 * Subcategory translations.
		 */
		$subcategoryTranslations =
			array();

		if ($subcategories) {

			$subcategoryIds =
				array();

			foreach (
				$subcategories
				as $subcategory
			) {

				$subcategoryIds[] =
					$subcategory->id;
			}


			if ($subcategoryIds) {

				$criteria =
					new CDbCriteria;

				$criteria->addInCondition(
					'subcategory_id',
					$subcategoryIds
				);

				$criteria->order =
					'subcategory_id ASC, language_id ASC';

				$subcategoryTranslations =
					SubcategoryTranslations::model()
						->findAll($criteria);
			}
		}


		/*
		 * Default language.
		 */
		$defaultLanguage =
			$this->getDefaultLanguage();


		/*
		 * Created flag.
		 */
		$created =
			(int) Yii::app()->request->getQuery(
				'created',
				0
			);


		return array(
			'model' =>
				$model,

			'languages' =>
				$languages,

			'translations' =>
				$translations,

			'translationsByLanguage' =>
				$translationsByLanguage,

			'subcategories' =>
				$subcategories,

			'subcategoryTranslations' =>
				$subcategoryTranslations,

			'defaultLanguage' =>
				$defaultLanguage,

			'created' =>
				$created,
		);
	}


	/**
	 * Returns the configured default language.
	 *
	 * @return Languages|null
	 */
	public function getDefaultLanguage()
	{
		return Languages::model()->findByAttributes(
			array(
				'is_default' => 1,
			)
		);
	}
}
