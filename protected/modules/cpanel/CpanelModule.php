<?php

class CpanelModule extends CWebModule
{
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'cpanel.models.*',
			'cpanel.components.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		if (parent::beforeControllerAction($controller, $action)) {
			if (Yii::app()->user->isGuest) {
				$controller->redirect(Yii::app()->baseUrl . '/login');
				return false;
			}
			Yii::app()->theme   = 'admin';
			$controller->layout = '//layouts/main';
			return true;
		} else
			return false;
	}
}
