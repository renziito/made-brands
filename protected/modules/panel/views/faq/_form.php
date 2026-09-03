<?php
/* @var $this FaqController */
/* @var $model Faqs */
/* @var $form CActiveForm */
/* @var $languages Languages[] */
/* @var $defaultLanguage Languages */
/* @var $translation FaqTranslations */
/* @var $translations FaqTranslations[] */
/* @var $translationsByLanguage array */
/* @var $faqForms FaqForms[] */

$languages = isset($languages) ? $languages : array();
$translationsByLanguage = isset($translationsByLanguage) ? $translationsByLanguage : array();
$faqForms = isset($faqForms) ? $faqForms : array();

$faqFormsData = array();
foreach ($faqForms as $faqForm) {
    $formId = (int) $faqForm->id;
    $formLabel = '';

    if (isset($faqForm->text)) {
        $formLabel = (string) $faqForm->text;
    } elseif (isset($faqForm->title)) {
        $formLabel = (string) $faqForm->title;
    } elseif (isset($faqForm->name)) {
        $formLabel = (string) $faqForm->name;
    }

    $faqFormsData[$formId] = $formLabel;
}

$fontAwesomeIcons = array();
$fontAwesomeMetadataPath = Yii::getPathOfAlias('webroot') . '/bin/fonts/font-awesome/metadata/icons.yml';

if (is_file($fontAwesomeMetadataPath)) {
    $lines = file($fontAwesomeMetadataPath, FILE_IGNORE_NEW_LINES);
    $currentIcon = null;
    $insideStyles = false;

    foreach ($lines as $line) {
        if (preg_match('/^([A-Za-z0-9][A-Za-z0-9-]*):\s*$/', $line, $matches)) {
            $currentIcon = $matches[1];
            $insideStyles = false;
            continue;
        }

        if ($currentIcon === null) {
            continue;
        }

        if (preg_match('/^\s{2}styles:\s*$/', $line)) {
            $insideStyles = true;
            continue;
        }

        if ($insideStyles && preg_match('/^\s{2}[A-Za-z0-9_-]+:/', $line)) {
            $insideStyles = false;
        }

        if (!$insideStyles) {
            continue;
        }

        if (preg_match('/^\s{4}-\s*([A-Za-z0-9_-]+)\s*$/', $line, $matches)) {
            $style = $matches[1];
            $prefix = null;

            switch ($style) {
                case 'solid':
                    $prefix = 'fas';
                    break;
                case 'regular':
                    $prefix = 'far';
                    break;
                case 'brands':
                    $prefix = 'fab';
                    break;
            }

            if ($prefix !== null) {
                $fontAwesomeIcons[] = $prefix . ' fa-' . $currentIcon;
            }
        }
    }
}

$fontAwesomeIcons = array_values(array_unique($fontAwesomeIcons));
sort($fontAwesomeIcons);

