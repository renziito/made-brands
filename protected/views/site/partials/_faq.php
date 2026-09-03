<?php
/*
|--------------------------------------------------------------------------
| FAQ
|--------------------------------------------------------------------------
| Frequently Asked Questions
|--------------------------------------------------------------------------
*/
Yii::app()->clientScript->registerCoreScript('jquery');
?>

<style>
    .faq__answer-content--has-form {
        display: flex;
        align-items: center;
        gap: 70px;
        padding: 25px 75px;
    }

    .faq__answer-text {
        flex: 1;
        min-width: 0;
    }

    .faq__form-button-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 280px;
        min-height: 70px;
        padding-left: 70px;
        border-left: 2px solid #36328f;
        box-sizing: border-box;
    }

    .faq__form-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 210px;
        min-height: 60px;
        padding: 0 25px;
        border: 0;
        border-radius: 15px;
        background: #050505;
        color: #fff;
        font-size: 15px;
        font-weight: 700;
        line-height: 1.2;
        text-align: center;
        cursor: pointer;
        transition: background-color .2s ease, transform .2s ease;
    }

    .faq__form-button:hover,
    .faq__form-button:focus {
        background: #222;
        color: #fff;
        outline: none;
        text-decoration: none;
    }

    .faq__form-button:active {
        transform: scale(.98);
    }

    .faq__form-button i {
        display: none;
    }

    @media (max-width: 767px) {
        .faq__answer-content--has-form {
            display: block;
            padding: 25px 30px;
        }

        .faq__answer-text {
            margin-bottom: 25px;
        }

        .faq__form-button-wrapper {
            min-height: auto;
            padding: 25px 0 0;
            border-left: 0;
            border-top: 2px solid #36328f;
        }

        .faq__form-button {
            width: 100%;
            min-width: 0;
        }
    }
</style>

