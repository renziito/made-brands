<?php

class IntroController extends Controller
{
    public function actionIndex()
    {
        $model = $this->getActiveIntro();

        $languages = Languages::model()->findAll(
            array(
                'condition' => 'is_active = 1',
                'order' => 'sort_order ASC, id ASC',
            )
        );

        $translations = array();

        foreach ($languages as $language) {

            $translation = null;

            if ($model !== null) {

                $translation =
                    IntroSectionTranslations::model()->findByAttributes(
                        array(
                            'intro_section_id' => $model->id,
                            'language_id' => $language->id,
                        )
                    );
            }

            $translations[$language->id] =
                $translation !== null
                ? $translation
                : new IntroSectionTranslations;
        }

        $this->render(
            'index',
            array(
                'model' => $model,
                'languages' => $languages,
                'translations' => $translations,
            )
        );
    }


    public function actionUpdate()
    {
        if (!Yii::app()->request->isPostRequest) {

            $this->redirect(
                array('index')
            );

            return;
        }


        /*
		 * =========================================================
		 * OBTENER TRADUCCIONES DEL FORMULARIO
		 * =========================================================
		 *
		 * El index.php envía:
		 *
		 * translations[LANGUAGE_ID][eyebrow]
		 * translations[LANGUAGE_ID][title]
		 * translations[LANGUAGE_ID][text]
		 * translations[LANGUAGE_ID][eyebrow_size]
		 * translations[LANGUAGE_ID][title_size]
		 * translations[LANGUAGE_ID][text_size]
		 *
		 * Por eso debemos leer "translations".
		 */

        $translationsPost =
            Yii::app()->request->getPost(
                'translations',
                array()
            );


        $languages = Languages::model()->findAll(
            array(
                'condition' => 'is_active = 1',
                'order' => 'sort_order ASC, id ASC',
            )
        );


        /*
		 * =========================================================
		 * VALIDAR QUE EXISTA CONTENIDO
		 * =========================================================
		 */

        $hasContent = false;

        foreach ($languages as $language) {

            $languageId = (int) $language->id;

            if (!isset($translationsPost[$languageId])) {
                continue;
            }

            $data = $translationsPost[$languageId];

            $eyebrow = isset($data['eyebrow'])
                ? trim($data['eyebrow'])
                : '';

            $title = isset($data['title'])
                ? trim($data['title'])
                : '';

            $text = isset($data['text'])
                ? trim($data['text'])
                : '';

            if (
                $eyebrow !== ''
                || $title !== ''
                || $text !== ''
            ) {

                $hasContent = true;

                break;
            }
        }


        if (!$hasContent) {

            Yii::app()->user->setFlash(
                'error',
                'Debes ingresar información para al menos un idioma.'
            );

            $this->redirect(
                array('index')
            );

            return;
        }


        /*
		 * =========================================================
		 * TRANSACCIÓN
		 * =========================================================
		 */

        $transaction =
            Yii::app()->db->beginTransaction();


        try {

            $now = date('Y-m-d H:i:s');


            /*
			 * =====================================================
			 * OBTENER LA VERSIÓN ACTUALMENTE ACTIVA
			 * =====================================================
			 */

            $currentIntro =
                $this->getActiveIntro();


            /*
			 * =====================================================
			 * CREAR NUEVA VERSIÓN
			 * =====================================================
			 *
			 * No modificamos directamente la versión actual.
			 */

            $newIntro = new IntroSections;

            $newIntro->type = 'intro';

            $newIntro->sort_order =
                $currentIntro !== null
                ? $currentIntro->sort_order
                : 1;

            $newIntro->is_active = 1;

            $newIntro->created_at = $now;

            $newIntro->updated_at = $now;


            if (!$newIntro->save()) {

                $errors = $newIntro->getErrors();

                $errorMessage =
                    'No fue posible crear la nueva versión del Intro.';

                if (!empty($errors)) {

                    $errorParts = array();

                    foreach ($errors as $attributeErrors) {

                        foreach ($attributeErrors as $attributeError) {

                            $errorParts[] = $attributeError;
                        }
                    }

                    if (!empty($errorParts)) {

                        $errorMessage .=
                            ' ' .
                            implode(
                                ' ',
                                $errorParts
                            );
                    }
                }

                throw new Exception(
                    $errorMessage
                );
            }


            /*
			 * =====================================================
			 * CREAR LAS TRADUCCIONES
			 * =====================================================
			 */

            foreach ($languages as $language) {

                $languageId = (int) $language->id;

                $data =
                    isset($translationsPost[$languageId])
                    ? $translationsPost[$languageId]
                    : array();


                $translation =
                    new IntroSectionTranslations;


                $translation->intro_section_id =
                    $newIntro->id;


                $translation->language_id =
                    $languageId;


                $translation->eyebrow =
                    isset($data['eyebrow'])
                    ? trim($data['eyebrow'])
                    : null;


                $translation->eyebrow_size =
                    isset($data['eyebrow_size'])
                    ? trim($data['eyebrow_size'])
                    : null;


                $translation->title =
                    isset($data['title'])
                    ? trim($data['title'])
                    : null;


                $translation->title_size =
                    isset($data['title_size'])
                    ? trim($data['title_size'])
                    : null;


                $translation->text =
                    isset($data['text'])
                    ? trim($data['text'])
                    : null;


                $translation->text_size =
                    isset($data['text_size'])
                    ? trim($data['text_size'])
                    : null;


                $translation->created_at =
                    $now;


                $translation->updated_at =
                    $now;


                if (!$translation->save()) {

                    $errors =
                        $translation->getErrors();

                    $errorMessage =
                        'No fue posible guardar la traducción del idioma ID ' .
                        $languageId .
                        '.';


                    if (!empty($errors)) {

                        $errorParts = array();

                        foreach (
                            $errors
                            as $attributeErrors
                        ) {

                            foreach (
                                $attributeErrors
                                as $attributeError
                            ) {

                                $errorParts[] =
                                    $attributeError;
                            }
                        }

                        if (!empty($errorParts)) {

                            $errorMessage .=
                                ' ' .
                                implode(
                                    ' ',
                                    $errorParts
                                );
                        }
                    }


                    throw new Exception(
                        $errorMessage
                    );
                }
            }


            /*
			 * =====================================================
			 * DESACTIVAR VERSIÓN ANTERIOR
			 * =====================================================
			 *
			 * Solo se hace después de crear correctamente
			 * la nueva versión y todas sus traducciones.
			 */

            if ($currentIntro !== null) {

                $currentIntro->is_active = 0;

                $currentIntro->updated_at = $now;


                if (!$currentIntro->save()) {

                    $errors =
                        $currentIntro->getErrors();

                    $errorMessage =
                        'No fue posible desactivar la versión anterior del Intro.';


                    if (!empty($errors)) {

                        $errorParts = array();

                        foreach (
                            $errors
                            as $attributeErrors
                        ) {

                            foreach (
                                $attributeErrors
                                as $attributeError
                            ) {

                                $errorParts[] =
                                    $attributeError;
                            }
                        }

                        if (!empty($errorParts)) {

                            $errorMessage .=
                                ' ' .
                                implode(
                                    ' ',
                                    $errorParts
                                );
                        }
                    }


                    throw new Exception(
                        $errorMessage
                    );
                }
            }


            /*
			 * =====================================================
			 * COMMIT
			 * =====================================================
			 */

            $transaction->commit();


            Yii::app()->user->setFlash(
                'success',
                'El Intro se actualizó correctamente.'
            );
        } catch (Exception $e) {

            $transaction->rollback();


            Yii::app()->user->setFlash(
                'error',
                'No fue posible actualizar el Intro: ' .
                    $e->getMessage()
            );
        }


        $this->redirect(
            array('index')
        );
    }


    protected function getActiveIntro()
    {
        return IntroSections::model()->find(
            array(
                'condition' => 'is_active = 1 AND type = :type',
                'params' => array(
                    ':type' => 'intro',
                ),
                'order' => 'id DESC',
            )
        );
    }
}
