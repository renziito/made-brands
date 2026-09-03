<?php
$this->pageTitle = 'Respuestas';
?>

<style>
    .respuestas-page {
        width: 100%;
    }

    .respuestas-page .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 25px;
    }

    .respuestas-page .page-header h1 {
        margin: 0;
        font-size: 28px;
        font-weight: 600;
    }

    .respuestas-page .forms-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
        gap: 18px;
        margin-bottom: 30px;
    }

    .respuestas-page .form-card {
        position: relative;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 20px;
        cursor: pointer;
        transition: all .2s ease;
        min-height: 125px;
        box-sizing: border-box;
    }

    .respuestas-page .form-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 5px 18px rgba(0, 0, 0, .06);
        transform: translateY(-1px);
    }

    .respuestas-page .form-card.active {
        border-color: #2563eb;
        box-shadow: 0 0 0 1px #2563eb;
    }

    .respuestas-page .form-card-title {
        font-size: 17px;
        font-weight: 600;
        color: #111827;
        margin: 0 0 10px;
        padding-right: 10px;
    }

    .respuestas-page .form-card-description {
        font-size: 13px;
        line-height: 1.45;
        color: #6b7280;
        margin: 0 0 15px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .respuestas-page .form-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .respuestas-page .form-card-count {
        font-size: 13px;
        color: #6b7280;
    }

    .respuestas-page .form-card-count strong {
        color: #111827;
        font-size: 18px;
        margin-right: 4px;
    }

    .respuestas-page .responses-section {
        display: none;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
    }

    .respuestas-page .responses-section.visible {
        display: block;
    }

    .respuestas-page .responses-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 22px;
        border-bottom: 1px solid #e5e7eb;
    }

    .respuestas-page .responses-header h2 {
        margin: 0;
        font-size: 20px;
        font-weight: 600;
        color: #111827;
    }

    .respuestas-page .responses-header .responses-total {
        font-size: 13px;
        color: #6b7280;
    }

    .respuestas-page .responses-loading {
        padding: 45px 20px;
        text-align: center;
        color: #6b7280;
        font-size: 14px;
    }

    .respuestas-page .responses-empty {
        padding: 55px 20px;
        text-align: center;
        color: #6b7280;
        font-size: 14px;
    }

    .respuestas-page .responses-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .respuestas-page .responses-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .respuestas-page .responses-table th {
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
        padding: 12px 15px;
        text-align: left;
        font-size: 12px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: .02em;
    }

    .respuestas-page .responses-table td {
        border-bottom: 1px solid #f0f0f0;
        padding: 14px 15px;
        font-size: 13px;
        color: #374151;
        vertical-align: middle;
    }

    .respuestas-page .responses-table tbody tr.response-row {
        cursor: pointer;
        transition: background .15s ease;
    }

    .respuestas-page .responses-table tbody tr.response-row:hover {
        background: #fafafa;
    }

    .respuestas-page .responses-table tbody tr.response-row.expanded {
        background: #f8fafc;
    }

    .respuestas-page .responses-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .respuestas-page .response-status {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 5px 9px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
    }

    .respuestas-page .response-status.registrado {
        background: #fef3c7;
        color: #92400e;
    }

    .respuestas-page .response-status.atendido {
        background: #dcfce7;
        color: #166534;
    }

    .respuestas-page .status-select {
        border: 1px solid #d1d5db;
        border-radius: 6px;
        background: #fff;
        padding: 6px 28px 6px 9px;
        font-size: 12px;
        color: #374151;
        cursor: pointer;
        outline: none;
    }

    .respuestas-page .status-select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, .1);
    }

    .respuestas-page .response-details-row {
        display: none;
    }

    .respuestas-page .response-details-row.open {
        display: table-row;
    }

    .respuestas-page .response-details {
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
        padding: 20px;
    }

    .respuestas-page .details-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px 25px;
    }

    .respuestas-page .detail-item {
        min-width: 0;
    }

    .respuestas-page .detail-label {
        display: block;
        font-size: 11px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: .02em;
        margin-bottom: 5px;
    }

    .respuestas-page .detail-value {
        display: block;
        font-size: 13px;
        line-height: 1.5;
        color: #111827;
        word-break: break-word;
        white-space: pre-wrap;
    }

    .respuestas-page .detail-value.empty {
        color: #9ca3af;
        font-style: italic;
    }

    .respuestas-page .response-meta {
        margin-top: 20px;
        padding-top: 15px;
        border-top: 1px solid #e5e7eb;
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .respuestas-page .response-meta-item {
        font-size: 12px;
        color: #6b7280;
    }

    .respuestas-page .response-meta-item strong {
        color: #374151;
        font-weight: 600;
    }

    .respuestas-page .response-actions {
        margin-top: 20px;
        padding-top: 15px;
        border-top: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .respuestas-page .response-actions label {
        font-size: 13px;
        font-weight: 600;
        color: #374151;
    }

    .respuestas-page .status-message {
        font-size: 12px;
        color: #16a34a;
        display: none;
    }

    .respuestas-page .status-message.error {
        color: #dc2626;
    }

    .respuestas-page .status-message.visible {
        display: inline;
    }

    .respuestas-page .no-value {
        color: #9ca3af;
    }

    @media (max-width: 768px) {
        .respuestas-page .forms-grid {
            grid-template-columns: 1fr;
        }

        .respuestas-page .responses-header {
            align-items: flex-start;
            gap: 10px;
            flex-direction: column;
        }

        .respuestas-page .details-grid {
            grid-template-columns: 1fr;
        }

        .respuestas-page .responses-table {
            min-width: 700px;
        }
    }
</style>

<div class="respuestas-page">

    <div class="page-header">
        <h1>Respuestas</h1>
    </div>

    <div class="forms-grid" id="forms-grid">

        <?php foreach ($forms as $form): ?>

            <?php
            $formId = (int)$form['id'];
            $formTitle = CHtml::encode($form['title']);
            $formDescription = CHtml::encode($form['description']);
            $responsesCount = (int)$form['responses_count'];
            ?>

            <div
                class="form-card"
                data-form-id="<?php echo $formId; ?>"
                data-form-title="<?php echo $formTitle; ?>">
                <div class="form-card-title">
                    <?php echo $formTitle; ?>
                </div>

                <?php if (!empty($form['description'])): ?>
                    <div class="form-card-description">
                        <?php echo $formDescription; ?>
                    </div>
                <?php endif; ?>

                <div class="form-card-footer">
                    <div class="form-card-count">
                        <strong><?php echo $responsesCount; ?></strong>
                        <?php echo $responsesCount == 1 ? 'respuesta' : 'respuestas'; ?>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>

    </div>

    <div class="responses-section" id="responses-section">

        <div class="responses-header">
            <h2 id="responses-title"></h2>
            <div class="responses-total" id="responses-total"></div>
        </div>

        <div id="responses-content"></div>

    </div>

</div>

<script type="text/javascript">
    (function() {
        'use strict';

        var formsGrid = document.getElementById('forms-grid');
        var responsesSection = document.getElementById('responses-section');
        var responsesTitle = document.getElementById('responses-title');
        var responsesTotal = document.getElementById('responses-total');
        var responsesContent = document.getElementById('responses-content');
        var activeFormCard = null;
        var currentResponses = [];

        var responsesUrl = <?php echo CJSON::encode($this->createUrl('respuesta/responses')); ?>;
        var updateStatusUrl = <?php echo CJSON::encode($this->createUrl('respuesta/updateStatus')); ?>;

        function escapeHtml(value) {
            if (value === null || typeof value === 'undefined') {
                return '';
            }

            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function getValue(values, fieldId) {
            if (!values || typeof values[fieldId] === 'undefined' || values[fieldId] === null) {
                return '';
            }

            return values[fieldId];
        }

        function formatValue(value, field) {
            if (value === null || typeof value === 'undefined' || value === '') {
                return '<span class="no-value">Sin respuesta</span>';
            }

            var stringValue = String(value);

            if (field && field.type === 'checkbox') {
                if (stringValue === '1' || stringValue.toLowerCase() === 'true' || stringValue.toLowerCase() === 'on') {
                    return 'Sí';
                }

                return 'No';
            }

            return escapeHtml(stringValue);
        }

        function getFirstFields(fields, amount) {
            var result = [];

            for (var i = 0; i < fields.length && result.length < amount; i++) {
                result.push(fields[i]);
            }

            return result;
        }

        function getFieldValue(submission, field) {
            return getValue(submission.values, String(field.id));
        }

        function getSubmissionStatus(submission) {
            if (submission.estado === 'atendido') {
                return 'atendido';
            }

            return 'registrado';
        }

        function statusLabel(status) {
            return status === 'atendido' ? 'Atendido' : 'Registrado';
        }

        function formatDate(value) {
            if (!value) {
                return '';
            }

            return escapeHtml(value);
        }

        function renderLoading() {
            responsesContent.innerHTML =
                '<div class="responses-loading">Cargando respuestas...</div>';
        }

        function renderEmpty() {
            responsesContent.innerHTML =
                '<div class="responses-empty">Este formulario todavía no tiene respuestas.</div>';
        }

        function renderResponses(data) {
            currentResponses = data.submissions || [];

            responsesTitle.textContent = data.form.title || 'Respuestas';
            responsesTotal.textContent =
                data.total + (data.total === 1 ? ' respuesta' : ' respuestas');

            if (!currentResponses.length) {
                renderEmpty();
                return;
            }

            var firstFields = getFirstFields(data.fields || [], 3);
            var html = '';

            html += '<div class="responses-table-wrapper">';
            html += '<table class="responses-table">';
            html += '<thead>';
            html += '<tr>';

            for (var i = 0; i < firstFields.length; i++) {
                html += '<th>' + escapeHtml(firstFields[i].label || firstFields[i].name) + '</th>';
            }

            html += '<th style="width:130px;">Estado</th>';
            html += '<th style="width:145px;">Fecha</th>';
            html += '</tr>';
            html += '</thead>';
            html += '<tbody>';

            for (var j = 0; j < currentResponses.length; j++) {
                var submission = currentResponses[j];
                var status = getSubmissionStatus(submission);

                html += '<tr class="response-row" data-index="' + j + '">';

                for (var k = 0; k < firstFields.length; k++) {
                    var field = firstFields[k];
                    var value = getSubmissionStatusValue(submission, field);

                    html += '<td>' + formatValue(value, field) + '</td>';
                }

                html += '<td>';
                html += '<span class="response-status ' + status + '">';
                html += escapeHtml(statusLabel(status));
                html += '</span>';
                html += '</td>';

                html += '<td>' + formatDate(submission.created_at) + '</td>';

                html += '</tr>';

                html += '<tr class="response-details-row" data-detail-index="' + j + '">';
                html += '<td colspan="' + (firstFields.length + 2) + '">';
                html += renderDetails(submission, data.fields || [], j);
                html += '</td>';
                html += '</tr>';
            }

            html += '</tbody>';
            html += '</table>';
            html += '</div>';

            responsesContent.innerHTML = html;

            bindResponseRows();
            bindStatusSelectors();
        }

        function getSubmissionStatusValue(submission, field) {
            return getFieldValue(submission, field);
        }

        function renderDetails(submission, fields, index) {
            var html = '';
            var status = getSubmissionStatus(submission);

            html += '<div class="response-details">';

            html += '<div class="details-grid">';

            for (var i = 0; i < fields.length; i++) {
                var field = fields[i];
                var value = getFieldValue(submission, field);

                html += '<div class="detail-item">';
                html += '<span class="detail-label">';
                html += escapeHtml(field.label || field.name);
                html += '</span>';
                html += '<span class="detail-value">';

                if (value === null || typeof value === 'undefined' || value === '') {
                    html += '<span class="empty">Sin respuesta</span>';
                } else {
                    html += formatValue(value, field);
                }

                html += '</span>';
                html += '</div>';
            }

            html += '</div>';

            html += '<div class="response-meta">';

            html += '<div class="response-meta-item">';
            html += '<strong>ID:</strong> ';
            html += escapeHtml(submission.id);
            html += '</div>';

            if (submission.ip_address) {
                html += '<div class="response-meta-item">';
                html += '<strong>IP:</strong> ';
                html += escapeHtml(submission.ip_address);
                html += '</div>';
            }

            if (submission.created_at) {
                html += '<div class="response-meta-item">';
                html += '<strong>Registrado:</strong> ';
                html += escapeHtml(submission.created_at);
                html += '</div>';
            }

            if (submission.updated_at) {
                html += '<div class="response-meta-item">';
                html += '<strong>Actualizado:</strong> ';
                html += escapeHtml(submission.updated_at);
                html += '</div>';
            }

            html += '</div>';

            html += '<div class="response-actions">';
            html += '<label for="status-' + escapeHtml(submission.id) + '">Estado:</label>';

            html += '<select';
            html += ' class="status-select"';
            html += ' id="status-' + escapeHtml(submission.id) + '"';
            html += ' data-submission-id="' + escapeHtml(submission.id) + '"';
            html += '>';

            html += '<option value="registrado"';
            if (status === 'registrado') {
                html += ' selected="selected"';
            }
            html += '>Registrado</option>';

            html += '<option value="atendido"';
            if (status === 'atendido') {
                html += ' selected="selected"';
            }
            html += '>Atendido</option>';

            html += '</select>';

            html += '<span class="status-message" id="status-message-' + escapeHtml(submission.id) + '"></span>';

            html += '</div>';

            html += '</div>';

            return html;
        }

        function bindResponseRows() {
            var rows = responsesContent.querySelectorAll('.response-row');

            for (var i = 0; i < rows.length; i++) {
                rows[i].addEventListener('click', function(event) {
                    if (event.target && (
                            event.target.tagName === 'SELECT' ||
                            event.target.tagName === 'OPTION' ||
                            event.target.tagName === 'BUTTON' ||
                            event.target.closest('.response-actions')
                        )) {
                        return;
                    }

                    var index = parseInt(this.getAttribute('data-index'), 10);

                    toggleResponse(index);
                });
            }
        }

        function toggleResponse(index) {
            var rows = responsesContent.querySelectorAll('.response-row');
            var detailRows = responsesContent.querySelectorAll('.response-details-row');

            for (var i = 0; i < rows.length; i++) {
                var rowIndex = parseInt(rows[i].getAttribute('data-index'), 10);

                if (rowIndex === index) {
                    var isOpen = detailRows[i] && detailRows[i].classList.contains('open');

                    for (var j = 0; j < rows.length; j++) {
                        rows[j].classList.remove('expanded');
                    }

                    for (var k = 0; k < detailRows.length; k++) {
                        detailRows[k].classList.remove('open');
                    }

                    if (!isOpen) {
                        rows[i].classList.add('expanded');

                        if (detailRows[i]) {
                            detailRows[i].classList.add('open');
                        }
                    }

                    break;
                }
            }
        }

        function bindStatusSelectors() {
            var selectors = responsesContent.querySelectorAll('.status-select');

            for (var i = 0; i < selectors.length; i++) {
                selectors[i].addEventListener('change', function(event) {
                    event.stopPropagation();

                    var select = this;
                    var submissionId = select.getAttribute('data-submission-id');
                    var estado = select.value;

                    updateStatus(select, submissionId, estado);
                });
            }
        }

        function updateStatus(select, submissionId, estado) {
            var message = document.getElementById('status-message-' + submissionId);

            if (message) {
                message.className = 'status-message';
                message.textContent = 'Guardando...';
                message.classList.add('visible');
            }

            select.disabled = true;

            var xhr = new XMLHttpRequest();

            xhr.open('POST', updateStatusUrl, true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

            xhr.onreadystatechange = function() {
                if (xhr.readyState !== 4) {
                    return;
                }

                select.disabled = false;

                var response = null;

                try {
                    response = JSON.parse(xhr.responseText);
                } catch (e) {
                    response = null;
                }

                if (xhr.status === 200 && response && response.success) {
                    for (var i = 0; i < currentResponses.length; i++) {
                        if (String(currentResponses[i].id) === String(submissionId)) {
                            currentResponses[i].estado = response.estado;
                            break;
                        }
                    }

                    var row = select.closest('.response-details-row');

                    if (row) {
                        var previousRow = row.previousElementSibling;

                        if (previousRow) {
                            var statusBadge = previousRow.querySelector('.response-status');

                            if (statusBadge) {
                                statusBadge.className = 'response-status ' + response.estado;
                                statusBadge.textContent = statusLabel(response.estado);
                            }
                        }
                    }

                    if (message) {
                        message.className = 'status-message visible';
                        message.textContent = 'Estado actualizado';

                        setTimeout(function() {
                            if (message) {
                                message.classList.remove('visible');
                            }
                        }, 1800);
                    }
                } else {
                    if (message) {
                        message.className = 'status-message error visible';
                        message.textContent =
                            response && response.message ?
                            response.message :
                            'No se pudo actualizar el estado.';
                    }
                }
            };

            var params =
                'id=' + encodeURIComponent(submissionId) +
                '&estado=' + encodeURIComponent(estado);

            xhr.send(params);
        }

        function loadResponses(formId, card) {
            if (activeFormCard) {
                activeFormCard.classList.remove('active');
            }

            activeFormCard = card;
            activeFormCard.classList.add('active');

            responsesSection.classList.add('visible');
            responsesTitle.textContent = 'Respuestas';
            responsesTotal.textContent = '';
            renderLoading();

            responsesSection.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });

            var xhr = new XMLHttpRequest();

            xhr.open(
                'GET',
                responsesUrl + '?form_id=' + encodeURIComponent(formId),
                true
            );

            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

            xhr.onreadystatechange = function() {
                if (xhr.readyState !== 4) {
                    return;
                }

                var response = null;

                try {
                    response = JSON.parse(xhr.responseText);
                } catch (e) {
                    response = null;
                }

                if (xhr.status === 200 && response && response.success) {
                    renderResponses(response);
                    return;
                }

                responsesContent.innerHTML =
                    '<div class="responses-empty">' +
                    escapeHtml(
                        response && response.message ?
                        response.message :
                        'No se pudieron cargar las respuestas.'
                    ) +
                    '</div>';
            };

            xhr.send();
        }

        var formCards = formsGrid.querySelectorAll('.form-card');

        for (var i = 0; i < formCards.length; i++) {
            formCards[i].addEventListener('click', function() {
                var formId = this.getAttribute('data-form-id');

                loadResponses(formId, this);
            });
        }
    })();
</script>