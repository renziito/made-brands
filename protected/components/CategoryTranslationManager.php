<?php

/**
 * Handles category translation business logic.
 *
 * This class keeps category translation creation,
 * loading and updating outside CategoriesController.
 *
 * Yii 1.x compatible.
 */
class CategoryTranslationManager
{
	/**
	 * Finds an existing category translation.
	 *
	 * @param integer $categoryId
	 * @param integer $languageId
	 * @return CategoryTranslations|null
	 */
	public function find(
		$categoryId,
		$languageId
	) {
		return CategoryTranslations::model()
			->findByAttributes(
				array(
					'category_id' =>
						(int) $categoryId,

					'language_id' =>
						(int) $languageId,
				)
			);
	}


	/**
	 * Creates a new translation model.
	 *
	 * @param integer $categoryId
	 * @param integer $languageId
	 * @return CategoryTranslations
	 */
	public function create(
		$categoryId,
		$languageId
	) {
		$translation =
			new CategoryTranslations;

		$translation->category_id =
			(int) $categoryId;

		$translation->language_id =
			(int) $languageId;

		return $translation;
	}


	/**
	 * Finds an existing translation or creates
	 * a new one when it does not exist.
	 *
	 * @param integer $categoryId
	 * @param integer $languageId
	 * @return CategoryTranslations
	 */
	public function findOrCreate(
		$categoryId,
		$languageId
	) {
		$translation =
			$this->find(
				$categoryId,
				$languageId
			);

		if ($translation === null) {

			$translation =
				$this->create(
					$categoryId,
					$languageId
				);
		}

		return $translation;
	}


	/**
	 * Loads the category and language associated
	 * with a translation.
	 *
	 * @param integer $categoryId
	 * @param integer $languageId
	 * @return array
	 * @throws CHttpException
	 */
	public function getFormData(
		$categoryId,
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
			$this->findOrCreate(
				$category->id,
				$language->id
			);


		return array(
			'category' =>
				$category,

			'language' =>
				$language,

			'translation' =>
				$translation,
		);
	}


	/**
	 * Applies POST attributes to a translation.
	 *
	 * The category and language IDs are deliberately
	 * assigned separately so they cannot be changed
	 * through the submitted form.
	 *
	 * @param CategoryTranslations $translation
	 * @param array $attributes
	 * @return CategoryTranslations
	 */
	public function applyAttributes(
		CategoryTranslations $translation,
		$attributes = array()
	) {
		if ($attributes) {

			$translation->attributes =
				$attributes;
		}

		return $translation;
	}


	/**
	 * Saves a category translation.
	 *
	 * @param CategoryTranslations $translation
	 * @return boolean
	 */
	public function save(
		CategoryTranslations $translation
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
	 * @param CategoryTranslations $translation
	 * @param array $attributes
	 * @return boolean
	 */
	public function saveAttributes(
		CategoryTranslations $translation,
		$attributes = array()
	) {
		$this->applyAttributes(
			$translation,
			$attributes
		);

		return $this->save(
			$translation
		);
	}


	/**
	 * Returns all translations for a category.
	 *
	 * @param integer $categoryId
	 * @return CategoryTranslations[]
	 */
	public function findAllByCategory(
		$categoryId
	) {
		return CategoryTranslations::model()
			->findAllByAttributes(
				array(
					'category_id' =>
						(int) $categoryId,
				),
				array(
					'order' =>
						'language_id ASC',
				)
			);
	}


	/**
	 * Returns all category translations indexed
	 * by language ID.
	 *
	 * Example:
	 *
	 * array(
	 *     '1' => CategoryTranslations,
	 *     '2' => CategoryTranslations,
	 * )
	 *
	 * @param integer $categoryId
	 * @return array
	 */
	public function findAllByLanguage(
		$categoryId
	) {
		$translations =
			$this->findAllByCategory(
				$categoryId
			);


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


		return $translationsByLanguage;
	}


	/**
	 * Returns all active languages together with
	 * their corresponding category translations.
	 *
	 * This is useful for rendering the translation
	 * management section of the category form.
	 *
	 * @param integer $categoryId
	 * @return array
	 */
	public function getLanguagesWithTranslations(
		$categoryId
	) {
		$languages =
			Languages::model()->findAll(
				array(
					'order' =>
						'sort_order ASC, id ASC',
				)
			);


		$translationsByLanguage =
			$this->findAllByLanguage(
				$categoryId
			);


		return array(
			'languages' =>
				$languages,

			'translationsByLanguage' =>
				$translationsByLanguage,
		);
	}
}
