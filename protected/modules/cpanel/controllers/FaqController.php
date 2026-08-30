<?php

class FaqController extends Controller
{
	/**
	 * Creates a new FAQ.
	 */
	public function actionCreate()
	{
		$model = new Faqs;

		$languages = Languages::model()->findAll(array(
			'order' => 'sort_order ASC, id ASC',
		));

		$defaultLanguage = Languages::model()->findByAttributes(array(
			'is_default' => 1,
		));

		$translation = new FaqTranslations;

		$faqForms = FaqForms::model()->findAll(array(
			'order' => 'id ASC',
		));

		/*
		 * Create FAQ.
		 */
		if (Yii::app()->request->isPostRequest) {

			$post = Yii::app()->request->getPost('Faqs', false);

			if ($post) {

				$model->attributes = $post;

				$model->created_at = date('Y-m-d H:i:s');
				$model->updated_at = date('Y-m-d H:i:s');

				if ($model->save()) {

					/*
					 * The default language can optionally be created
					 * together with the FAQ.
					 */
					$translationPost =
						Yii::app()->request->getPost(
							'FaqTranslations',
							false
						);

					if (
						$translationPost &&
						$defaultLanguage !== null
					) {

						$translation =
							new FaqTranslations;

						$translation->attributes =
							$translationPost;

						$translation->faq_id =
							$model->id;

						$translation->language_id =
							$defaultLanguage->id;

						$translation->created_at =
							date('Y-m-d H:i:s');

						$translation->updated_at =
							date('Y-m-d H:i:s');

						$translation->save();
					}

					$this->redirect(array(
						'update',
						'id' => $model->id,
					));
				}
			}
		}

		$this->render('create', array(
			'model' => $model,
			'languages' => $languages,
			'defaultLanguage' => $defaultLanguage,
			'translation' => $translation,
			'faqForms' => $faqForms,
		));
	}


	/**
	 * Updates a particular FAQ.
	 */
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);

		$languages = Languages::model()->findAll(array(
			'order' => 'sort_order ASC, id ASC',
		));

		$translations =
			FaqTranslations::model()->findAllByAttributes(
				array(
					'faq_id' => $model->id,
				),
				array(
					'order' => 'language_id ASC',
				)
			);

		$translationsByLanguage = array();

		foreach ($translations as $translation) {

			$translationsByLanguage[(string) $translation->language_id] = $translation;
		}

		$faqForms = FaqForms::model()->findAll(array(
			'order' => 'id ASC',
		));

		/*
		 * Update main FAQ information.
		 */
		if (Yii::app()->request->isPostRequest) {

			$post = Yii::app()->request->getPost(
				'Faqs',
				false
			);

			if ($post) {

				$model->attributes = $post;

				$model->updated_at =
					date('Y-m-d H:i:s');

				$model->save();
			}

			/*
			 * Save translations.
			 *
			 * The form sends:
			 *
			 * FaqTranslations[LANGUAGE_ID][question]
			 * FaqTranslations[LANGUAGE_ID][answer]
			 * FaqTranslations[LANGUAGE_ID][form_id]
			 */
			$translationPost =
				Yii::app()->request->getPost(
					'FaqTranslations',
					false
				);

			if ($translationPost) {

				$transaction =
					Yii::app()->db->beginTransaction();

				try {

					foreach (
						$translationPost
						as $languageId => $attributes
					) {

						$languageId =
							(int) $languageId;

						if (!$languageId) {
							continue;
						}

						$translation =
							FaqTranslations::model()
							->findByAttributes(
								array(
									'faq_id' =>
									$model->id,
									'language_id' =>
									$languageId,
								)
							);

						if ($translation === null) {

							$translation =
								new FaqTranslations;

							$translation->faq_id =
								$model->id;

							$translation->language_id =
								$languageId;

							$translation->created_at =
								date('Y-m-d H:i:s');
						}

						/*
						 * Only allow actual translation fields.
						 */
						if (isset($attributes['question'])) {

							$translation->question =
								$attributes['question'];
						}

						if (isset($attributes['answer'])) {

							$translation->answer =
								$attributes['answer'];
						}

						if (isset($attributes['form_id'])) {

							$formId =
								trim(
									(string)
									$attributes['form_id']
								);

							$translation->form_id =
								$formId !== ''
								? $formId
								: null;
						}

						$translation->updated_at =
							date('Y-m-d H:i:s');

						if (!$translation->save()) {

							throw new Exception(
								'No se pudo guardar la traducción.'
							);
						}
					}

					$transaction->commit();

					$this->redirect(array(
						'update',
						'id' => $model->id,
					));
				} catch (Exception $e) {

					$transaction->rollback();

					/*
					 * Reload translations so the form
					 * can display the validation state.
					 */
					$translations =
						FaqTranslations::model()
						->findAllByAttributes(
							array(
								'faq_id' =>
								$model->id,
							),
							array(
								'order' =>
								'language_id ASC',
							)
						);

					$translationsByLanguage =
						array();

					foreach (
						$translations
						as $translation
					) {

						$translationsByLanguage[(string)
						$translation->language_id] = $translation;
					}

					Yii::app()->user->setFlash(
						'error',
						'No se pudieron guardar las traducciones.'
					);
				}
			}

			/*
			 * If only the main FAQ was submitted,
			 * redirect normally.
			 */
			if (!$translationPost) {

				$this->redirect(array(
					'update',
					'id' => $model->id,
				));
			}
		}

		$this->render('update', array(
			'model' => $model,
			'languages' => $languages,
			'translations' => $translations,
			'translationsByLanguage' =>			$translationsByLanguage,
			'faqForms' => $faqForms,
		));
	}


	/**
	 * Soft deletes a particular FAQ.
	 */
	public function actionDelete($id)
	{
		$model = $this->loadModel($id);

		$model->is_active = 0;
		$model->updated_at =
			date('Y-m-d H:i:s');

		if ($model->save(false)) {

			$this->redirect(array(
				'index',
			));
		}
	}


	/**
	 * Manages all FAQs.
	 */
	public function actionIndex()
	{
		$model = new Faqs('search');

		$model->unsetAttributes();

		$attributes =
			Yii::app()->request->getQuery(
				'Faqs',
				false
			);

		if ($attributes) {

			$model->attributes =
				$attributes;
		}

		$languages =
			Languages::model()->findAll(array(
				'order' =>
				'sort_order ASC, id ASC',
			));

		$this->render('index', array(
			'model' => $model,
			'languages' => $languages,
		));
	}


	/**
	 * Returns the requested FAQ.
	 */
	public function loadModel($id)
	{
		$model =
			Faqs::model()->findByPk($id);

		if ($model === null) {

			throw new CHttpException(
				404,
				'La página solicitada no existe.'
			);
		}

		return $model;
	}
}
