<?php

class BrandsSectionController extends Controller
{
    /**
     * Layout utilizado por el controlador.
     */
    public $layout = '//layouts/column2';


    /**
     * Filtros.
     */
    public function filters()
    {
        return array(
            'accessControl',
        );
    }


    /**
     * Control de acceso.
     */
    public function accessRules()
    {
        return array(
            array(
                'allow',
                'users' => array('@'),
            ),

            array(
                'deny',
                'users' => array('*'),
            ),
        );
    }


    /**
     * Lista los registros.
     */
    public function actionIndex()
    {
        $model = new BrandsSection('search');

        $model->unsetAttributes();

        if (isset($_GET['BrandsSection'])) {
            $model->attributes = $_GET['BrandsSection'];
        }

        $this->render('index', array(
            'model' => $model,
        ));
    }


    /**
     * Crea un nuevo registro.
     *
     * Se utiliza cuando un idioma todavía no tiene
     * un registro en brands_section.
     */
    public function actionCreate()
    {
        $model = new BrandsSection;



        if (isset($_POST['BrandsSection'])) {

            $model->attributes = $_POST['BrandsSection'];


            /*
			 * --------------------------------------------------
			 * IMAGE
			 * --------------------------------------------------
			 */

            $imageFile = CUploadedFile::getInstance(
                $model,
                'image'
            );

            if ($imageFile !== null) {

                $imagePath = $this->saveImage(
                    $imageFile,
                    $model->language_id
                );

                if ($imagePath === false) {

                    $model->addError(
                        'image',
                        'No se pudo procesar la imagen.'
                    );
                } else {

                    $model->image = $imagePath;
                }
            }


            /*
			 * --------------------------------------------------
			 * DATES
			 * --------------------------------------------------
			 */

            $model->created_at = date('Y-m-d H:i:s');
            $model->updated_at = date('Y-m-d H:i:s');


            /*
			 * --------------------------------------------------
			 * SAVE
			 * --------------------------------------------------
			 */
            if (!$model->hasErrors() && $model->save()) {

                $this->redirect(Yii::app()->createAbsoluteUrl('panel/brands'));
            }
        }


        $this->render('create', array(
            'model' => $model,
        ));
    }


    /**
     * Actualiza un registro existente.
     *
     * @param integer $id
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        /*
		 * Guardamos la imagen anterior antes de procesar
		 * cualquier archivo nuevo.
		 */
        $oldImage = $model->image;


        if (isset($_POST['BrandsSection'])) {

            $model->attributes = $_POST['BrandsSection'];


            /*
			 * --------------------------------------------------
			 * IMAGE
			 * --------------------------------------------------
			 *
			 * Si no se selecciona una nueva imagen,
			 * mantenemos la imagen existente.
			 */

            $imageFile = CUploadedFile::getInstance(
                $model,
                'image'
            );


            if ($imageFile !== null) {

                $imagePath = $this->saveImage(
                    $imageFile,
                    $model->language_id
                );


                if ($imagePath === false) {

                    $model->addError(
                        'image',
                        'No se pudo procesar la imagen.'
                    );

                    /*
					 * Restauramos la imagen anterior
					 * para no perderla.
					 */
                    $model->image = $oldImage;
                } else {

                    $model->image = $imagePath;
                }
            } else {

                /*
				 * No se subió imagen nueva.
				 * Conservamos la existente.
				 */
                $model->image = $oldImage;
            }


            /*
			 * --------------------------------------------------
			 * UPDATED AT
			 * --------------------------------------------------
			 */

            $model->updated_at = date('Y-m-d H:i:s');


            /*
			 * --------------------------------------------------
			 * SAVE
			 * --------------------------------------------------
			 */

            if (!$model->hasErrors() && $model->save()) {

                /*
				 * Si se subió una imagen nueva correctamente,
				 * eliminamos la anterior.
				 */
                if (
                    $imageFile !== null &&
                    $model->image !== $oldImage
                ) {

                    $this->deleteOldImage(
                        $oldImage
                    );
                }


                $this->redirect(Yii::app()->createAbsoluteUrl('panel/brands'));
            }
        }


