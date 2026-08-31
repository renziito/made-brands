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
			 * UPLOAD IMAGES
			 * ---------------------------------------------
			 */

			$mainImage = CUploadedFile::getInstanceByName(
				'Products[main_image]'
			);

			$infographicImage = CUploadedFile::getInstanceByName(
				'Products[infographic_image]'
			);

			$uploadedImages = array();

			$transaction =
				Yii::app()->db->beginTransaction();

			try {

				/*
				 * MAIN IMAGE
				 */

				if ($mainImage !== null) {

					$model->main_image =
						$this->saveProductImage(
							$mainImage,
							'main'
						);

					$uploadedImages[] =
						$model->main_image;
				} else {

					$model->main_image = null;
				}

				/*
				 * INFOGRAPHIC IMAGE
				 */

				if ($infographicImage !== null) {

					$model->infographic_image =
						$this->saveProductImage(
							$infographicImage,
							'infographic'
						);

					$uploadedImages[] =
						$model->infographic_image;
				} else {

					$model->infographic_image = null;
				}

				/*
				 * PRODUCT
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

				$translation->created_at =
					date('Y-m-d H:i:s');

				$translation->updated_at =
					date('Y-m-d H:i:s');

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

				/*
				 * Remove images uploaded during
				 * this failed operation.
				 */
				foreach ($uploadedImages as $uploadedImage) {

					$this->deleteProductImage(
						$uploadedImage
					);
				}

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
		 * KEEP ORIGINAL IMAGE VALUES
		 * ----------------------------------------------------------
		 */

		$oldMainImage =
			$model->main_image;

		$oldInfographicImage =
			$model->infographic_image;


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

			/*
			 * Never allow the text POST to overwrite
			 * the existing image filenames.
			 */
			$model->main_image =
				$oldMainImage;

			$model->infographic_image =
				$oldInfographicImage;

			$model->updated_at =
				date('Y-m-d H:i:s');


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
			 */

			$defaultTranslation = null;

			if ($defaultLanguage !== null) {

				$defaultTranslation =
					ProductTranslations::model()->findByAttributes(
						array(
							'product_id' =>
							(int) $model->id,

							'language_id' =>
							(int) $defaultLanguage->id,
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
			 * NEW UPLOADS
			 * ------------------------------------------------------
			 */

			$mainImage =
				CUploadedFile::getInstanceByName(
					'Products[main_image]'
				);

			$infographicImage =
				CUploadedFile::getInstanceByName(
					'Products[infographic_image]'
				);


			$newMainImage =
				null;

			$newInfographicImage =
				null;


			/*
			 * ------------------------------------------------------
			 * DATABASE TRANSACTION
			 * ------------------------------------------------------
			 */

			$transaction =
				Yii::app()->db->beginTransaction();

			try {

				/*
				 * --------------------------------------------------
				 * MAIN IMAGE
				 * --------------------------------------------------
				 *
				 * If a new file was selected, process it.
				 * Otherwise keep the current filename.
				 */

				if ($mainImage !== null) {

					$newMainImage =
						$this->saveProductImage(
							$mainImage,
							'main'
						);

					$model->main_image =
						$newMainImage;
				}


				/*
				 * --------------------------------------------------
				 * INFOGRAPHIC IMAGE
				 * --------------------------------------------------
				 */

				if ($infographicImage !== null) {

					$newInfographicImage =
						$this->saveProductImage(
							$infographicImage,
							'infographic'
						);

					$model->infographic_image =
						$newInfographicImage;
				}


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
				 */

				if (
					is_array($translationPost) &&
					!empty($translationPost)
				) {

					foreach (
						$translationPost
						as $translationId =>
						$translationAttributes
					) {

						$translationId =
							(int) $translationId;

						if ($translationId <= 0) {
							continue;
						}

						$translation =
							ProductTranslations::model()
							->findByAttributes(
								array(
									'id' =>
									$translationId,

									'product_id' =>
									(int) $model->id,
								)
							);

						if ($translation === null) {

							throw new CException(
								'No se encontró una traducción válida del producto.'
							);
						}


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

						$translation->updated_at =
							date('Y-m-d H:i:s');

						if (!$translation->save()) {

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
					isset(
						$newTranslationPost['language_id']
					) &&
					$newTranslationPost['language_id'] !== ''
				) {

					$newLanguageId =
						(int) $newTranslationPost['language_id'];

					$existingTranslation =
						ProductTranslations::model()
						->findByAttributes(
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

					$newTranslation->created_at =
						date('Y-m-d H:i:s');

					$newTranslation->updated_at =
						date('Y-m-d H:i:s');

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


				/*
				 * Delete old images only after the database
				 * transaction has been successfully committed.
				 */

				if (
					$newMainImage !== null &&
					$oldMainImage !== null &&
					$oldMainImage !== ''
				) {

					$this->deleteProductImage(
						$oldMainImage
					);
				}

				if (
					$newInfographicImage !== null &&
					$oldInfographicImage !== null &&
					$oldInfographicImage !== ''
				) {

					$this->deleteProductImage(
						$oldInfographicImage
					);
				}


				$this->redirect(
					array(
						'index',
					)
				);
			} catch (Exception $e) {

				$transaction->rollback();


				/*
				 * Remove the newly generated files because
				 * the update failed.
				 */

				if ($newMainImage !== null) {

					$this->deleteProductImage(
						$newMainImage
					);
				}

				if ($newInfographicImage !== null) {

					$this->deleteProductImage(
						$newInfographicImage
					);
				}


				/*
				 * Restore the model values so the form continues
				 * showing the original images after the error.
				 */

				$model->main_image =
					$oldMainImage;

				$model->infographic_image =
					$oldInfographicImage;


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
					'product_id' =>
					(int) $model->id,
				),
				array(
					'order' =>
					'language_id ASC, id ASC',
				)
			);


		$this->render(
			'update',
			array(
				'model' =>
				$model,

				'translations' =>
				$translations,

				'defaultLanguage' =>
				$defaultLanguage,
			)
		);
	}


	/**
	 * Saves and optimizes a product image.
	 *
	 * The original width and height are preserved.
	 * The image is NOT resized.
	 *
	 * Supported formats:
	 * - JPEG
	 * - PNG
	 * - WebP
	 *
	 * @param CUploadedFile $file
	 * @param string $prefix
	 * @return string
	 * @throws CException
	 */
	protected function saveProductImage(
		$file,
		$prefix
	) {

		if (
			!$file instanceof CUploadedFile
		) {

			throw new CException(
				'El archivo de imagen no es válido.'
			);
		}


		/*
		 * Upload error.
		 */

		if (
			$file->error !== UPLOAD_ERR_OK
		) {

			throw new CException(
				'No se pudo recibir correctamente la imagen.'
			);
		}


		/*
		 * Maximum upload size: 15 MB.
		 */

		if (
			(int) $file->size <= 0 ||
			(int) $file->size > 15 * 1024 * 1024
		) {

			throw new CException(
				'La imagen debe tener un tamaño entre 1 byte y 15 MB.'
			);
		}


		/*
		 * Validate the actual image contents.
		 * Do not trust only the browser MIME type.
		 */

		$imageInfo =
			@getimagesize(
				$file->tempName
			);

		if ($imageInfo === false) {

			throw new CException(
				'El archivo seleccionado no es una imagen válida.'
			);
		}


		$mime =
			isset($imageInfo['mime'])
			? strtolower(
				(string) $imageInfo['mime']
			)
			: '';


		$allowedMimeTypes = array(
			'image/jpeg',
			'image/png',
			'image/webp',
		);


		if (
			!in_array(
				$mime,
				$allowedMimeTypes,
				true
			)
		) {

			throw new CException(
				'Solo se permiten imágenes JPG, PNG o WebP.'
			);
		}


		/*
		 * GD is required.
		 */

		if (
			!function_exists(
				'imagecreatefromstring'
			)
		) {

			throw new CException(
				'La extensión GD de PHP no está disponible.'
			);
		}


		/*
		 * Read the original image.
		 */

		$imageData =
			@file_get_contents(
				$file->tempName
			);

		if ($imageData === false) {

			throw new CException(
				'No se pudo leer la imagen.'
			);
		}


		$image =
			@imagecreatefromstring(
				$imageData
			);

		if ($image === false) {

			throw new CException(
				'No se pudo procesar la imagen.'
			);
		}


		/*
		 * ----------------------------------------------------------
		 * IMPORTANT
		 * ----------------------------------------------------------
		 *
		 * No imagecreatetruecolor().
		 * No imagecopyresampled().
		 * No width/height calculations.
		 *
		 * Therefore the original dimensions are preserved.
		 * We only recompress the image.
		 * ----------------------------------------------------------
		 */


		$uploadDirectory =
			Yii::getPathOfAlias(
				'webroot'
			) .
			DIRECTORY_SEPARATOR .
			'images' .
			DIRECTORY_SEPARATOR .
			'products';


		if (
			!is_dir(
				$uploadDirectory
			)
		) {

			if (
				!@mkdir(
					$uploadDirectory,
					0755,
					true
				)
			) {

				imagedestroy($image);

				throw new CException(
					'No se pudo crear la carpeta de imágenes de productos.'
				);
			}
		}


		if (
			!is_writable(
				$uploadDirectory
			)
		) {

			imagedestroy($image);

			throw new CException(
				'La carpeta de imágenes de productos no tiene permisos de escritura.'
			);
		}


		/*
		 * Keep the original format.
		 *
		 * This avoids changing the public URL extension and
		 * preserves compatibility with existing product images.
		 */

		switch ($mime) {

			case 'image/jpeg':
				$extension = 'jpg';
				break;

			case 'image/png':
				$extension = 'png';
				break;

			case 'image/webp':
				$extension = 'webp';
				break;

			default:

				imagedestroy($image);

				throw new CException(
					'Formato de imagen no soportado.'
				);
		}


		$filename =
			$prefix .
			'_' .
			uniqid(
				'',
				true
			) .
			'.' .
			$extension;


		$targetPath =
			$uploadDirectory .
			DIRECTORY_SEPARATOR .
			$filename;


		$saved = false;


		/*
		 * ----------------------------------------------------------
		 * JPEG
		 * ----------------------------------------------------------
		 *
		 * Quality 82 gives a good reduction in file size while
		 * preserving visual quality.
		 */

		if (
			$mime === 'image/jpeg'
		) {

			if (
				function_exists(
					'imageinterlace'
				)
			) {

				imageinterlace(
					$image,
					true
				);
			}


			$saved =
				@imagejpeg(
					$image,
					$targetPath,
					82
				);
		}


		/*
		 * ----------------------------------------------------------
		 * PNG
		 * ----------------------------------------------------------
		 *
		 * PNG compression level 6.
		 * Alpha channel is preserved.
		 */ elseif (
			$mime === 'image/png'
		) {

			imagealphablending(
				$image,
				false
			);

			imagesavealpha(
				$image,
				true
			);


			$saved =
				@imagepng(
					$image,
					$targetPath,
					6
				);
		}


		/*
		 * ----------------------------------------------------------
		 * WEBP
		 * ----------------------------------------------------------
		 */ elseif (
			$mime === 'image/webp'
		) {

			if (
				!function_exists(
					'imagewebp'
				)
			) {

				imagedestroy($image);

				throw new CException(
					'El servidor no soporta WebP mediante GD.'
				);
			}


			$saved =
				@imagewebp(
					$image,
					$targetPath,
					82
				);
		}


		imagedestroy(
			$image
		);


		/*
		 * Validate the resulting file.
		 */

		if (
			!$saved ||
			!is_file(
				$targetPath
			) ||
			filesize(
				$targetPath
			) <= 0
		) {

			@unlink(
				$targetPath
			);

			throw new CException(
				'No se pudo guardar la imagen optimizada.'
			);
		}


		return $filename;
	}


	/**
	 * Deletes a product image from disk.
	 *
	 * @param string $filename
	 */
	protected function deleteProductImage(
		$filename
	) {

		$filename =
			basename(
				(string) $filename
			);


		if (
			$filename === ''
		) {
			return;
		}


		$imagePath =
			Yii::getPathOfAlias(
				'webroot'
			) .
			DIRECTORY_SEPARATOR .
			'images' .
			DIRECTORY_SEPARATOR .
			'products' .
			DIRECTORY_SEPARATOR .
			$filename;


		if (
			is_file(
				$imagePath
			)
		) {

			@unlink(
				$imagePath
			);
		}
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

		$model->status = 0;

		if ($model->save()) {

			$this->redirect(
				array(
					'index',
				)
			);
		}
	}


	/**
	 * Manages all models.
	 */
	public function actionIndex()
	{
		$model = new Products('search');

		$model->unsetAttributes();

		$attributes =
			Yii::app()->request->getQuery(
				'Products',
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
	 * Returns the data model based on the primary key given in the GET variable.
	 *
	 * @param integer $id the ID of the model to be loaded
	 * @return Products the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model =
			Products::model()->findByPk(
				$id
			);

		if (
			$model === null
		) {

			throw new CHttpException(
				404,
				'La página solicitada no existe.'
			);
		}

		return $model;
	}
}
