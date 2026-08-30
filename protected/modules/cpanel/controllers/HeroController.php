<?php

class HeroController extends Controller
{
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'index' page.
	 */
	public function actionCreate()
	{
		$model = new HeroSlides;
		$translation = new HeroSlideTranslations;

		$post = Yii::app()->request->getPost('HeroSlides', false);
		$translationPost = Yii::app()->request->getPost('HeroSlideTranslations', false);

		if ($post) {

			$model->attributes = $post;

			if ($translationPost) {
				$translation->attributes = $translationPost;
			}

			// ======================================================
			// DEFAULT LANGUAGE: ESPAÑOL
			// ======================================================

			$spanishLanguage = Languages::model()->find(
				'LOWER(code) = :code OR LOWER(name) = :name',
				array(
					':code' => 'es',
					':name' => 'español',
				)
			);

			if (!$spanishLanguage) {

				$model->addError(
					'image',
					'No se encontró el idioma español en la configuración de idiomas.'
				);
			} else {

				$translation->language_id = $spanishLanguage->id;

				// ==================================================
				// DATES
				// ==================================================

				$now = date('Y-m-d H:i:s');

				$model->created_at = $now;
				$model->updated_at = $now;

				$translation->created_at = $now;
				$translation->updated_at = $now;

				// ==================================================
				// IMAGE UPLOAD
				// ==================================================

				$image = CUploadedFile::getInstance(
					$model,
					'image'
				);

				// ==================================================
				// VALIDATE IMAGE
				// ==================================================

				if (!$image) {

					$model->addError(
						'image',
						'Debes seleccionar una imagen.'
					);
				} else {

					$transaction = null;
					$createdImagePath = null;

					try {

						// ==================================================
						// TEMPORARY IMAGE
						// ==================================================

						$sourcePath = $image->getTempName();

						if (!is_file($sourcePath)) {

							throw new Exception(
								'No se pudo acceder al archivo de imagen.'
							);
						}

						// ==================================================
						// REAL IMAGE INFORMATION
						// ==================================================

						$imageInfo = @getimagesize($sourcePath);

						if ($imageInfo === false) {

							throw new Exception(
								'El archivo seleccionado no es una imagen válida.'
							);
						}

						$width = (int) $imageInfo[0];
						$height = (int) $imageInfo[1];
						$mime = isset($imageInfo['mime'])
							? strtolower($imageInfo['mime'])
							: '';

						// ==================================================
						// ALLOWED MIME TYPES
						// ==================================================

						$allowedMimeTypes = array(
							'image/jpeg',
							'image/png',
							'image/webp',
						);

						if (!in_array($mime, $allowedMimeTypes)) {

							throw new Exception(
								'El formato de imagen no está permitido. Utiliza JPG, PNG o WebP.'
							);
						}

						// ==================================================
						// VALID DIMENSIONS
						// ==================================================

						if ($width <= 0 || $height <= 0) {

							throw new Exception(
								'Las dimensiones de la imagen no son válidas.'
							);
						}

						// ==================================================
						// CHECK GD
						// ==================================================

						if (!function_exists('imagecreatetruecolor')) {

							throw new Exception(
								'El servidor no tiene habilitada la extensión GD de PHP.'
							);
						}

						if (!function_exists('imagewebp')) {

							throw new Exception(
								'El servidor no tiene soporte para generar imágenes WebP.'
							);
						}

						// ==================================================
						// CREATE SOURCE IMAGE
						// ==================================================

						$sourceImage = false;

						switch ($mime) {

							case 'image/jpeg':

								if (!function_exists('imagecreatefromjpeg')) {

									throw new Exception(
										'El servidor no tiene soporte para imágenes JPEG.'
									);
								}

								$sourceImage = @imagecreatefromjpeg(
									$sourcePath
								);

								break;

							case 'image/png':

								if (!function_exists('imagecreatefrompng')) {

									throw new Exception(
										'El servidor no tiene soporte para imágenes PNG.'
									);
								}

								$sourceImage = @imagecreatefrompng(
									$sourcePath
								);

								break;

							case 'image/webp':

								if (!function_exists('imagecreatefromwebp')) {

									throw new Exception(
										'El servidor no tiene soporte para imágenes WebP.'
									);
								}

								$sourceImage = @imagecreatefromwebp(
									$sourcePath
								);

								break;
						}

						if (!$sourceImage) {

							throw new Exception(
								'No se pudo procesar la imagen seleccionada.'
							);
						}

						// ==================================================
						// UPLOAD DIRECTORY
						// ==================================================

						$uploadDirectory = Yii::getPathOfAlias(
							'webroot.images'
						);

						$uploadDirectory .= DIRECTORY_SEPARATOR . 'hero-slides';

						if (!is_dir($uploadDirectory)) {

							if (!mkdir(
								$uploadDirectory,
								0755,
								true
							)) {

								imagedestroy($sourceImage);

								throw new Exception(
									'No se pudo crear el directorio de imágenes.'
								);
							}
						}

						if (!is_writable($uploadDirectory)) {

							imagedestroy($sourceImage);

							throw new Exception(
								'El directorio de imágenes no tiene permisos de escritura.'
							);
						}

						// ==================================================
						// IMAGE NAME
						// ==================================================

						$fileName =
							'hero-slide-' .
							uniqid('', true) .
							'.webp';

						$filePath =
							$uploadDirectory .
							DIRECTORY_SEPARATOR .
							$fileName;

						// ==================================================
						// WEBP OPTIMIZATION
						// ==================================================
						//
						// IMPORTANTE:
						// No hacemos resize.
						//
						// Si la imagen original es:
						//
						// 1920 x 800
						//
						// el WebP seguirá siendo:
						//
						// 1920 x 800
						//
						// ==================================================

						$optimized = @imagewebp(
							$sourceImage,
							$filePath,
							85
						);

						imagedestroy($sourceImage);

						if (!$optimized) {

							throw new Exception(
								'No se pudo optimizar y guardar la imagen WebP.'
							);
						}

						$createdImagePath = $filePath;

						// ==================================================
						// SAVE IMAGE PATH
						// ==================================================

						$model->image = $fileName;

						// ==================================================
						// DATABASE TRANSACTION
						// ==================================================

						$transaction = Yii::app()->db->beginTransaction();

						// ==================================================
						// SAVE HERO SLIDE
						// ==================================================

						if (!$model->save()) {

							throw new Exception(
								'No se pudo guardar el Hero Slide.'
							);
						}

						// ==================================================
						// SAVE TRANSLATION
						// ==================================================

						$translation->hero_slide_id = $model->id;

						if (!$translation->save()) {

							throw new Exception(
								'No se pudo guardar la traducción en español.'
							);
						}

						// ==================================================
						// COMMIT
						// ==================================================

						$transaction->commit();

						$this->redirect(array('index'));
					} catch (Exception $e) {

						// ==================================================
						// ROLLBACK DATABASE
						// ==================================================

						if ($transaction !== null) {

							try {
								$transaction->rollback();
							} catch (Exception $rollbackException) {
								// Ignore rollback exception.
							}
						}

						// ==================================================
						// REMOVE CREATED IMAGE
						// ==================================================

						if (
							$createdImagePath &&
							is_file($createdImagePath)
						) {
							@unlink($createdImagePath);
						}

						// ==================================================
						// MODEL ERROR
						// ==================================================

						$model->addError(
							'image',
							$e->getMessage()
						);
					}
				}
			}
		}

		// ==========================================================
		// RENDER
		// ==========================================================

		$this->render('create', array(
			'model' => $model,
			'translation' => $translation,
		));
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

		$post = Yii::app()->request->getPost(
			'HeroSlides',
			false
		);

		if ($post) {

			// ======================================================
			// PRESERVE CURRENT IMAGE
			// ======================================================
			//
			// The image must never be overwritten by the value
			// sent by the form.
			//
			// The image field will only be changed if a new
			// uploaded file is actually provided.
			// ======================================================

			$currentImage = $model->image;

			// ======================================================
			// REMOVE IMAGE FROM MASS ASSIGNMENT
			// ======================================================
			//
			// The form may send image="" when no new image
			// was selected. We do not want that empty value
			// to overwrite the existing database value.
			// ======================================================

			if (isset($post['image'])) {
				unset($post['image']);
			}

			$model->attributes = $post;

			// ======================================================
			// RESTORE CURRENT IMAGE
			// ======================================================

			$model->image = $currentImage;

			$model->updated_at = date('Y-m-d H:i:s');

			// ======================================================
			// IMAGE
			// ======================================================

			$image = CUploadedFile::getInstance(
				$model,
				'image'
			);

			$transaction = Yii::app()->db->beginTransaction();

			try {

				// ==================================================
				// VALIDATE IMAGE
				// ==================================================

				if ($image) {

					$extension = strtolower(
						$image->getExtensionName()
					);

					$allowedExtensions = array(
						'jpg',
						'jpeg',
						'png',
						'webp',
					);

					if (!in_array(
						$extension,
						$allowedExtensions
					)) {

						$model->addError(
							'image',
							'El formato de imagen no es válido.'
						);

						throw new Exception(
							'Formato de imagen no válido.'
						);
					}

					// ==================================================
					// IMAGE INFO
					// ==================================================

					$imageInfo = @getimagesize(
						$image->getTempName()
					);

					if (!$imageInfo) {

						$model->addError(
							'image',
							'El archivo seleccionado no es una imagen válida.'
						);

						throw new Exception(
							'El archivo seleccionado no es una imagen válida.'
						);
					}

					// ==================================================
					// UPLOAD DIRECTORY
					// ==================================================

					$uploadDirectory = Yii::getPathOfAlias(
						'webroot.images'
					);

					$uploadDirectory .=
						DIRECTORY_SEPARATOR .
						'hero-slides';

					if (!is_dir($uploadDirectory)) {

						if (!mkdir(
							$uploadDirectory,
							0755,
							true
						)) {

							$model->addError(
								'image',
								'No se pudo crear el directorio de imágenes.'
							);

							throw new Exception(
								'No se pudo crear el directorio de imágenes.'
							);
						}
					}

					// ==================================================
					// IMAGE NAME
					// ==================================================

					$fileName =
						'hero-slide-' .
						$model->id .
						'-' .
						time() .
						'.' .
						$extension;

					$filePath =
						$uploadDirectory .
						DIRECTORY_SEPARATOR .
						$fileName;

					// ==================================================
					// OPTIMIZE IMAGE
					// ==================================================

					$imageResource = null;

					switch ($extension) {

						case 'jpg':
						case 'jpeg':

							$imageResource = @imagecreatefromjpeg(
								$image->getTempName()
							);

							break;

						case 'png':

							$imageResource = @imagecreatefrompng(
								$image->getTempName()
							);

							break;

						case 'webp':

							if (function_exists('imagecreatefromwebp')) {

								$imageResource = @imagecreatefromwebp(
									$image->getTempName()
								);
							}

							break;
					}

					if (!$imageResource) {

						// If GD cannot process the image,
						// save the original instead of corrupting it.

						if (!$image->saveAs($filePath)) {

							$model->addError(
								'image',
								'No se pudo guardar la imagen.'
							);

							throw new Exception(
								'No se pudo guardar la imagen.'
							);
						}
					} else {

						// ==================================================
						// KEEP ORIGINAL DIMENSIONS
						// ==================================================

						$width = imagesx($imageResource);
						$height = imagesy($imageResource);

						// ==================================================
						// OPTIMIZED OUTPUT
						// ==================================================

						switch ($extension) {

							case 'jpg':
							case 'jpeg':

								imageinterlace(
									$imageResource,
									true
								);

								$saved = imagejpeg(
									$imageResource,
									$filePath,
									85
								);

								break;

							case 'png':

								$saved = imagepng(
									$imageResource,
									$filePath,
									6
								);

								break;

							case 'webp':

								$saved = false;

								if (function_exists('imagewebp')) {

									$saved = imagewebp(
										$imageResource,
										$filePath,
										85
									);
								}

								break;

							default:

								$saved = false;
						}

						imagedestroy(
							$imageResource
						);

						if (!$saved) {

							$model->addError(
								'image',
								'No se pudo optimizar y guardar la imagen.'
							);

							throw new Exception(
								'No se pudo optimizar y guardar la imagen.'
							);
						}
					}

					// ==================================================
					// DELETE OLD IMAGE
					// ==================================================

					if (
						$currentImage &&
						$currentImage !== $fileName
					) {

						$oldImagePath =
							$uploadDirectory .
							DIRECTORY_SEPARATOR .
							$currentImage;

						if (
							is_file($oldImagePath) &&
							is_writable($oldImagePath)
						) {

							@unlink(
								$oldImagePath
							);
						}
					}

					// ==================================================
					// UPDATE IMAGE FIELD
					// ==================================================

					$model->image = $fileName;
				}

				// ======================================================
				// SAVE HERO SLIDE
				// ======================================================

				if (!$model->save()) {

					throw new Exception(
						'No se pudo actualizar el Hero Slide.'
					);
				}

				// ======================================================
				// COMMIT
				// ======================================================

				$transaction->commit();

				$this->redirect(
					array('index')
				);

				return;
			} catch (Exception $e) {

				try {
					$transaction->rollback();
				} catch (Exception $rollbackException) {
					// Ignore rollback exception.
				}

				if (!$model->hasErrors()) {

					$model->addError(
						'image',
						$e->getMessage()
					);
				}
			}
		}

		$this->render(
			'update',
			array(
				'model' => $model,
			)
		);
	}