<section id="faq" class="faq" style="background-color:<?= WebUtils::getSiteSetting('section_background_color') ?>">
    <div class="container">
        <div class="faq__header">
            <h2 class="faq__title" style="font-family:<?= WebUtils::getSiteSetting('heading_font_family') ?>">
                <?= WebUtils::getMenuItemByKey('frequently_asked_questions', $languageId)['label'] ?>
            </h2>
            <div class="faq__title-line"></div>
        </div>

        <div class="faq__list" id="faqAccordion">
            <?php foreach ($faqItems as $index => $item): ?>
                <div class="faq__item">
                    <button type="button" class="faq__question collapsed" data-toggle="collapse" data-parent="#faqAccordion" data-target="#<?= $item['id']; ?>" aria-expanded="false" aria-controls="<?= $item['id']; ?>">
                        <span class="faq__icon"><i class="<?= $item['icon']; ?>" aria-hidden="true"></i></span>
                        <span class="faq__question-text"><?= CHtml::encode($item['question']); ?></span>
                        <span class="faq__plus"><i class="fa fa-plus" aria-hidden="true"></i></span>
                    </button>

                    <div id="<?= $item['id']; ?>" class="faq__answer collapse">
                        <?php if (!empty($item['form_id'])): ?>
                            <div class="faq__answer-content faq__answer-content--has-form" style="font-family:<?= WebUtils::getSiteSetting('body_font_family') ?>">
                                <div class="faq__answer-text">
                                    <?= CHtml::encode($item['answer']); ?>
                                </div>

                                <div class="faq__form-button-wrapper">
                                    <button type="button" class="faq__form-button" data-form-id="<?= (int) $item['form_id']; ?>">
                                        <?= CHtml::encode($item['form_text']); ?>
                                    </button>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="faq__answer-content" style="font-family:<?= WebUtils::getSiteSetting('body_font_family') ?>">
                                <?= CHtml::encode($item['answer']); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<div class="modal fade" id="faqFormModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="faqFormModalTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body" id="faqFormModalBody">
                <div class="faq-form-loading" style="text-align:center;padding:25px;">
                    <i class="fa fa-spinner fa-spin fa-2x" aria-hidden="true"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="faqSuccessModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body" id="faqSuccessModalBody"></div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    (function($) {
        'use strict';

        var faqFormUrl = '<?= Yii::app()->createUrl('panel/faq/getForm'); ?>';
        var faqSubmitUrl = '<?= Yii::app()->createUrl('panel/faq/submitForm'); ?>';

        function escapeHtml(value) {
            return $('<div>').text(value == null ? '' : value).html();
        }

        function parseOptions(options) {
            if (!options) return [];
            if ($.isArray(options)) return options;
            if (typeof options === 'object') return options;

            try {
                var parsed = JSON.parse(options);

                if ($.isArray(parsed) || typeof parsed === 'object') {
                    return parsed;
                }
            } catch (e) {}

            return String(options)
                .split(/\r?\n/)
                .map(function(option) {
                    return $.trim(option);
                })
                .filter(function(option) {
                    return option !== '';
                });
        }

        function renderField(field) {
            var id = 'faq_field_' + field.id;
            var name = 'fields[' + field.id + ']';
            var type = String(field.type || 'text').toLowerCase();
            var required = parseInt(field.is_required, 10) === 1;
            var requiredAttribute = required ? ' required' : '';
            var placeholder = field.placeholder ? ' placeholder="' + escapeHtml(field.placeholder) + '"' : '';
            var defaultValue = field.default_value || '';
            var html = '';

            html += '<div class="form-group faq-dynamic-field" data-field-id="' + field.id + '">';
            html += '<label for="' + id + '">' + escapeHtml(field.label);

            if (required) {
                html += ' <span class="text-danger">*</span>';
            }

            html += '</label>';

            if (type === 'textarea') {
                html += '<textarea class="form-control" id="' + id + '" name="' + name + '"' + placeholder + requiredAttribute + ' rows="5">';
                html += escapeHtml(defaultValue);
                html += '</textarea>';
            } else if (type === 'select') {
                html += '<select class="form-control" id="' + id + '" name="' + name + '"' + requiredAttribute + '>';
                html += '<option value="">Seleccionar...</option>';

                var selectOptions = parseOptions(field.options);

                $.each(selectOptions, function(value, label) {
                    var optionValue;
                    var optionLabel;

                    if (typeof label === 'object' && label !== null) {
                        optionValue = label.value !== undefined ? label.value : '';
                        optionLabel = label.label !== undefined ? label.label : optionValue;
                    } else if (typeof selectOptions === 'object' && !$.isArray(selectOptions)) {
                        optionValue = value;
                        optionLabel = label;
                    } else {
                        optionValue = label;
                        optionLabel = label;
                    }

                    var selected = String(optionValue) === String(defaultValue) ? ' selected' : '';

                    html += '<option value="' + escapeHtml(optionValue) + '"' + selected + '>';
                    html += escapeHtml(optionLabel);
                    html += '</option>';
                });

                html += '</select>';
            } else if (type === 'radio') {
                var radioOptions = parseOptions(field.options);

                $.each(radioOptions, function(value, label) {
                    var optionValue;
                    var optionLabel;

                    if (typeof label === 'object' && label !== null) {
                        optionValue = label.value !== undefined ? label.value : '';
                        optionLabel = label.label !== undefined ? label.label : optionValue;
                    } else if (typeof radioOptions === 'object' && !$.isArray(radioOptions)) {
                        optionValue = value;
                        optionLabel = label;
                    } else {
                        optionValue = label;
                        optionLabel = label;
                    }

                    var checked = String(optionValue) === String(defaultValue) ? ' checked' : '';

                    html += '<div class="form-check">';
                    html += '<label class="form-check-label">';
                    html += '<input class="form-check-input" type="radio" name="' + name + '" value="' + escapeHtml(optionValue) + '"' + checked + requiredAttribute + '>';
                    html += ' ' + escapeHtml(optionLabel);
                    html += '</label>';
                    html += '</div>';
                });
            } else if (type === 'checkbox') {
                var checkboxOptions = parseOptions(field.options);

                if (checkboxOptions.length || typeof checkboxOptions === 'object') {
                    $.each(checkboxOptions, function(value, label) {
                        var optionValue;
                        var optionLabel;

                        if (typeof label === 'object' && label !== null) {
                            optionValue = label.value !== undefined ? label.value : '';
                            optionLabel = label.label !== undefined ? label.label : optionValue;
                        } else if (typeof checkboxOptions === 'object' && !$.isArray(checkboxOptions)) {
                            optionValue = value;
                            optionLabel = label;
                        } else {
                            optionValue = label;
                            optionLabel = label;
                        }

                        html += '<div class="form-check">';
                        html += '<label class="form-check-label">';
                        html += '<input class="form-check-input" type="checkbox" name="' + name + '[]" value="' + escapeHtml(optionValue) + '">';
                        html += ' ' + escapeHtml(optionLabel);
                        html += '</label>';
                        html += '</div>';
                    });
                } else {
                    var checked = defaultValue == '1' || defaultValue === 'true' ? ' checked' : '';

                    html += '<div class="form-check">';
                    html += '<label class="form-check-label">';
                    html += '<input class="form-check-input" type="checkbox" id="' + id + '" name="' + name + '" value="1"' + checked + requiredAttribute + '>';
                    html += '</label>';
                    html += '</div>';
                }
            } else {
                var inputType = [
                    'text',
                    'email',
                    'number',
                    'tel',
                    'url',
                    'date',
                    'datetime-local',
                    'time',
                    'password'
                ].indexOf(type) !== -1 ? type : 'text';

                html += '<input type="' + inputType + '" class="form-control" id="' + id + '" name="' + name + '" value="' + escapeHtml(defaultValue) + '"' + placeholder + requiredAttribute + '>';
            }

            html += '<div class="faq-field-error text-danger" style="display:none;margin-top:5px;"></div>';
            html += '</div>';

            return html;
        }

        function showFormError(message) {
            $('#faqFormGeneralError').html(escapeHtml(message)).show();
        }

        function clearFormErrors() {
            $('#faqFormGeneralError').hide().html('');
            $('.faq-field-error').hide().html('');
            $('.faq-dynamic-field .form-control').removeClass('is-invalid');
        }

        function renderForm(form) {
            var html = '';

            if (form.description) {
                html += '<div class="faq-form-description" style="margin-bottom:20px;">';
                html += form.description;
                html += '</div>';
            }

            html += '<div id="faqFormGeneralError" class="alert alert-danger" style="display:none;"></div>';
            html += '<form id="faqDynamicForm" novalidate>';
            html += '<input type="hidden" name="form_id" value="' + escapeHtml(form.id) + '">';
            html += '<input type="hidden" name="YII_CSRF_TOKEN" value="<?= CHtml::encode(Yii::app()->request->csrfToken); ?>">';

            $.each(form.fields, function(index, field) {
                html += renderField(field);
            });

            html += '<div style="margin-top:20px;">';
            html += '<button type="submit" class="btn btn-primary faq-form-submit">';
            html += '<i class="fa fa-paper-plane" aria-hidden="true"></i>  ';
            html += escapeHtml(' ' + form.submit_label || ' Enviar');
            html += '</button>';
            html += '</div>';
            html += '</form>';

            $('#faqFormModalTitle').text(form.title || '');
            $('#faqFormModalBody').html(html);

            $('#faqDynamicForm').on('submit', function(e) {
                e.preventDefault();

                clearFormErrors();

                var formElement = this;

                if (!formElement.checkValidity()) {
                    formElement.reportValidity();
                    return false;
                }

                var submitButton = $(formElement).find('.faq-form-submit');

                submitButton.prop('disabled', true);
                submitButton.html('<i class="fa fa-spinner fa-spin"></i> Enviando...');

                $.ajax({
                    url: faqSubmitUrl,
                    type: 'POST',
                    dataType: 'json',
                    data: $(formElement).serialize(),
                    success: function(response) {
                        if (response && response.success) {
                            $('#faqFormModal').modal('hide');
                            $('#faqSuccessModalBody').html(response.message || 'El formulario fue enviado correctamente.');
                            $('#faqSuccessModal').modal('show');
                            formElement.reset();
                            return;
                        }

                        if (response && response.errors) {
                            $.each(response.errors, function(fieldId, message) {
                                var fieldContainer = $('.faq-dynamic-field[data-field-id="' + fieldId + '"]');

                                fieldContainer.find('.faq-field-error').html(escapeHtml(message)).show();
                                fieldContainer.find('.form-control').addClass('is-invalid');
                            });
                        }

                        showFormError(response && response.message ? response.message : 'No se pudo enviar el formulario.');
                    },
                    error: function(xhr) {
                        var message = 'Ocurrió un error al enviar el formulario.';

                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }

                        showFormError(message);
                    },
                    complete: function() {
                        submitButton.prop('disabled', false);
                        submitButton.html('<i class="fa fa-paper-plane"></i> ' + escapeHtml(' ' + form.submit_label || ' Enviar'));
                    }
                });

                return false;
            });
        }

        $(document).on('click', '.faq__form-button', function(e) {
            e.preventDefault();

            var formId = $(this).data('form-id');

            if (!formId) {
                return;
            }

            $('#faqFormModalTitle').text('');
            $('#faqFormModalBody').html('<div style="text-align:center;padding:25px;"><i class="fa fa-spinner fa-spin fa-2x" aria-hidden="true"></i></div>');
            $('#faqFormModal').modal('show');

            $.ajax({
                url: faqFormUrl,
                type: 'GET',
                dataType: 'json',
                data: {
                    id: formId
                },
                success: function(response) {
                    if (!response || !response.success) {
                        $('#faqFormModalBody').html('<div class="alert alert-danger">' + escapeHtml(response && response.message ? response.message : 'No se pudo cargar el formulario.') + '</div>');
                        return;
                    }

                    renderForm(response.form);
                },
                error: function(xhr) {
                    var message = 'No se pudo cargar el formulario.';

                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }

                    $('#faqFormModalBody').html('<div class="alert alert-danger">' + escapeHtml(message) + '</div>');
                }
            });
        });

        $('#faqFormModal').on('hidden.bs.modal', function() {
            $('#faqFormModalBody').html('');
            $('#faqFormModalTitle').text('');
        });
    })(jQuery);
</script>