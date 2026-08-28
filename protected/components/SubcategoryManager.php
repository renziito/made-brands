<?php

/**
 * Handles subcategory business logic.
 *
 * This class keeps subcategory creation, updating,
 * removal and translation management outside
 * CategoriesController.
 *
 * Yii 1.x compatible.
 */
class SubcategoryManager
{
	/**
	 * Finds a subcategory belonging to a category.
	 *
	 * @param integer $categoryId
	 * @param integer $subcategoryId
	 * @param boolean $activeOnly
	 * @return Subcategories|null
	 */
	public function find(
		$categoryId,
		$subcategoryId,
		$activeOnly = false
	) {
		$attributes = array(
			'id' =>
			(int) $subcategoryId,

			'category_id' =>
			(int) $categoryId,
		);


		if ($activeOnly) {

			$attributes['is_active'] = 1;
		}


		return Subcategories::model()
			->findByAttributes(
				$attributes
			);
	}


	/**
	 * Creates a new subcategory model.
	 *
	 * The category ID and default active state are
	 * assigned by the manager and are not taken from
	 * user-submitted values.
	 *
	 * @param integer $categoryId
	 * @return Subcategories
	 */
	public function create(
		$categoryId
	) {
		$subcategory =
			new Subcategories;

		$subcategory->category_id =
			(int) $categoryId;

		$subcategory->is_active =
			1;

		return $subcategory;
	}


	/**
	 * Applies submitted attributes to a subcategory.
	 *
	 * The category ID is deliberately excluded from
	 * the submitted attributes and is assigned by the
	 * manager.
	 *
	 * @param Subcategories $subcategory
	 * @param array $attributes
	 * @param integer $categoryId
	 * @return Subcategories
	 */
	public function applyAttributes(
		Subcategories $subcategory,
		$attributes = array(),
		$categoryId = 0
	) {
		if ($attributes) {

			$subcategory->attributes =
				$attributes;
		}


		if ($categoryId > 0) {

			$subcategory->category_id =
				(int) $categoryId;
		}


		return $subcategory;
	}


	/**
	 * Saves an existing subcategory.
	 *
	 * This method does not create a translation.
	 *
	 * @param Subcategories $subcategory
	 * @return boolean
	 */
	public function save(
		Subcategories $subcategory
	) {
		$now =
			date('Y-m-d H:i:s');


		if ($subcategory->isNewRecord) {

			$subcategory->created_at =
				$now;
		}


		$subcategory->updated_at =
			$now;


		if (!$subcategory->validate()) {

			return false;
		}


		return $subcategory->save(false);
	}


