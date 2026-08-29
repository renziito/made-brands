<?php

class BusinessController extends Controller
{
	/**
	 * Creates a new business together with its default language translation.
	 */
	public function actionCreate()
	{
		$model = new Businesses;
		$translation = new BusinessTranslations;

		$postBusiness = Yii::app()->request->getPost(
			'Businesses',
			false
		);

		$postTranslation = Yii::app()->request->getPost(
			'BusinessTranslations',
			false
		);

		if ($postBusiness || $postTranslation) {

			if ($postBusiness) {

				/*
			 * Do not assign the uploaded file directly
			 * to the image database attribute.
			 */
				$model->attributes = $postBusiness;
			}

			if ($postTranslation) {

				$translation->attributes =
					$postTranslation;
			}


			/*
		 * Get default language.
		 */
			$defaultLanguage =
				Languages::model()->findByAttributes(
					array(
						'is_default' => 1,
					)
				);


			if ($defaultLanguage === null) {

				$model->addError(
					'is_active',
					'No existe un idioma predeterminado configurado.'
				);
			} else {

				/*
			 * Get uploaded image.
			 */
				$imageFile =
					CUploadedFile::getInstance(
						$model,
						'image'
					);


				/*
			 * Force default language.
			 */
				$translation->language_id =
					$defaultLanguage->id;


				/*
			 * Timestamps.
			 */
				$now =
					date('Y-m-d H:i:s');

				$model->created_at =
					$now;

				$model->updated_at =
					$now;

				$translation->created_at =
					$now;

				$translation->updated_at =
					$now;


				/*
			 * New businesses are active by default.
			 */
				if (
					$model->is_active === null ||
					$model->is_active === ''
				) {

					$model->is_active = 1;
				}


				/*
			 * Validate uploaded image separately.
			 */
				$imageValid = true;

				if ($imageFile !== null) {

					$allowedTypes = array(
						'image/jpeg',
						'image/png',
						'image/webp',
					);

					if (
						!in_array(
							$imageFile->type,
							$allowedTypes,
							true
						)
					) {

						$model->addError(
							'image',
							'La imagen debe ser JPG, PNG o WebP.'
						);

						$imageValid = false;
					}
				}


				/*
			 * Validate only the actual Business fields.
			 *
			 * image is handled manually because the database
			 * stores the final WebP path, not CUploadedFile.
			 */
				$businessValid =
					$model->validate(
						array(
							'icon',
							'sort_order',
							'is_active',
						)
					);


				if (
					$businessValid &&
					$imageValid
				) {

					$transaction =
						Yii::app()->db->beginTransaction();


					$savedImagePath = null;


					try {

						/*
					 * Save business first to obtain ID.
					 */
						if (!$model->save(false)) {

							throw new Exception(
								'No se pudo guardar el business.'
							);
						}


						/*
					 * Save uploaded image.
					 */
						if ($imageFile !== null) {

							$savedImagePath =
								$this->saveBusinessImage(
									$imageFile,
									$model->id
								);


							$model->image =
								$savedImagePath;


							$model->updated_at =
								date('Y-m-d H:i:s');


							if (!$model->save(false)) {

								throw new Exception(
									'No se pudo guardar la imagen del business.'
								);
							}
						}


						/*
					 * Now that the business exists,
					 * assign its ID to the translation.
					 */
						$translation->business_id =
							$model->id;


						/*
					 * Validate translation AFTER business_id
					 * has been assigned.
					 */
						if (!$translation->validate()) {

							$errors =
								$translation->getErrors();

							$message =
								'No se pudo guardar la traducción.';


							if (!empty($errors)) {

								$firstAttribute =
									array_key_first(
										$errors
									);

								if (
									isset(
										$errors[$firstAttribute][0]
									)
								) {

									$message =
										$errors[$firstAttribute][0];
								}
							}


							throw new Exception(
								$message
							);
						}


						/*
					 * Save translation.
					 */
						if (!$translation->save(false)) {

							throw new Exception(
								'No se pudo guardar la traducción del business.'
							);
						}


						/*
					 * Everything succeeded.
					 */
						$transaction->commit();


						/*
					 * Continue to update so the administrator
					 * can add more languages.
					 */
						$this->redirect(
							array(
								'index'
							)
						);
					} catch (Exception $e) {

						/*
					 * Roll back database changes.
					 */
						$transaction->rollback();


						/*
					 * Remove image if it was already written
					 * to disk but the transaction failed.
					 */
						if (
							$savedImagePath !== null
						) {

							$this->deleteBusinessImage(
								$savedImagePath
							);
						}


						/*
					 * Show the real error in the form.
					 */
						$model->addError(
							'id',
							$e->getMessage()
						);
					}
				}
			}
		}


		/*
	 * Default language for the initial render.
	 */
		$defaultLanguage =
			Languages::model()->findByAttributes(
				array(
					'is_default' => 1,
				)
			);


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
	 * Updates only the base business information.
	 *
	 * Translation fields are intentionally excluded.
	 *
	 * Only image and icon can be modified here.
	 */
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);