Yii::app()->clientScript->registerCss(
    'admin-form-faqs',
    '
.admin-form-page {
	width: 100%;
	max-width: 1100px;
	margin: 0 auto;
}

.admin-form {
	margin-top: 28px;
}

.admin-form-card,
.admin-form-translation-card {
	overflow: hidden;
	background: #fff;
	border: 1px solid #e5e7eb;
	border-radius: 10px;
	box-shadow: 0 1px 2px rgba(0, 0, 0, .03);
}

.admin-form-translation-card {
	margin-top: 18px;
}

.admin-form-card__header {
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 30px;
	padding: 16px 20px;
	border-bottom: 1px solid #e5e7eb;
}

.admin-form-card__heading {
	display: flex;
	align-items: center;
	gap: 12px;
	min-width: 0;
}

.admin-form-card__icon {
	display: flex;
	align-items: center;
	justify-content: center;
	width: 36px;
	height: 36px;
	flex-shrink: 0;
	border-radius: 7px;
	background: #f3f4f6;
	color: #374151;
	font-size: 14px;
}

.admin-form-card__title {
	margin: 0;
	color: #111827;
	font-size: 15px;
	font-weight: 600;
	line-height: 1.3;
}

.admin-form-card__description {
	margin: 2px 0 0;
	color: #9ca3af;
	font-size: 12px;
	line-height: 1.4;
}

.admin-form-card__header-actions {
	display: flex;
	align-items: center;
	justify-content: flex-end;
	gap: 26px;
	margin-left: auto;
}

.admin-form-card__order,
.admin-form-card__active {
	display: flex;
	align-items: center;
	gap: 9px;
	position: relative;
}

.admin-form-card__action-label {
	display: inline-block;
	margin: 0;
	color: #6b7280;
	font-size: 11px;
	font-weight: 600;
	line-height: 1;
	white-space: nowrap;
}

.admin-form-card__order-input {
	width: 68px !important;
	height: 34px !important;
	padding: 6px 9px !important;
	box-sizing: border-box;
	border: 1px solid #d1d5db;
	border-radius: 6px;
	outline: none;
	background: #fff;
	color: #374151;
	font-family: inherit;
	font-size: 12px;
	text-align: center;
}

.admin-form-card__order-input:focus {
	border-color: #9ca3af;
	box-shadow: 0 0 0 3px rgba(17, 24, 39, .06);
}

.admin-form-card__order .error {
	position: absolute;
	top: 38px;
	right: 0;
	white-space: nowrap;
	color: #dc2626;
	font-size: 10px;
}

.admin-form-card__body {
	padding: 24px 20px;
}

.admin-form-card .errorSummary {
	margin: 0 0 20px;
	padding: 13px 15px;
	border: 1px solid #fecaca;
	border-radius: 7px;
	background: #fef2f2;
	color: #991b1b;
	font-size: 12px;
	line-height: 1.5;
}

.admin-form-card .errorSummary ul {
	margin: 6px 0 0 18px;
	padding: 0;
}

.admin-form-fields {
	display: grid;
	grid-template-columns: repeat(2, minmax(0, 1fr));
	gap: 20px 18px;
}

.admin-form-field {
	min-width: 0;
}

.admin-form-field--full {
	grid-column: 1 / -1;
}

.admin-form-field label {
	display: block;
	margin: 0 0 7px;
	color: #374151;
	font-size: 12px;
	font-weight: 600;
	line-height: 1.4;
}

.admin-form-field label .required {
	margin-left: 2px;
	color: #dc2626;
	font-weight: 700;
}

.admin-form-field input[type="text"],
.admin-form-field input[type="number"],
.admin-form-field select,
.admin-form-field textarea {
	display: block;
	width: 100%;
	box-sizing: border-box;
	padding: 9px 11px;
	border: 1px solid #d1d5db;
	border-radius: 6px;
	outline: none;
	background: #fff;
	color: #374151;
	font-family: inherit;
	font-size: 13px;
	line-height: 1.5;
	transition: border-color .15s ease, box-shadow .15s ease;
}

.admin-form-field input[type="text"],
.admin-form-field input[type="number"],
.admin-form-field select {
	height: 40px;
}

.admin-form-field textarea {
	min-height: 150px;
	resize: vertical;
}

.admin-form-field input:focus,
.admin-form-field select:focus,
.admin-form-field textarea:focus {
	border-color: #9ca3af;
	box-shadow: 0 0 0 3px rgba(17, 24, 39, .06);
}

.admin-form-field .error {
	display: block;
	margin-top: 6px;
	color: #dc2626;
	font-size: 11px;
	line-height: 1.4;
}

.admin-icon-picker {
	width: 100%;
}

.admin-icon-picker__selected {
	display: flex;
	align-items: center;
	gap: 12px;
	min-height: 54px;
	padding: 8px 12px;
	box-sizing: border-box;
	border: 1px solid #e5e7eb;
	border-radius: 7px;
	background: #f9fafb;
}

.admin-icon-picker__selected-icon {
	display: flex;
	align-items: center;
	justify-content: center;
	width: 38px;
	height: 38px;
	flex-shrink: 0;
	border: 1px solid #e5e7eb;
	border-radius: 6px;
	background: #fff;
	color: #374151;
	font-size: 17px;
}

.admin-icon-picker__selected-info {
	min-width: 0;
}

.admin-icon-picker__selected-label {
	display: block;
	margin-bottom: 2px;
	color: #9ca3af;
	font-size: 10px;
	line-height: 1.3;
}

.admin-icon-picker__selected-value {
	display: block;
	overflow: hidden;
	color: #374151;
	font-family: monospace;
	font-size: 11px;
	line-height: 1.4;
	text-overflow: ellipsis;
	white-space: nowrap;
}

.admin-icon-picker__search {
	margin-top: 10px;
}

.admin-icon-picker__search input {
	display: block;
	width: 100%;
	height: 40px;
	padding: 0 11px;
	box-sizing: border-box;
	border: 1px solid #d1d5db;
	border-radius: 6px;
	outline: none;
	background: #fff;
	color: #374151;
	font-family: inherit;
	font-size: 13px;
}

.admin-icon-picker__search input:focus {
	border-color: #9ca3af;
	box-shadow: 0 0 0 3px rgba(17, 24, 39, .06);
}

.admin-icon-picker__count {
	margin-top: 7px;
	color: #9ca3af;
	font-size: 10px;
}

.admin-icon-picker__grid {
	display: grid;
	grid-template-columns: repeat(10, minmax(0, 1fr));
	gap: 6px;
	max-height: 350px;
	overflow-y: auto;
	margin-top: 9px;
	padding: 8px;
	border: 1px solid #e5e7eb;
	border-radius: 7px;
	background: #f9fafb;
}

.admin-icon-picker__item {
	display: flex;
	align-items: center;
	justify-content: center;
	width: 100%;
	height: 42px;
	padding: 0;
	border: 1px solid transparent;
	border-radius: 6px;
	background: #fff;
	color: #4b5563;
	cursor: pointer;
	font-size: 16px;
	transition: background-color .12s ease, border-color .12s ease, color .12s ease, transform .12s ease;
}

.admin-icon-picker__item:hover {
	background: #f3f4f6;
	border-color: #d1d5db;
	color: #111827;
	transform: translateY(-1px);
}

.admin-icon-picker__item.is-selected {
	background: #111827;
	border-color: #111827;
	color: #fff;
}

.admin-icon-picker__empty {
	display: none;
	grid-column: 1 / -1;
	padding: 25px 15px;
	color: #9ca3af;
	font-size: 12px;
	text-align: center;
}

.admin-icon-picker__empty.is-visible {
	display: block;
}

.faq-switch {
	position: relative;
	display: inline-flex;
	width: 42px;
	height: 24px;
	flex-shrink: 0;
	margin: 0;
}

.faq-switch input {
	position: absolute;
	width: 1px;
	height: 1px;
	opacity: 0;
}

.faq-switch__track {
	position: relative;
	display: block;
	width: 42px;
	height: 24px;
	border-radius: 999px;
	background: #d1d5db;
	cursor: pointer;
	transition: background-color .15s ease;
}

.faq-switch__track::after {
	position: absolute;
	top: 3px;
	left: 3px;
	width: 18px;
	height: 18px;
	border-radius: 50%;
	background: #fff;
	box-shadow: 0 1px 2px rgba(0, 0, 0, .18);
	content: "";
	transition: transform .15s ease;
}

.faq-switch input:checked + .faq-switch__track {
	background: #111827;
}

.faq-switch input:checked + .faq-switch__track::after {
	transform: translateX(18px);
}

.admin-form-translation-card__header {
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 20px;
	padding: 16px 20px;
	border-bottom: 1px solid #e5e7eb;
}

.admin-form-translation-card__heading {
	display: flex;
	align-items: center;
	gap: 11px;
	min-width: 0;
}

.admin-form-translation-card__language {
	display: flex;
	align-items: center;
	justify-content: center;
	width: 36px;
	height: 32px;
	border-radius: 6px;
	background: #f3f4f6;
	color: #374151;
	font-size: 10px;
	font-weight: 700;
	text-transform: uppercase;
}

.admin-form-translation-card__title {
	margin: 0;
	color: #111827;
	font-size: 14px;
	font-weight: 600;
	line-height: 1.3;
}

.admin-form-translation-card__native {
	margin: 2px 0 0;
	color: #9ca3af;
	font-size: 11px;
	line-height: 1.3;
}

.admin-form-translation-card__status {
	display: inline-flex;
	align-items: center;
	gap: 6px;
	padding: 5px 9px;
	border-radius: 999px;
	background: #f3f4f6;
	color: #6b7280;
	font-size: 10px;
	font-weight: 700;
}

.admin-form-translation-card__status--saved {
	background: #ecfdf3;
	color: #15803d;
}

.admin-form-translation-card__body {
	padding: 20px;
}

.admin-form-form-hint {
	display: flex;
	align-items: center;
	gap: 7px;
	min-height: 30px;
	margin-top: 7px;
	padding: 6px 9px;
	box-sizing: border-box;
	border-radius: 6px;
	background: #f9fafb;
	color: #6b7280;
	font-size: 11px;
	line-height: 1.35;
}

.admin-form-form-hint i {
	color: #9ca3af;
}

.admin-form-main-footer,
.admin-form-translation-card__footer {
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 16px;
	padding: 14px 20px;
	border-top: 1px solid #e5e7eb;
	background: #f9fafb;
}

.admin-form-footer__note {
	color: #6b7280;
	font-size: 11px;
	line-height: 1.4;
}

.admin-form-footer__note .required {
	color: #dc2626;
	font-weight: 700;
}

.admin-form-actions {
	display: flex;
	align-items: center;
	justify-content: flex-end;
	gap: 8px;
	margin-left: auto;
}

.admin-form-button {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	gap: 8px;
	min-width: 112px;
	height: 38px;
	padding: 0 15px;
	box-sizing: border-box;
	border: 1px solid transparent;
	border-radius: 7px;
	cursor: pointer;
	font-family: inherit;
	font-size: 12px;
	font-weight: 600;
	line-height: 1;
	text-decoration: none !important;
	transition: background-color .15s ease, border-color .15s ease, box-shadow .15s ease, color .15s ease, transform .1s ease;
}

.admin-form-button:active {
	transform: translateY(1px);
}

.admin-form-button:hover {
	text-decoration: none !important;
}

.admin-form-button--primary {
	background: #111827;
	border-color: #111827;
	color: #fff !important;
	box-shadow: 0 1px 2px rgba(0, 0, 0, .08);
}

.admin-form-button--primary:hover {
	background: #1f2937;
	border-color: #1f2937;
	color: #fff !important;
	box-shadow: 0 2px 5px rgba(0, 0, 0, .10);
}

.admin-form-button--secondary {
	background: #fff;
	border-color: #d1d5db;
	color: #374151 !important;
}

.admin-form-button--secondary:hover {
	background: #f3f4f6;
	border-color: #9ca3af;
	color: #111827 !important;
}

.admin-form-empty {
	padding: 40px 20px;
	color: #9ca3af;
	font-size: 12px;
	text-align: center;
}

.admin-form-empty i {
	display: block;
	margin-bottom: 9px;
	font-size: 20px;
}

@media (max-width: 900px) {
	.admin-icon-picker__grid {
		grid-template-columns: repeat(8, minmax(0, 1fr));
	}
}

@media (max-width: 768px) {
	.admin-form-card__header,
	.admin-form-translation-card__header {
		align-items: flex-start;
		flex-direction: column;
	}

	.admin-form-card__header-actions {
		width: 100%;
		margin-left: 0;
		justify-content: flex-start;
	}

	.admin-form-fields {
		grid-template-columns: 1fr;
	}

	.admin-form-field--full {
		grid-column: auto;
	}

	.admin-form-card__body,
	.admin-form-translation-card__body {
		padding: 20px 16px;
	}

	.admin-form-main-footer,
	.admin-form-translation-card__footer {
		align-items: stretch;
		flex-direction: column;
	}

	.admin-form-actions {
		width: 100%;
		margin-left: 0;
	}

	.admin-form-button {
		flex: 1;
	}

	.admin-icon-picker__grid {
		grid-template-columns: repeat(6, minmax(0, 1fr));
	}
}
'
);

