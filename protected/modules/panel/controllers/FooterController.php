<?php

class FooterController extends Controller
{
    /**
     * Página principal del footer.
     *
     * Muestra:
     * - El único Contact CTA
     * - Todos los Contact Items
     * - Sus traducciones por idioma
     */
    public function actionIndex()
    {
        $cta = ContactCta::model()->find();

        // Si todavía no existe el CTA, crear el registro base.
        if ($cta === null) {
            $cta = new ContactCta();
            $cta->icon = null;
            $cta->url = null;
            $cta->is_active = 1;
            $cta->created_at = date('Y-m-d H:i:s');
            $cta->updated_at = date('Y-m-d H:i:s');

            if (!$cta->save()) {
                throw new CHttpException(
                    500,
                    'No se pudo crear el registro del CTA.'
                );
            }
        }

        $items = ContactItems::model()->findAll(array(
            'order' => 'sort_order ASC, id ASC',
        ));

        $languages = Languages::model()->findAll(array(
            'order' => 'id ASC',
        ));

        $this->render('index', array(
            'cta' => $cta,
            'items' => $items,
            'languages' => $languages,
            'fontAwesomeIcons' => $this->getFont(),
        ));
    }

    public function getFont()
    {
        $fontAwesomeIcons = array();

        $iconsFile = Yii::getPathOfAlias('webroot') . '/bin/fonts/font-awesome/metadata/icons.yml';

        if (file_exists($iconsFile)) {

            $yaml = file_get_contents($iconsFile);

            /*
     * Font Awesome metadata.
     *
     * Los estilos:
     * solid   -> fas
     * regular -> far
     * brands  -> fab
     */

            $currentIcon = null;
            $currentStyle = null;

            $lines = preg_split("/\r\n|\n|\r/", $yaml);

            foreach ($lines as $line) {

                if (preg_match('/^([a-zA-Z0-9-]+):\s*$/', $line, $matches)) {

                    $currentIcon = $matches[1];
                    $currentStyle = null;

                    continue;
                }

                if (preg_match('/^\s+styles:\s*$/', $line)) {
                    continue;
                }

                if (preg_match('/^\s+-\s+(solid|regular|brands)\s*$/', $line, $matches)) {

                    $style = $matches[1];

                    if ($style === 'solid') {
                        $prefix = 'fas';
                    } elseif ($style === 'regular') {
                        $prefix = 'far';
                    } elseif ($style === 'brands') {
                        $prefix = 'fab';
                    } else {
                        continue;
                    }

                    $fontAwesomeIcons[] = array(
                        'name' => $currentIcon,
                        'class' => $prefix . ' fa-' . $currentIcon,
                        'style' => $style,
                    );
                }
            }
        }
        return $fontAwesomeIcons;
    }


    /**
     * Guarda los datos generales del CTA.
     *
     * POST:
     * - icon
     * - url
     * - is_active
     */
    public function actionSaveCta()
    {
        if (!Yii::app()->request->isPostRequest) {
            throw new CHttpException(400, 'Solicitud inválida.');
        }

        $cta = ContactCta::model()->find();

        if ($cta === null) {
            $cta = new ContactCta();
            $cta->created_at = date('Y-m-d H:i:s');
        }

        $cta->icon = isset($_POST['icon']) ? trim($_POST['icon']) : null;
        $cta->url = isset($_POST['url']) ? trim($_POST['url']) : null;
        $cta->is_active = isset($_POST['is_active']) ? 1 : 0;
        $cta->updated_at = date('Y-m-d H:i:s');

        if ($cta->save()) {
            Yii::app()->user->setFlash(
                'success',
                'CTA actualizado correctamente.'
            );
        } else {
            Yii::app()->user->setFlash(
                'error',
                'No se pudo actualizar el CTA.'
            );
        }

        $this->redirect(array('index'));
    }

