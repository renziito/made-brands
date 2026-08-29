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

        $post = Yii::app()->request->getPost();

        $translationsPost =
            isset($post['IntroSectionTranslations'])
            ? $post['IntroSectionTranslations']
            : array();

        $languages = Languages::model()->findAll(
            array(
                'condition' => 'is_active = 1',
                'order' => 'sort_order ASC, id ASC',
            )
        );

        /*
		 * Validar antes de modificar la información actual.
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

        $transaction =
            Yii::app()->db->beginTransaction();

        try {

            $now = date('Y-m-d H:i:s');

            /*
			 * Obtener la versión actualmente activa.
			 */
            $currentIntro =
                $this->getActiveIntro();

            /*
			 * Crear una NUEVA versión.
			 *
			 * No hacemos UPDATE sobre la actual.
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

                throw new Exception(
                    'No fue posible crear la nueva versión del Intro.'
                );
            }

            /*
			 * Crear las traducciones de la nueva versión.
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

                $translation->created_at = $now;
                $translation->updated_at = $now;

                if (!$translation->save()) {

                    throw new Exception(
                        'No fue posible guardar la traducción del idioma ID ' .
                            $languageId .
                            '.'
                    );
                }
            }

            /*
			 * Soft delete de la versión anterior.
			 *
			 * Se hace DESPUÉS de crear correctamente
			 * la nueva versión.
			 */
            if ($currentIntro !== null) {

                $currentIntro->is_active = 0;
                $currentIntro->updated_at = $now;

                if (!$currentIntro->save()) {

                    throw new Exception(
                        'No fue posible desactivar la versión anterior del Intro.'
                    );
                }
            }

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
