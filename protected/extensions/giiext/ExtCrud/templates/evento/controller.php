<?php
/**
 * This is the template for generating a controller class file for CRUD feature.
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>
class <?php echo $this->controllerClass; ?> extends <?php echo $this->baseControllerClass; ?>{
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'index' page.
	 */
	public function actionCreate(){
		$model = new <?php echo $this->modelClass; ?>;
		$post = Yii::app()->request->getPost('<?php echo $this->modelClass; ?>', false);
		if($post){
			$model->attributes = $post;
<?php
foreach($this->tableSchema->columns as $column){
	$dbType = strtolower($column->dbType);
	if(
		$dbType === 'date' ||
		$dbType === 'datetime' ||
		$dbType === 'timestamp'
	){
?>
			$model-><?php echo $column->name; ?> = <?php
			if($dbType === 'date'){
				echo "date('Y-m-d')";
			}else{
				echo "date('Y-m-d H:i:s')";
			}
			?>;
<?php
	}
}
?>
			if($model->save()){
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
	public function actionUpdate($id){
		$model = $this->loadModel($id);
		$post = Yii::app()->request->getPost('<?php echo $this->modelClass; ?>', false);
		if($post){
			$model->attributes = $post;
<?php
foreach($this->tableSchema->columns as $column){
	$dbType = strtolower($column->dbType);
	$isDateTime = (
		$dbType === 'date' ||
		$dbType === 'datetime' ||
		$dbType === 'timestamp'
	);
	$isUpdateField = preg_match('/update/i', $column->name);
	if($isDateTime && $isUpdateField){
?>
			$model-><?php echo $column->name; ?> = <?php
			if($dbType === 'date'){
				echo "date('Y-m-d')";
			}else{
				echo "date('Y-m-d H:i:s')";
			}
			?>;
<?php
	}
}
?>
			if($model->save()){
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
	public function actionDelete($id){
		$model = $this->loadModel($id);
<?php
$tinyintColumns = array();
foreach($this->tableSchema->columns as $column){
	$dbType = strtolower($column->dbType);
	if(strpos($dbType, 'tinyint') === 0){
		$tinyintColumns[] = $column->name;
	}
}
foreach($tinyintColumns as $columnName){
?>
		$model-><?php echo $columnName; ?> = 0;
<?php
}
?>
		if($model->save()){
			$this->redirect(array('index'));
		}
	}
	/**
	 * Manages all models.
	 */
	public function actionIndex(){
		$model = new <?php echo $this->modelClass; ?>('search');
		$model->unsetAttributes();
		$attributes = Yii::app()->request->getQuery('<?php echo $this->modelClass; ?>', false);
		if($attributes){
			$model->attributes = $attributes;
		}
		$this->render('index', array('model' => $model));
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return <?php echo $this->modelClass; ?> the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id){
		$model = <?php echo $this->modelClass; ?>::model()->findByPk($id);
		if($model === null){
			throw new CHttpException(404, 'La página solicitada no existe.');
		}
		return $model;
	}
}