        $this->render('update', array(
            'model' => $model,
        ));
    }


    /**
     * Elimina un registro.
     *
     * @param integer $id
     */
    public function actionDelete($id)
    {
        if (Yii::app()->request->isPostRequest) {

            $model = $this->loadModel($id);

            $image = $model->image;

            if ($model->delete()) {

                $this->deleteOldImage(
                    $image
                );
            }
        } else {

            throw new CHttpException(
                400,
                'Invalid request.'
            );
        }


        if (!isset($_GET['ajax'])) {

            $this->redirect(
                array('index')
            );
        }
    }


    /**
     * Carga un BrandsSection por ID.
     *
     * @param integer $id
     * @return BrandsSection
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = BrandsSection::model()->findByPk(
            $id
        );


        if ($model === null) {

            throw new CHttpException(
                404,
                'El registro solicitado no existe.'
            );
        }


        return $model;
    }


    /**
     * Guarda y optimiza una imagen.
     *
     * La imagen final se almacena en:
     *
     * /images/brands-section/
     *
     * en formato WebP.
     *
     * @param CUploadedFile $imageFile
     * @param integer|string $languageId
     * @return string|false
     */
    protected function saveImage($imageFile, $languageId)
    {
        if (
            $imageFile === null ||
            $imageFile->getError() !== UPLOAD_ERR_OK
        ) {

            return false;
        }


        /*
		 * --------------------------------------------------
		 * VALID EXTENSION
		 * --------------------------------------------------
		 */

        $allowedExtensions = array(
            'jpg',
            'jpeg',
            'png',
            'webp',
            'gif',
        );

        $extension =
            strtolower(
                $imageFile->getExtensionName()
            );


        if (
            !in_array(
                $extension,
                $allowedExtensions,
                true
            )
        ) {

            return false;
        }


        /*
		 * --------------------------------------------------
		 * BASE PATH
		 * --------------------------------------------------
		 */

        $basePath =
            Yii::getPathOfAlias('webroot') .
            '/images/brands-section';


        if (
            !is_dir($basePath)
        ) {

            if (
                !mkdir(
                    $basePath,
                    0755,
                    true
                )
            ) {

                return false;
            }
        }


        /*
		 * --------------------------------------------------
		 * IMAGE NAME
		 * --------------------------------------------------
		 */

        $fileName =
            'brands-section-' .
            (int) $languageId .
            '-' .
            time() .
            '.webp';


        $filePath =
            $basePath .
            '/' .
            $fileName;


        /*
		 * --------------------------------------------------
		 * SOURCE IMAGE
		 * --------------------------------------------------
		 */

        $sourcePath =
            $imageFile->getTempName();


        $imageInfo =
            @getimagesize(
                $sourcePath
            );


        if ($imageInfo === false) {

            return false;
        }


        $mimeType =
            isset($imageInfo['mime'])
            ? $imageInfo['mime']
            : '';


        /*
		 * --------------------------------------------------
		 * CREATE SOURCE RESOURCE
		 * --------------------------------------------------
		 */

        $sourceImage = false;


        switch ($mimeType) {

            case 'image/jpeg':

                if (function_exists('imagecreatefromjpeg')) {

                    $sourceImage =
                        @imagecreatefromjpeg(
                            $sourcePath
                        );
                }

                break;


            case 'image/png':

                if (function_exists('imagecreatefrompng')) {

                    $sourceImage =
                        @imagecreatefrompng(
                            $sourcePath
                        );
                }

                break;


            case 'image/webp':

                if (function_exists('imagecreatefromwebp')) {

                    $sourceImage =
                        @imagecreatefromwebp(
                            $sourcePath
                        );
                }

                break;


            case 'image/gif':

                if (function_exists('imagecreatefromgif')) {

                    $sourceImage =
                        @imagecreatefromgif(
                            $sourcePath
                        );
                }

                break;
        }


        if ($sourceImage === false) {

            return false;
        }


        /*
		 * --------------------------------------------------
		 * CHECK WEBP SUPPORT
		 * --------------------------------------------------
		 */

        if (!function_exists('imagewebp')) {

            imagedestroy(
                $sourceImage
            );

            return false;
        }


        /*
		 * --------------------------------------------------
		 * ORIGINAL DIMENSIONS
		 * --------------------------------------------------
		 */

        $width =
            imagesx(
                $sourceImage
            );

        $height =
            imagesy(
                $sourceImage
            );


        if (
            $width <= 0 ||
            $height <= 0
        ) {

            imagedestroy(
                $sourceImage
            );

            return false;
        }


        /*
		 * --------------------------------------------------
		 * MAX WEB DIMENSION
		 * --------------------------------------------------
		 *
		 * No agrandamos imágenes pequeñas.
		 *
		 */

        $maxWidth = 2400;
        $maxHeight = 1600;


        $newWidth = $width;
        $newHeight = $height;


        if (
            $width > $maxWidth ||
            $height > $maxHeight
        ) {

            $ratio = min(
                $maxWidth / $width,
                $maxHeight / $height
            );

            $newWidth =
                (int) round(
                    $width * $ratio
                );

            $newHeight =
                (int) round(
                    $height * $ratio
                );
        }


        /*
		 * --------------------------------------------------
		 * DESTINATION
		 * --------------------------------------------------
		 */

        $destination =
            imagecreatetruecolor(
                $newWidth,
                $newHeight
            );


        if ($destination === false) {

            imagedestroy(
                $sourceImage
            );

            return false;
        }


        /*
		 * --------------------------------------------------
		 * TRANSPARENCY
		 * --------------------------------------------------
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
            $newWidth,
            $newHeight,
            $transparent
        );


        /*
		 * --------------------------------------------------
		 * RESIZE
		 * --------------------------------------------------
		 */

        $result =
            imagecopyresampled(
                $destination,
                $sourceImage,
                0,
                0,
                0,
                0,
                $newWidth,
                $newHeight,
                $width,
                $height
            );


        if ($result === false) {

            imagedestroy(
                $sourceImage
            );

            imagedestroy(
                $destination
            );

            return false;
        }


        /*
		 * --------------------------------------------------
		 * SAVE WEBP
		 * --------------------------------------------------
		 *
		 * Calidad 82:
		 * buen equilibrio entre peso y calidad visual.
		 *
		 */

        $saved =
            imagewebp(
                $destination,
                $filePath,
                82
            );


        /*
		 * --------------------------------------------------
		 * CLEAN MEMORY
		 * --------------------------------------------------
		 */

        imagedestroy(
            $sourceImage
        );

        imagedestroy(
            $destination
        );


        if (
            !$saved ||
            !file_exists($filePath)
        ) {

            return false;
        }


        /*
		 * --------------------------------------------------
		 * RETURN WEB PATH
		 * --------------------------------------------------
		 */

        return
            '/images/brands-section/' .
            $fileName;
    }


    /**
     * Elimina una imagen anterior.
     *
     * @param string $image
     */
    protected function deleteOldImage($image)
    {
        if (
            empty($image)
        ) {

            return;
        }


        /*
		 * Solo eliminamos archivos pertenecientes
		 * al directorio de Brands Section.
		 */
        $prefix =
            '/images/brands-section/';


        if (
            strpos(
                $image,
                $prefix
            ) !== 0
        ) {

            return;
        }


        $filePath =
            Yii::getPathOfAlias('webroot') .
            $image;


        if (
            is_file($filePath)
        ) {

            @unlink(
                $filePath
            );
        }
    }
}
