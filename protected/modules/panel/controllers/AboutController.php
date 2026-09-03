<?php

class AboutController extends Controller
{
    /**
     * Displays the current About configuration.
     *
     * The index page is also the create form when there is no active
     * About section.
     *
     * There is intentionally no delete action.
     */
    public function actionIndex()
    {
        $model = $this->getCurrentAbout();

        if ($model === null) {

            $model = new AboutSections();

            $model->is_active = 1;
            $model->sort_order = 0;
        }

        $languages = Languages::model()->findAll(array(
            'order' => 'id ASC',
        ));

        $translations = array();
        $stats = array();
        $statTranslations = array();

        if (!$model->isNewRecord) {

            $translationRows = AboutSectionTranslations::model()->findAll(
                'about_section_id = :about_section_id',
                array(
                    ':about_section_id' => $model->id,
                )
            );

            foreach ($translationRows as $translation) {

                $translations[$translation->language_id] = $translation;
            }

            $stats = AboutSectionStats::model()->findAll(array(
                'condition' => 'about_section_id = :about_section_id',
                'params' => array(
                    ':about_section_id' => $model->id,
                ),
                'order' => 'sort_order ASC, id ASC',
            ));

            if (!empty($stats)) {

                $statIds = array();

                foreach ($stats as $stat) {
                    $statIds[] = (int) $stat->id;
                }

                $statTranslationRows = AboutSectionStatTranslations::model()->findAll(
                    array(
                        'condition' => 'stat_id IN (' . implode(',', $statIds) . ')',
                    )
                );

                foreach ($statTranslationRows as $translation) {

                    $statTranslations[$translation->stat_id][$translation->language_id] = $translation;
                }
            }
        }

        $this->render('index', array(
            'model' => $model,
            'languages' => $languages,
            'translations' => $translations,
            'stats' => $stats,
            'statTranslations' => $statTranslations,
        ));
    }


