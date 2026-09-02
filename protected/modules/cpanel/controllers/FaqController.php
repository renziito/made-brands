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
					$translationPost = Yii::app()->request->getPost('FaqTranslations', false);

					if ($translationPost && $defaultLanguage !== null) {
						$translation = new FaqTranslations;
						$translation->attributes = $translationPost;
						$translation->faq_id = $model->id;
						$translation->language_id = $defaultLanguage->id;
						$translation->created_at = date('Y-m-d H:i:s');
						$translation->updated_at = date('Y-m-d H:i:s');
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

			$post = Yii::app()->request->getPost('Faqs', false);

			if ($post) {
				$model->attributes = $post;
				$model->updated_at = date('Y-m-d H:i:s');
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

					foreach ($translationPost as $languageId => $attributes) {
						$languageId = (int) $languageId;

						if (!$languageId) {
							continue;
						}

						$translation =
							FaqTranslations::model()->findByAttributes(
								array(
									'faq_id' => $model->id,
									'language_id' => $languageId,
								)
							);

						if ($translation === null) {
							$translation = new FaqTranslations;
							$translation->faq_id = $model->id;
							$translation->language_id = $languageId;
							$translation->created_at = date('Y-m-d H:i:s');
						}

						/*
						 * Only allow actual translation fields.
						 */
						if (isset($attributes['question'])) {
							$translation->question = $attributes['question'];
						}

						if (isset($attributes['answer'])) {
							$translation->answer = $attributes['answer'];
						}

						if (isset($attributes['question'])) {
							$translation->question = $attributes['question'];
						}

						if (isset($attributes['form_text'])) {
							$translation->form_text = $attributes['form_text'];
						}

						if (isset($attributes['form_id'])) {
							$translation->form_id = $attributes['form_id'];
						}

						$translation->updated_at = date('Y-m-d H:i:s');

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


	/**
	 * Returns a dynamic FAQ form.
	 */
	public function actionGetForm($id)
	{
		$this->layout = false;

		if (!Yii::app()->request->isAjaxRequest) {
			throw new CHttpException(
				400,
				'La solicitud debe realizarse mediante AJAX.'
			);
		}

		$form = FaqForms::model()->findByAttributes(array(
			'id' => (int) $id,
			'is_active' => 1,
		));

		if ($form === null) {
			header('Content-Type: application/json; charset=UTF-8');

			echo CJSON::encode(array(
				'success' => false,
				'message' => 'El formulario no existe o no está disponible.',
			));

			Yii::app()->end();
		}

		$fields = FaqFormFields::model()->findAll(array(
			'condition' => 'form_id = :form_id AND is_active = 1',
			'params' => array(
				':form_id' => $form->id,
			),
			'order' => 'sort_order ASC, id ASC',
		));

		$formData = array(
			'id' => (int) $form->id,
			'title' => $form->title,
			'description' => $form->description,
			'success_message' => $form->success_message,
			'submit_label' => 'Enviar',
			'fields' => array(),
		);

		foreach ($fields as $field) {

			$formData['fields'][] = array(
				'id' => (int) $field->id,
				'name' => $field->name,
				'label' => $field->label,
				'type' => $field->type,
				'placeholder' => $field->placeholder,
				'default_value' => $field->default_value,
				'options' => $field->options,
				'is_required' => (int) $field->is_required,
				'sort_order' => (int) $field->sort_order,
			);
		}

		header('Content-Type: application/json; charset=UTF-8');

		echo CJSON::encode(array(
			'success' => true,
			'form' => $formData,
		));

		Yii::app()->end();
	}


	/**
	 * Saves a dynamic FAQ form submission through AJAX.
	 */
	public function actionSubmitForm()
	{
		$this->layout = false;

		if (!Yii::app()->request->isAjaxRequest) {
			throw new CHttpException(
				400,
				'La solicitud debe realizarse mediante AJAX.'
			);
		}

		header('Content-Type: application/json; charset=UTF-8');

		$formId = (int) Yii::app()->request->getPost(
			'form_id',
			0
		);

		if (!$formId) {
			echo CJSON::encode(array(
				'success' => false,
				'message' => 'El formulario no es válido.',
			));

			Yii::app()->end();
		}

		$form = FaqForms::model()->findByAttributes(array(
			'id' => $formId,
			'is_active' => 1,
		));

		if ($form === null) {
			echo CJSON::encode(array(
				'success' => false,
				'message' => 'El formulario no existe o no está disponible.',
			));

			Yii::app()->end();
		}

		$fields = FaqFormFields::model()->findAll(array(
			'condition' => 'form_id = :form_id AND is_active = 1',
			'params' => array(
				':form_id' => $form->id,
			),
			'order' => 'sort_order ASC, id ASC',
		));

		$postFields = Yii::app()->request->getPost(
			'fields',
			array()
		);

		$errors = array();
		$values = array();

		foreach ($fields as $field) {

			$fieldId = (int) $field->id;

			$value = isset($postFields[$fieldId])
				? $postFields[$fieldId]
				: '';

			if (is_array($value)) {
				$value = array_map(
					'trim',
					$value
				);

				$value = array_filter(
					$value,
					function ($item) {
						return $item !== '';
					}
				);

				$value = array_values($value);
			} else {
				$value = trim((string) $value);
			}

			$isEmpty = is_array($value)
				? count($value) === 0
				: $value === '';

			if ((int) $field->is_required === 1 && $isEmpty) {

				$errors[$fieldId] =
					'Este campo es obligatorio.';

				continue;
			}

			/*
		 * Validate email fields.
		 */
			if (
				!$isEmpty &&
				strtolower($field->type) === 'email' &&
				!filter_var($value, FILTER_VALIDATE_EMAIL)
			) {

				$errors[$fieldId] =
					'Ingrese un correo electrónico válido.';

				continue;
			}

			/*
		 * Store arrays as JSON so checkbox/multiple values
		 * remain available as a single submission value.
		 */
			if (is_array($value)) {
				$value = CJSON::encode($value);
			}

			$values[$fieldId] = $value;
		}

		if (!empty($errors)) {
			echo CJSON::encode(array(
				'success' => false,
				'message' =>
				'Por favor, revise los campos marcados.',
				'errors' => $errors,
			));

			Yii::app()->end();
		}

		$transaction =
			Yii::app()->db->beginTransaction();

		try {

			$now = date('Y-m-d H:i:s');

			$submission =
				new FaqFormSubmissions;

			$submission->form_id =
				$form->id;

			$submission->ip_address =
				Yii::app()->request->userHostAddress;

			$submission->user_agent =
				Yii::app()->request->userAgent;

			$submission->created_at =
				$now;

			$submission->updated_at =
				$now;

			if (!$submission->save()) {
				throw new Exception(
					'No se pudo guardar el envío del formulario.'
				);
			}

			foreach ($fields as $field) {

				$fieldId = (int) $field->id;

				$value = isset($values[$fieldId])
					? $values[$fieldId]
					: '';

				$submissionValue =
					new FaqFormSubmissionValues;

				$submissionValue->submission_id =
					$submission->id;

				$submissionValue->field_id =
					$fieldId;

				$submissionValue->value =
					$value;

				$submissionValue->created_at =
					$now;

				$submissionValue->updated_at =
					$now;

				if (!$submissionValue->save()) {
					throw new Exception(
						'No se pudo guardar uno de los valores del formulario.'
					);
				}
			}

			$transaction->commit();

			echo CJSON::encode(array(
				'success' => true,
				'message' => $form->success_message
					? $form->success_message
					: 'El formulario fue enviado correctamente.',
				'submission_id' => $submission->id,
			));
		} catch (Exception $e) {

			$transaction->rollback();

			echo CJSON::encode(array(
				'success' => false,
				'message' =>
				'No se pudo guardar el formulario. Inténtelo nuevamente.',
			));
		}

		Yii::app()->end();
	}
}