    /**
     * Guarda la traducción de un idioma específico del CTA.
     *
     * POST:
     * - contact_cta_id
     * - language_id
     * - title
     * - title_size
     * - text
     * - text_size
     * - button_text
     * - button_text_size
     */
    public function actionSaveCtaTranslation()
    {
        if (!Yii::app()->request->isPostRequest) {
            throw new CHttpException(400, 'Solicitud inválida.');
        }

        $ctaId = isset($_POST['contact_cta_id'])
            ? (int) $_POST['contact_cta_id']
            : 0;

        $languageId = isset($_POST['language_id'])
            ? (int) $_POST['language_id']
            : 0;

        if ($ctaId <= 0 || $languageId <= 0) {
            Yii::app()->user->setFlash(
                'error',
                'Datos de traducción inválidos.'
            );

            $this->redirect(array('index'));
        }

        $cta = ContactCta::model()->findByPk($ctaId);

        if ($cta === null) {
            throw new CHttpException(404, 'CTA no encontrado.');
        }

        $translation = ContactCtaTranslations::model()->findByAttributes(array(
            'contact_cta_id' => $ctaId,
            'language_id' => $languageId,
        ));

        if ($translation === null) {
            $translation = new ContactCtaTranslations();
            $translation->contact_cta_id = $ctaId;
            $translation->language_id = $languageId;
            $translation->created_at = date('Y-m-d H:i:s');
        }

        $translation->title = isset($_POST['title'])
            ? trim($_POST['title'])
            : '';

        $translation->title_size = isset($_POST['title_size'])
            ? trim($_POST['title_size'])
            : null;

        $translation->text = isset($_POST['text'])
            ? trim($_POST['text'])
            : null;

        $translation->text_size = isset($_POST['text_size'])
            ? trim($_POST['text_size'])
            : null;

        $translation->button_text = isset($_POST['button_text'])
            ? trim($_POST['button_text'])
            : null;

        $translation->button_text_size = isset($_POST['button_text_size'])
            ? trim($_POST['button_text_size'])
            : null;

        $translation->updated_at = date('Y-m-d H:i:s');

        if ($translation->save()) {
            Yii::app()->user->setFlash(
                'success',
                'Traducción del CTA guardada correctamente.'
            );
        } else {
            Yii::app()->user->setFlash(
                'error',
                'No se pudo guardar la traducción del CTA.'
            );
        }

        $this->redirect(array('index'));
    }

    /**
     * Crea un nuevo Contact Item.
     *
     * Los campos de traducción se guardan posteriormente
     * mediante actionSaveItemTranslation().
     */
    public function actionCreateItem()
    {
        if (!Yii::app()->request->isPostRequest) {
            throw new CHttpException(400, 'Solicitud inválida.');
        }

        $item = new ContactItems();

        $item->icon = isset($_POST['icon'])
            ? trim($_POST['icon'])
            : null;

        $item->sort_order = isset($_POST['sort_order'])
            ? (int) $_POST['sort_order']
            : 0;

        $item->is_active = isset($_POST['is_active'])
            ? 1
            : 0;

        $item->created_at = date('Y-m-d H:i:s');
        $item->updated_at = date('Y-m-d H:i:s');

        if ($item->save()) {
            Yii::app()->user->setFlash(
                'success',
                'Contact item creado correctamente.'
            );
        } else {
            Yii::app()->user->setFlash(
                'error',
                'No se pudo crear el contact item.'
            );
        }

        $this->redirect(array('index'));
    }