    /**
     * Creates a new About version.
     *
     * The current active version is deactivated first.
     * A completely new version is then created with its translations,
     * statistics and statistic translations.
     *
     * Images are converted to optimized WebP files.
     *
     * There is intentionally no delete action.
     */
    public function actionUpdate()
    {
        $current = $this->getCurrentAbout();

        $post = $_POST;

        if (empty($post)) {

            $this->redirect(array('index'));

            return;
        }


        /*
		 * ---------------------------------------------------------
		 * Image upload
		 * ---------------------------------------------------------
		 */

        $imageFile = CUploadedFile::getInstanceByName(
            'AboutSections[image]'
        );


        /*
		 * If no new image is uploaded, preserve the image
		 * from the current version.
		 */

        $imagePath = null;

        if ($current !== null && !empty($current->image)) {
            $imagePath = $current->image;
        }


        $transaction = Yii::app()->db->beginTransaction();


        try {

            /*
			 * ---------------------------------------------------------
			 * 1. Deactivate current About version
			 * ---------------------------------------------------------
			 */

            if ($current !== null) {

                $current->is_active = 0;
                $current->updated_at = date('Y-m-d H:i:s');

                if (!$current->save(false, array(
                    'is_active',
                    'updated_at',
                ))) {

                    throw new Exception(
                        'No se pudo desactivar la versión actual del About.'
                    );
                }
            }


            /*
			 * ---------------------------------------------------------
			 * 2. Process image
			 * ---------------------------------------------------------
			 */

            if ($imageFile !== null) {

                $imagePath = $this->saveOptimizedWebp($imageFile);
            }


            /*
			 * ---------------------------------------------------------
			 * 3. Create new About version
			 * ---------------------------------------------------------
			 */

            $model = new AboutSections();

            $model->image = $imagePath;

            $model->sort_order = isset($post['AboutSections']['sort_order'])
                ? (int) $post['AboutSections']['sort_order']
                : 0;

            $model->is_active = 1;

            $model->created_at = date('Y-m-d H:i:s');
            $model->updated_at = date('Y-m-d H:i:s');


            if (!$model->save()) {

                throw new Exception(
                    'No se pudo crear la nueva versión del About.'
                );
            }


            /*
			 * ---------------------------------------------------------
			 * 4. Create About translations
			 * ---------------------------------------------------------
			 */

            $translationPost = isset($post['translations'])
                ? $post['translations']
                : array();


            foreach ($translationPost as $languageId => $translationData) {

                $languageId = (int) $languageId;


                if (
                    $languageId <= 0 ||
                    !is_array($translationData)
                ) {
                    continue;
                }


                $translation = new AboutSectionTranslations();

                $translation->about_section_id = $model->id;
                $translation->language_id = $languageId;


                $translation->eyebrow = isset($translationData['eyebrow'])
                    ? trim($translationData['eyebrow'])
                    : null;


                $translation->eyebrow_size = isset($translationData['eyebrow_size'])
                    ? trim($translationData['eyebrow_size'])
                    : null;


                $translation->title = isset($translationData['title'])
                    ? trim($translationData['title'])
                    : null;


                $translation->title_size = isset($translationData['title_size'])
                    ? trim($translationData['title_size'])
                    : null;


                $translation->text = isset($translationData['text'])
                    ? trim($translationData['text'])
                    : null;


                $translation->text_size = isset($translationData['text_size'])
                    ? trim($translationData['text_size'])
                    : null;


                $translation->secondary_text = isset($translationData['secondary_text'])
                    ? trim($translationData['secondary_text'])
                    : null;


                $translation->secondary_text_size = isset($translationData['secondary_text_size'])
                    ? trim($translationData['secondary_text_size'])
                    : null;


                $translation->created_at = date('Y-m-d H:i:s');
                $translation->updated_at = date('Y-m-d H:i:s');


                if (!$translation->save()) {

                    throw new Exception(
                        'No se pudo guardar la traducción del About para el idioma ' .
                            $languageId .
                            '.'
                    );
                }
            }


            /*
			 * ---------------------------------------------------------
			 * 5. Create statistics
			 * ---------------------------------------------------------
			 */

            $statsPost = isset($post['stats'])
                ? $post['stats']
                : array();

            $statIndex = 0;


            foreach ($statsPost as $statData) {

                if (!is_array($statData)) {
                    continue;
                }


                /*
				 * Ignore completely empty statistic rows.
				 */

                $hasContent = false;


                if (
                    isset($statData['translations']) &&
                    is_array($statData['translations'])
                ) {

                    foreach ($statData['translations'] as $translationData) {

                        if (!is_array($translationData)) {
                            continue;
                        }


                        $value = isset($translationData['value'])
                            ? trim($translationData['value'])
                            : '';


                        $label = isset($translationData['label'])
                            ? trim($translationData['label'])
                            : '';


                        if ($value !== '' || $label !== '') {

                            $hasContent = true;

                            break;
                        }
                    }
                }


                if (!$hasContent) {
                    continue;
                }


                /*
				 * -----------------------------------------------------
				 * Create statistic
				 * -----------------------------------------------------
				 */

                $stat = new AboutSectionStats();

                $stat->about_section_id = $model->id;


                $stat->sort_order = isset($statData['sort_order'])
                    ? (int) $statData['sort_order']
                    : $statIndex;


                $stat->is_active = isset($statData['is_active'])
                    ? (int) $statData['is_active']
                    : 1;


                $stat->created_at = date('Y-m-d H:i:s');
                $stat->updated_at = date('Y-m-d H:i:s');


                if (!$stat->save()) {

                    throw new Exception(
                        'No se pudo guardar la estadística del About.'
                    );
                }


                /*
				 * -----------------------------------------------------
				 * Create statistic translations
				 * -----------------------------------------------------
				 */

                $statTranslationPost = isset($statData['translations'])
                    ? $statData['translations']
                    : array();


                foreach ($statTranslationPost as $languageId => $translationData) {

                    $languageId = (int) $languageId;


                    if (
                        $languageId <= 0 ||
                        !is_array($translationData)
                    ) {
                        continue;
                    }


                    $value = isset($translationData['value'])
                        ? trim($translationData['value'])
                        : '';


                    $label = isset($translationData['label'])
                        ? trim($translationData['label'])
                        : '';


                    if ($value === '' && $label === '') {
                        continue;
                    }


                    $statTranslation = new AboutSectionStatTranslations();

                    $statTranslation->stat_id = $stat->id;
                    $statTranslation->language_id = $languageId;


                    $statTranslation->value = $value;


                    $statTranslation->value_size = isset($translationData['value_size'])
                        ? trim($translationData['value_size'])
                        : null;


                    $statTranslation->label = $label;


                    $statTranslation->label_size = isset($translationData['label_size'])
                        ? trim($translationData['label_size'])
                        : null;


                    $statTranslation->created_at = date('Y-m-d H:i:s');
                    $statTranslation->updated_at = date('Y-m-d H:i:s');


                    if (!$statTranslation->save()) {

                        throw new Exception(
                            'No se pudo guardar la traducción de la estadística para el idioma ' .
                                $languageId .
                                '.'
                        );
                    }
                }


                $statIndex++;
            }


            /*
			 * ---------------------------------------------------------
			 * 6. Commit transaction
			 * ---------------------------------------------------------
			 */

            $transaction->commit();


            Yii::app()->user->setFlash(
                'success',
                'La sección Nosotros se actualizó correctamente.'
            );


            $this->redirect(array('index'));
        } catch (Exception $e) {

            $transaction->rollback();


            Yii::log(
                $e->getMessage(),
                CLogger::LEVEL_ERROR,
                'about'
            );


            Yii::app()->user->setFlash(
                'error',
                $e->getMessage()
            );


            $this->redirect(array('index'));
        }
    }


