<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm */

$this->pageTitle = Yii::app()->name . ' - Login';
$this->breadcrumbs = array(
	'Login',
);
?>

<div class="login-page">
	<div class="container">
		<div class="login-page__wrapper">

			<div class="login-card">

				<div class="login-card__header">
					<h1 class="login-card__title">
						Bienvenido
					</h1>

					<p class="login-card__description">
						Inicia sesión para acceder al panel de administración.
					</p>
				</div>

				<div class="login-card__body">

					<?php $form = $this->beginWidget('CActiveForm', array(
						'id' => 'login-form',
						'enableClientValidation' => true,
						'clientOptions' => array(
							'validateOnSubmit' => true,
						),
						'htmlOptions' => array(
							'class' => 'login-form',
						),
					)); ?>

					<div class="login-form__field">
						<?php echo $form->labelEx($model, 'username', array(
							'class' => 'login-form__label',
						)); ?>

						<div class="login-form__input-wrapper">
							<span class="login-form__input-icon">
								<i class="fa fa-user"></i>
							</span>

							<?php echo $form->textField($model, 'username', array(
								'class' => 'login-form__input',
								'placeholder' => 'Ingresa tu usuario',
								'autocomplete' => 'username',
							)); ?>
						</div>

						<?php echo $form->error($model, 'username', array(
							'class' => 'login-form__error',
						)); ?>
					</div>

					<div class="login-form__field">
						<?php echo $form->labelEx($model, 'password', array(
							'class' => 'login-form__label',
						)); ?>

						<div class="login-form__input-wrapper">
							<span class="login-form__input-icon">
								<i class="fa fa-lock"></i>
							</span>

							<?php echo $form->passwordField($model, 'password', array(
								'class' => 'login-form__input',
								'placeholder' => 'Ingresa tu contraseña',
								'autocomplete' => 'current-password',
							)); ?>

							<button
								type="button"
								class="login-form__password-toggle"
								aria-label="Mostrar contraseña"
								tabindex="-1">
								<i class="fa fa-eye"></i>
							</button>
						</div>

						<?php echo $form->error($model, 'password', array(
							'class' => 'login-form__error',
						)); ?>
					</div>

					<div class="login-form__options">
						<label class="login-form__remember">
							<?php echo $form->checkBox($model, 'rememberMe', array(
								'class' => 'login-form__checkbox',
							)); ?>

							<span class="login-form__checkmark"></span>

							<span>
								Recordarme
							</span>
						</label>
					</div>

					<div class="login-form__actions">
						<?php echo CHtml::submitButton('Iniciar sesión', array(
							'class' => 'login-form__submit',
						)); ?>
					</div>

					<?php $this->endWidget(); ?>

				</div>

				<div class="login-card__footer">
					<span class="login-card__footer-icon">
						<i class="fa fa-shield"></i>
					</span>

					<span>
						Acceso seguro al panel de administración
					</span>
				</div>

			</div>

		</div>
	</div>
</div>

<script>
	$(document).on('click', '.login-form__password-toggle', function() {
		var $button = $(this);
		var $input = $('#LoginForm_password');
		var $icon = $button.find('i');

		if (!$input.length) {
			return;
		}

		if ($input.attr('type') === 'password') {
			$input.attr('type', 'text');
			$icon.removeClass('fa-eye').addClass('fa-eye-slash');
			$button.attr('aria-label', 'Ocultar contraseña');
		} else {
			$input.attr('type', 'password');
			$icon.removeClass('fa-eye-slash').addClass('fa-eye');
			$button.attr('aria-label', 'Mostrar contraseña');
		}
	});
</script>