Yii::app()->clientScript->registerScript(
    'faq-form',
    "
(function() {
	var faqForms = " . CJSON::encode($faqFormsData) . ";

	function updateFaqFormHint(select) {
		var formId = $(select).val();
		var hint = $(select).closest('.admin-form-field').find('.admin-form-form-hint-text');

		if (!hint.length) {
			return;
		}

		if (formId && typeof faqForms[formId] !== 'undefined') {
			hint.text('Formulario seleccionado: ' + faqForms[formId]);
		} else {
			hint.text('No hay formulario seleccionado.');
		}
	}

	$('.js-faq-form-select').each(function() {
		updateFaqFormHint(this);
	});

	$(document).on('change', '.js-faq-form-select', function() {
		updateFaqFormHint(this);
	});

	var fontAwesomeIcons = " . CJSON::encode($fontAwesomeIcons) . ";
	var iconGrid = $('#faq-icon-grid');
	var iconSearch = $('#faq-icon-search');
	var iconEmpty = $('#faq-icon-empty');
	var iconCount = $('#faq-icon-count');
	var iconValue = $('#faq-icon-value');

	function renderIcons(filter) {
		filter = (filter || '').toLowerCase().trim();
		iconGrid.empty();

		var visibleCount = 0;

		$.each(fontAwesomeIcons, function(index, icon) {
			if (filter && icon.toLowerCase().indexOf(filter) === -1) {
				return;
			}

			var button = $('<button>', {
				type: 'button',
				class: 'admin-icon-picker__item',
				'data-icon': icon,
				title: icon
			});

			if (icon === iconValue.val()) {
				button.addClass('is-selected');
			}

			button.append($('<i>', {
				class: icon,
				'aria-hidden': 'true'
			}));

			iconGrid.append(button);
			visibleCount++;
		});

		iconCount.text(visibleCount + ' iconos disponibles');

		if (visibleCount === 0) {
			iconEmpty.addClass('is-visible');
		} else {
			iconEmpty.removeClass('is-visible');
		}
	}

	iconGrid.on('click', '.admin-icon-picker__item', function() {
		var button = $(this);
		var icon = button.attr('data-icon');

		if (!icon) {
			return;
		}

		iconValue.val(icon);
		$('#faq-icon-selected-value').text(icon);
		$('#faq-icon-selected-preview').html('<i class=\"' + icon + '\" aria-hidden=\"true\"></i>');
		iconGrid.find('.admin-icon-picker__item').removeClass('is-selected');
		button.addClass('is-selected');
	});

	iconSearch.on('input', function() {
		renderIcons($(this).val());
	});

	renderIcons();

	$(document).on('click', '.js-save-faq', function() {
		$('.admin-form-translation-card').find('input, textarea, select').prop('disabled', true);
	});

	$(document).on('click', '.js-save-language', function() {
		var button = $(this);
		var currentCard = button.closest('.admin-form-translation-card');

		if (!currentCard.length) {
			return true;
		}

		$('.admin-form-translation-card').not(currentCard).find('input, textarea, select').prop('disabled', true);
		currentCard.find('input, textarea, select').prop('disabled', false);
	});
})();
"
);
?>

