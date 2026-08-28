<?php

/**
 * Handles standardized AJAX responses.
 *
 * This class keeps response formatting and AJAX
 * rendering logic outside controllers.
 *
 * Yii 1.x compatible.
 */
class AjaxResponseHelper
{
	/**
	 * Renders an AJAX form view and ends the request.
	 *
	 * The view is rendered as HTML and returned directly
	 * to the AJAX caller.
	 *
	 * Example:
	 *
	 * $ajax->form(
	 *     $this,
	 *     'ajax/subcategory',
	 *     array(
	 *         'model' => $subcategory,
	 *     )
	 * );
	 *
	 * @param Controller $controller
	 * @param string $view
	 * @param array $data
	 * @return void
	 */
	public function form(
		Controller $controller,
		$view,
		$data = array()
	) {
		$html =
			$controller->renderPartial(
				$view,
				$data,
				true
			);


		header(
			'Content-Type: text/html; charset=UTF-8'
		);


		echo $html;


		Yii::app()->end();
	}


	/**
	 * Renders a CategoriesController AJAX form.
	 *
	 * This is a convenience method for the current
	 * Categories module structure where AJAX forms live
	 * under views/categories/ajax/.
	 *
	 * Example:
	 *
	 * $ajax->categoryForm(
	 *     $this,
	 *     'subcategory',
	 *     array(
	 *         'model' => $subcategory,
	 *     )
	 * );
	 *
	 * @param Controller $controller
	 * @param string $view
	 * @param array $data
	 * @return void
	 */
	public function categoryForm(
		Controller $controller,
		$view,
		$data = array()
	) {
		$this->form(
			$controller,
			'ajax/' . $view,
			$data
		);
	}


	/**
	 * Sends a successful JSON AJAX response.
	 *
	 * Example:
	 *
	 * $ajax->success(array(
	 *     'message' => 'Guardado correctamente.',
	 *     'refresh' => true,
	 * ));
	 *
	 * The response always contains:
	 *
	 *     success: true
	 *
	 * Additional data can be supplied through $data.
	 *
	 * @param array $data
	 * @return void
	 */
	public function success(
		$data = array()
	) {
		header(
			'Content-Type: application/json; charset=UTF-8'
		);


		echo CJSON::encode(
			array_merge(
				array(
					'success' => true,
				),
				$data
			)
		);


		Yii::app()->end();
	}


	/**
	 * Sends a failed JSON AJAX response containing
	 * rendered HTML.
	 *
	 * This is useful when server-side validation fails
	 * and the complete modal form must be returned with
	 * its validation errors.
	 *
	 * Example:
	 *
	 * $html = $controller->renderPartial(
	 *     'ajax/subcategory',
	 *     $data,
	 *     true
	 * );
	 *
	 * $ajax->formValidation(
	 *     $html
	 * );
	 *
	 * @param string $html
	 * @return void
	 */
	public function formValidation(
		$html
	) {
		header(
			'Content-Type: application/json; charset=UTF-8'
		);


		echo CJSON::encode(
			array(
				'success' => false,
				'html' => $html,
			)
		);


		Yii::app()->end();
	}


	/**
	 * Sends a failed JSON AJAX response.
	 *
	 * This is useful when there is no rendered form
	 * available and only an error message needs to
	 * be returned.
	 *
	 * @param string $message
	 * @param array $data
	 * @return void
	 */
	public function error(
		$message,
		$data = array()
	) {
		header(
			'Content-Type: application/json; charset=UTF-8'
		);


		$response =
			array(
				'success' => false,
				'message' => $message,
			);


		if ($data) {

			$response =
				array_merge(
					$response,
					$data
				);
		}


		echo CJSON::encode(
			$response
		);


		Yii::app()->end();
	}


	/**
	 * Sends a generic JSON AJAX response.
	 *
	 * This method is useful when the controller needs
	 * complete control over the response payload.
	 *
	 * @param array $data
	 * @return void
	 */
	public function json(
		$data = array()
	) {
		header(
			'Content-Type: application/json; charset=UTF-8'
		);


		echo CJSON::encode(
			$data
		);


		Yii::app()->end();
	}
}