		/*
		 * Only allow image and icon to be modified here.
		 */
		$post = Yii::app()->request->getPost(
			'Businesses',
			false
		);

		if ($post) {

			/*
			 * Keep the current image before processing
			 * a possible replacement.
			 */
			$oldImage = $model->image;

			/*
			 * Icon.
			 */
			if (array_key_exists('icon', $post)) {

				$model->icon =
					$post['icon'];
			}


			/*
			 * Uploaded image.
			 */
			$imageFile = CUploadedFile::getInstance(
				$model,
				'image'
			);

			if ($imageFile !== null) {

				$allowedTypes = array(
					'image/jpeg',
					'image/png',
					'image/webp',
				);

				if (
					!in_array(
						$imageFile->type,
						$allowedTypes,
						true
					)
				) {

					$model->addError(
						'image',
						'La imagen debe ser JPG, PNG o WebP.'
					);
				} else {

					try {

						/*
						 * Save the new image.
						 */
						$newImage =
							$this->saveBusinessImage(
								$imageFile,
								$model->id
							);

						$model->image =
							$newImage;

						$model->updated_at =
							date('Y-m-d H:i:s');

						if ($model->save(false)) {

							/*
							 * Delete the previous image only
							 * after the database has been updated.
							 */
							if (
								!empty($oldImage) &&
								$oldImage !== $newImage
							) {

								$this->deleteBusinessImage(
									$oldImage
								);
							}

							$this->redirect(array(
								'update',
								'id' => $model->id,
							));
						}
					} catch (Exception $e) {

						$model->addError(
							'image',
							$e->getMessage()
						);
					}
				}
			} else {

				/*
				 * No new image was uploaded.
				 * Keep the existing image.
				 */
				$model->updated_at =
					date('Y-m-d H:i:s');

				if ($model->save(false)) {

					$this->redirect(array(
						'update',
						'id' => $model->id,
					));
				}
			}
		}

		$data =
			$this->getUpdateData($model);