	/**
	 * Soft deletes a particular model.
	 * Instead of physically deleting the record, all tinyint fields are set to 0.
	 * If the update is successful, the browser will be redirected to the 'index' page.
	 *
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$model = $this->loadModel($id);

		$model->is_active = 0;

		if ($model->save()) {
			$this->redirect(array('index'));
		}
	}

	/**
	 * Manages all models.
	 */
	public function actionIndex()
	{
		$model = new HeroSlides('search');

		$model->unsetAttributes();

		$attributes = Yii::app()->request->getQuery(
			'HeroSlides',
			false
		);

		if ($attributes) {
			$model->attributes = $attributes;
		}

		$this->render(
			'index',
			array(
				'model' => $model,
			)
		);
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 *
	 * @param integer $id the ID of the model to be loaded
	 * @return HeroSlides the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model = HeroSlides::model()->findByPk($id);

		if ($model === null) {

			throw new CHttpException(
				404,
				'La página solicitada no existe.'
			);
		}

		return $model;
	}

	/**
	 * Creates a translation for an existing hero slide.
	 * If creation is successful, the browser will be redirected to the 'index' page.
	 *
	 * @param integer $id Hero slide ID
	 * @param integer $language_id Language ID
	 */
	public function actionCreateTranslation($id, $language_id)
	{
		$heroSlide = HeroSlides::model()->findByPk($id);

		if (!$heroSlide) {

			throw new CHttpException(
				404,
				'El Hero Slide solicitado no existe.'
			);
		}

		$language = Languages::model()->findByPk($language_id);

		if (!$language) {

			throw new CHttpException(
				404,
				'El idioma solicitado no existe.'
			);
		}

		// ======================================================
		// CHECK EXISTING TRANSLATION
		// ======================================================

		$existingTranslation = HeroSlideTranslations::model()->findByAttributes(
			array(
				'hero_slide_id' => $heroSlide->id,
				'language_id' => $language->id,
			)
		);

		if ($existingTranslation) {

			$this->redirect(array(
				'updateTranslation',
				'id' => $heroSlide->id,
				'language_id' => $language->id,
			));

			return;
		}

		// ======================================================
		// CREATE TRANSLATION
		// ======================================================

		$translation = new HeroSlideTranslations;

		$translation->hero_slide_id = $heroSlide->id;
		$translation->language_id = $language->id;

		$now = date('Y-m-d H:i:s');

		$translation->created_at = $now;
		$translation->updated_at = $now;

		$post = Yii::app()->request->getPost(
			'HeroSlideTranslations',
			false
		);

		if ($post) {

			$translation->attributes = $post;

			// Never allow the request to change the relationship.
			$translation->hero_slide_id = $heroSlide->id;
			$translation->language_id = $language->id;

			$translation->updated_at = $now;

			if ($translation->save()) {

				$this->redirect(array('index'));

				return;
			}
		}

		// ======================================================
		// RENDER
		// ======================================================

		$this->render(
			'update_translation',
			array(
				'model' => $translation,
				'heroSlide' => $heroSlide,
				'language' => $language,
			)
		);
	}