	/**
	 * Creates a new subcategory together with its
	 * translation in the configured default language.
	 *
	 * The operation is atomic:
	 *
	 *     Subcategories
	 *          +
	 *     SubcategoryTranslations
	 *
	 * are saved inside the same database transaction.
	 *
	 * The language ID is always determined server-side
	 * from Languages.is_default.
	 *
	 * @param integer $categoryId
	 * @param array $subcategoryAttributes
	 * @param array $translationAttributes
	 * @return array
	 */
	public function createWithDefaultTranslation(
		$categoryId,
		$subcategoryAttributes = array(),
		$translationAttributes = array()
	) {
		$subcategory =
			$this->create(
				$categoryId
			);


		$translation =
			new SubcategoryTranslations;


		/*
	 * Apply submitted subcategory attributes.
	 */
		if ($subcategoryAttributes) {

			$subcategory->attributes =
				$subcategoryAttributes;
		}


		/*
	 * Apply submitted translation attributes.
	 */
		if ($translationAttributes) {

			$translation->attributes =
				$translationAttributes;
		}


		/*
	 * Category ownership must always come from
	 * the manager, never from POST data.
	 */
		$subcategory->category_id =
			(int) $categoryId;


		/*
	 * The default language is determined server-side.
	 */
		$defaultLanguage =
			$this->getDefaultLanguage();


		if ($defaultLanguage === null) {

			$subcategory->addError(
				'id',
				'No existe un idioma predeterminado configurado.'
			);

			return array(
				'subcategory' =>
				$subcategory,

				'translation' =>
				$translation,

				'defaultLanguage' =>
				null,

				'saved' =>
				false,
			);
		}


		/*
	 * The language is controlled by the server.
	 *
	 * We do NOT trust the language_id coming
	 * from the form.
	 */
		$translation->language_id =
			$defaultLanguage->id;


		$now =
			date('Y-m-d H:i:s');


		$subcategory->created_at =
			$now;

		$subcategory->updated_at =
			$now;


		$translation->created_at =
			$now;

		$translation->updated_at =
			$now;


		/*
	 * Start transaction BEFORE saving either model.
	 */
		$transaction =
			Yii::app()->db->beginTransaction();


		try {

			/*
		 * ==================================================
		 * SUBCATEGORY
		 * ==================================================
		 *
		 * Validate first.
		 */
			if (!$subcategory->validate()) {

				$transaction->rollback();

				return array(
					'subcategory' =>
					$subcategory,

					'translation' =>
					$translation,

					'defaultLanguage' =>
					$defaultLanguage,

					'saved' =>
					false,
				);
			}


			/*
		 * Save subcategory.
		 *
		 * At this point we obtain the new ID.
		 */
			if (!$subcategory->save(false)) {

				throw new Exception(
					'No se pudo guardar la subcategoría.'
				);
			}


			/*
		 * ==================================================
		 * TRANSLATION
		 * ==================================================
		 *
		 * Now that the subcategory exists, its ID
		 * is available.
		 */
			$translation->subcategory_id =
				$subcategory->id;


			/*
		 * Validate translation AFTER assigning
		 * subcategory_id.
		 */
			if (!$translation->validate()) {

				$transaction->rollback();

				return array(
					'subcategory' =>
					$subcategory,

					'translation' =>
					$translation,

					'defaultLanguage' =>
					$defaultLanguage,

					'saved' =>
					false,
				);
			}


			/*
		 * Save translation.
		 */
			if (!$translation->save(false)) {

				throw new Exception(
					'No se pudo guardar la traducción de la subcategoría.'
				);
			}


			/*
		 * Everything succeeded.
		 */
			$transaction->commit();


			return array(
				'subcategory' =>
				$subcategory,

				'translation' =>
				$translation,

				'defaultLanguage' =>
				$defaultLanguage,

				'saved' =>
				true,
			);
		} catch (Exception $e) {

			/*
		 * Any database error rolls back BOTH records.
		 */
			$transaction->rollback();


			$subcategory->addError(
				'id',
				$e->getMessage()
			);


			return array(
				'subcategory' =>
				$subcategory,

				'translation' =>
				$translation,

				'defaultLanguage' =>
				$defaultLanguage,

				'saved' =>
				false,
			);
		}
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


	/**
	 * Returns all active subcategories belonging
	 * to a category.
	 *
	 * @param integer $categoryId
	 * @return Subcategories[]
	 */
	public function findAllByCategory(
		$categoryId
	) {
		return Subcategories::model()
			->findAllByAttributes(
				array(
					'category_id' =>
					(int) $categoryId,

					'is_active' =>
					1,
				),
				array(
					'order' =>
					'sort_order ASC, id ASC',
				)
			);
	}


	/**
	 * Returns all subcategories belonging to a
	 * category, including inactive records.
	 *
	 * @param integer $categoryId
	 * @return Subcategories[]
	 */
	public function findAllByCategoryIncludingInactive(
		$categoryId
	) {
		return Subcategories::model()
			->findAllByAttributes(
				array(
					'category_id' =>
					(int) $categoryId,
				),
				array(
					'order' =>
					'sort_order ASC, id ASC',
				)
			);
	}


	/**
	 * Returns all translations for the supplied
	 * subcategories.
	 *
	 * @param Subcategories[] $subcategories
	 * @return SubcategoryTranslations[]
	 */
	public function findTranslations(
		$subcategories
	) {
		$translations =
			array();


		if (!$subcategories) {

			return $translations;
		}


		$subcategoryIds =
			array();


		foreach (
			$subcategories
			as $subcategory
		) {

			$subcategoryIds[] =
				$subcategory->id;
		}


		if (!$subcategoryIds) {

			return $translations;
		}


		$criteria =
			new CDbCriteria;


		$criteria->addInCondition(
			'subcategory_id',
			$subcategoryIds
		);


		$criteria->order =
			'subcategory_id ASC, language_id ASC';


		$translations =
			SubcategoryTranslations::model()
			->findAll(
				$criteria
			);


		return $translations;
	}


	/**
	 * Returns subcategory translations indexed by:
	 *
	 *     subcategory_id
	 *          ->
	 *     language_id
	 *
	 * Example:
	 *
	 * array(
	 *     '10' => array(
	 *         '1' => SubcategoryTranslations,
	 *         '2' => SubcategoryTranslations,
	 *     ),
	 * )
	 *
	 * @param Subcategories[] $subcategories
	 * @return array
	 */
	public function indexTranslationsByLanguage(
		$subcategories
	) {
		$translations =
			$this->findTranslations(
				$subcategories
			);


		$indexed =
			array();


		foreach (
			$translations
			as $translation
		) {

			$subcategoryId =
				(string) $translation->subcategory_id;


			$languageId =
				(string) $translation->language_id;


			if (!isset(
				$indexed[$subcategoryId]
			)) {

				$indexed[$subcategoryId] =
					array();
			}


			$indexed[$subcategoryId][$languageId] =
				$translation;
		}


		return $indexed;
	}


	/**
	 * Finds a translation for a specific
	 * subcategory and language.
	 *
	 * @param integer $subcategoryId
	 * @param integer $languageId
	 * @return SubcategoryTranslations|null
	 */
	public function findTranslation(
		$subcategoryId,
		$languageId
	) {
		return SubcategoryTranslations::model()
			->findByAttributes(
				array(
					'subcategory_id' =>
					(int) $subcategoryId,

					'language_id' =>
					(int) $languageId,
				)
			);
	}


	/**
	 * Creates a new translation model.
	 *
	 * @param integer $subcategoryId
	 * @param integer $languageId
	 * @return SubcategoryTranslations
	 */
	public function createTranslation(
		$subcategoryId,
		$languageId
	) {
		$translation =
			new SubcategoryTranslations;


		$translation->subcategory_id =
			(int) $subcategoryId;


		$translation->language_id =
			(int) $languageId;


		return $translation;
	}


	/**
	 * Finds an existing translation or creates
	 * a new translation model.
	 *
	 * @param integer $subcategoryId
	 * @param integer $languageId
	 * @return SubcategoryTranslations
	 */
	public function findOrCreateTranslation(
		$subcategoryId,
		$languageId
	) {
		$translation =
			$this->findTranslation(
				$subcategoryId,
				$languageId
			);


		if ($translation === null) {

			$translation =
				$this->createTranslation(
					$subcategoryId,
					$languageId
				);
		}


		return $translation;
	}


	/**
	 * Applies submitted attributes to a translation.
	 *
	 * The subcategory ID and language ID are assigned
	 * by the manager and therefore cannot be changed
	 * through POST data.
	 *
	 * @param SubcategoryTranslations $translation
	 * @param array $attributes
	 * @param integer $subcategoryId
	 * @param integer $languageId
	 * @return SubcategoryTranslations
	 */
	public function applyTranslationAttributes(
		SubcategoryTranslations $translation,
		$attributes = array(),
		$subcategoryId = 0,
		$languageId = 0
	) {
		if ($attributes) {

			$translation->attributes =
				$attributes;
		}


		if ($subcategoryId > 0) {

			$translation->subcategory_id =
				(int) $subcategoryId;
		}


		if ($languageId > 0) {

			$translation->language_id =
				(int) $languageId;
		}


		return $translation;
	}


	/**
	 * Saves a subcategory translation.
	 *
	 * @param SubcategoryTranslations $translation
	 * @return boolean
	 */
	public function saveTranslation(
		SubcategoryTranslations $translation
	) {
		$now =
			date('Y-m-d H:i:s');


		if ($translation->isNewRecord) {

			$translation->created_at =
				$now;
		}


		$translation->updated_at =
			$now;


		if (!$translation->validate()) {

			return false;
		}


		return $translation->save(false);
	}


	/**
	 * Saves a translation using submitted attributes.
	 *
	 * @param SubcategoryTranslations $translation
	 * @param array $attributes
	 * @param integer $subcategoryId
	 * @param integer $languageId
	 * @return boolean
	 */
	public function saveTranslationAttributes(
		SubcategoryTranslations $translation,
		$attributes = array(),
		$subcategoryId = 0,
		$languageId = 0
	) {
		$this->applyTranslationAttributes(
			$translation,
			$attributes,
			$subcategoryId,
			$languageId
		);


		return $this->saveTranslation(
			$translation
		);
	}


	/**
	 * Removes a subcategory using a soft delete.
	 *
	 * The database record is preserved and only
	 * is_active is changed to 0.
	 *
	 * @param Subcategories $subcategory
	 * @return boolean
	 */
	public function remove(
		Subcategories $subcategory
	) {
		$subcategory->is_active =
			0;


		$subcategory->updated_at =
			date('Y-m-d H:i:s');


		return $subcategory->save(false);
	}


	/**
	 * Returns all data needed by the subcategory
	 * management section.
	 *
	 * @param integer $categoryId
	 * @return array
	 */
	public function getCategoryData(
		$categoryId
	) {
		$subcategories =
			$this->findAllByCategory(
				$categoryId
			);


		$subcategoryTranslations =
			$this->findTranslations(
				$subcategories
			);


		$subcategoryTranslationsByLanguage =
			$this->indexTranslationsByLanguage(
				$subcategories
			);


		$defaultLanguage =
			$this->getDefaultLanguage();


		return array(
			'subcategories' =>
			$subcategories,

			'subcategoryTranslations' =>
			$subcategoryTranslations,

			'subcategoryTranslationsByLanguage' =>
			$subcategoryTranslationsByLanguage,

			'defaultLanguage' =>
			$defaultLanguage,
		);
	}


	/**
	 * Returns form data for a subcategory translation.
	 *
	 * @param integer $categoryId
	 * @param integer $subcategoryId
	 * @param integer $languageId
	 * @return array
	 * @throws CHttpException
	 */
	public function getTranslationFormData(
		$categoryId,
		$subcategoryId,
		$languageId
	) {
		$category =
			Categories::model()->findByPk(
				(int) $categoryId
			);


		if ($category === null) {

			throw new CHttpException(
				404,
				'La categoría solicitada no existe.'
			);
		}


		$subcategory =
			$this->find(
				$category->id,
				$subcategoryId,
				false
			);


		if ($subcategory === null) {

			throw new CHttpException(
				404,
				'La subcategoría solicitada no existe.'
			);
		}


		$language =
			Languages::model()->findByPk(
				(int) $languageId
			);


		if ($language === null) {

			throw new CHttpException(
				404,
				'El idioma solicitado no existe.'
			);
		}


		$translation =
			$this->findOrCreateTranslation(
				$subcategory->id,
				$language->id
			);


		return array(
			'category' =>
			$category,

			'subcategory' =>
			$subcategory,

			'language' =>
			$language,

			'translation' =>
			$translation,
		);
	}
}
