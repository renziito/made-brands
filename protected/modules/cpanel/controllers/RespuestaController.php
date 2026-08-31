<?php

class RespuestaController extends Controller
{
    public function actionIndex()
    {
        $forms = Yii::app()->db->createCommand()
            ->select("
				f.id,
				f.title,
				f.description,
				f.is_active,
				COUNT(s.id) AS responses_count
			")
            ->from('faq_forms f')
            ->leftJoin('faq_form_submissions s', 's.form_id = f.id')
            ->group('f.id, f.title, f.description, f.is_active')
            ->order('responses_count DESC, f.id ASC')
            ->queryAll();

        $this->render('index', array(
            'forms' => $forms,
        ));
    }

    public function actionResponses($form_id)
    {
        $form_id = (int)$form_id;

        if ($form_id <= 0) {
            $this->sendJson(array(
                'success' => false,
                'message' => 'Formulario no válido.',
            ));
        }

        $form = FaqForms::model()->findByPk($form_id);

        if ($form === null) {
            $this->sendJson(array(
                'success' => false,
                'message' => 'El formulario no existe.',
            ));
        }

        $fields = FaqFormFields::model()->findAllByAttributes(
            array(
                'form_id' => $form_id,
                'is_active' => 1,
            ),
            array(
                'order' => 't.sort_order ASC, t.id ASC',
            )
        );

        $submissions = FaqFormSubmissions::model()->findAllByAttributes(
            array(
                'form_id' => $form_id,
            ),
            array(
                'order' => 't.created_at DESC, t.id DESC',
                'with' => array(
                    'faqFormSubmissionValues',
                ),
            )
        );

        $responseData = array();

        foreach ($submissions as $submission) {
            $values = array();

            foreach ($submission->faqFormSubmissionValues as $submissionValue) {
                $values[(string)$submissionValue->field_id] = $submissionValue->value;
            }

            $responseData[] = array(
                'id' => (string)$submission->id,
                'estado' => $submission->estado ? $submission->estado : 'registrado',
                'created_at' => $submission->created_at,
                'updated_at' => $submission->updated_at,
                'ip_address' => $submission->ip_address,
                'user_agent' => $submission->user_agent,
                'values' => $values,
            );
        }

        $fieldData = array();

        foreach ($fields as $field) {
            $fieldData[] = array(
                'id' => (string)$field->id,
                'name' => $field->name,
                'label' => $field->label,
                'type' => $field->type,
                'sort_order' => (int)$field->sort_order,
                'options' => $field->options,
            );
        }

        $this->sendJson(array(
            'success' => true,
            'form' => array(
                'id' => (string)$form->id,
                'title' => $form->title,
                'description' => $form->description,
            ),
            'fields' => $fieldData,
            'submissions' => $responseData,
            'total' => count($responseData),
        ));
    }

    public function actionUpdateStatus()
    {
        if (!Yii::app()->request->isPostRequest) {
            $this->sendJson(array(
                'success' => false,
                'message' => 'Método no permitido.',
            ));
        }

        $id = (int)Yii::app()->request->getPost('id');
        $estado = trim(Yii::app()->request->getPost('estado'));

        if ($id <= 0) {
            $this->sendJson(array(
                'success' => false,
                'message' => 'Respuesta no válida.',
            ));
        }

        $estadosPermitidos = array(
            'registrado',
            'atendido',
        );

        if (!in_array($estado, $estadosPermitidos, true)) {
            $this->sendJson(array(
                'success' => false,
                'message' => 'Estado no válido.',
            ));
        }

        $model = FaqFormSubmissions::model()->findByPk($id);

        if ($model === null) {
            $this->sendJson(array(
                'success' => false,
                'message' => 'La respuesta no existe.',
            ));
        }

        $model->estado = $estado;
        $model->updated_at = date('Y-m-d H:i:s');

        if ($model->save(false, array('estado', 'updated_at'))) {
            $this->sendJson(array(
                'success' => true,
                'estado' => $model->estado,
                'message' => 'Estado actualizado correctamente.',
            ));
        }

        $this->sendJson(array(
            'success' => false,
            'message' => 'No se pudo actualizar el estado.',
        ));
    }

    private function sendJson($data)
    {
        header('Content-Type: application/json; charset=UTF-8');

        echo CJSON::encode($data);

        Yii::app()->end();
    }
}