		$this->render(
			'update',
			$data
		);
	}


	/**
	 * Saves a business image as WebP.
	 *
	 * Physical path:
	 *
	 *     [basePath]/images/business/
	 *
	 * Database value:
	 *
	 *     /images/business/business-ID.webp
	 *
	 * @param CUploadedFile $file
	 * @param integer $businessId
	 * @return string
	 * @throws Exception
	 */
	protected function saveBusinessImage(
		CUploadedFile $file,
		$businessId
	) {
		/*
		 * Base server path.
		 */
		$basePath =
			Yii::getPathOfAlias('webroot');

		/*
		 * Physical directory.
		 */
		$directory =
			$basePath .
			DIRECTORY_SEPARATOR .
			'images' .
			DIRECTORY_SEPARATOR .
			'business';


		/*
		 * Create directory if it doesn't exist.
		 */
		if (!is_dir($directory)) {

			if (
				!mkdir(
					$directory,
					0755,
					true
				)
			) {

				throw new Exception(
					'No se pudo crear la carpeta de imágenes del business.'
				);
			}
		}


		/*
		 * Make sure the directory is writable.
		 */
		if (!is_writable($directory)) {

			throw new Exception(
				'La carpeta de imágenes del business no tiene permisos de escritura.'
			);
		}


		/*
		 * Generate deterministic filename based
		 * on the business ID.
		 */
		$filename =
			'business-' .
			(int) $businessId .
			'.webp';


		$physicalPath =
			$directory .
			DIRECTORY_SEPARATOR .
			$filename;


		/*
		 * Load source image.
		 */
		$imageInfo =
			@getimagesize(
				$file->tempName
			);

		if ($imageInfo === false) {

			throw new Exception(
				'El archivo seleccionado no es una imagen válida.'
			);
		}


		$mimeType =
			isset($imageInfo['mime'])
			? $imageInfo['mime']
			: '';


		/*
		 * Create GD image according to source type.
		 */
		switch ($mimeType) {

			case 'image/jpeg':

				$source =
					@imagecreatefromjpeg(
						$file->tempName
					);

				break;


			case 'image/png':

				$source =
					@imagecreatefrompng(
						$file->tempName
					);

				break;


			case 'image/webp':

				$source =
					@imagecreatefromwebp(
						$file->tempName
					);

				break;


			default:

				throw new Exception(
					'Formato de imagen no soportado.'
				);
		}


		if (!$source) {

			throw new Exception(
				'No se pudo procesar la imagen.'
			);
		}


		/*
		 * Preserve dimensions.
		 */
		$width =
			imagesx($source);

		$height =
			imagesy($source);


		/*
		 * Create destination image.
		 */
		$destination =
			imagecreatetruecolor(
				$width,
				$height
			);


		if (!$destination) {

			imagedestroy($source);

			throw new Exception(
				'No se pudo crear la imagen de destino.'
			);
		}


		/*
		 * Preserve transparency for PNG/WebP.
		 */
		imagealphablending(
			$destination,
			false
		);

		imagesavealpha(
			$destination,
			true
		);

		$transparent =
			imagecolorallocatealpha(
				$destination,
				255,
				255,
				255,
				127
			);

		imagefilledrectangle(
			$destination,
			0,
			0,
			$width,
			$height,
			$transparent
		);


		/*
		 * Resample image.
		 *
		 * At this point we preserve the original dimensions.
		 * The output is still optimized as WebP.
		 */
		imagecopyresampled(
			$destination,
			$source,
			0,
			0,
			0,
			0,
			$width,
			$height,
			$width,
			$height
		);


		/*
		 * Save as WebP.
		 *
		 * Quality 85 provides a good balance between
		 * visual quality and file size for the web.
		 */
		$result =
			@imagewebp(
				$destination,
				$physicalPath,
				85
			);


		imagedestroy(
			$source
		);

		imagedestroy(
			$destination
		);


		if (!$result) {

			throw new Exception(
				'No se pudo guardar la imagen WebP.'
			);
		}


		/*
		 * Return the public URL stored in the database.
		 */
		return '/images/business/' . $filename;
	}


	/**
	 * Deletes a business image from disk.
	 *
	 * @param string $imagePath
	 * @return void
	 */
	protected function deleteBusinessImage(
		$imagePath
	) {
		if (empty($imagePath)) {
			return;
		}


		/*
		 * Only allow deletion of files inside
		 * the business image directory.
		 */
		$prefix =
			'/images/business/';

		if (
			strpos(
				$imagePath,
				$prefix
			) !== 0
		) {
			return;
		}


		$basePath =
			Yii::getPathOfAlias('webroot');


		$relativePath =
			ltrim(
				$imagePath,
				'/\\'
			);


		$physicalPath =
			$basePath .
			DIRECTORY_SEPARATOR .
			str_replace(
				'/',
				DIRECTORY_SEPARATOR,
				$relativePath
			);


		if (
			is_file(
				$physicalPath
			)
		) {

			@unlink(
				$physicalPath
			);
		}
	}


	/**
	 * Adds or updates a translation for a business.
	 */
	public function actionTranslation()
	{
		$businessId = (int) Yii::app()->request->getQuery(
			'business_id',
			0
		);

		$languageId = (int) Yii::app()->request->getQuery(
			'language_id',
			0
		);

		$business = $this->loadModel(
			$businessId
		);

		if ($languageId <= 0) {

			throw new CHttpException(
				400,
				'Debe especificarse un idioma.'
			);
		}

		$language =
			Languages::model()->findByPk(
				$languageId
			);

		if ($language === null) {

			throw new CHttpException(
				404,
				'El idioma solicitado no existe.'
			);
		}

		$translation =
			BusinessTranslations::model()->findByAttributes(
				array(
					'business_id' => $business->id,
					'language_id' => $language->id,
				)
			);

		if ($translation === null) {
			$translation =
				new BusinessTranslations;
		}

		if (Yii::app()->request->isPostRequest) {

			$post =
				Yii::app()->request->getPost(
					'BusinessTranslations',
					false
				);

			if ($post) {
				$translation->attributes =
					$post;
			}

			$now =
				date('Y-m-d H:i:s');

			if ($translation->isNewRecord) {
				$translation->created_at =
					$now;
			}

			$translation->updated_at =
				$now;

			$translation->business_id =
				$business->id;

			$translation->language_id =
				$language->id;

			if ($translation->validate()) {

				if ($translation->save(false)) {

					$this->redirect(array(
						'update',
						'id' => $business->id,
					));
				}
			}
		}

		$this->render(
			'translation',
			array(
				'model' => $translation,
				'business' => $business,
				'language' => $language,
			)
		);
	}


	/**
	 * Returns all data required by update.php / _form.php.
	 */
	protected function getUpdateData(
		Businesses $model
	) {
		/*
		 * Load every language.
		 */
		$languages =
			Languages::model()->findAll(
				array(
					'order' =>
					'sort_order ASC, id ASC',
				)
			);


		/*
		 * Load all translations belonging
		 * to this business.
		 */
		$translations =
			BusinessTranslations::model()->findAllByAttributes(
				array(
					'business_id' => $model->id,
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

			$translationsByLanguage[(string) $translation->language_id] =
				$translation;
		}


		/*
		 * Default language.
		 */
		$defaultLanguage =
			Languages::model()->findByAttributes(
				array(
					'is_default' => 1,
				)
			);


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

			'defaultLanguage' =>
			$defaultLanguage,

			'created' =>
			$created,
		);
	}


	/**
	 * Manages all models.
	 */
	public function actionIndex()
	{
		$model =
			new Businesses('search');

		$model->unsetAttributes();

		$attributes =
			Yii::app()->request->getQuery(
				'Businesses',
				false
			);

		if ($attributes) {

			$model->attributes =
				$attributes;
		}

		$this->render(
			'index',
			array(
				'model' => $model,
			)
		);
	}


	/**
	 * Returns the requested business.
	 */
	public function loadModel($id)
	{
		$model =
			Businesses::model()->findByPk(
				$id
			);

		if ($model === null) {

			throw new CHttpException(
				404,
				'La página solicitada no existe.'
			);
		}

		return $model;
	}

	/**
	 * Soft deletes a business.
	 *
	 * The business is not physically deleted.
	 * It is simply marked as inactive.
	 */
	public function actionDelete($id)
	{
		$model = $this->loadModel($id);

		$model->is_active = 0;
		$model->updated_at = date('Y-m-d H:i:s');

		if ($model->save(false)) {

			$this->redirect(array(
				'index',
			));
		}

		throw new CHttpException(
			500,
			'No se pudo desactivar el business.'
		);
	}
}
