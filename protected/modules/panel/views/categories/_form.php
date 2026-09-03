<?php
/* @var $this CategoriesController */
/* @var $model Categories */
/* @var $form CActiveForm */

$translation = isset($translation) ? $translation : new CategoryTranslations;
$defaultLanguage = isset($defaultLanguage) ? $defaultLanguage : null;
$languages = isset($languages) ? $languages : array();
$translations = isset($translations) ? $translations : array();
$subcategories = isset($subcategories) ? $subcategories : array();
$subcategoryTranslations = isset($subcategoryTranslations) ? $subcategoryTranslations : array();

$translationsByLanguage = array();
foreach ($translations as $categoryTranslation) {
	$translationsByLanguage[(string) $categoryTranslation->language_id] = $categoryTranslation;
}

$subcategoryTranslationsBySubcategory = array();
foreach ($subcategoryTranslations as $subcategoryTranslation) {
	$subcategoryId = (string) $subcategoryTranslation->subcategory_id;
	if (!isset($subcategoryTranslationsBySubcategory[$subcategoryId])) $subcategoryTranslationsBySubcategory[$subcategoryId] = array();
	$subcategoryTranslationsBySubcategory[$subcategoryId][] = $subcategoryTranslation;
}

$subcategoryTranslationsByLanguage = array();
foreach ($subcategoryTranslations as $subcategoryTranslation) {
	$subcategoryId = (string) $subcategoryTranslation->subcategory_id;
	$languageId = (string) $subcategoryTranslation->language_id;
	if (!isset($subcategoryTranslationsByLanguage[$subcategoryId])) $subcategoryTranslationsByLanguage[$subcategoryId] = array();
	$subcategoryTranslationsByLanguage[$subcategoryId][$languageId] = $subcategoryTranslation;
}