	/**
	 * Updates a translation for an existing hero slide.
	 * If update is successful, the browser will be redirected to the 'index' page.
	 *
	 * @param integer $id Hero slide ID
	 * @param integer $language_id Language ID
	 */
	public function actionUpdateTranslation($id, $language_id)
	{
		// ======================================================
		// HERO SLIDE
		// ======================================================

		$heroSlide = HeroSlides::model()->findByPk($id);

		if (!$heroSlide) {

			throw new CHttpException(
				404,
				'El Hero Slide solicitado no existe.'
			);
		}

		// ======================================================
		// LANGUAGE
		// ======================================================

		$language = Languages::model()->findByPk($language_id);

		if (!$language) {

			throw new CHttpException(
				404,
				'El idioma solicitado no existe.'
			);
		}

		// ======================================================
		// TRANSLATION
		// ======================================================

		$translation = HeroSlideTranslations::model()->findByAttributes(
			array(
				'hero_slide_id' => $heroSlide->id,
				'language_id' => $language->id,
			)
		);

		if (!$translation) {

			throw new CHttpException(
				404,
				'No existe una traducción para este Hero Slide en el idioma seleccionado.'
			);
		}

		// ======================================================
		// POST
		// ======================================================

		$post = Yii::app()->request->getPost(
			'HeroSlideTranslations',
			false
		);

		if ($post) {

			$translation->attributes = $post;

			// Never allow the request to modify the relationship.
			$translation->hero_slide_id = $heroSlide->id;
			$translation->language_id = $language->id;

			$translation->updated_at = date('Y-m-d H:i:s');

			if ($translation->save()) {

				$this->redirect(array('index'));

				return;
			}
		}

		// ======================================================
		// RENDER
		// ======================================================

		$this->render(
			'update_translation',
			array(
				'model' => $translation,
				'heroSlide' => $heroSlide,
				'language' => $language,
			)
		);
	}
}
