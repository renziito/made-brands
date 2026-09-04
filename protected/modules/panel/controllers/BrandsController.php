<?php
class BrandsController extends Controller
{
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'index' page.
	 */
	public function actionCreate()
	{
		$model = new Brands;
		$post = Yii::app()->request->getPost('Brands', false);

		if ($post) {
			$model->attributes = $post;

			$model->slug = Utils::slugify($model->name);
			$model->created_at = date('Y-m-d H:i:s');
			$model->updated_at = date('Y-m-d H:i:s');

			$uploadedFile = CUploadedFile::getInstance($model, 'logo');

			if ($uploadedFile) {
				$logo = $this->saveOptimizedLogo($uploadedFile);

				if ($logo === false) {
					$model->addError('logo', 'No se pudo procesar la imagen del logo.');
				} else {
					$model->logo = $logo;
				}
			}

			if (!$model->hasErrors() && $model->save()) {
				$this->redirect(array('index'));
			}
		}

		$this->render('create', array(
			'model' => $model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);
		$post = Yii::app()->request->getPost('Brands', false);

		if ($post) {
			$oldLogo = $model->logo;

			$model->attributes = $post;

			$model->slug = Utils::slugify($model->name);
			$model->updated_at = date('Y-m-d H:i:s');

			$uploadedFile = CUploadedFile::getInstance($model, 'logo');

			if ($uploadedFile) {
				$logo = $this->saveOptimizedLogo($uploadedFile);

				if ($logo === false) {
					$model->addError('logo', 'No se pudo procesar la imagen del logo.');
				} else {
					$model->logo = $logo;
				}
			} else {
				$model->logo = $oldLogo;
			}

			if (!$model->hasErrors() && $model->save()) {
				if ($uploadedFile && $oldLogo && $oldLogo !== $model->logo) {
					$this->deleteLogoFile($oldLogo);
				}

				$this->redirect(array('index'));
			}
		}

		$this->render('update', array(
			'model' => $model,
		));
	}

	/**
	 * Soft deletes a particular model.
	 * Instead of physically deleting the record, all tinyint fields are set to 0.
	 * If the update is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$model = $this->loadModel($id);
		$model->is_featured = 0;
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
		$model = new Brands('search');
		$model->unsetAttributes();

		$attributes = Yii::app()->request->getQuery('Brands', false);

		if ($attributes) {
			$model->attributes = $attributes;
		}

		$this->render('index', array(
			'model' => $model,
		));
	}

	/**
	 * Saves and optimizes a brand logo.
	 *
	 * The original dimensions are preserved.
	 * The image is converted to WebP to reduce file size.
	 *
	 * @param CUploadedFile $uploadedFile
	 * @return string|false
	 */
	private function saveOptimizedLogo($uploadedFile)
	{
		$uploadPath = Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'brands' . DIRECTORY_SEPARATOR;

		if (!is_dir($uploadPath)) {
			if (!mkdir($uploadPath, 0755, true)) {
				return false;
			}
		}

		$extension = strtolower($uploadedFile->getExtensionName());

		$allowedExtensions = array(
			'jpg',
			'jpeg',
			'png',
			'webp',
		);

		if (!in_array($extension, $allowedExtensions, true)) {
			return false;
		}

		$imageInfo = @getimagesize($uploadedFile->getTempName());

		if ($imageInfo === false) {
			return false;
		}

		$mimeType = isset($imageInfo['mime']) ? $imageInfo['mime'] : '';

		switch ($mimeType) {
			case 'image/jpeg':
				$image = @imagecreatefromjpeg($uploadedFile->getTempName());
				break;

			case 'image/png':
				$image = @imagecreatefrompng($uploadedFile->getTempName());
				break;

			case 'image/webp':
				if (!function_exists('imagecreatefromwebp')) {
					return false;
				}
				$image = @imagecreatefromwebp($uploadedFile->getTempName());
				break;

			default:
				return false;
		}

		if (!$image) {
			return false;
		}

		$width = imagesx($image);
		$height = imagesy($image);

		if ($width <= 0 || $height <= 0) {
			imagedestroy($image);
			return false;
		}

		/*
		 * Preserve transparency.
		 */
		if (function_exists('imagealphablending')) {
			imagealphablending($image, false);
		}

		if (function_exists('imagesavealpha')) {
			imagesavealpha($image, true);
		}

		/*
		 * Generate a unique filename.
		 */
		$filename = 'brand-' . date('His') . '.webp';
		$filePath = $uploadPath . $filename;

		/*
		 * WebP quality:
		 * 85 gives a good balance between visual quality and file size.
		 *
		 * Dimensions are NOT changed.
		 */
		if (!function_exists('imagewebp')) {
			imagedestroy($image);
			return false;
		}

		$saved = @imagewebp($image, $filePath, 85);

		imagedestroy($image);

		if (!$saved || !file_exists($filePath)) {
			if (file_exists($filePath)) {
				@unlink($filePath);
			}

			return false;
		}

		/*
		 * Return the relative path stored in the database.
		 */
		return 'images/brands/' . $filename;
	}

	/**
	 * Deletes the previous logo file.
	 *
	 * @param string $logo
	 * @return void
	 */
	private function deleteLogoFile($logo)
	{
		if (!$logo) {
			return;
		}

		$filename = basename($logo);
		$filePath = Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'brands' . DIRECTORY_SEPARATOR . $filename;

		if (file_exists($filePath)) {
			@unlink($filePath);
		}
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Brands the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model = Brands::model()->findByPk($id);

		if ($model === null) {
			throw new CHttpException(404, 'La página solicitada no existe.');
		}

		return $model;
	}
}
