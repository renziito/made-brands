<?php

/* @var $this ExtrasController */
/* @var $model MenuItems */

$this->breadcrumbs = array(
	'Menu Items' => array('index'),
	'Administrar',
);

$dataProvider = $model->search();

$languages = Languages::model()->findAll(array(
	'order' => 'sort_order ASC, id ASC',
));

$menuItems = $dataProvider->getData();
$menuItemIds = array();

foreach ($menuItems as $menuItem) {
	$menuItemIds[] = (int) $menuItem->id;
}

$translationsByMenuItem = array();

if ($menuItemIds) {
	$criteria = new CDbCriteria;
	$criteria->addInCondition('menu_item_id', $menuItemIds);
	$criteria->order = 'menu_item_id ASC, language_id ASC';

	$translations = MenuItemTranslations::model()->findAll($criteria);

	foreach ($translations as $translation) {
		$menuItemId = (int) $translation->menu_item_id;
		$languageId = (int) $translation->language_id;

		if (!isset($translationsByMenuItem[$menuItemId])) {
			$translationsByMenuItem[$menuItemId] = array();
		}

		$translationsByMenuItem[$menuItemId][$languageId] = $translation;
	}
}

Yii::app()->clientScript->registerCss('admin-crud-menu-items', '
.admin-crud-page { width: 100%; }
.admin-crud-header { display: flex; align-items: flex-end; justify-content: space-between; gap: 24px; margin-bottom: 24px; }
.admin-crud-heading { min-width: 0; }
.admin-crud-eyebrow { margin-bottom: 6px; color: #6b7280; font-size: 11px; font-weight: 700; letter-spacing: .08em; line-height: 1.4; text-transform: uppercase; }
.admin-crud-title { margin: 0; color: #111827; font-size: 30px; font-weight: 600; line-height: 1.2; }
.admin-crud-description { margin: 7px 0 0; color: #6b7280; font-size: 14px; line-height: 1.5; }

.admin-crud-card { overflow: hidden; background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; box-shadow: 0 1px 2px rgba(0,0,0,.03); }
.admin-crud-card__header { display: flex; align-items: center; justify-content: space-between; gap: 20px; padding: 16px 20px; border-bottom: 1px solid #e5e7eb; }
.admin-crud-card__title { display: flex; align-items: center; gap: 11px; min-width: 0; }
.admin-crud-card__icon { display: flex; align-items: center; justify-content: center; width: 34px; height: 34px; flex-shrink: 0; border-radius: 7px; background: #f3f4f6; color: #374151; font-size: 14px; }
.admin-crud-card__heading { margin: 0; color: #111827; font-size: 15px; font-weight: 600; line-height: 1.3; }
.admin-crud-card__description { margin: 2px 0 0; color: #9ca3af; font-size: 12px; line-height: 1.4; }

.admin-crud-table-wrapper { width: 100%; overflow-x: auto; overflow-y: visible; -webkit-overflow-scrolling: touch; }
.admin-crud-table { width: 100%; min-width: 1000px; margin: 0; border: 0; border-collapse: separate; border-spacing: 0; background: #fff; }
.admin-crud-table thead th { height: 42px; padding: 0 14px; background: #f9fafb; border: 0; border-bottom: 1px solid #e5e7eb; color: #6b7280; font-size: 11px; font-weight: 700; letter-spacing: .04em; line-height: 1; text-align: left; text-transform: uppercase; white-space: nowrap; }
.admin-crud-table tbody td { height: 52px; padding: 0 14px; background: #fff; border: 0; border-bottom: 1px solid #f0f1f3; color: #374151; font-size: 13px; vertical-align: middle; }
.admin-crud-table tbody tr:hover td { background: #fafafa; }
.admin-crud-table tbody tr:last-child td { border-bottom: 0; }

.admin-crud-key__value { display: inline-block; padding: 4px 7px; border-radius: 5px; background: #f3f4f6; color: #374151; font-family: monospace; font-size: 12px; }
.admin-crud-boolean { color: #374151; font-size: 12px; font-weight: 600; }
.admin-crud-link { color: #6b7280; font-family: monospace; font-size: 12px; }
.admin-crud-muted { color: #9ca3af; }

.admin-crud-languages-column { min-width: 50px; }
.admin-crud-actions-column { width: 110px; min-width: 110px; text-align: right !important; white-space: nowrap; }

.admin-crud-languages { display: flex; align-items: center; justify-content: flex-start; flex-wrap: wrap; gap: 5px; }
.admin-crud-actions { display: flex; align-items: center; justify-content: flex-end; }

.admin-crud-action { display: inline-flex; align-items: center; justify-content: center; gap: 6px; height: 30px; padding: 0 9px; box-sizing: border-box; border: 1px solid #e5e7eb; border-radius: 5px; background: #fff; color: #6b7280 !important; font-size: 11px; font-weight: 700; text-decoration: none !important; transition: background-color .15s ease, border-color .15s ease, color .15s ease; }
.admin-crud-action:hover { background: #f3f4f6; border-color: #d1d5db; color: #111827 !important; text-decoration: none !important; }
.admin-crud-action--edit { padding: 0 11px; background: #111827; border-color: #111827; color: #fff !important; }
.admin-crud-action--edit:hover { background: #1f2937; border-color: #1f2937; color: #fff !important; }
.admin-crud-action--language { min-width: 36px; }
.admin-crud-action--language.is-translated { background: #f3f4f6; border-color: #d1d5db; color: #111827 !important; }

.admin-crud-empty { padding: 64px 20px; text-align: center; }
.admin-crud-empty__icon { display: flex; align-items: center; justify-content: center; width: 46px; height: 46px; margin: 0 auto 12px; border-radius: 50%; background: #f3f4f6; color: #9ca3af; font-size: 18px; }
.admin-crud-empty__title { margin-bottom: 4px; color: #374151; font-size: 14px; font-weight: 600; }
.admin-crud-empty__text { color: #9ca3af; font-size: 12px; }

.admin-crud-footer { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 12px 20px; border-top: 1px solid #e5e7eb; background: #fff; }
.admin-crud-summary { color: #6b7280; font-size: 12px; }
.admin-crud-pagination { margin: 0; }
.admin-crud-pagination ul.yiiPager { display: flex; align-items: center; gap: 4px; margin: 0; padding: 0; list-style: none; }
.admin-crud-pagination ul.yiiPager li { display: inline-flex; margin: 0; padding: 0; }
.admin-crud-pagination ul.yiiPager li a,
.admin-crud-pagination ul.yiiPager li span { display: inline-flex; align-items: center; justify-content: center; min-width: 30px; height: 30px; padding: 0 8px; box-sizing: border-box; border: 1px solid #e5e7eb; border-radius: 5px; background: #fff; color: #4b5563; font-size: 12px; text-decoration: none; }
.admin-crud-pagination ul.yiiPager li a:hover { background: #f9fafb; color: #111827; text-decoration: none; }
.admin-crud-pagination ul.yiiPager li.selected a,
.admin-crud-pagination ul.yiiPager li.selected span { background: #111827; border-color: #111827; color: #fff; }
.admin-crud-pagination ul.yiiPager li.hidden a,
.admin-crud-pagination ul.yiiPager li.hidden span { opacity: .45; cursor: default; }

.admin-crud-modal { position: fixed; z-index: 99999; top: 0; left: 0; display: none; align-items: center; justify-content: center; width: 100%; height: 100%; padding: 20px; box-sizing: border-box; background: rgba(17,24,39,.48); }
.admin-crud-modal.is-visible { display: flex; }
.admin-crud-modal__dialog { width: 100%; max-width: 460px; overflow: hidden; background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; box-shadow: 0 20px 50px rgba(0,0,0,.18); transform: translateY(8px); opacity: 0; transition: transform .15s ease, opacity .15s ease; }
.admin-crud-modal.is-visible .admin-crud-modal__dialog { transform: translateY(0); opacity: 1; }
.admin-crud-modal__header { display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; padding: 20px; border-bottom: 1px solid #e5e7eb; }
.admin-crud-modal__heading { display: flex; align-items: flex-start; gap: 12px; min-width: 0; }
.admin-crud-modal__icon { display: flex; align-items: center; justify-content: center; width: 38px; height: 38px; flex-shrink: 0; border-radius: 8px; background: #f3f4f6; color: #374151; font-size: 15px; }
.admin-crud-modal__title { margin: 0; color: #111827; font-size: 16px; font-weight: 600; line-height: 1.4; }
.admin-crud-modal__message { margin: 4px 0 0; color: #9ca3af; font-size: 12px; line-height: 1.4; }
.admin-crud-modal__close { display: inline-flex; align-items: center; justify-content: center; width: 30px; height: 30px; flex-shrink: 0; border: 0; border-radius: 5px; background: transparent; color: #9ca3af; cursor: pointer; font-size: 14px; }
.admin-crud-modal__close:hover { background: #f3f4f6; color: #374151; }
.admin-crud-modal__body { padding: 20px; }
.admin-crud-modal__label { display: block; margin-bottom: 7px; color: #374151; font-size: 12px; font-weight: 600; }
.admin-crud-modal__input { display: block; width: 100%; height: 40px; padding: 0 11px; box-sizing: border-box; border: 1px solid #d1d5db; border-radius: 6px; outline: none; background: #fff; color: #111827; font-family: inherit; font-size: 13px; transition: border-color .15s ease, box-shadow .15s ease; }
.admin-crud-modal__input:focus { border-color: #9ca3af; box-shadow: 0 0 0 3px rgba(17,24,39,.06); }
.admin-crud-modal__footer { display: flex; align-items: center; justify-content: flex-end; gap: 8px; padding: 14px 20px; border-top: 1px solid #e5e7eb; background: #f9fafb; }
.admin-crud-modal__button { display: inline-flex; align-items: center; justify-content: center; gap: 7px; height: 36px; padding: 0 13px; box-sizing: border-box; border: 1px solid transparent; border-radius: 6px; cursor: pointer; font-family: inherit; font-size: 12px; font-weight: 600; text-decoration: none; }
.admin-crud-modal__button--cancel { background: #fff; border-color: #d1d5db; color: #374151; }
.admin-crud-modal__button--cancel:hover { background: #f3f4f6; }
.admin-crud-modal__button--save { background: #111827; border-color: #111827; color: #fff; }
.admin-crud-modal__button--save:hover { background: #1f2937; border-color: #1f2937; }

@media (max-width: 768px) {
	.admin-crud-header { align-items: stretch; flex-direction: column; }
	.admin-crud-footer { align-items: flex-start; flex-direction: column; }
	.admin-crud-modal { padding: 16px; }
}

/* ==========================================================
   BUTTONS
   ========================================================== */

.admin-crud-button {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	gap: 8px;
	height: 38px;
	padding: 0 14px;
	border: 1px solid transparent;
	border-radius: 7px;
	box-sizing: border-box;
	cursor: pointer;
	font-size: 13px;
	font-weight: 600;
	line-height: 1;
	text-decoration: none !important;
	transition:
		background-color .15s ease,
		border-color .15s ease,
		box-shadow .15s ease,
		color .15s ease;
}

.admin-crud-button:hover {
	text-decoration: none !important;
}

.admin-crud-button--primary {
	background: #111827;
	border-color: #111827;
	color: #fff !important;
}

.admin-crud-button--primary:hover {
	background: #1f2937;
	border-color: #1f2937;
	color: #fff !important;
}

.admin-crud-button--secondary {
	background: #fff;
	border-color: #d1d5db;
	color: #374151 !important;
}

.admin-crud-button--secondary:hover,
.admin-crud-button--secondary.is-active {
	background: #f3f4f6;
	border-color: #9ca3af;
	color: #111827 !important;
}

.admin-crud-alert {
	display: flex;
	align-items: center;
	gap: 9px;
	margin-bottom: 18px;
	padding: 12px 14px;
	border: 1px solid #d1d5db;
	border-radius: 7px;
	font-size: 13px;
	font-weight: 500;
}

.admin-crud-alert--success {
	background: #f0fdf4;
	border-color: #bbf7d0;
	color: #166534;
}

');

Yii::app()->clientScript->registerScript('crud-index-menu-items', "
function openTranslationModal(menuItemId, languageId, languageName, label, actionUrl) {
	$('#menu-items-translation-menu-item-id').val(menuItemId);
	$('#menu-items-translation-language-id').val(languageId);
	$('#menu-items-translation-label').val(label);
	$('#menu-items-translation-form').attr('action', actionUrl);
	$('#menu-items-translation-language').text(languageName);
	$('#menu-items-translation-modal').addClass('is-visible').attr('aria-hidden', 'false');
	$('body').css('overflow', 'hidden');

	setTimeout(function() {
		$('#menu-items-translation-label').trigger('focus');
	}, 100);
}

function closeTranslationModal() {
	$('#menu-items-translation-modal').removeClass('is-visible').attr('aria-hidden', 'true');
	$('body').css('overflow', '');
}

$(document).on('click', '.admin-crud-action--language', function(e) {
	e.preventDefault();
	e.stopImmediatePropagation();

	var button = $(this);

	openTranslationModal(
		button.data('menu-item-id'),
		button.data('language-id'),
		button.data('language-name'),
		button.data('label'),
		button.data('action-url')
	);

	return false;
});

$('#menu-items-translation-cancel, #menu-items-translation-close').on('click', function(e) {
	e.preventDefault();
	closeTranslationModal();
	return false;
});

$('#menu-items-translation-modal').on('click', function(e) {
	if (e.target === this) {
		closeTranslationModal();
	}
});

$(document).on('keydown', function(e) {
	if (e.key === 'Escape') {
		closeTranslationModal();
	}
});
");

?>

<div class="admin-crud-page">
	<div class="admin-crud-header">
		<div class="admin-crud-heading">
			<div class="admin-crud-eyebrow">Contenido</div>
			<h1 class="admin-crud-title">Menu Items</h1>
			<p class="admin-crud-description">Administra las opciones del menú y sus traducciones.</p>
		</div>
		<div class="admin-crud-actions">

			<a
				class="admin-crud-button admin-crud-button--primary"
				href="<?php echo $this->createUrl('create'); ?>"
				title="Nuevo Item"
				aria-label="Nueva Item">

				<i
					class="fas fa-plus"
					aria-hidden="true"></i>

				<span>
					Nueva Item
				</span>

			</a>

		</div>
	</div>

	<?php if (Yii::app()->user->hasFlash('success')): ?>
		<div class="admin-crud-alert admin-crud-alert--success">
			<i class="fas fa-check-circle" aria-hidden="true"></i>
			<span><?php echo CHtml::encode(Yii::app()->user->getFlash('success')); ?></span>
		</div>
	<?php endif; ?>

	<div class="admin-crud-card">
		<div class="admin-crud-card__header">
			<div class="admin-crud-card__title">
				<div class="admin-crud-card__icon">
					<i class="fas fa-list" aria-hidden="true"></i>
				</div>
				<div>
					<h2 class="admin-crud-card__heading">Listado</h2>
					<p class="admin-crud-card__description">Edita la configuración del menú o selecciona un idioma para modificar su texto.</p>
				</div>
			</div>
		</div>

		<div class="admin-crud-table-wrapper">
			<?php if ($menuItems): ?>
				<table class="admin-crud-table">
					<thead>
						<tr>
							<th>Id</th>
							<th>Key</th>
							<th>Es menú</th>
							<th>Es botón</th>
							<th>Link</th>
							<th>Orden</th>
							<th class="admin-crud-languages-column">Idiomas</th>
							<th class="admin-crud-actions-column">Acciones</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($menuItems as $data): ?>
							<?php
							$menuItemId = (int) $data->id;
							$itemTranslations = isset($translationsByMenuItem[$menuItemId])
								? $translationsByMenuItem[$menuItemId]
								: array();
							?>
							<tr>
								<td><?php echo $menuItemId; ?></td>
								<td>
									<span class="admin-crud-key__value"><?php echo CHtml::encode($data->key); ?></span>
								</td>
								<td>
									<span class="admin-crud-boolean"><?php echo $data->is_menu ? 'Sí' : 'No'; ?></span>
								</td>
								<td>
									<span class="admin-crud-boolean"><?php echo $data->is_button ? 'Sí' : 'No'; ?></span>
								</td>
								<td>
									<?php if ($data->link !== null && $data->link !== ''): ?>
										<span class="admin-crud-link"><?php echo CHtml::encode($data->link); ?></span>
									<?php else: ?>
										<span class="admin-crud-muted">—</span>
									<?php endif; ?>
								</td>
								<td><?php echo (int) $data->sort_order; ?></td>
								<td class="admin-crud-languages-column">
									<div class="admin-crud-languages">
										<?php foreach ($languages as $language): ?>
											<?php
											$languageId = (int) $language->id;
											$translationExists = isset($itemTranslations[$languageId]);
											$label = $translationExists ? $itemTranslations[$languageId]->label : '';

											if (isset($language->code) && $language->code !== '') {
												$languageCode = $language->code;
											} elseif (isset($language->slug) && $language->slug !== '') {
												$languageCode = $language->slug;
											} else {
												$languageCode = substr($language->name, 0, 2);
											}

											$languageName = isset($language->native_name) && $language->native_name !== ''
												? $language->native_name
												: $language->name;

											$translationActionUrl = $this->createUrl('updateTranslation');
											?>
											<a
												class="admin-crud-action admin-crud-action--language<?php echo $translationExists ? ' is-translated' : ''; ?>"
												href="#"
												data-menu-item-id="<?php echo $menuItemId; ?>"
												data-language-id="<?php echo $languageId; ?>"
												data-language-name="<?php echo CHtml::encode($languageName); ?>"
												data-label="<?php echo CHtml::encode($label); ?>"
												data-action-url="<?php echo CHtml::encode($translationActionUrl); ?>"
												title="<?php echo CHtml::encode($translationExists ? 'Editar ' . $languageName : 'Agregar ' . $languageName); ?>"
												aria-label="<?php echo CHtml::encode($translationExists ? 'Editar ' . $languageName : 'Agregar ' . $languageName); ?>">
												<?php echo CHtml::encode(strtoupper($languageCode)); ?>
											</a>
										<?php endforeach; ?>
									</div>
								</td>
								<td class="admin-crud-actions-column">
									<div class="admin-crud-actions">
										<a
											class="admin-crud-action admin-crud-action--edit"
											href="<?php echo $this->createUrl('update', array('id' => $menuItemId)); ?>"
											title="Editar opciones del menú"
											aria-label="Editar opciones del menú">
											<i class="fas fa-pen" aria-hidden="true"></i>
											Editar
										</a>
									</div>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php else: ?>
				<div class="admin-crud-empty">
					<div class="admin-crud-empty__icon">
						<i class="fas fa-inbox" aria-hidden="true"></i>
					</div>
					<div class="admin-crud-empty__title">No hay registros</div>
					<div class="admin-crud-empty__text">No se encontraron registros para mostrar.</div>
				</div>
			<?php endif; ?>
		</div>

		<div class="admin-crud-footer">
			<div class="admin-crud-summary">
				<?php
				$pagination = $dataProvider->getPagination();
				$itemCount = $dataProvider->getItemCount();
				$totalCount = $dataProvider->getTotalItemCount();
				$start = $itemCount > 0 ? $pagination->getOffset() + 1 : 0;
				$end = $pagination->getOffset() + $itemCount;
				echo 'Mostrando ' . $start . '–' . $end . ' de ' . $totalCount . ' registros';
				?>
			</div>

			<div class="admin-crud-pagination">
				<?php
				$this->widget('CLinkPager', array(
					'pages' => $dataProvider->getPagination(),
					'header' => '',
					'firstPageLabel' => '«',
					'prevPageLabel' => '‹',
					'nextPageLabel' => '›',
					'lastPageLabel' => '»',
					'maxButtonCount' => 5,
				));
				?>
			</div>
		</div>
	</div>
</div>

<div
	id="menu-items-translation-modal"
	class="admin-crud-modal"
	aria-hidden="true"
	role="dialog"
	aria-modal="true"
	aria-labelledby="menu-items-translation-modal-title">
	<div class="admin-crud-modal__dialog">
		<form
			id="menu-items-translation-form"
			method="post"
			action="<?php echo $this->createUrl('updateTranslation'); ?>">

			<div class="admin-crud-modal__header">
				<div class="admin-crud-modal__heading">
					<div class="admin-crud-modal__icon">
						<i class="fas fa-language" aria-hidden="true"></i>
					</div>
					<div>
						<h3 id="menu-items-translation-modal-title" class="admin-crud-modal__title">Traducción</h3>
						<p class="admin-crud-modal__message">
							<span id="menu-items-translation-language"></span>
						</p>
					</div>
				</div>

				<button
					type="button"
					id="menu-items-translation-close"
					class="admin-crud-modal__close"
					title="Cerrar"
					aria-label="Cerrar">
					<i class="fas fa-times" aria-hidden="true"></i>
				</button>
			</div>

			<div class="admin-crud-modal__body">
				<input
					type="hidden"
					id="menu-items-translation-menu-item-id"
					name="menu_item_id"
					value="">

				<input
					type="hidden"
					id="menu-items-translation-language-id"
					name="language_id"
					value="">

				<label
					class="admin-crud-modal__label"
					for="menu-items-translation-label">
					Texto
				</label>

				<input
					type="text"
					id="menu-items-translation-label"
					name="label"
					class="admin-crud-modal__input"
					autocomplete="off"
					required>
			</div>

			<div class="admin-crud-modal__footer">
				<button
					type="button"
					id="menu-items-translation-cancel"
					class="admin-crud-modal__button admin-crud-modal__button--cancel">
					Cancelar
				</button>

				<button
					type="submit"
					class="admin-crud-modal__button admin-crud-modal__button--save">
					<i class="fas fa-save" aria-hidden="true"></i>
					Guardar
				</button>
			</div>
		</form>
	</div>
</div>