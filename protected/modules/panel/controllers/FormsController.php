<?php

class FormsController extends Controller
{
    /**
     * Creates a new form.
     */
    public function actionCreate()
    {
        $model = new FaqForms();

        $post = Yii::app()->request->getPost('FaqForms', false);

        if ($post) {
            $model->attributes = $post;
            $model->created_at = date('Y-m-d H:i:s');
            $model->updated_at = date('Y-m-d H:i:s');

            if ($model->save()) {
                $this->redirect(array(
                    'update',
                    'id' => $model->id,
                ));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular form.
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        $post = Yii::app()->request->getPost('FaqForms', false);

        if ($post) {
            $model->attributes = $post;
            $model->updated_at = date('Y-m-d H:i:s');

            if ($model->save()) {
                $this->redirect(array(
                    'update',
                    'id' => $model->id,
                ));
            }
        }

        $fields = FaqFormFields::model()->findAll(array(
            'condition' => 'form_id = :form_id',
            'params' => array(
                ':form_id' => $model->id,
            ),
            'order' => 'sort_order ASC, id ASC',
        ));

        $this->render('update', array(
            'model' => $model,
            'fields' => $fields,
        ));
    }

    /**
     * Soft deletes a form.
     */
    public function actionDelete($id)
    {
        $model = $this->loadModel($id);

        $model->is_active = 0;
        $model->updated_at = date('Y-m-d H:i:s');

        if ($model->save()) {
            $this->redirect(array('index'));
        }
    }

    /**
     * Creates a new field for a form.
     */
    public function actionCreateField($form_id)
    {
        $form = $this->loadModel($form_id);

        $model = new FaqFormFields();
        $model->form_id = $form->id;
        $model->is_active = 1;

        $post = Yii::app()->request->getPost('FaqFormFields', false);

        if ($post) {
            $model->attributes = $post;
            $model->form_id = $form->id;
            $model->created_at = date('Y-m-d H:i:s');
            $model->updated_at = date('Y-m-d H:i:s');

            if ($model->save()) {
                $this->redirect(array(
                    'update',
                    'id' => $form->id,
                ));
            }
        }

        $this->render('field/create', array(
            'model' => $model,
            'formModel' => $form,
        ));
    }

    /**
     * Updates a particular field.
     */
    public function actionUpdateField($id)
    {
        $model = $this->loadFieldModel($id);

        $form = $this->loadModel($model->form_id);

        $post = Yii::app()->request->getPost('FaqFormFields', false);

        if ($post) {
            $model->attributes = $post;
            $model->form_id = $form->id;
            $model->updated_at = date('Y-m-d H:i:s');

            if ($model->save()) {
                $this->redirect(array(
                    'update',
                    'id' => $form->id,
                ));
            }
        }

        $this->render('field/update', array(
            'model' => $model,
            'formModel' => $form,
        ));
    }

    /**
     * Soft deletes a field.
     */
    public function actionDeleteField($id)
    {
        $model = $this->loadFieldModel($id);

        $formId = $model->form_id;

        $model->is_active = 0;
        $model->updated_at = date('Y-m-d H:i:s');

        if ($model->save()) {
            $this->redirect(array(
                'update',
                'id' => $formId,
            ));
        }
    }

    /**
     * Manages all forms.
     */
    public function actionIndex()
    {
        $model = new FaqForms('search');
        $model->unsetAttributes();

        $attributes = Yii::app()->request->getQuery('FaqForms', false);

        if ($attributes) {
            $model->attributes = $attributes;
        }

        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Loads a form.
     */
    public function loadModel($id)
    {
        $model = FaqForms::model()->findByPk($id);

        if ($model === null) {
            throw new CHttpException(
                404,
                'La página solicitada no existe.'
            );
        }

        return $model;
    }

    /**
     * Loads a field.
     */
    public function loadFieldModel($id)
    {
        $model = FaqFormFields::model()->findByPk($id);

        if ($model === null) {
            throw new CHttpException(
                404,
                'El campo solicitado no existe.'
            );
        }

        return $model;
    }
}