<div class="admin-form-page">
	<?php
    $form = $this->beginWidget(
        'CActiveForm',
        array(
                            'id' => 'faqs-form',
                            'enableAjaxValidation' => false,
                            'htmlOptions' => array(
                                'class' => 'admin-form',
                            ),
                        )
    );
?>

<div class="admin-form-card">
	<div class="admin-form-card__header">
		<div class="admin-form-card__heading">
			<div class="admin-form-card__icon">
				<?php echo $model->isNewRecord ? '<i class="fas fa-plus"></i>' : '<i class="fas fa-pen"></i>'; ?>
			</div>
			<div>
				<h2 class="admin-form-card__title">Información de la FAQ</h2>
				<p class="admin-form-card__description">
					<?php echo $model->isNewRecord ? 'Completa la información general de la nueva FAQ.' : 'Modifica la información general de la FAQ.'; ?>
				</p>
			</div>
		</div>

		<div class="admin-form-card__header-actions">
			<div class="admin-form-card__order">
				<label for="faq-sort-order" class="admin-form-card__action-label">Order</label>
				<?= $form->numberField($model, 'sort_order', array('id' => 'faq-sort-order', 'min' => 0, 'class' => 'admin-form-card__order-input')); ?>
				<?= $form->error($model, 'sort_order'); ?>
			</div>
			<div class="admin-form-card__active">
				<span class="admin-form-card__action-label">Active</span>
				<label class="faq-switch faq-switch--header">
					<?= CHtml::activeCheckBox($model, 'is_active', array('uncheckValue' => '0', 'checked' => $model->isNewRecord ? true : (bool) $model->is_active)); ?>
					<span class="faq-switch__track"></span>
				</label>
			</div>
		</div>
	</div>

	<div class="admin-form-card__body">
		<?= $form->errorSummary($model, '<strong>Por favor verifica la información:</strong>'); ?>

		<div class="admin-form-fields">
			<div class="admin-form-field admin-form-field--full">
				<label>Icono</label>
				<div class="admin-icon-picker">
					<?php
                $currentIcon = !empty($model->icon) ? $model->icon : 'fas fa-question-circle';