Yii::app()->clientScript->registerCss('admin-form-categories', '
.admin-form-page{width:100%;max-width:1100px;margin:0 auto}.admin-form{margin-top:28px}.admin-form-card{overflow:hidden;background:#fff;border:1px solid #e5e7eb;border-radius:10px;box-shadow:0 1px 2px rgba(0,0,0,.03)}.admin-form-card+.admin-form-card{margin-top:20px}.admin-form-card__header{display:flex;align-items:center;justify-content:space-between;gap:24px;padding:18px 20px;border-bottom:1px solid #e5e7eb}.admin-form-card__heading{display:flex;align-items:center;gap:12px;min-width:0}.admin-form-card__icon{display:flex;align-items:center;justify-content:center;width:36px;height:36px;flex-shrink:0;border-radius:7px;background:#f3f4f6;color:#374151;font-size:14px}.admin-form-card__title{margin:0;color:#111827;font-size:15px;font-weight:600;line-height:1.3}.admin-form-card__description{margin:2px 0 0;color:#9ca3af;font-size:12px;line-height:1.4}.admin-form-status{display:flex;align-items:center;gap:20px;flex-shrink:0}.admin-form-status__item{display:flex;align-items:center;gap:10px}.admin-form-status__text{display:flex;flex-direction:column;gap:2px}.admin-form-status__label{color:#374151;font-size:12px;font-weight:600;line-height:1.3}.admin-form-status__description{color:#9ca3af;font-size:11px;line-height:1.3}.admin-form-switch{position:relative;display:inline-flex;align-items:center;width:42px;height:24px;flex-shrink:0}.admin-form-switch input{position:absolute;width:1px;height:1px;margin:0;opacity:0}.admin-form-switch__track{position:relative;display:block;width:42px;height:24px;border-radius:999px;background:#d1d5db;cursor:pointer;transition:background-color .15s ease,box-shadow .15s ease}.admin-form-switch__track::after{position:absolute;top:3px;left:3px;width:18px;height:18px;border-radius:50%;background:#fff;box-shadow:0 1px 2px rgba(0,0,0,.18);content:"";transition:transform .15s ease}.admin-form-switch input:checked+.admin-form-switch__track{background:#111827}.admin-form-switch input:checked+.admin-form-switch__track::after{transform:translateX(18px)}.admin-form-switch input:focus+.admin-form-switch__track{box-shadow:0 0 0 3px rgba(17,24,39,.08)}.admin-form-card__body{padding:24px 20px}.admin-form-required-note{display:flex;align-items:center;gap:6px;margin:0 0 22px;color:#6b7280;font-size:12px}.admin-form-required-note .required{color:#dc2626;font-weight:700}.admin-form-card .errorSummary{margin:0 0 22px;padding:14px 16px;border:1px solid #fecaca;border-radius:7px;background:#fef2f2;color:#991b1b;font-size:13px;line-height:1.5}.admin-form-card .errorSummary ul{margin:7px 0 0 18px;padding:0}.admin-form-card .errorSummary li{margin:3px 0}.admin-form-card .errorSummary a{color:#991b1b}.admin-form-fields{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:20px 18px}.admin-form-field{min-width:0}.admin-form-field--full{grid-column:1/-1}.admin-form-field label{display:block;margin:0 0 7px;color:#374151;font-size:12px;font-weight:600;line-height:1.4}.admin-form-field label .required{margin-left:2px;color:#dc2626;font-weight:700}.admin-form-field input[type=text],.admin-form-field input[type=password],.admin-form-field input[type=email],.admin-form-field input[type=number],.admin-form-field input[type=url],.admin-form-field input[type=tel],.admin-form-field input[type=date],.admin-form-field input[type=datetime],.admin-form-field input[type=datetime-local],.admin-form-field input[type=time],.admin-form-field input[type=search],.admin-form-field input[type=file],.admin-form-field select,.admin-form-field textarea{display:block;width:100%;box-sizing:border-box;padding:9px 11px;border:1px solid #d1d5db;border-radius:6px;outline:none;background:#fff;color:#374151;font-family:inherit;font-size:13px;line-height:1.5;transition:border-color .15s ease,box-shadow .15s ease,background-color .15s ease}.admin-form-field input[type=text],.admin-form-field input[type=password],.admin-form-field input[type=email],.admin-form-field input[type=number],.admin-form-field input[type=url],.admin-form-field input[type=tel],.admin-form-field input[type=date],.admin-form-field input[type=datetime],.admin-form-field input[type=datetime-local],.admin-form-field input[type=time],.admin-form-field input[type=search],.admin-form-field input[type=file],.admin-form-field select{min-height:40px}.admin-form-field input[type=file]{padding:7px 9px;cursor:pointer}.admin-form-field textarea{min-height:120px;resize:vertical}.admin-form-field input:focus,.admin-form-field select:focus,.admin-form-field textarea:focus{border-color:#9ca3af;box-shadow:0 0 0 3px rgba(17,24,39,.06)}.admin-form-field input:disabled,.admin-form-field select:disabled,.admin-form-field textarea:disabled{background:#f9fafb;color:#9ca3af;cursor:not-allowed}.admin-form-field .error{display:block;margin-top:6px;color:#dc2626;font-size:11px;line-height:1.4}.admin-form-field input.error,.admin-form-field select.error,.admin-form-field textarea.error{border-color:#fca5a5;background:#fffafa}.admin-form-field .hint{display:block;margin-top:6px;color:#9ca3af;font-size:11px;line-height:1.4}.admin-form-image-preview{display:flex;align-items:center;gap:14px;margin-top:12px}.admin-form-image-preview img{display:block;width:72px;height:72px;object-fit:cover;border:1px solid #e5e7eb;border-radius:7px;background:#f9fafb}.admin-form-image-preview__text{color:#6b7280;font-size:11px;line-height:1.4}.admin-form-language{display:flex;align-items:center;justify-content:space-between;gap:14px;min-height:40px;padding:0 11px;box-sizing:border-box;border:1px solid #e5e7eb;border-radius:6px;background:#f9fafb}.admin-form-language__name{display:flex;align-items:center;gap:8px;color:#374151;font-size:13px;font-weight:600}.admin-form-language__code{display:inline-flex;align-items:center;justify-content:center;min-width:30px;height:22px;padding:0 6px;box-sizing:border-box;border-radius:4px;background:#e5e7eb;color:#374151;font-size:10px;font-weight:700}.admin-form-language__badge{display:inline-flex;align-items:center;height:22px;padding:0 7px;border-radius:4px;background:#f3f4f6;color:#6b7280;font-size:10px;font-weight:600}.admin-form-field--switch{display:flex;align-items:center;justify-content:space-between;gap:16px;min-height:40px;padding:10px 12px;box-sizing:border-box;border:1px solid #e5e7eb;border-radius:7px;background:#f9fafb}.admin-form-field--switch .admin-form-field__label{margin:0}.admin-form-field__switch{display:inline-flex;flex-shrink:0}.admin-form-translations{display:flex;flex-direction:column}.admin-form-translation{display:flex;align-items:center;justify-content:space-between;gap:20px;padding:14px 0;border-bottom:1px solid #f0f1f3}.admin-form-translation:first-child{padding-top:0}.admin-form-translation:last-child{padding-bottom:0;border-bottom:0}.admin-form-translation__language{display:flex;align-items:center;gap:10px;min-width:0}.admin-form-translation__code{display:inline-flex;align-items:center;justify-content:center;width:36px;height:28px;flex-shrink:0;border-radius:5px;background:#f3f4f6;color:#374151;font-size:10px;font-weight:700}.admin-form-translation__name{min-width:0;color:#374151;font-size:13px;font-weight:600}.admin-form-translation__native{display:block;margin-top:2px;color:#9ca3af;font-size:11px;font-weight:400}.admin-form-translation__status{display:flex;align-items:center;gap:8px;flex-shrink:0}.admin-form-translation__status-text{color:#6b7280;font-size:11px;font-weight:600}.admin-form-translation__status-dot{width:7px;height:7px;border-radius:50%;background:#d1d5db}.admin-form-translation--translated .admin-form-translation__status-dot{background:#22c55e}.admin-form-translation--translated .admin-form-translation__status-text{color:#374151}.admin-form-translation--inactive{opacity:.65}.admin-form-subcategories{display:flex;flex-direction:column}.admin-form-subcategory{padding:16px 0;border-bottom:1px solid #f0f1f3}.admin-form-subcategory:first-child{padding-top:0}.admin-form-subcategory:last-child{padding-bottom:0;border-bottom:0}.admin-form-subcategory__top{display:grid;grid-template-columns:minmax(0,1fr) auto auto;align-items:center;gap:18px}.admin-form-subcategory__content{display:flex;align-items:flex-start;gap:10px;min-width:0}.admin-form-subcategory__icon{display:flex;align-items:center;justify-content:center;width:34px;height:34px;flex-shrink:0;border-radius:7px;background:#f3f4f6;color:#6b7280;font-size:12px}.admin-form-subcategory__name{min-width:0;overflow:hidden;color:#374151;font-size:13px;font-weight:600;text-overflow:ellipsis;white-space:nowrap}.admin-form-subcategory__meta{margin-top:3px;color:#9ca3af;font-size:11px}.admin-form-subcategory__actions{display:flex;align-items:center;justify-content:flex-end;gap:6px;flex-shrink:0}.admin-form-subcategory__languages{display:flex;align-items:center;gap:8px;min-width:190px;padding:7px 9px;box-sizing:border-box;border:1px solid #eef0f2;border-radius:7px;background:#fafafa}.admin-form-subcategory__languages-label{color:#6b7280;font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;white-space:nowrap}.admin-form-subcategory__language-list{display:flex;align-items:center;justify-content:flex-end;flex-wrap:wrap;gap:5px}.admin-form-subcategory__language{display:inline-flex;align-items:center;justify-content:center;gap:4px;height:25px;min-width:34px;padding:0 7px;box-sizing:border-box;border:1px solid #e5e7eb;border-radius:5px;background:#fff;color:#9ca3af;font-size:9px;font-weight:700;line-height:1;cursor:pointer}.admin-form-subcategory__language:hover{border-color:#cfd4da;background:#f9fafb;color:#374151}.admin-form-subcategory__language--translated{border-color:#d1d5db;background:#f3f4f6;color:#374151}.admin-form-subcategory__language--inactive{opacity:.5}.admin-form-subcategory__language-status{font-size:9px}.admin-form-subcategory__language--translated .admin-form-subcategory__language-status{color:#22c55e}.admin-form-subcategory__no-languages{color:#9ca3af;font-size:10px;white-space:nowrap}.admin-form-small-button--danger{border-color:#e5caca;background:#fff;color:#b42318!important}.admin-form-small-button--danger:hover{border-color:#d9aaaa;background:#fff7f7;color:#991b1b!important}.admin-form-small-button{display:inline-flex;align-items:center;justify-content:center;gap:6px;height:32px;padding:0 10px;box-sizing:border-box;border:1px solid #d1d5db;border-radius:6px;background:#fff;color:#4b5563!important;cursor:pointer;font-size:11px;font-weight:600;text-decoration:none!important}.admin-form-small-button:hover{background:#f9fafb;border-color:#9ca3af;color:#111827!important;text-decoration:none!important}.admin-form-small-button--primary{background:#111827;border-color:#111827;color:#fff!important}.admin-form-small-button--primary:hover{background:#1f2937;border-color:#1f2937;color:#fff!important}.admin-form-card__footer{display:flex;align-items:center;justify-content:space-between;gap:20px;padding:14px 20px;border-top:1px solid #e5e7eb;background:#f9fafb}.admin-form-footer__note{color:#6b7280;font-size:11px;line-height:1.4}.admin-form-footer__note .required{color:#dc2626;font-weight:700}.admin-form-actions{display:flex;width:100%;align-items:center;justify-content:flex-end;gap:8px}.admin-form-button{display:inline-flex;align-items:center;justify-content:center;gap:7px;height:36px;padding:0 12px;box-sizing:border-box;border:1px solid #d1d5db;border-radius:6px;background:#fff;color:#4b5563!important;cursor:pointer;font-size:12px;font-weight:600;line-height:1;text-decoration:none!important}.admin-form-button:hover{background:#f9fafb;border-color:#9ca3af;color:#111827!important;text-decoration:none!important}.admin-form-button--secondary{background:#fff;border-color:#d1d5db}.admin-form-button--primary{background:#111827;border-color:#111827;color:#fff!important}.admin-form-button--primary:hover{background:#1f2937;border-color:#1f2937;color:#fff!important}.admin-form-button:disabled{opacity:.65;cursor:not-allowed}.admin-form-empty{padding:24px 0;text-align:center}.admin-form-empty__icon{display:flex;align-items:center;justify-content:center;width:40px;height:40px;margin:0 auto 10px;border-radius:50%;background:#f3f4f6;color:#9ca3af;font-size:14px}.admin-form-empty__title{margin-bottom:4px;color:#4b5563;font-size:13px;font-weight:600}.admin-form-empty__text{color:#9ca3af;font-size:11px;line-height:1.5}.admin-form-add-button{display:inline-flex;align-items:center;justify-content:center;gap:7px;height:36px;padding:0 12px;box-sizing:border-box;border:1px solid #d1d5db;border-radius:6px;background:#fff;color:#374151!important;font-size:12px;font-weight:600;text-decoration:none!important;cursor:pointer}.admin-form-add-button:hover{background:#f9fafb;border-color:#9ca3af;color:#111827!important;text-decoration:none!important}.admin-form-modal{display:none;position:fixed;z-index:9999;top:0;left:0;width:100%;height:100%;padding:30px 20px;box-sizing:border-box;background:rgba(17,24,39,.55);overflow-y:auto}.admin-form-modal__dialog{width:100%;max-width:620px;margin:40px auto;overflow:hidden;border-radius:10px;background:#fff;box-shadow:0 20px 50px rgba(0,0,0,.18)}.admin-form-modal__header{display:flex;align-items:center;justify-content:space-between;gap:20px;padding:18px 20px;border-bottom:1px solid #e5e7eb}.admin-form-modal__title{margin:0;color:#111827;font-size:15px;font-weight:600}.admin-form-modal__close{display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border:0;border-radius:6px;background:transparent;color:#6b7280;cursor:pointer;font-size:14px}.admin-form-modal__close:hover{background:#f3f4f6;color:#111827}.admin-form-modal__body{padding:20px}.admin-form-modal__loading{padding:35px 20px;text-align:center;color:#9ca3af;font-size:13px}.admin-form-modal__error{margin:0 0 16px;padding:12px 14px;border:1px solid #fecaca;border-radius:6px;background:#fef2f2;color:#991b1b;font-size:12px}.admin-form-modal__footer{display:flex;align-items:center;justify-content:flex-end;gap:8px;padding:15px 20px;border-top:1px solid #e5e7eb;background:#f9fafb}.admin-form-modal__footer .admin-form-button{height:36px}.admin-modal-form-fields{display:grid;grid-template-columns:1fr 1fr;gap:18px}.admin-modal-form-field{min-width:0}.admin-modal-form-field--full{grid-column:1/-1}.admin-modal-form-field label{display:block;margin-bottom:7px;color:#374151;font-size:12px;font-weight:600}.admin-modal-form-field input,.admin-modal-form-field select,.admin-modal-form-field textarea{display:block;width:100%;box-sizing:border-box;padding:9px 11px;border:1px solid #d1d5db;border-radius:6px;outline:none;background:#fff;color:#374151;font-family:inherit;font-size:13px}.admin-modal-form-field input,.admin-modal-form-field select{height:40px}.admin-modal-form-field textarea{min-height:120px;resize:vertical}.admin-modal-form-field input:focus,.admin-modal-form-field select:focus,.admin-modal-form-field textarea:focus{border-color:#9ca3af;box-shadow:0 0 0 3px rgba(17,24,39,.06)}.admin-modal-form-field .error{display:block;margin-top:5px;color:#dc2626;font-size:11px}.admin-modal-form-field__hint{display:block;margin-top:5px;color:#9ca3af;font-size:11px}@media(max-width:768px){.admin-form-card__header{align-items:flex-start;flex-direction:column}.admin-form-status{width:100%;justify-content:space-between;padding-top:12px;border-top:1px solid #f0f1f3}.admin-form-fields{grid-template-columns:1fr}.admin-form-field--full{grid-column:auto}.admin-form-card__body{padding:20px 16px}.admin-form-card__footer{align-items:stretch;flex-direction:column;gap:12px}.admin-form-actions{width:100%}.admin-form-button{flex:1}.admin-form-translation{align-items:flex-start;flex-direction:column}.admin-form-translation__status{width:100%;justify-content:space-between}.admin-form-subcategory__top{grid-template-columns:1fr;align-items:stretch;gap:10px}.admin-form-subcategory__languages{min-width:0;width:100%}.admin-form-subcategory__language-list{justify-content:flex-start}.admin-form-subcategory__actions{width:100%;justify-content:flex-end}.admin-modal-form-fields{grid-template-columns:1fr}.admin-modal-form-field--full{grid-column:auto}}
');
?>

<div class="admin-form-page">
	<?php $form = $this->beginWidget('CActiveForm', array(
		'id' => 'categories-form',
		'enableAjaxValidation' => false,
		'htmlOptions' => array('class' => 'admin-form', 'enctype' => 'multipart/form-data'),
	)); ?>

	<div class="admin-form-card">
		<div class="admin-form-card__header">
			<div class="admin-form-card__heading">
				<div class="admin-form-card__icon"><?= $model->isNewRecord ? '<i class="fas fa-plus"></i>' : '<i class="fas fa-pen"></i>'; ?></div>
				<div>
					<h2 class="admin-form-card__title">Información de la categoría</h2>
					<p class="admin-form-card__description"><?= $model->isNewRecord ? 'Completa la información de la nueva categoría.' : 'Actualiza la información general de esta categoría.'; ?></p>
				</div>
			</div>

			<div class="admin-form-status">
				<div class="admin-form-status__item">
					<div class="admin-form-status__text">
						<span class="admin-form-status__label">Is Featured</span>
						<span class="admin-form-status__description"><?= $model->isNewRecord ? 'Activo por defecto' : ($model->is_featured ? 'Activo' : 'Inactivo'); ?></span>
					</div>
					<label class="admin-form-switch">
						<?= CHtml::activeCheckBox($model, 'is_featured', array('uncheckValue' => '0', 'class' => 'admin-form-status__input', 'checked' => $model->isNewRecord ? true : (bool) $model->is_featured)); ?>
						<span class="admin-form-switch__track"></span>
					</label>
				</div>

				<div class="admin-form-status__item">
					<div class="admin-form-status__text">
						<span class="admin-form-status__label">Is Active</span>
						<span class="admin-form-status__description"><?= $model->isNewRecord ? 'Activo por defecto' : ($model->is_active ? 'Activo' : 'Inactivo'); ?></span>
					</div>
					<label class="admin-form-switch">
						<?= CHtml::activeCheckBox($model, 'is_active', array('uncheckValue' => '0', 'class' => 'admin-form-status__input', 'checked' => $model->isNewRecord ? true : (bool) $model->is_active)); ?>
						<span class="admin-form-switch__track"></span>
					</label>
				</div>
			</div>
		</div>

		<div class="admin-form-card__body">
			<?= $form->errorSummary($model, '<strong>Por favor verifica la información:</strong>'); ?>

			<div class="admin-form-fields">
				<div class="admin-form-field">
					<?= $form->labelEx($model, 'image'); ?>
					<?= $form->fileField($model, 'image', array('accept' => 'image/jpeg,image/png,image/gif,image/webp')); ?>
					<?= $form->error($model, 'image'); ?>
					<span class="hint">Formatos permitidos: JPG, JPEG, PNG, GIF y WEBP. Tamaño máximo: 5 MB.</span>

					<?php if (!$model->isNewRecord && !empty($model->image)): ?>
						<div class="admin-form-image-preview">
							<img src="<?= CHtml::encode(Yii::app()->baseUrl . '/' . ltrim($model->image, '/')); ?>" alt="Imagen actual">
							<span class="admin-form-image-preview__text">Imagen actual. Selecciona una nueva para reemplazarla.</span>
						</div>
					<?php endif; ?>
				</div>

				<div class="admin-form-field">
					<?= $form->labelEx($model, 'sort_order'); ?>
					<?= $form->textField($model, 'sort_order', array('type' => 'number', 'min' => '0')); ?>
					<?= $form->error($model, 'sort_order'); ?>
					<span class="hint">Determina la posición de la categoría en los listados.</span>
				</div>
			</div>
		</div>

		<div class="admin-form-card__footer">
			<div class="admin-form-actions">
				<a href="<?php echo $this->createUrl('index'); ?>" class="admin-form-button admin-form-button--secondary"><i class="fas fa-arrow-left"></i> Volver</a>
				<button type="submit" class="admin-form-button admin-form-button--primary pull-right"><?= $model->isNewRecord ? '<i class="fas fa-plus"></i> Crear categoría' : '<i class="fas fa-save"></i> Guardar cambios'; ?></button>
			</div>
		</div>
	</div>

	<?php if ($model->isNewRecord): ?>

		<div class="admin-form-card">
			<div class="admin-form-card__header">
				<div class="admin-form-card__heading">
					<div class="admin-form-card__icon"><i class="fas fa-language"></i></div>
					<div>
						<h2 class="admin-form-card__title">Traducción inicial</h2>
						<p class="admin-form-card__description">La categoría debe tener una traducción en el idioma predeterminado.</p>
					</div>
				</div>
			</div>

			<div class="admin-form-card__body">
				<div class="admin-form-fields">
					<div class="admin-form-field admin-form-field--full">
						<label>Idioma <span class="required">*</span></label>
						<div class="admin-form-language">
							<div class="admin-form-language__name">
								<?php if ($defaultLanguage): ?>
									<span class="admin-form-language__code"><?= CHtml::encode(strtoupper($defaultLanguage->code)); ?></span>
									<span><?= CHtml::encode($defaultLanguage->native_name); ?></span>
								<?php else: ?>
									<span>Idioma predeterminado</span>
								<?php endif; ?>
							</div>
							<span class="admin-form-language__badge">Idioma predeterminado</span>
						</div>

						<?php if ($defaultLanguage): ?>
							<?= CHtml::hiddenField('CategoryTranslations[language_id]', $defaultLanguage->id); ?>
						<?php endif; ?>
					</div>

					<div class="admin-form-field">
						<?= $form->labelEx($translation, 'name'); ?>
						<?= $form->textField($translation, 'name', array('maxlength' => 255)); ?>
						<?= $form->error($translation, 'name'); ?>
					</div>

					<div class="admin-form-field">
						<?= $form->labelEx($translation, 'name_size'); ?>
						<?= $form->textField($translation, 'name_size', array('maxlength' => 20)); ?>
						<?= $form->error($translation, 'name_size'); ?>
						<span class="hint">Tamaño o clase utilizada para el nombre.</span>
					</div>

					<div class="admin-form-field admin-form-field--full">
						<?= $form->labelEx($translation, 'description'); ?>
						<?= $form->textArea($translation, 'description', array('rows' => 6)); ?>
						<?= $form->error($translation, 'description'); ?>
					</div>

					<div class="admin-form-field">
						<?= $form->labelEx($translation, 'description_size'); ?>
						<?= $form->textField($translation, 'description_size', array('maxlength' => 20)); ?>
						<?= $form->error($translation, 'description_size'); ?>
					</div>
				</div>
			</div>
		</div>

	<?php else: ?>

		<div class="admin-form-card" id="category-translations-card">
			<div class="admin-form-card__header">
				<div class="admin-form-card__heading">
					<div class="admin-form-card__icon"><i class="fas fa-language"></i></div>
					<div>
						<h2 class="admin-form-card__title">Traducciones</h2>
						<p class="admin-form-card__description">Administra las traducciones disponibles para esta categoría.</p>
					</div>
				</div>
			</div>

			<div class="admin-form-card__body">
				<?php if ($languages): ?>
					<div class="admin-form-translations" id="category-translations-list">
						<?php foreach ($languages as $language): ?>
							<?php
							$languageKey = (string) $language->id;
							$categoryTranslation = isset($translationsByLanguage[$languageKey]) ? $translationsByLanguage[$languageKey] : null;
							$isTranslated = $categoryTranslation !== null;
							$translationClasses = 'admin-form-translation' . ($isTranslated ? ' admin-form-translation--translated' : '') . (!(int) $language->is_active ? ' admin-form-translation--inactive' : '');
							?>

							<div class="<?= $translationClasses; ?>" data-language-id="<?= CHtml::encode($language->id); ?>">
								<div class="admin-form-translation__language">
									<span class="admin-form-translation__code"><?= CHtml::encode(strtoupper($language->code)); ?></span>
									<div>
										<div class="admin-form-translation__name"><?= CHtml::encode($language->name); ?></div>
										<span class="admin-form-translation__native"><?= CHtml::encode($language->native_name); ?></span>
									</div>
								</div>

								<div class="admin-form-translation__status">
									<span class="admin-form-translation__status-dot"></span>
									<span class="admin-form-translation__status-text"><?= $isTranslated ? 'Traducido' : 'Sin traducción'; ?></span>
									<button type="button" class="admin-form-small-button js-category-translation-modal" data-category-id="<?= CHtml::encode($model->id); ?>" data-language-id="<?= CHtml::encode($language->id); ?>">
										<i class="fas <?= $isTranslated ? 'fa-pen' : 'fa-plus'; ?>"></i> <?= $isTranslated ? 'Editar' : 'Agregar'; ?>
									</button>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php else: ?>
					<div class="admin-form-empty">
						<div class="admin-form-empty__icon"><i class="fas fa-language"></i></div>
						<div class="admin-form-empty__title">No hay idiomas disponibles</div>
						<div class="admin-form-empty__text">Configura los idiomas del sistema para poder agregar traducciones.</div>
					</div>
				<?php endif; ?>
			</div>
		</div>

		<div class="admin-form-card" id="category-subcategories-card">
			<div class="admin-form-card__header">
				<div class="admin-form-card__heading">
					<div class="admin-form-card__icon"><i class="fas fa-sitemap"></i></div>
					<div>
						<h2 class="admin-form-card__title">Subcategorías</h2>
						<p class="admin-form-card__description">Administra las subcategorías y sus traducciones.</p>
					</div>
				</div>

				<button type="button" class="admin-form-add-button js-subcategory-modal" data-category-id="<?= CHtml::encode($model->id); ?>">
					<i class="fas fa-plus"></i> Agregar subcategoría
				</button>
			</div>

			<div class="admin-form-card__body">
				<?php if ($subcategories): ?>
					<div class="admin-form-subcategories" id="category-subcategories-list">
						<?php foreach ($subcategories as $subcategory): ?>
							<?php
							$subcategoryId = (string) $subcategory->id;
							$currentSubcategoryTranslations = isset($subcategoryTranslationsByLanguage[$subcategoryId]) ? $subcategoryTranslationsByLanguage[$subcategoryId] : array();
							$subcategoryName = 'Subcategoría #' . $subcategory->id;

							if ($defaultLanguage) {
								$defaultLanguageId = (string) $defaultLanguage->id;

								if (isset($currentSubcategoryTranslations[$defaultLanguageId])) {
									$defaultTranslation = $currentSubcategoryTranslations[$defaultLanguageId];

									if (trim((string) $defaultTranslation->name) !== '') {
										$subcategoryName = $defaultTranslation->name;
									}
								}
							}
							?>

							<div class="admin-form-subcategory" data-subcategory-id="<?= CHtml::encode($subcategory->id); ?>">
								<div class="admin-form-subcategory__top">
									<div class="admin-form-subcategory__content">
										<div class="admin-form-subcategory__icon"><i class="fas fa-folder-open"></i></div>
										<div style="min-width:0;">
											<div class="admin-form-subcategory__name"><?= CHtml::encode($subcategoryName); ?></div>
											<div class="admin-form-subcategory__meta">ID #<?= CHtml::encode($subcategory->id); ?> &nbsp;·&nbsp; Orden <?= CHtml::encode($subcategory->sort_order); ?> &nbsp;·&nbsp; <?= (int) $subcategory->is_active ? 'Activa' : 'Inactiva'; ?></div>
										</div>
									</div>

									<div class="admin-form-subcategory__languages">
										<span class="admin-form-subcategory__languages-label">Idiomas</span>
										<div class="admin-form-subcategory__language-list">
											<?php if ($languages): ?>
												<?php foreach ($languages as $language): ?>
													<?php
													$languageId = (string) $language->id;
													$hasTranslation = isset($currentSubcategoryTranslations[$languageId]);
													$languageClasses = 'admin-form-subcategory__language' . ($hasTranslation ? ' admin-form-subcategory__language--translated' : '') . (!(int) $language->is_active ? ' admin-form-subcategory__language--inactive' : '');
													?>

													<button type="button" class="<?= $languageClasses; ?> js-subcategory-translation-modal" data-category-id="<?= CHtml::encode($model->id); ?>" data-subcategory-id="<?= CHtml::encode($subcategory->id); ?>" data-language-id="<?= CHtml::encode($language->id); ?>" title="<?= CHtml::encode($language->native_name); ?>">
														<span><?= CHtml::encode(strtoupper($language->code)); ?></span>
														<span class="admin-form-subcategory__language-status"><?= $hasTranslation ? '●' : '○'; ?></span>
													</button>
												<?php endforeach; ?>
											<?php else: ?>
												<span class="admin-form-subcategory__no-languages">No hay idiomas configurados.</span>
											<?php endif; ?>
										</div>
									</div>

									<div class="admin-form-subcategory__actions">
										<button type="button" class="admin-form-small-button admin-form-small-button--danger js-subcategory-remove" data-category-id="<?= CHtml::encode($model->id); ?>" data-subcategory-id="<?= CHtml::encode($subcategory->id); ?>" title="Remover subcategoría" aria-label="Remover subcategoría">
											<i class="fas fa-trash-alt"></i> Remover
										</button>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php else: ?>
					<div class="admin-form-empty">
						<div class="admin-form-empty__icon"><i class="fas fa-sitemap"></i></div>
						<div class="admin-form-empty__title">No hay subcategorías</div>
						<div class="admin-form-empty__text">Esta categoría todavía no tiene subcategorías asociadas.</div>
					</div>
				<?php endif; ?>
			</div>
		</div>

	<?php endif; ?>

	<?php $this->endWidget(); ?>
</div>

<div id="admin-form-modal" class="admin-form-modal" aria-hidden="true">
	<div class="admin-form-modal__dialog">
		<div class="admin-form-modal__header">
			<h3 id="admin-form-modal-title" class="admin-form-modal__title">Editar</h3>
			<button type="button" id="admin-form-modal-close" class="admin-form-modal__close" aria-label="Cerrar"><i class="fas fa-times"></i></button>
		</div>

		<div id="admin-form-modal-body" class="admin-form-modal__body">
			<div class="admin-form-modal__loading"><i class="fas fa-spinner fa-spin"></i> Cargando...</div>
		</div>
	</div>
</div>

<?php
$translationUrl = Yii::app()->getUrlManager()->createUrl('/panel/categories/translation');
$subcategoryUrl = Yii::app()->getUrlManager()->createUrl('/panel/categories/subcategory');
$subcategoryTranslationUrl = Yii::app()->getUrlManager()->createUrl('/panel/categories/subcategoryTranslation');

Yii::app()->clientScript->registerScript('categories-admin-ajax-modal', "
(function($) {
	'use strict';

	var modal = $('#admin-form-modal');
	var modalTitle = $('#admin-form-modal-title');
	var modalBody = $('#admin-form-modal-body');
	var modalClose = $('#admin-form-modal-close');

	$(document).on('click', '.js-modal-cancel', function(e) {
		e.preventDefault();
		e.stopPropagation();
		closeModal();
	});

	function openModal(title, url, data) {
		modalTitle.text(title);
		modalBody.html('<div class=\"admin-form-modal__loading\"><i class=\"fas fa-spinner fa-spin\"></i> Cargando...</div>');
		modal.css('display', 'block');
		modal.attr('aria-hidden', 'false');
		$('body').css('overflow', 'hidden');

		$.ajax({
			url: url,
			type: 'GET',
			data: data,
			dataType: 'html',
			success: function(response) {
				modalBody.html(response);
			},
			error: function(xhr) {
				console.log(xhr);
				modalBody.html('<div class=\"admin-form-modal__error\">No fue posible cargar el formulario.</div>');
			}
		});
	}

	function closeModal() {
		modal.css('display', 'none');
		modal.attr('aria-hidden', 'true');
		modalBody.empty();
		$('body').css('overflow', '');
	}

	modalClose.on('click', function() {
		closeModal();
	});

	modal.on('click', function(e) {
		if (e.target === this) closeModal();
	});

	$(document).on('keydown', function(e) {
		if (e.key === 'Escape' && modal.css('display') !== 'none') closeModal();
	});

	$(document).on('click', '.js-category-translation-modal', function(e) {
		e.preventDefault();
		var button = $(this);
		var categoryId = button.data('category-id');
		var languageId = button.data('language-id');

		openModal(
			languageId ? 'Editar traducción' : 'Agregar traducción',
			" . CJSON::encode($translationUrl) . ",
			{category_id: categoryId, language_id: languageId, ajax: 1}
		);
	});

	$(document).on('click', '.js-subcategory-modal', function(e) {
		e.preventDefault();
		var button = $(this);
		var categoryId = button.data('category-id');
		var subcategoryId = button.data('subcategory-id');

		openModal(
			subcategoryId ? 'Editar subcategoría' : 'Agregar subcategoría',
			" . CJSON::encode($subcategoryUrl) . ",
			{category_id: categoryId, id: subcategoryId, ajax: 1}
		);
	});

	$(document).on('click', '.js-subcategory-remove', function(e) {
		e.preventDefault();

		var button = $(this);
		var categoryId = button.data('category-id');
		var subcategoryId = button.data('subcategory-id');

		if (!categoryId || !subcategoryId) return;

		if (!window.confirm('¿Deseas remover esta subcategoría?')) return;

		button.prop('disabled', true);

		$.ajax({
			url: " . CJSON::encode(Yii::app()->getUrlManager()->createUrl('/panel/categories/removeSubcategory')) . ",
			type: 'POST',
			data: {category_id: categoryId, subcategory_id: subcategoryId, ajax: 1},
			dataType: 'json',
			success: function(response) {
				if (response && response.success) {
					var subcategory = $('.admin-form-subcategory[data-subcategory-id=\"' + subcategoryId + '\"]');

					subcategory.fadeOut(200, function() {
						$(this).remove();

						if ($('#category-subcategories-list .admin-form-subcategory').length === 0) {
							window.location.reload();
						}
					});

					return;
				}

				alert(response && response.message ? response.message : 'No fue posible remover la subcategoría.');
			},
			error: function() {
				alert('No fue posible remover la subcategoría.');
			},
			complete: function() {
				button.prop('disabled', false);
			}
		});
	});

	$(document).on('click', '.js-subcategory-translation-modal', function(e) {
		e.preventDefault();

		var button = $(this);

		openModal(
			'Traducción de subcategoría',
			" . CJSON::encode($subcategoryTranslationUrl) . ",
			{
				category_id: button.data('category-id'),
				subcategory_id: button.data('subcategory-id'),
				language_id: button.data('language-id'),
				ajax: 1
			}
		);
	});

	$(document).on('submit', '#admin-ajax-modal-form', function(e) {
		e.preventDefault();

		var form = $(this);
		var submitButton = form.find('[type=\"submit\"]');

		submitButton.prop('disabled', true);

		$.ajax({
			url: form.attr('action'),
			type: 'POST',
			data: form.serialize(),
			dataType: 'json',
			success: function(response) {
				if (response && response.success) {
					if (response.html) modalBody.html(response.html);
					if (response.refresh) window.location.reload();
					return;
				}

				if (response && response.html) {
					modalBody.html(response.html);
					return;
				}

				modalBody.prepend('<div class=\"admin-form-modal__error\">No fue posible guardar la información.</div>');
			},
			error: function() {
				modalBody.prepend('<div class=\"admin-form-modal__error\">No fue posible guardar la información.</div>');
			},
			complete: function() {
				submitButton.prop('disabled', false);
			}
		});
	});
})(jQuery);
");
?>