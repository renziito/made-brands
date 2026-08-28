<?php
class LanguagesController extends Controller
{
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'index' page.
	 */
	public function actionCreate()
	{
		$model = new Languages;
		$post = Yii::app()->request->getPost('Languages', false);
		if ($post) {
			$model->attributes = $post;
			$model->created_at = date('Y-m-d H:i:s');
			$model->updated_at = date('Y-m-d H:i:s');
			if ($model->save()) {
				$this->redirect(array('index'));
			}
		}
		$this->render('create', array('model' => $model));
	}
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);
		$post = Yii::app()->request->getPost('Languages', false);
		if ($post) {
			$model->attributes = $post;
			$model->updated_at = date('Y-m-d H:i:s');
			if ($model->save()) {
				$this->redirect(array('index'));
			}
		}
		$this->render('update', array('model' => $model));
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
		$model->is_default = 0;
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
		$model = new Languages('search');
		$model->unsetAttributes();
		$attributes = Yii::app()->request->getQuery('Languages', false);
		if ($attributes) {
			$model->attributes = $attributes;
		}
		$this->render('index', array('model' => $model));
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Languages the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model = Languages::model()->findByPk($id);
		if ($model === null) {
			throw new CHttpException(404, 'La página solicitada no existe.');
		}
		return $model;
	}
}
