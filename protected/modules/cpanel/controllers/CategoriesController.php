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
		$this->categoryManager = new CategoryManager;
		$this->categoryTranslationManager = new CategoryTranslationManager;
		$this->subcategoryManager = new SubcategoryManager;
		$this->ajax = new AjaxResponseHelper;
	}

	/**
	 * Creates a new category together with its default language translation.
	 */
	public function actionCreate()
	{
		$model = new Categories;
		$translation = new CategoryTranslations;
		$defaultLanguage = null;

		$postCategory = Yii::app()->request->getPost('Categories', false);
		$postTranslation = Yii::app()->request->getPost('CategoryTranslations', false);

		if ($postCategory || $postTranslation) {
			$result = $this->categoryManager->create($postCategory ? $postCategory : array(), $postTranslation ? $postTranslation : array());
			$model = $result['model'];
			$translation = $result['translation'];
			$defaultLanguage = $result['defaultLanguage'];

			if ($result['saved']) {
				if (!$this->saveCategoryImage($model)) {
					$model->addError('image', 'No fue posible guardar la imagen de la categoría.');
				} else {
					$this->redirect(array('update', 'id' => $model->id, 'created' => 1));
				}
			}
		}

		if (!$defaultLanguage) {
			$defaultLanguage = $this->categoryManager->getDefaultLanguage();
		}

		$this->render('create', array(
			'model' => $model,
			'translation' => $translation,
			'defaultLanguage' => $defaultLanguage,
		));
	}

	/**
	 * Updates a particular category.
	 */
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);
		$post = Yii::app()->request->getPost('Categories', false);
		$oldImage = (string) $model->image;

		if ($post) {
			if ($this->categoryManager->update($model, $post)) {
				$model->image = $oldImage;

				if (!$model->save(false, array('image'))) {
					$model->addError('image', 'No fue posible conservar la imagen actual.');
				}

				if (!$this->saveCategoryImage($model, $oldImage)) {
					$model->addError('image', 'No fue posible guardar la nueva imagen.');
				} else {
					$this->redirect(array('update', 'id' => $model->id));
				}
			}
		}

		$data = $this->categoryManager->getUpdateData($model);
		$this->render('update', $data);
	}

	/**
	 * Saves and optimizes the uploaded category image.
	 *
	 * The physical image is stored in:
	 * /images/categories
	 *
	 * The database stores only the filename.
	 *
	 * Example:
	 * category_123456.webp
	 */
	protected function saveCategoryImage($model, $oldImage = '')
	{
		$image = CUploadedFile::getInstance($model, 'image');

		if ($image === null || $image->error === UPLOAD_ERR_NO_FILE) {
			$model->image = $oldImage !== '' ? $oldImage : (string) $model->image;
			return true;
		}

		if ($image->error !== UPLOAD_ERR_OK) {
			$model->addError('image', 'La imagen no pudo ser subida correctamente.');
			return false;
		}

		$allowedExtensions = array('jpg', 'jpeg', 'png', 'webp');
		$extension = strtolower($image->getExtensionName());

		if (!in_array($extension, $allowedExtensions, true)) {
			$model->addError('image', 'El formato de imagen no es válido. Usa JPG, JPEG, PNG o WEBP.');
			return false;
		}

		if ((int) $image->size > 5 * 1024 * 1024) {
			$model->addError('image', 'La imagen no puede superar los 5 MB.');
			return false;
		}

		$imageInfo = @getimagesize($image->tempName);

		if ($imageInfo === false) {
			$model->addError('image', 'El archivo seleccionado no es una imagen válida.');
			return false;
		}

		$allowedMimeTypes = array(
			'image/jpeg',
			'image/png',
			'image/webp',
		);

		if (!in_array($imageInfo['mime'], $allowedMimeTypes, true)) {
			$model->addError('image', 'El tipo de imagen no es válido.');
			return false;
		}

		$basePath = Yii::getPathOfAlias('webroot');
		$uploadPath = $basePath . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'categories';

		if (!is_dir($uploadPath) && !@mkdir($uploadPath, 0755, true)) {
			$model->addError('image', 'No fue posible crear la carpeta de imágenes.');
			return false;
		}

		if (!is_writable($uploadPath)) {
			$model->addError('image', 'La carpeta de imágenes no tiene permisos de escritura.');
			return false;
		}

		$fileName = 'category_' . uniqid('', true) . '.' . $extension;
		$filePath = $uploadPath . DIRECTORY_SEPARATOR . $fileName;

		if (!$this->optimizeCategoryImage($image->tempName, $filePath, $imageInfo['mime'])) {
			$model->addError('image', 'No fue posible optimizar y guardar la imagen.');
			return false;
		}

		$model->image = 'images' . DIRECTORY_SEPARATOR . 'categories' . DIRECTORY_SEPARATOR . $fileName;

		if (!$model->save(false, array('image'))) {
			@unlink($filePath);
			$model->image = $oldImage !== '' ? $oldImage : '';
			$model->addError('image', 'No fue posible guardar la referencia de la imagen.');
			return false;
		}

		if ($oldImage !== '' && $oldImage !== $fileName) {
			$oldFile = $uploadPath . DIRECTORY_SEPARATOR . basename($oldImage);

			if (is_file($oldFile) && realpath($oldFile) !== realpath($filePath)) {
				@unlink($oldFile);
			}
		}

		return true;
	}

	/**
	 * Optimizes an image without resizing it.
	 *
	 * Imagick is preferred because it can strip metadata while preserving
	 * the original dimensions. GD is used as a fallback.
	 */
	protected function optimizeCategoryImage($sourcePath, $destinationPath, $mimeType)
	{
		if (extension_loaded('imagick')) {
			try {
				$image = new Imagick;
				$image->readImage($sourcePath);
				$image->setIteratorIndex(0);
				$image->stripImage();

				if ($mimeType === 'image/jpeg') {
					$image->setImageFormat('jpeg');
					$image->setImageCompression(Imagick::COMPRESSION_JPEG);
					$image->setImageCompressionQuality(92);
					$image->setInterlaceScheme(Imagick::INTERLACE_PLANE);
				} elseif ($mimeType === 'image/webp') {
					$image->setImageFormat('webp');
					$image->setImageCompressionQuality(90);
				} elseif ($mimeType === 'image/png') {
					$image->setImageFormat('png');
					$image->setOption('png:compression-level', '9');
					$image->setOption('png:compression-filter', '5');
					$image->setOption('png:compression-strategy', '1');
				}

				$result = $image->writeImage($destinationPath);
				$image->clear();
				$image->destroy();

				return $result;
			} catch (Exception $e) {
				if (is_file($destinationPath)) {
					@unlink($destinationPath);
				}
			}
		}

		if (!extension_loaded('gd')) {
			return false;
		}

		switch ($mimeType) {
			case 'image/jpeg':
				$source = @imagecreatefromjpeg($sourcePath);

				if (!$source) {
					return false;
				}

				$result = imagejpeg($source, $destinationPath, 92);
				imagedestroy($source);

				return $result;

			case 'image/png':
				$source = @imagecreatefrompng($sourcePath);

				if (!$source) {
					return false;
				}

				imagealphablending($source, false);
				imagesavealpha($source, true);

				$result = imagepng($source, $destinationPath, 9);
				imagedestroy($source);

				return $result;

			case 'image/webp':
				if (!function_exists('imagecreatefromwebp') || !function_exists('imagewebp')) {
					return false;
				}

				$source = @imagecreatefromwebp($sourcePath);

				if (!$source) {
					return false;
				}

				$result = imagewebp($source, $destinationPath, 90);
				imagedestroy($source);

				return $result;
		}

		return false;
	}

	/**
	 * AJAX modal:
	 *
	 * Add / edit category translation.
	 *
	 * GET:
	 * Returns the modal form.
	 *
	 * POST:
	 * Validates and saves the translation.
	 */
	public function actionTranslation()
	{
		$categoryId = (int) Yii::app()->request->getQuery('category_id', 0);
		$languageId = (int) Yii::app()->request->getQuery('language_id', 0);
		$data = $this->categoryTranslationManager->getFormData($categoryId, $languageId);
		$category = $data['category'];
		$language = $data['language'];
		$translation = $data['translation'];

		if (Yii::app()->request->isPostRequest) {
			$post = Yii::app()->request->getPost('CategoryTranslations', array());
			$this->categoryTranslationManager->applyAttributes($translation, $post);
			$translation->category_id = $category->id;
			$translation->language_id = $language->id;

			if ($this->categoryTranslationManager->save($translation)) {
				$this->ajax->success(array(
					'message' => 'La traducción se guardó correctamente.',
					'refresh' => true,
				));
			}

			$this->ajax->categoryForm($this, 'translation', array(
				'model' => $translation,
				'category' => $category,
				'language' => $language,
			));
			return;
		}

		$this->ajax->categoryForm($this, 'translation', array(
			'model' => $translation,
			'category' => $category,
			'language' => $language,
		));
	}

	/**
	 * AJAX modal:
	 *
	 * Add / edit a subcategory.
	 */
	public function actionSubcategory()
	{
		$categoryId = (int) Yii::app()->request->getQuery('category_id', 0);
		$subcategoryId = (int) Yii::app()->request->getQuery('id', 0);
		$category = $this->loadModel($categoryId);

		if ($subcategoryId > 0) {
			$subcategory = $this->subcategoryManager->find($category->id, $subcategoryId, true);

			if ($subcategory === null) {
				throw new CHttpException(404, 'La subcategoría solicitada no existe.');
			}

			if (Yii::app()->request->isPostRequest) {
				$post = Yii::app()->request->getPost('Subcategories', array());
				$this->subcategoryManager->applyAttributes($subcategory, $post, $category->id);

				if ($this->subcategoryManager->save($subcategory)) {
					$this->ajax->success(array(
						'message' => 'La subcategoría se guardó correctamente.',
						'refresh' => true,
					));
				}

				$this->ajax->categoryForm($this, 'subcategory', array(
					'model' => $subcategory,
					'category' => $category,
				));
				return;
			}

			$this->ajax->categoryForm($this, 'subcategory', array(
				'model' => $subcategory,
				'category' => $category,
			));
			return;
		}

		$subcategory = $this->subcategoryManager->create($category->id);
		$translation = new SubcategoryTranslations;
		$defaultLanguage = $this->subcategoryManager->getDefaultLanguage();

		if (Yii::app()->request->isPostRequest) {
			$postSubcategory = Yii::app()->request->getPost('Subcategories', array());
			$postTranslation = Yii::app()->request->getPost('SubcategoryTranslations', array());

			$result = $this->subcategoryManager->createWithDefaultTranslation($category->id, $postSubcategory, $postTranslation);
			$subcategory = $result['subcategory'];
			$translation = $result['translation'];
			$defaultLanguage = $result['defaultLanguage'];

			if ($result['saved']) {
				$this->ajax->success(array(
					'message' => 'La subcategoría se guardó correctamente.',
					'refresh' => true,
				));
			}

			$this->ajax->categoryForm($this, 'subcategory', array(
				'model' => $subcategory,
				'category' => $category,
				'translation' => $translation,
				'defaultLanguage' => $defaultLanguage,
			));
			return;
		}

		$this->ajax->categoryForm($this, 'subcategory', array(
			'model' => $subcategory,
			'category' => $category,
			'translation' => $translation,
			'defaultLanguage' => $defaultLanguage,
		));
	}

	/**
	 * Soft deletes a subcategory.
	 */
	public function actionRemoveSubcategory()
	{
		$categoryId = (int) Yii::app()->request->getPost('category_id', 0);
		$subcategoryId = (int) Yii::app()->request->getPost('subcategory_id', 0);

		if ($categoryId <= 0 || $subcategoryId <= 0) {
			$this->ajax->error('Los datos de la subcategoría no son válidos.');
			return;
		}

		$subcategory = $this->subcategoryManager->find($categoryId, $subcategoryId, false);

		if ($subcategory === null) {
			$this->ajax->error('La subcategoría solicitada no existe.');
			return;
		}

		if (!$this->subcategoryManager->remove($subcategory)) {
			$this->ajax->error('No fue posible remover la subcategoría.');
			return;
		}

		$this->ajax->success(array(
			'message' => 'La subcategoría fue removida correctamente.',
		));
	}

	/**
	 * AJAX modal:
	 *
	 * Add / edit a subcategory translation.
	 */
	public function actionSubcategoryTranslation()
	{
		$categoryId = (int) Yii::app()->request->getQuery('category_id', 0);
		$subcategoryId = (int) Yii::app()->request->getQuery('subcategory_id', 0);
		$languageId = (int) Yii::app()->request->getQuery('language_id', 0);
		$data = $this->subcategoryManager->getTranslationFormData($categoryId, $subcategoryId, $languageId);
		$category = $data['category'];
		$subcategory = $data['subcategory'];
		$language = $data['language'];
		$translation = $data['translation'];

		if (Yii::app()->request->isPostRequest) {
			$post = Yii::app()->request->getPost('SubcategoryTranslations', array());
			$this->subcategoryManager->applyTranslationAttributes($translation, $post, $subcategory->id, $language->id);

			if ($this->subcategoryManager->saveTranslation($translation)) {
				$this->ajax->success(array(
					'message' => 'La traducción de la subcategoría se guardó correctamente.',
					'refresh' => true,
				));
			}

			$this->ajax->categoryForm($this, 'subcategoryTranslation', array(
				'model' => $translation,
				'category' => $category,
				'subcategory' => $subcategory,
				'language' => $language,
			));
			return;
		}

		$this->ajax->categoryForm($this, 'subcategoryTranslation', array(
			'model' => $translation,
			'category' => $category,
			'subcategory' => $subcategory,
			'language' => $language,
		));
	}

	/**
	 * Soft deletes a category.
	 */
	public function actionDelete($id)
	{
		$model = $this->loadModel($id);

		if ($this->categoryManager->delete($model)) {
			$this->redirect(array('index'));
		}
	}

	/**
	 * Manages all categories.
	 */
	public function actionIndex()
	{
		$model = new Categories('search');
		$model->unsetAttributes();
		$attributes = Yii::app()->request->getQuery('Categories', false);

		if ($attributes) {
			$model->attributes = $attributes;
		}

		$this->render('index', array('model' => $model));
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
		$model = Categories::model()->findByPk((int) $id);

		if ($model === null) {
			throw new CHttpException(404, 'La página solicitada no existe.');
		}

		return $model;
	}
}
