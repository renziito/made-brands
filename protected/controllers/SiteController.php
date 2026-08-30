<?php

class SiteController extends Controller
{
	public function actionIndex()
	{
		$languageCode = Yii::app()->session->get('language', 'es');
		$language = Languages::model()->find('code = :code', array(':code' => $languageCode));
		$languageId = $language ? $language->id : null;

		$heroSlides = WebUtils::getHero($languageId);
		$introContent = WebUtils::getIntro($languageId);
		$businesses = WebUtils::getBusinesses($languageId);

		$this->render('index', array(
			'heroSlides' => $heroSlides,
			'introContent' => $introContent,
			'businesses' => $businesses,
		));
	}


	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if ($error = Yii::app()->errorHandler->error) {
			if (Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model = new LoginForm;
		if (isset($_POST['LoginForm'])) {
			$model->attributes = $_POST['LoginForm'];
			if ($model->validate() && $model->login())
				$this->redirect(Yii::app()->createAbsoluteUrl('cpanel'));
		}
		$this->render('login', array('model' => $model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}