?>
					<?= CHtml::hiddenField('Faqs[icon]', $currentIcon, array('id' => 'faq-icon-value')); ?>

					<div class="admin-icon-picker__selected">
						<div id="faq-icon-selected-preview" class="admin-icon-picker__selected-icon">
							<i class="<?= CHtml::encode($currentIcon); ?>" aria-hidden="true"></i>
						</div>
						<div class="admin-icon-picker__selected-info">
							<span class="admin-icon-picker__selected-label">Icono seleccionado</span>
							<span id="faq-icon-selected-value" class="admin-icon-picker__selected-value"><?= CHtml::encode($currentIcon); ?></span>
						</div>
					</div>

					<div class="admin-icon-picker__search">
						<input type="text" id="faq-icon-search" placeholder="Buscar icono..." autocomplete="off">
					</div>

					<div id="faq-icon-count" class="admin-icon-picker__count">Cargando iconos...</div>
					<div id="faq-icon-grid" class="admin-icon-picker__grid"></div>
					<div id="faq-icon-empty" class="admin-icon-picker__empty">No se encontraron iconos.</div>
				</div>

				<?= $form->error($model, 'icon'); ?>
			</div>
		</div>
	</div>

	<div class="admin-form-main-footer">
		<div class="admin-form-footer__note">
			<span class="required">*</span> Campos obligatorios
		</div>

		<div class="admin-form-actions">
			<a href="<?php echo $this->createUrl('index'); ?>" class="admin-form-button admin-form-button--secondary">
				<i class="fas <?php echo $model->isNewRecord ? 'fa-times' : 'fa-arrow-left'; ?>" aria-hidden="true"></i>
				<?php echo $model->isNewRecord ? 'Cancelar' : 'Volver'; ?>
			</a>

			<button type="submit" class="admin-form-button admin-form-button--primary js-save-faq">
				<i class="fas <?php echo $model->isNewRecord ? 'fa-plus' : 'fa-save'; ?>" aria-hidden="true"></i>
				<?php echo $model->isNewRecord ? 'Crear FAQ' : 'Guardar cambios'; ?>
			</button>
		</div>
	</div>
