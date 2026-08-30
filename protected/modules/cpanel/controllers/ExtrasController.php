<?php
class ExtrasController extends Controller
{
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'index' page.
	 */
	public function actionCreate()
	{
		$model = new MenuItems;
		$post = Yii::app()->request->getPost('MenuItems', false);
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
		$post = Yii::app()->request->getPost('MenuItems', false);
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
		$model->is_menu = 0;
		$model->is_button = 0;
		$model->active = 0;
		if ($model->save()) {
			$this->redirect(array('index'));
		}
	}
	/**
	 * Manages all models.
	 */
	public function actionIndex()
	{
		$model = new MenuItems('search');
		$model->unsetAttributes();
		$attributes = Yii::app()->request->getQuery('MenuItems', false);
		if ($attributes) {
			$model->attributes = $attributes;
		}
		$this->render('index', array('model' => $model));
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return MenuItems the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model = MenuItems::model()->findByPk($id);
		if ($model === null) {
			throw new CHttpException(404, 'La página solicitada no existe.');
		}
		return $model;
	}

	public function actionUpdateTranslation()
	{
		if (!Yii::app()->request->isPostRequest) {
			throw new CHttpException(400, 'Solicitud inválida.');
		}

		$menuItemId = (int) Yii::app()->request->getPost('menu_item_id');
		$languageId = (int) Yii::app()->request->getPost('language_id');
		$label = trim(Yii::app()->request->getPost('label', ''));

		if ($menuItemId <= 0 || $languageId <= 0) {
			throw new CHttpException(400, 'Los datos de la traducción son inválidos.');
		}

		$menuItem = MenuItems::model()->findByPk($menuItemId);

		if ($menuItem === null) {
			throw new CHttpException(404, 'El menu item no existe.');
		}

		$language = Languages::model()->findByPk($languageId);

		if ($language === null) {
			throw new CHttpException(404, 'El idioma no existe.');
		}

		$translation = MenuItemTranslations::model()->findByAttributes(array(
			'menu_item_id' => $menuItemId,
			'language_id' => $languageId,
		));

		if ($translation === null) {
			$translation = new MenuItemTranslations;
			$translation->menu_item_id = $menuItemId;
			$translation->language_id = $languageId;
		}

		$translation->label = $label;

		if (!$translation->save()) {
			$errors = array();

			foreach ($translation->getErrors() as $attributeErrors) {
				$errors = array_merge($errors, $attributeErrors);
			}

			throw new CHttpException(
				500,
				'No se pudo guardar la traducción: ' . implode(' ', $errors)
			);
		}

		Yii::app()->user->setFlash('success', 'La traducción se actualizó correctamente.');

		$this->redirect(array('index'));
	}
}
