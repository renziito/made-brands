<?php
/* @var $this HeroSlidesController */
/* @var $model HeroSlides */

$this->breadcrumbs = array(
	'Hero Slides' => array('index'),
	'Administrar',
);

$hasActiveFilters = false;
$filterValues = isset($_GET[CHtml::modelName($model)]) ? $_GET[CHtml::modelName($model)] : array();

foreach (array('id', 'is_active') as $filterAttribute) {
	if (isset($filterValues[$filterAttribute]) && $filterValues[$filterAttribute] !== '') {
		$hasActiveFilters = true;
		break;
	}
}

$dataProvider = $model->search();

$languages = Languages::model()->findAll(array(
	'order' => 'name ASC',
));

Yii::app()->clientScript->registerCss('admin-crud-hero-slides', "

.admin-crud-page {
	width: 100%;
}

.admin-crud-header {
	display: flex;
	align-items: flex-end;
	justify-content: space-between;
	gap: 24px;
	margin-bottom: 24px;
}

.admin-crud-heading {
	min-width: 0;
}

.admin-crud-title {
	margin: 0;
	color: #111827;
	font-size: 30px;
	font-weight: 600;
	line-height: 1.2;
}

.admin-crud-description {
	margin: 7px 0 0;
	color: #6b7280;
	font-size: 14px;
	line-height: 1.5;
}

.admin-crud-actions {
	display: flex;
	align-items: center;
	gap: 8px;
	flex-shrink: 0;
}

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
	transition: background-color .15s ease, border-color .15s ease, box-shadow .15s ease, color .15s ease;
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

.admin-crud-card {
	overflow: hidden;
	background: #fff;
	border: 1px solid #e5e7eb;
	border-radius: 10px;
	box-shadow: 0 1px 2px rgba(0, 0, 0, .03);
}

.admin-crud-card__header {
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 20px;
	padding: 16px 20px;
	border-bottom: 1px solid #e5e7eb;
}

.admin-crud-card__title {
	display: flex;
	align-items: center;
	gap: 11px;
	min-width: 0;
}

.admin-crud-card__icon {
	display: flex;
	align-items: center;
	justify-content: center;
	width: 34px;
	height: 34px;
	flex-shrink: 0;
	border-radius: 7px;
	background: #f3f4f6;
	color: #374151;
	font-size: 14px;
}

.admin-crud-card__heading {
	margin: 0;
	color: #111827;
	font-size: 15px;
	font-weight: 600;
	line-height: 1.3;
}

.admin-crud-card__description {
	margin: 2px 0 0;
	color: #9ca3af;
	font-size: 12px;
	line-height: 1.4;
}

.admin-crud-filter {
	display: none;
	padding: 18px 20px;
	background: #f9fafb;
	border-bottom: 1px solid #e5e7eb;
}

.admin-crud-filter.is-visible {
	display: block;
}

.admin-crud-filter__header {
	margin-bottom: 14px;
}

.admin-crud-filter__title {
	display: flex;
	align-items: center;
	gap: 8px;
	color: #374151;
	font-size: 13px;
	font-weight: 600;
}

.admin-crud-filter__title i {
	color: #6b7280;
}

.admin-crud-filter__hint {
	margin: 4px 0 0;
	color: #9ca3af;
	font-size: 12px;
}

.admin-crud-filter__fields {
	display: grid;
	grid-template-columns: repeat(3, minmax(0, 1fr));
	gap: 14px;
}

.admin-crud-filter__label {
	display: block;
	margin-bottom: 6px;
	color: #4b5563;
	font-size: 11px;
	font-weight: 600;
}

.admin-crud-filter__input {
	display: block;
	width: 100%;
	height: 36px;
	padding: 0 10px;
	box-sizing: border-box;
	border: 1px solid #d1d5db;
	border-radius: 6px;
	outline: none;
	background: #fff;
	color: #374151;
	font-size: 13px;
}

.admin-crud-filter__input:focus {
	border-color: #9ca3af;
	box-shadow: 0 0 0 3px rgba(17, 24, 39, .06);
}

.admin-crud-filter__footer {
	display: flex;
	align-items: center;
	justify-content: flex-end;
	gap: 8px;
	margin-top: 16px;
}

.admin-crud-table-wrapper {
	width: 100%;
	overflow-x: auto;
	overflow-y: visible;
	-webkit-overflow-scrolling: touch;
}

.admin-crud-grid {
	width: 100%;
	margin: 0 !important;
	border: 0 !important;
	background: #fff;
}

.admin-crud-grid table {
	width: 100%;
	min-width: 950px;
	margin: 0 !important;
	border: 0 !important;
	border-collapse: separate !important;
	border-spacing: 0 !important;
}

.admin-crud-grid thead th {
	height: 42px;
	padding: 0 14px;
	background: #f9fafb;
	border: 0 !important;
	border-bottom: 1px solid #e5e7eb !important;
	color: #6b7280;
	font-size: 11px;
	font-weight: 700;
	letter-spacing: .04em;
	text-align: left;
	text-transform: uppercase;
	white-space: nowrap;
}

.admin-crud-grid thead th a {
	color: #6b7280;
	text-decoration: none;
}

.admin-crud-grid thead th a:hover {
	color: #111827;
}

.admin-crud-grid tbody td {
	height: 72px;
	padding: 8px 14px;
	background: #fff;
	border: 0 !important;
	border-bottom: 1px solid #f0f1f3 !important;
	color: #374151;
	font-size: 13px;
	vertical-align: middle;
}

.admin-crud-grid tbody tr:hover td {
	background: #fafafa;
}

.admin-crud-thumbnail {
	display: flex;
	align-items: center;
	justify-content: center;
	width: 96px;
	height: 54px;
	overflow: hidden;
	border: 1px solid #e5e7eb;
	border-radius: 6px;
	background: #f3f4f6;
}

.admin-crud-thumbnail img {
	display: block;
	width: 100%;
	height: 100%;
	object-fit: cover;
}

.admin-crud-thumbnail--empty {
	color: #9ca3af;
	font-size: 16px;
}

.admin-crud-languages {
	display: flex;
	align-items: center;
	flex-wrap: wrap;
	gap: 5px;
	max-width: 250px;
}

.admin-crud-language-badge {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	min-width: 28px;
	height: 24px;
	padding: 0 7px;
	box-sizing: border-box;
	border: 1px solid #e5e7eb;
	border-radius: 5px;
	background: #f9fafb;
	color: #4b5563;
	font-size: 10px;
	font-weight: 700;
	text-transform: uppercase;
}

.admin-crud-language-badge--default {
	background: #f3f4f6;
	border-color: #d1d5db;
	color: #111827;
}

.admin-crud-language-more {
	color: #9ca3af;
	font-size: 11px;
}

.admin-crud-language-button {
	display: inline-flex;
	align-items: center;
	gap: 6px;
	height: 26px;
	padding: 0 9px;
	border: 1px solid #d1d5db;
	border-radius: 5px;
	background: #fff;
	color: #4b5563 !important;
	cursor: pointer;
	font-family: inherit;
	font-size: 11px;
	font-weight: 600;
	text-decoration: none !important;
}

.admin-crud-language-button:hover {
	background: #f3f4f6;
	border-color: #9ca3af;
	color: #111827 !important;
}

.admin-crud-status {
	display: inline-flex;
	align-items: center;
	gap: 6px;
}

.admin-crud-status__dot {
	width: 7px;
	height: 7px;
	border-radius: 50%;
	background: #9ca3af;
}

.admin-crud-status--active {
	color: #166534;
}

.admin-crud-status--active .admin-crud-status__dot {
	background: #16a34a;
}

.admin-crud-status--inactive {
	color: #6b7280;
}

.admin-crud-actions-column {
	width: 108px;
	min-width: 108px;
	text-align: right !important;
	white-space: nowrap;
}

.admin-crud-action {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 30px;
	height: 30px;
	margin-left: 3px;
	border-radius: 5px;
	color: #6b7280 !important;
	font-size: 12px;
	text-decoration: none !important;
}

.admin-crud-action:hover {
	background: #f3f4f6;
	color: #111827 !important;
}

.admin-crud-action--delete:hover {
	background: #fef2f2;
	color: #dc2626 !important;
}

/* ==========================================================
   LANGUAGE MODAL
   ========================================================== */

.admin-crud-modal {
	position: fixed;
	z-index: 99999;
	top: 0;
	left: 0;
	display: none;
	align-items: center;
	justify-content: center;
	width: 100%;
	height: 100%;
	padding: 20px;
	box-sizing: border-box;
	background: rgba(17, 24, 39, .48);
}

.admin-crud-modal.is-visible {
	display: flex;
}

.admin-crud-modal__dialog {
	width: 100%;
	max-width: 500px;
	max-height: calc(100vh - 40px);
	overflow: hidden;
	background: #fff;
	border: 1px solid #e5e7eb;
	border-radius: 10px;
	box-shadow: 0 20px 50px rgba(0, 0, 0, .18);
}

.admin-crud-modal__header {
	display: flex;
	align-items: flex-start;
	justify-content: space-between;
	gap: 16px;
	padding: 20px;
	border-bottom: 1px solid #e5e7eb;
}

.admin-crud-modal__heading {
	display: flex;
	align-items: flex-start;
	gap: 12px;
}

.admin-crud-modal__icon {
	display: flex;
	align-items: center;
	justify-content: center;
	width: 38px;
	height: 38px;
	flex-shrink: 0;
	border-radius: 8px;
	background: #f3f4f6;
	color: #374151;
	font-size: 15px;
}

.admin-crud-modal__title {
	margin: 0;
	color: #111827;
	font-size: 16px;
	font-weight: 600;
	line-height: 1.4;
}

.admin-crud-modal__message {
	margin: 5px 0 0;
	color: #6b7280;
	font-size: 12px;
	line-height: 1.5;
}

.admin-crud-modal__close {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 30px;
	height: 30px;
	flex-shrink: 0;
	border: 0;
	border-radius: 5px;
	background: transparent;
	color: #9ca3af;
	cursor: pointer;
}

.admin-crud-modal__close:hover {
	background: #f3f4f6;
	color: #374151;
}

.admin-crud-modal__body {
	max-height: 420px;
	overflow-y: auto;
	padding: 8px 20px;
}

.admin-crud-language-row {
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 16px;
	padding: 13px 0;
	border-bottom: 1px solid #f0f1f3;
}

.admin-crud-language-row:last-child {
	border-bottom: 0;
}

.admin-crud-language-info {
	display: flex;
	align-items: center;
	gap: 11px;
	min-width: 0;
}

.admin-crud-language-code {
	display: flex;
	align-items: center;
	justify-content: center;
	width: 34px;
	height: 30px;
	flex-shrink: 0;
	border-radius: 6px;
	background: #f3f4f6;
	color: #374151;
	font-size: 10px;
	font-weight: 700;
	text-transform: uppercase;
}

.admin-crud-language-name {
	color: #374151;
	font-size: 13px;
	font-weight: 600;
}

.admin-crud-language-default {
	display: block;
	margin-top: 2px;
	color: #9ca3af;
	font-size: 10px;
}

.admin-crud-language-action {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	gap: 6px;
	height: 32px;
	padding: 0 10px;
	box-sizing: border-box;
	border: 1px solid #d1d5db;
	border-radius: 6px;
	background: #fff;
	color: #374151 !important;
	font-size: 11px;
	font-weight: 600;
	text-decoration: none !important;
	white-space: nowrap;
}

.admin-crud-language-action:hover {
	background: #f3f4f6;
	border-color: #9ca3af;
	color: #111827 !important;
}

.admin-crud-language-action--add {
	background: #111827;
	border-color: #111827;
	color: #fff !important;
}

.admin-crud-language-action--add:hover {
	background: #1f2937;
	border-color: #1f2937;
	color: #fff !important;
}

.admin-crud-modal__footer {
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 8px;
	padding: 14px 20px;
	border-top: 1px solid #e5e7eb;
	background: #f9fafb;
}

.admin-crud-modal__button {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	gap: 7px;
	height: 36px;
	padding: 0 13px;
	border: 1px solid #d1d5db;
	border-radius: 6px;
	background: #fff;
	color: #374151 !important;
	cursor: pointer;
	font-family: inherit;
	font-size: 12px;
	font-weight: 600;
	text-decoration: none !important;
}

.admin-crud-modal__button:hover {
	background: #f3f4f6;
}

.admin-crud-empty {
	padding: 64px 20px !important;
	text-align: center !important;
}

.admin-crud-empty__icon {
	display: flex;
	align-items: center;
	justify-content: center;
	width: 46px;
	height: 46px;
	margin: 0 auto 12px;
	border-radius: 50%;
	background: #f3f4f6;
	color: #9ca3af;
	font-size: 18px;
}

.admin-crud-empty__title {
	margin-bottom: 4px;
	color: #374151;
	font-size: 14px;
	font-weight: 600;
}

.admin-crud-empty__text {
	color: #9ca3af;
	font-size: 12px;
}

.admin-crud-footer {
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 16px;
	padding: 12px 20px;
	border-top: 1px solid #e5e7eb;
	background: #fff;
}

.admin-crud-summary {
	color: #6b7280;
	font-size: 12px;
}

.admin-crud-pagination {
	margin: 0;
}

.admin-crud-pagination ul.yiiPager {
	display: flex;
	align-items: center;
	gap: 4px;
	margin: 0;
	padding: 0;
	list-style: none;
}

.admin-crud-pagination ul.yiiPager li {
	display: inline-flex;
	margin: 0;
	padding: 0;
}

.admin-crud-pagination ul.yiiPager li a,
.admin-crud-pagination ul.yiiPager li span {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	min-width: 30px;
	height: 30px;
	padding: 0 8px;
	box-sizing: border-box;
	border: 1px solid #e5e7eb;
	border-radius: 5px;
	background: #fff;
	color: #4b5563;
	font-size: 12px;
	text-decoration: none;
}

.admin-crud-pagination ul.yiiPager li a:hover {
	background: #f9fafb;
	color: #111827;
}

.admin-crud-pagination ul.yiiPager li.selected a,
.admin-crud-pagination ul.yiiPager li.selected span {
	background: #111827;
	border-color: #111827;
	color: #fff;
}

@media (max-width: 900px) {
	.admin-crud-filter__fields {
		grid-template-columns: repeat(2, minmax(0, 1fr));
	}
}

@media (max-width: 768px) {
	.admin-crud-header {
		align-items: stretch;
		flex-direction: column;
	}

	.admin-crud-actions {
		width: 100%;
	}

	.admin-crud-button {
		flex: 1;
	}

	.admin-crud-filter__fields {
		grid-template-columns: 1fr;
	}

	.admin-crud-footer {
		align-items: flex-start;
		flex-direction: column;
	}

	.admin-crud-modal {
		padding: 16px;
	}

	.admin-crud-modal__dialog {
		max-height: calc(100vh - 32px);
	}
}

");

Yii::app()->clientScript->registerScript(
	'crud-index-hero-slides',
	"
$(function() {

	$('#hero-slides-filter-toggle').on('click', function(e) {
		e.preventDefault();

		var button = $(this);
		var panel = $('#hero-slides-filter-panel');
		var icon = button.find('.filter-toggle-icon');

		panel.toggleClass('is-visible');
		button.toggleClass('is-active');
		icon.toggleClass('fa-chevron-down fa-chevron-up');

		return false;
	});

	$(document).on('click', '.js-hero-slides-language', function(e) {
		e.preventDefault();
		e.stopPropagation();

		var slideId = $(this).attr('data-slide-id');

		if (!slideId) {
			return false;
		}

		var modal = $('#hero-slides-languages-modal-' + slideId);

		if (!modal.length) {
			return false;
		}

		$('.hero-slides-language-modal.is-visible')
			.removeClass('is-visible')
			.attr('aria-hidden', 'true');

		modal
			.addClass('is-visible')
			.attr('aria-hidden', 'false');

		$('body').css('overflow', 'hidden');

		return false;
	});

	$(document).on('click', '.hero-slides-language-modal-close', function(e) {
		e.preventDefault();
		e.stopPropagation();

		var modal = $(this).closest('.hero-slides-language-modal');

		modal
			.removeClass('is-visible')
			.attr('aria-hidden', 'true');

		$('body').css('overflow', '');

		return false;
	});

	$(document).on('click', '.hero-slides-language-modal', function(e) {
		if (e.target === this) {
			$(this)
				.removeClass('is-visible')
				.attr('aria-hidden', 'true');

			$('body').css('overflow', '');
		}
	});

	$(document).on('keydown', function(e) {
		if (e.key === 'Escape') {
			$('.hero-slides-language-modal.is-visible')
				.removeClass('is-visible')
				.attr('aria-hidden', 'true');

			$('body').css('overflow', '');
		}
	});

	var crud_9d99837470DeleteUrl = null;

	function openHeroSlidesDeleteModal(url) {
		crud_9d99837470DeleteUrl = url;

		$('#hero-slides-delete-modal')
			.addClass('is-visible')
			.attr('aria-hidden', 'false');

		$('body').css('overflow', 'hidden');
	}

	function closeHeroSlidesDeleteModal() {
		crud_9d99837470DeleteUrl = null;

		$('#hero-slides-delete-modal')
			.removeClass('is-visible')
			.attr('aria-hidden', 'true');

		$('body').css('overflow', '');
	}

	$(document).on('click', '#hero-slides-grid .admin-crud-action--delete', function(e) {
		e.preventDefault();
		e.stopImmediatePropagation();

		var url = $(this).attr('href');

		if (!url) {
			return false;
		}

		openHeroSlidesDeleteModal(url);

		return false;
	});

	$('#hero-slides-delete-cancel').on('click', function(e) {
		e.preventDefault();
		closeHeroSlidesDeleteModal();
		return false;
	});

	$('#hero-slides-delete-confirm').on('click', function(e) {
		e.preventDefault();

		if (!crud_9d99837470DeleteUrl) {
			closeHeroSlidesDeleteModal();
			return false;
		}

		window.location.href = crud_9d99837470DeleteUrl;

		return false;
	});

	$('#hero-slides-delete-modal').on('click', function(e) {
		if (e.target === this) {
			closeHeroSlidesDeleteModal();
		}
	});

});
"
);
?>

<div class="admin-crud-page">

	<div class="admin-crud-header">

		<div class="admin-crud-heading">

			<h1 class="admin-crud-title">
				Hero Slides
			</h1>

			<p class="admin-crud-description">
				Gestiona y administra hero slides.
			</p>

		</div>

		<div class="admin-crud-actions">

			<a
				id="hero-slides-filter-toggle"
				class="admin-crud-button admin-crud-button--secondary"
				href="#"
				title="Filtrar"
				aria-label="Filtrar">
				<i class="fas fa-filter" aria-hidden="true"></i>
				<span>Filtrar</span>
				<i class="fas fa-chevron-down filter-toggle-icon" aria-hidden="true"></i>
			</a>

			<a
				class="admin-crud-button admin-crud-button--primary"
				href="<?php echo $this->createUrl('create'); ?>"
				title="Nuevo hero slide"
				aria-label="Nuevo hero slide">
				<i class="fas fa-plus" aria-hidden="true"></i>
				<span>Nuevo hero slide</span>
			</a>

		</div>

	</div>

	<div
		id="hero-slides-filter-panel"
		class="admin-crud-filter<?php echo $hasActiveFilters ? ' is-visible' : ''; ?>">

		<div class="admin-crud-filter__header">

			<div class="admin-crud-filter__title">
				<i class="fas fa-sliders-h"></i>
				Filtrar registros
			</div>

			<p class="admin-crud-filter__hint">
				Completa uno o varios campos para filtrar los resultados.
			</p>

		</div>

		<form
			method="get"
			action="<?php echo $this->createUrl('index'); ?>">

			<div class="admin-crud-filter__fields">

				<div>

					<label
						class="admin-crud-filter__label"
						for="hero-slides-filter-id">
						Id
					</label>

					<?php echo CHtml::activeTextField(
						$model,
						'id',
						array(
							'id' => 'hero-slides-filter-id',
							'class' => 'admin-crud-filter__input',
							'autocomplete' => 'off',
						)
					); ?>

				</div>

				<div>

					<label
						class="admin-crud-filter__label"
						for="hero-slides-filter-is_active">
						Is Active
					</label>

					<?php echo CHtml::activeTextField(
						$model,
						'is_active',
						array(
							'id' => 'hero-slides-filter-is_active',
							'class' => 'admin-crud-filter__input',
							'autocomplete' => 'off',
						)
					); ?>

				</div>

			</div>

			<div class="admin-crud-filter__footer">

				<a
					class="admin-crud-button admin-crud-button--secondary"
					href="<?php echo $this->createUrl('index'); ?>">
					<i class="fas fa-undo"></i>
					Limpiar
				</a>

				<button
					type="submit"
					class="admin-crud-button admin-crud-button--primary">
					<i class="fas fa-search"></i>
					Buscar
				</button>

			</div>

		</form>

	</div>

	<div class="admin-crud-card">

		<div class="admin-crud-card__header">

			<div class="admin-crud-card__title">

				<div class="admin-crud-card__icon">
					<i class="fas fa-list"></i>
				</div>

				<div>

					<h2 class="admin-crud-card__heading">
						Listado
					</h2>

					<p class="admin-crud-card__description">
						Registros disponibles en el sistema.
					</p>

				</div>

			</div>

		</div>

		<div class="admin-crud-table-wrapper">

			<?php $this->widget('zii.widgets.grid.CGridView', array(
				'id' => 'hero-slides-grid',
				'dataProvider' => $dataProvider,
				'filter' => null,
				'ajaxUpdate' => false,
				'enableSorting' => true,
				'enablePagination' => true,
				'itemsCssClass' => 'admin-crud-table',
				'htmlOptions' => array(
					'class' => 'admin-crud-grid',
				),
				'template' => '{items}',
				'emptyText' => '
				<div class="admin-crud-empty">
					<div class="admin-crud-empty__icon">
						<i class="fas fa-inbox"></i>
					</div>
					<div class="admin-crud-empty__title">No hay registros</div>
					<div class="admin-crud-empty__text">No se encontraron registros para mostrar.</div>
				</div>
			',
				'columns' => array(

					'id',

					array(
						'name' => 'image',
						'header' => 'Imagen',
						'type' => 'raw',
						'value' => function ($data) {

							if (!$data->image) {
								return '
								<div class="admin-crud-thumbnail admin-crud-thumbnail--empty">
									<i class="fas fa-image"></i>
								</div>
							';
							}

							$imageUrl =
								Yii::app()->baseUrl .
								'/images/hero-slides/' .
								$data->image;

							return '
							<div class="admin-crud-thumbnail">
								<img
									src="' . CHtml::encode($imageUrl) . '"
									alt="Hero Slide #' . (int) $data->id . '"
									loading="lazy"
								>
							</div>
						';
						},
						'htmlOptions' => array(
							'style' => 'width:120px;',
						),
					),

					array(
						'name' => 'languages',
						'header' => 'Idiomas',
						'type' => 'raw',
						'value' => function ($data) use ($languages) {

							$translations = HeroSlideTranslations::model()->findAllByAttributes(
								array(
									'hero_slide_id' => $data->id,
								)
							);

							$translationLanguages = array();

							foreach ($translations as $translation) {
								$translationLanguages[(int) $translation->language_id] = true;
							}

							$badges = '';
							$displayed = 0;
							$total = count($translationLanguages);

							foreach ($languages as $language) {

								if (!isset($translationLanguages[(int) $language->id])) {
									continue;
								}

								if ($displayed >= 4) {
									break;
								}

								if (isset($language->code) && $language->code) {
									$code = $language->code;
								} elseif (isset($language->slug) && $language->slug) {
									$code = $language->slug;
								} else {
									$code = substr($language->name, 0, 2);
								}

								$isSpanish = strtolower($code) === 'es';

								$badges .=
									'<span class="admin-crud-language-badge' .
									($isSpanish ? ' admin-crud-language-badge--default' : '') .
									'">' .
									CHtml::encode($code) .
									'</span>';

								$displayed++;
							}

							if ($total > 4) {
								$badges .=
									'<span class="admin-crud-language-more">+' .
									($total - 4) .
									'</span>';
							}

							return
								'<div class="admin-crud-languages">' .
								$badges .
								'<button
								type="button"
								class="admin-crud-language-button js-hero-slides-language"
								data-slide-id="' . (int) $data->id . '"
								title="Administrar idiomas"
								aria-label="Administrar idiomas"
							>
								<i class="fas fa-language"></i>
								Idiomas
							</button>' .
								'</div>';
						},
						'htmlOptions' => array(
							'style' => 'min-width:250px;',
						),
					),

					'alignment',

					'button_url',

					'sort_order',

					array(
						'name' => 'is_active',
						'header' => 'Estado',
						'type' => 'raw',
						'value' => function ($data) {

							if ($data->is_active) {
								return '
								<span class="admin-crud-status admin-crud-status--active">
									<span class="admin-crud-status__dot"></span>
									Activo
								</span>
							';
							}

							return '
							<span class="admin-crud-status admin-crud-status--inactive">
								<span class="admin-crud-status__dot"></span>
								Inactivo
							</span>
						';
						},
					),

					array(
						'header' => 'Acciones',
						'type' => 'raw',
						'value' => function ($data) {

							$updateUrl = $this->createUrl(
								'update',
								array(
									'id' => $data->id,
								)
							);

							$deleteUrl = $this->createUrl(
								'delete',
								array(
									'id' => $data->id,
								)
							);

							return
								'<a
				href="' . CHtml::encode($updateUrl) . '"
				class="admin-crud-action"
				title="Editar"
				aria-label="Editar"
			>
				<i class="fas fa-pen" aria-hidden="true"></i>
			</a>' .

								'<a
				href="' . CHtml::encode($deleteUrl) . '"
				class="admin-crud-action js-hero-slides-delete admin-crud-action admin-crud-action--delete"
				title="Eliminar"
				aria-label="Eliminar"
			>
				<i class="fas fa-trash-alt" aria-hidden="true"></i>
			</a>';
						},
						'headerHtmlOptions' => array(
							'class' => 'admin-crud-actions-column',
						),
						'htmlOptions' => array(
							'class' => 'admin-crud-actions-column',
						),
					),

				),
			)); ?>


		</div>

		<div class="admin-crud-footer">

			<div class="admin-crud-summary">

				<?php

				$pagination = $dataProvider->getPagination();
				$itemCount = $dataProvider->getItemCount();
				$totalCount = $dataProvider->getTotalItemCount();

				$start = $itemCount > 0
					? $pagination->getOffset() + 1
					: 0;

				$end = $pagination->getOffset() + $itemCount;

				echo
				'Mostrando ' .
					$start .
					'–' .
					$end .
					' de ' .
					$totalCount .
					' registros';

				?>

			</div>

			<div class="admin-crud-pagination">

				<?php $this->widget('CLinkPager', array(
					'pages' => $dataProvider->getPagination(),
					'header' => '',
					'firstPageLabel' => '«',
					'prevPageLabel' => '‹',
					'nextPageLabel' => '›',
					'lastPageLabel' => '»',
					'maxButtonCount' => 5,
				)); ?>

			</div>

		</div>

	</div>
	```

</div>

<?php foreach ($dataProvider->getData() as $slide): ?>
	<?php

	$slideTranslations = HeroSlideTranslations::model()->findAllByAttributes(
		array(
			'hero_slide_id' => $slide->id,
		)
	);

	$availableTranslations = array();

	foreach ($slideTranslations as $slideTranslation) {
		$availableTranslations[(int) $slideTranslation->language_id] = $slideTranslation;
	}

	?>

	<div
		id="hero-slides-languages-modal-<?php echo (int) $slide->id; ?>"
		class="admin-crud-modal hero-slides-language-modal"
		data-slide-id="<?php echo (int) $slide->id; ?>"
		aria-hidden="true"
		role="dialog"
		aria-modal="true">

		<div class="admin-crud-modal__dialog">

			<div class="admin-crud-modal__header">

				<div class="admin-crud-modal__heading">

					<div class="admin-crud-modal__icon">
						<i class="fas fa-language"></i>
					</div>

					<div>

						<h3 class="admin-crud-modal__title">
							Idiomas del Hero Slide
						</h3>

						<p class="admin-crud-modal__message">
							Administra las traducciones disponibles para este slide.
						</p>

					</div>

				</div>

				<button
					type="button"
					class="admin-crud-modal__close hero-slides-language-modal-close"
					title="Cerrar"
					aria-label="Cerrar">
					<i class="fas fa-times"></i>
				</button>

			</div>

			<div class="admin-crud-modal__body">

				<?php foreach ($languages as $language): ?>

					<?php

					$languageId = (int) $language->id;
					$hasTranslation = isset($availableTranslations[$languageId]);

					if (isset($language->code) && $language->code) {
						$code = $language->code;
					} elseif (isset($language->slug) && $language->slug) {
						$code = $language->slug;
					} else {
						$code = substr($language->name, 0, 2);
					}

					$isSpanish = strtolower($code) === 'es';

					?>

					<div class="admin-crud-language-row">

						<div class="admin-crud-language-info">

							<div class="admin-crud-language-code">
								<?php echo CHtml::encode($code); ?>
							</div>

							<div>

								<div class="admin-crud-language-name">
									<?php echo CHtml::encode($language->name); ?>
								</div>

								<?php if ($isSpanish): ?>

									<span class="admin-crud-language-default">
										Idioma predeterminado
									</span>

								<?php endif; ?>

							</div>

						</div>

						<?php if ($hasTranslation): ?>

							<a
								class="admin-crud-language-action"
								href="<?php echo $this->createUrl(
											'updateTranslation',
											array(
												'id' => $slide->id,
												'language_id' => $languageId,
											)
										); ?>">
								<i class="fas fa-pen"></i>
								Editar
							</a>

						<?php else: ?>

							<a
								class="admin-crud-language-action admin-crud-language-action--add"
								href="<?php echo $this->createUrl(
											'createTranslation',
											array(
												'id' => $slide->id,
												'language_id' => $languageId,
											)
										); ?>">
								<i class="fas fa-plus"></i>
								Agregar
							</a>

						<?php endif; ?>

					</div>

				<?php endforeach; ?>

			</div>

			<div class="admin-crud-modal__footer">

				<span style="color:#9ca3af;font-size:11px;">
					Slide #<?php echo (int) $slide->id; ?>
				</span>

				<button
					type="button"
					class="admin-crud-modal__button hero-slides-language-modal-close">
					<i class="fas fa-times"></i>
					Cerrar
				</button>

			</div>

		</div>

	</div>

<?php endforeach; ?>

<div
	id="hero-slides-delete-modal"
	class="admin-crud-modal"
	aria-hidden="true"
	role="dialog"
	aria-modal="true">

	<div class="admin-crud-modal__dialog">

		<div class="admin-crud-modal__header">

			<div class="admin-crud-modal__heading">

				<div class="admin-crud-modal__icon">
					<i class="fas fa-exclamation-triangle"></i>
				</div>

				<div>

					<h3 class="admin-crud-modal__title">
						Eliminar registro
					</h3>

					<p class="admin-crud-modal__message">
						¿Está seguro de que desea eliminar este registro?
						Esta acción no se puede deshacer.
					</p>

				</div>

			</div>

		</div>

		<div class="admin-crud-modal__footer">

			<button
				type="button"
				id="hero-slides-delete-cancel"
				class="admin-crud-modal__button">
				<i class="fas fa-times"></i>
				Cancelar
			</button>

			<button
				type="button"
				id="hero-slides-delete-confirm"
				class="admin-crud-modal__button"
				style="background:#dc2626;border-color:#dc2626;color:#fff !important;">
				<i class="fas fa-trash-alt"></i>
				Eliminar
			</button>

		</div>

	</div>

</div>