</div>

<?php if (!empty($languages)): ?>
	<?php foreach ($languages as $language): ?>
		<?php
        $languageId = (int) $language->id;
	    $languageKey = (string) $language->id;
	    $currentTranslation = isset($translationsByLanguage[$languageKey]) ? $translationsByLanguage[$languageKey] : new FaqTranslations();
	    $hasTranslation = !$currentTranslation->isNewRecord;
	    $languageCode = isset($language->code) ? $language->code : substr((string) $language->name, 0, 2);
	    ?>

		<div class="admin-form-translation-card" data-language-id="<?php echo $languageId; ?>">
			<div class="admin-form-translation-card__header">
				<div class="admin-form-translation-card__heading">
					<div class="admin-form-translation-card__language">
						<?php echo CHtml::encode(strtoupper($languageCode)); ?>
					</div>
					<div>
						<h2 class="admin-form-translation-card__title">
							<?php echo CHtml::encode(isset($language->native_name) ? $language->native_name : $language->name); ?>
						</h2>
						<p class="admin-form-translation-card__native"><?php echo CHtml::encode($language->name); ?></p>
					</div>
				</div>

				<div class="admin-form-translation-card__status<?php echo $hasTranslation ? ' admin-form-translation-card__status--saved' : ''; ?>">
					<i class="fas <?php echo $hasTranslation ? 'fa-check' : 'fa-circle'; ?>" aria-hidden="true"></i>
					<?php echo $hasTranslation ? 'Guardado' : 'Sin traducción'; ?>
				</div>
			</div>

			<div class="admin-form-translation-card__body">
				<div class="admin-form-fields">
					<div class="admin-form-field admin-form-field--full">
						<label for="faq-question-<?php echo $languageId; ?>">
							Pregunta <span class="required">*</span>
						</label>

						<input
							type="text"
							id="faq-question-<?php echo $languageId; ?>"
							name="FaqTranslations[<?php echo $languageId; ?>][question]"
							value="<?php echo CHtml::encode($currentTranslation->question); ?>"
							maxlength="255"
							required />

						<?php if ($currentTranslation->hasErrors('question')): ?>
							<span class="error"><?php echo CHtml::encode($currentTranslation->getError('question')); ?></span>
						<?php endif; ?>
					</div>

					<div class="admin-form-field admin-form-field--full">
						<label for="faq-answer-<?php echo $languageId; ?>">
							Respuesta <span class="required">*</span>
						</label>

						<textarea
							id="faq-answer-<?php echo $languageId; ?>"
							name="FaqTranslations[<?php echo $languageId; ?>][answer]"
							rows="8"
							required><?php echo CHtml::encode($currentTranslation->answer); ?></textarea>

						<?php if ($currentTranslation->hasErrors('answer')): ?>
							<span class="error"><?php echo CHtml::encode($currentTranslation->getError('answer')); ?></span>
						<?php endif; ?>
					</div>

					<div class="admin-form-field">
						<label for="faq-form-text-<?php echo $languageId; ?>">Texto del botón</label>

						<input
							type="text"
							id="faq-form-text-<?php echo $languageId; ?>"
							name="FaqTranslations[<?php echo $languageId; ?>][form_text]"
							value="<?php echo CHtml::encode($currentTranslation->form_text); ?>"
							maxlength="255"
							placeholder="Ej. Solicitar información" />

						<?php if ($currentTranslation->hasErrors('form_text')): ?>
							<span class="error"><?php echo CHtml::encode($currentTranslation->getError('form_text')); ?></span>
						<?php endif; ?>
					</div>

					<div class="admin-form-field">
						<label for="faq-form-<?php echo $languageId; ?>">Formulario</label>

						<select
							id="faq-form-<?php echo $languageId; ?>"
							name="FaqTranslations[<?php echo $languageId; ?>][form_id]"
							class="js-faq-form-select">
							<option value="">Sin formulario</option>

							<?php foreach ($faqForms as $faqForm): ?>
								<?php
	                            $formId = (int) $faqForm->id;
							    $formLabel = isset($faqFormsData[$formId]) ? $faqFormsData[$formId] : '';
							    ?>

								<option
									value="<?php echo $formId; ?>"
									<?php echo ((string) $currentTranslation->form_id === (string) $formId) ? 'selected' : ''; ?>>
									<?php echo CHtml::encode($formLabel); ?>
								</option>
							<?php endforeach; ?>
						</select>

						<div class="admin-form-form-hint">
							<i class="fas fa-link" aria-hidden="true"></i>
							<span class="admin-form-form-hint-text">No hay formulario seleccionado.</span>
						</div>
					</div>
				</div>
			</div>

			<div class="admin-form-translation-card__footer">
				<div class="admin-form-footer__note">
					<span class="required">*</span> Campos obligatorios
				</div>

				<div class="admin-form-actions">
					<button
						type="submit"
						name="save_language"
						value="<?php echo $languageId; ?>"
						class="admin-form-button admin-form-button--primary js-save-language">
						<i class="fas fa-save" aria-hidden="true"></i>
						<?php echo $hasTranslation ? 'Guardar cambios' : 'Guardar traducción'; ?>
					</button>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
<?php else: ?>
	<div class="admin-form-card">
		<div class="admin-form-empty">
			<i class="fas fa-language" aria-hidden="true"></i>
			No hay idiomas configurados.
		</div>
	</div>
<?php endif; ?>

<?php $this->endWidget(); ?>


</div>