    /**
     * Actualiza los datos generales de un Contact Item.
     *
     * POST:
     * - id
     * - icon
     * - sort_order
     * - is_active
     */
    public function actionSaveItem()
    {
        if (!Yii::app()->request->isPostRequest) {
            throw new CHttpException(400, 'Solicitud inválida.');
        }

        $id = isset($_POST['id'])
            ? (int) $_POST['id']
            : 0;

        if ($id <= 0) {
            Yii::app()->user->setFlash(
                'error',
                'Contact item inválido.'
            );

            $this->redirect(array('index'));
        }

        $item = ContactItems::model()->findByPk($id);

        if ($item === null) {
            throw new CHttpException(404, 'Contact item no encontrado.');
        }

        $item->icon = isset($_POST['icon'])
            ? trim($_POST['icon'])
            : null;

        $item->sort_order = isset($_POST['sort_order'])
            ? (int) $_POST['sort_order']
            : 0;

        $item->is_active = isset($_POST['is_active'])
            ? 1
            : 0;

        $item->updated_at = date('Y-m-d H:i:s');

        if ($item->save()) {
            Yii::app()->user->setFlash(
                'success',
                'Contact item actualizado correctamente.'
            );
        } else {
            Yii::app()->user->setFlash(
                'error',
                'No se pudo actualizar el contact item.'
            );
        }

        $this->redirect(array('index'));
    }

    /**
     * Guarda la traducción de un Contact Item para un idioma.
     *
     * POST:
     * - contact_item_id
     * - language_id
     * - label
     * - label_size
     * - value
     * - value_size
     */
    public function actionSaveItemTranslation()
    {
        if (!Yii::app()->request->isPostRequest) {
            throw new CHttpException(400, 'Solicitud inválida.');
        }

        $itemId = isset($_POST['contact_item_id'])
            ? (int) $_POST['contact_item_id']
            : 0;

        $languageId = isset($_POST['language_id'])
            ? (int) $_POST['language_id']
            : 0;

        if ($itemId <= 0 || $languageId <= 0) {
            Yii::app()->user->setFlash(
                'error',
                'Datos de traducción inválidos.'
            );

            $this->redirect(array('index'));
        }

        $item = ContactItems::model()->findByPk($itemId);

        if ($item === null) {
            throw new CHttpException(404, 'Contact item no encontrado.');
        }

        $translation = ContactItemTranslations::model()->findByAttributes(array(
            'contact_item_id' => $itemId,
            'language_id' => $languageId,
        ));

        if ($translation === null) {
            $translation = new ContactItemTranslations();
            $translation->contact_item_id = $itemId;
            $translation->language_id = $languageId;
            $translation->created_at = date('Y-m-d H:i:s');
        }

        $translation->label = isset($_POST['label'])
            ? trim($_POST['label'])
            : '';

        $translation->label_size = isset($_POST['label_size'])
            ? trim($_POST['label_size'])
            : null;

        $translation->value = isset($_POST['value'])
            ? trim($_POST['value'])
            : '';

        $translation->value_size = isset($_POST['value_size'])
            ? trim($_POST['value_size'])
            : null;

        $translation->updated_at = date('Y-m-d H:i:s');

        if ($translation->save()) {
            Yii::app()->user->setFlash(
                'success',
                'Traducción del contact item guardada correctamente.'
            );
        } else {
            Yii::app()->user->setFlash(
                'error',
                'No se pudo guardar la traducción del contact item.'
            );
        }

        $this->redirect(array('index'));
    }

    /**
     * Elimina un Contact Item.
     *
     * Las traducciones se eliminan automáticamente por
     * ON DELETE CASCADE en la FK.
     */
    public function actionDeleteItem($id)
    {
        if (!Yii::app()->request->isPostRequest) {
            throw new CHttpException(400, 'Solicitud inválida.');
        }

        $id = (int) $id;

        if ($id <= 0) {
            Yii::app()->user->setFlash(
                'error',
                'Contact item inválido.'
            );

            $this->redirect(array('index'));
        }

        $item = ContactItems::model()->findByPk($id);

        if ($item === null) {
            throw new CHttpException(404, 'Contact item no encontrado.');
        }

        if ($item->delete()) {
            Yii::app()->user->setFlash(
                'success',
                'Contact item eliminado correctamente.'
            );
        } else {
            Yii::app()->user->setFlash(
                'error',
                'No se pudo eliminar el contact item.'
            );
        }

        $this->redirect(array('index'));
    }
}