    /**
     * Saves an uploaded image as optimized WebP.
     *
     * Supported input formats:
     * JPG / JPEG
     * PNG
     * WEBP
     *
     * The original file is never stored.
     *
     * @param CUploadedFile $imageFile
     * @return string Relative image URL
     * @throws Exception
     */
    protected function saveOptimizedWebp($imageFile)
    {
        /*
		 * ---------------------------------------------------------
		 * Validate upload
		 * ---------------------------------------------------------
		 */

        if (!$imageFile instanceof CUploadedFile) {

            throw new Exception(
                'El archivo de imagen no es válido.'
            );
        }


        if ($imageFile->getError() !== UPLOAD_ERR_OK) {

            throw new Exception(
                'Se produjo un error al subir la imagen.'
            );
        }


        /*
		 * ---------------------------------------------------------
		 * Validate extension
		 * ---------------------------------------------------------
		 */

        $allowedExtensions = array(
            'jpg',
            'jpeg',
            'png',
            'webp',
        );


        $extension = strtolower(
            $imageFile->getExtensionName()
        );


        if (!in_array($extension, $allowedExtensions, true)) {

            throw new Exception(
                'El formato de imagen no es válido. Solo se permiten JPG, PNG o WEBP.'
            );
        }


        /*
		 * ---------------------------------------------------------
		 * Validate MIME type
		 * ---------------------------------------------------------
		 */

        $allowedMimeTypes = array(
            'image/jpeg',
            'image/png',
            'image/webp',
        );


        $finfo = null;

        if (class_exists('finfo')) {

            $finfo = new finfo(FILEINFO_MIME_TYPE);

            $mimeType = $finfo->file(
                $imageFile->getTempName()
            );
        } else {

            $mimeType = $imageFile->getType();
        }


        if (!in_array($mimeType, $allowedMimeTypes, true)) {

            throw new Exception(
                'El archivo seleccionado no es una imagen válida.'
            );
        }


        /*
		 * ---------------------------------------------------------
		 * Check GD
		 * ---------------------------------------------------------
		 */

        if (!extension_loaded('gd')) {

            throw new Exception(
                'La extensión GD de PHP es necesaria para optimizar las imágenes.'
            );
        }


        if (!function_exists('imagewebp')) {

            throw new Exception(
                'El servidor no tiene soporte para convertir imágenes a WebP.'
            );
        }


        /*
		 * ---------------------------------------------------------
		 * Create source image
		 * ---------------------------------------------------------
		 */

        switch ($mimeType) {

            case 'image/jpeg':

                $source = @imagecreatefromjpeg(
                    $imageFile->getTempName()
                );

                break;


            case 'image/png':

                $source = @imagecreatefrompng(
                    $imageFile->getTempName()
                );

                break;


            case 'image/webp':

                $source = @imagecreatefromwebp(
                    $imageFile->getTempName()
                );

                break;


            default:

                $source = false;

                break;
        }


        if (!$source) {

            throw new Exception(
                'No se pudo procesar la imagen seleccionada.'
            );
        }


        /*
		 * ---------------------------------------------------------
		 * Read dimensions
		 * ---------------------------------------------------------
		 */

        $width = imagesx($source);
        $height = imagesy($source);


        if ($width <= 0 || $height <= 0) {

            imagedestroy($source);

            throw new Exception(
                'Las dimensiones de la imagen no son válidas.'
            );
        }


        /*
		 * ---------------------------------------------------------
		 * Optional maximum dimensions
		 *
		 * Large images are resized before being converted to WebP.
		 *
		 * Maximum width: 2400px
		 * Maximum height: 1600px
		 * ---------------------------------------------------------
		 */

        $maxWidth = 2400;
        $maxHeight = 1600;


        $newWidth = $width;
        $newHeight = $height;


        if ($width > $maxWidth || $height > $maxHeight) {

            $ratio = min(
                $maxWidth / $width,
                $maxHeight / $height
            );

            $newWidth = (int) round($width * $ratio);
            $newHeight = (int) round($height * $ratio);
        }


        /*
		 * ---------------------------------------------------------
		 * Create destination canvas
		 * ---------------------------------------------------------
		 */

        $destination = imagecreatetruecolor(
            $newWidth,
            $newHeight
        );


        if (!$destination) {

            imagedestroy($source);

            throw new Exception(
                'No se pudo crear la imagen optimizada.'
            );
        }


        /*
		 * ---------------------------------------------------------
		 * Preserve transparency
		 * ---------------------------------------------------------
		 */

        imagealphablending(
            $destination,
            false
        );

        imagesavealpha(
            $destination,
            true
        );

        $transparent = imagecolorallocatealpha(
            $destination,
            0,
            0,
            0,
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
		 * ---------------------------------------------------------
		 * Resize image
		 * ---------------------------------------------------------
		 */

        $success = imagecopyresampled(
            $destination,
            $source,
            0,
            0,
            0,
            0,
            $newWidth,
            $newHeight,
            $width,
            $height
        );


        if (!$success) {

            imagedestroy($source);
            imagedestroy($destination);

            throw new Exception(
                'No se pudo redimensionar la imagen.'
            );
        }


        /*
		 * ---------------------------------------------------------
		 * Create destination directory
		 * ---------------------------------------------------------
		 */

        $basePath = Yii::getPathOfAlias('webroot') . '/images/about';


        if (!is_dir($basePath)) {

            if (!mkdir($basePath, 0775, true)) {

                imagedestroy($source);
                imagedestroy($destination);

                throw new Exception(
                    'No se pudo crear el directorio de imágenes del About.'
                );
            }
        }


        if (!is_writable($basePath)) {

            imagedestroy($source);
            imagedestroy($destination);

            throw new Exception(
                'El directorio de imágenes del About no tiene permisos de escritura.'
            );
        }


        /*
		 * ---------------------------------------------------------
		 * Generate unique WebP filename
		 * ---------------------------------------------------------
		 */

        $fileName =
            'about_' .
            date('YmdHis') .
            '_' .
            uniqid() .
            '.webp';


        $absolutePath = $basePath . '/' . $fileName;


        /*
		 * ---------------------------------------------------------
		 * Save optimized WebP
		 *
		 * Quality 82 provides a good balance between visual quality
		 * and file size for web usage.
		 * ---------------------------------------------------------
		 */

        $saved = imagewebp(
            $destination,
            $absolutePath,
            82
        );


        /*
		 * Free memory.
		 */

        imagedestroy($source);
        imagedestroy($destination);


        if (!$saved || !file_exists($absolutePath)) {

            throw new Exception(
                'No se pudo guardar la imagen optimizada en formato WebP.'
            );
        }


        /*
		 * ---------------------------------------------------------
		 * Return public relative URL
		 * ---------------------------------------------------------
		 */

        return '/images/about/' . $fileName;
    }


    /**
     * Returns the currently active About version.
     *
     * @return AboutSections|null
     */
    protected function getCurrentAbout()
    {
        return AboutSections::model()->find(
            'is_active = 1'
        );
    }


    /**
     * Returns the About translation for a language.
     *
     * @param integer $aboutSectionId
     * @param integer $languageId
     * @return AboutSectionTranslations|null
     */
    protected function getTranslation($aboutSectionId, $languageId)
    {
        return AboutSectionTranslations::model()->find(
            'about_section_id = :about_section_id AND language_id = :language_id',
            array(
                ':about_section_id' => (int) $aboutSectionId,
                ':language_id' => (int) $languageId,
            )
        );
    }


    /**
     * Returns the statistic translation for a language.
     *
     * @param integer $statId
     * @param integer $languageId
     * @return AboutSectionStatTranslations|null
     */
    protected function getStatTranslation($statId, $languageId)
    {
        return AboutSectionStatTranslations::model()->find(
            'stat_id = :stat_id AND language_id = :language_id',
            array(
                ':stat_id' => (int) $statId,
                ':language_id' => (int) $languageId,
            )
        );
    }
}
