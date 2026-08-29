<?php

/* @var $this IntroController */
/* @var $model AboutSections */
/* @var $languages Languages[] */
/* @var $translations AboutSectionTranslations[] */
/* @var $stats AboutSectionStats[] */
/* @var $statTranslations AboutSectionStatTranslations[] */

$this->pageTitle = 'Intro / Nosotros';

$isNewRecord = $model->isNewRecord;

$formAction = $this->createUrl('update');


$this->breadcrumbs = array(
    'About' => array('index'),
    'Administrar',
);


?>

<div class="container">
    <div class="page-header">

        <div>
            <span class="section-label">CONTENIDO DEL SITIO</span>

            <h1>About / Nosotros</h1>

            <p class="text-muted">
                Administra el contenido principal de la sección Nosotros.
            </p>
        </div>

    </div>


    <?php if (Yii::app()->user->hasFlash('success')): ?>

        <div class="alert alert-success">
            <?php echo CHtml::encode(Yii::app()->user->getFlash('success')); ?>
        </div>

    <?php endif; ?>


    <?php if (Yii::app()->user->hasFlash('error')): ?>

        <div class="alert alert-danger">
            <?php echo CHtml::encode(Yii::app()->user->getFlash('error')); ?>
        </div>

    <?php endif; ?>


    <form
        id="intro-form"
        method="post"
        action="<?php echo CHtml::encode($formAction); ?>"
        enctype="multipart/form-data"
        autocomplete="off">

        <?php echo CHtml::hiddenField(
            'YII_CSRF_TOKEN',
            Yii::app()->request->csrfToken
        ); ?>


        <!-- =========================================================
	     SECCIÓN NOSOTROS
	========================================================= -->

        <div class="admin-card">

            <div class="admin-card-header">

                <div>
                    <h2>Sección Nosotros</h2>

                    <p>
                        Configura la imagen y los datos generales de esta sección.
                    </p>
                </div>

            </div>


            <div class="admin-card-body">

                <div class="form-row">

                    <div class="form-group  form-group-full">

                        <label for="about-image">
                            Imagen
                        </label>

                        <div class="image-upload-wrapper">

                            <?php if (!$model->isNewRecord && !empty($model->image)): ?>

                                <div class="current-image">

                                    <img
                                        src="<?= Yii::app()->getBaseUrl() . CHtml::encode($model->image); ?>"
                                        alt="Imagen actual">

                                    <div class="current-image-info">
                                        <span>Imagen actual</span>
                                    </div>

                                </div>

                            <?php endif; ?>


                            <input
                                type="file"
                                id="about-image"
                                name="AboutSections[image]"
                                class="form-control-file"
                                accept="image/*">

                        </div>

                        <small class="form-help">
                            Selecciona una imagen. Se recomienda utilizar JPG, PNG o WEBP.
                        </small>

                    </div>

                </div>

            </div>

        </div>


        <!-- =========================================================
	     TRADUCCIONES
	========================================================= -->

        <div class="admin-card">

            <div class="admin-card-header">

                <div>
                    <h2>Traducciones</h2>

                    <p>
                        Ingresa el contenido de Nosotros para cada idioma.
                    </p>
                </div>

            </div>


            <div class="admin-card-body">

                <?php if (!empty($languages)): ?>

                    <div class="language-tabs">

                        <?php foreach ($languages as $languageIndex => $language): ?>

                            <?php

                            $languageId = (int) $language->id;

                            $languageTranslation = isset($translations[$languageId])
                                ? $translations[$languageId]
                                : null;

                            $languageCode = '';

                            if (isset($language->code)) {
                                $languageCode = $language->code;
                            } elseif (isset($language->language_code)) {
                                $languageCode = $language->language_code;
                            }

                            $languageName = '';

                            if (isset($language->name)) {
                                $languageName = $language->name;
                            } elseif (isset($language->language)) {
                                $languageName = $language->language;
                            } else {
                                $languageName = 'Idioma #' . $languageId;
                            }

                            ?>

                            <button
                                type="button"
                                class="language-tab <?php echo $languageIndex === 0 ? 'is-active' : ''; ?>"
                                data-language-tab="<?php echo $languageId; ?>">

                                <?php echo CHtml::encode($languageName); ?>

                                <?php if ($languageCode !== ''): ?>

                                    <span>
                                        <?php echo CHtml::encode(strtoupper($languageCode)); ?>
                                    </span>

                                <?php endif; ?>

                            </button>

                        <?php endforeach; ?>

                    </div>


                    <div class="language-panels">

                        <?php foreach ($languages as $languageIndex => $language): ?>

                            <?php

                            $languageId = (int) $language->id;

                            $translation = isset($translations[$languageId])
                                ? $translations[$languageId]
                                : null;

                            $eyebrow = $translation
                                ? $translation->eyebrow
                                : '';

                            $eyebrowSize = $translation
                                ? $translation->eyebrow_size
                                : '';

                            $title = $translation
                                ? $translation->title
                                : '';

                            $titleSize = $translation
                                ? $translation->title_size
                                : '';

                            $text = $translation
                                ? $translation->text
                                : '';

                            $textSize = $translation
                                ? $translation->text_size
                                : '';

                            $secondaryText = $translation
                                ? $translation->secondary_text
                                : '';

                            $secondaryTextSize = $translation
                                ? $translation->secondary_text_size
                                : '';

                            ?>

                            <div
                                class="language-panel <?php echo $languageIndex === 0 ? 'is-active' : ''; ?>"
                                data-language-panel="<?php echo $languageId; ?>">

                                <div class="form-grid" style="grid-template-columns: repeat(4, minmax(0, 1fr));">

                                    <div class="form-group form-group-full">

                                        <label>
                                            Eyebrow
                                        </label>

                                        <input
                                            type="text"
                                            name="translations[<?php echo $languageId; ?>][eyebrow]"
                                            class="form-control"
                                            value="<?php echo CHtml::encode($eyebrow); ?>">

                                    </div>





                                    <div class="form-group form-group-full">

                                        <label>
                                            Título
                                        </label>

                                        <textarea
                                            name="translations[<?php echo $languageId; ?>][title]"
                                            class="form-control form-control-title"
                                            rows="3"><?php echo CHtml::encode($title); ?></textarea>

                                    </div>

                                    <div class="form-group form-group-full">

                                        <label>
                                            Texto
                                        </label>

                                        <textarea
                                            name="translations[<?php echo $languageId; ?>][text]"
                                            class="form-control"
                                            rows="5"><?php echo CHtml::encode($text); ?></textarea>

                                    </div>



                                    <div class="form-group form-group-full">

                                        <label>
                                            Texto secundario
                                        </label>

                                        <textarea
                                            name="translations[<?php echo $languageId; ?>][secondary_text]"
                                            class="form-control"
                                            rows="4"><?php echo CHtml::encode($secondaryText); ?></textarea>

                                    </div>

                                    <div class="form-group form-group-small">

                                        <label>
                                            Tamaño del eyebrow
                                        </label>

                                        <input
                                            type="text"
                                            name="translations[<?php echo $languageId; ?>][eyebrow_size]"
                                            class="form-control"
                                            value="<?php echo CHtml::encode($eyebrowSize); ?>"
                                            placeholder="Ej. 14px">

                                    </div>


                                    <div class="form-group form-group-small">

                                        <label>
                                            Tamaño del título
                                        </label>

                                        <input
                                            type="text"
                                            name="translations[<?php echo $languageId; ?>][title_size]"
                                            class="form-control"
                                            value="<?php echo CHtml::encode($titleSize); ?>"
                                            placeholder="Ej. 48px">

                                    </div>


                                    <div class="form-group form-group-small">

                                        <label>
                                            Tamaño del texto
                                        </label>

                                        <input
                                            type="text"
                                            name="translations[<?php echo $languageId; ?>][text_size]"
                                            class="form-control"
                                            value="<?php echo CHtml::encode($textSize); ?>"
                                            placeholder="Ej. 18px">

                                    </div>

                                    <div class="form-group form-group-small">

                                        <label>
                                            Tamaño del texto secundario
                                        </label>

                                        <input
                                            type="text"
                                            name="translations[<?php echo $languageId; ?>][secondary_text_size]"
                                            class="form-control"
                                            value="<?php echo CHtml::encode($secondaryTextSize); ?>"
                                            placeholder="Ej. 16px">

                                    </div>



                                </div>

                            </div>

                        <?php endforeach; ?>

                    </div>

                <?php else: ?>

                    <div class="empty-state">
                        <p>No hay idiomas configurados.</p>
                    </div>

                <?php endif; ?>

            </div>

        </div>


        <!-- =========================================================
	     ESTADÍSTICAS
	========================================================= -->

        <div class="admin-card">

            <div class="admin-card-header">

                <div>
                    <h2>Estadísticas</h2>

                    <p>
                        Agrega las estadísticas que aparecerán en la sección Nosotros.
                    </p>
                </div>


                <button
                    type="button"
                    class="btn btn-secondary"
                    id="add-stat">
                    <i class="fa fa-plus"></i>
                    Agregar estadística
                </button>

            </div>


            <div class="admin-card-body">

                <div id="stats-container">

                    <?php if (!empty($stats)): ?>

                        <?php foreach ($stats as $statIndex => $stat): ?>

                            <?php
                            $statId = (int) $stat->id;
                            ?>

                            <div
                                class="stat-item"
                                data-stat-index="<?php echo $statIndex; ?>">

                                <div class="stat-item-header">

                                    <div>

                                        <span class="stat-number">
                                            <?php echo $statIndex + 1; ?>
                                        </span>

                                        <strong>
                                            Estadística
                                        </strong>

                                    </div>


                                    <button
                                        type="button"
                                        class="btn btn-danger btn-remove-stat">
                                        <i class="fa fa-trash"></i>
                                        Quitar
                                    </button>

                                </div>


                                <div class="stat-item-settings">

                                    <div class="form-group form-group-small">

                                        <label>
                                            Orden
                                        </label>

                                        <input
                                            type="number"
                                            name="stats[<?php echo $statIndex; ?>][sort_order]"
                                            class="form-control stat-sort-order"
                                            value="<?php echo (int) $stat->sort_order; ?>">

                                    </div>


                                    <div class="form-group form-group-small">

                                        <label>
                                            Estado
                                        </label>

                                        <select
                                            name="stats[<?php echo $statIndex; ?>][is_active]"
                                            class="form-control">

                                            <option
                                                value="1"
                                                <?php echo (int) $stat->is_active === 1 ? 'selected' : ''; ?>>
                                                Activo
                                            </option>

                                            <option
                                                value="0"
                                                <?php echo (int) $stat->is_active === 0 ? 'selected' : ''; ?>>
                                                Inactivo
                                            </option>

                                        </select>

                                    </div>

                                </div>


                                <?php if (!empty($languages)): ?>

                                    <div class="stat-language-tabs">

                                        <?php foreach ($languages as $languageIndex => $language): ?>

                                            <?php

                                            $languageId = (int) $language->id;

                                            $statTranslation =
                                                isset($statTranslations[$statId][$languageId])
                                                ? $statTranslations[$statId][$languageId]
                                                : null;

                                            $languageCode = '';

                                            if (isset($language->code)) {
                                                $languageCode = $language->code;
                                            } elseif (isset($language->language_code)) {
                                                $languageCode = $language->language_code;
                                            }

                                            $languageName = '';

                                            if (isset($language->name)) {
                                                $languageName = $language->name;
                                            } elseif (isset($language->language)) {
                                                $languageName = $language->language;
                                            } else {
                                                $languageName = 'Idioma #' . $languageId;
                                            }

                                            ?>

                                            <button
                                                type="button"
                                                class="stat-language-tab <?php echo $languageIndex === 0 ? 'is-active' : ''; ?>"
                                                data-stat-language-tab="<?php echo $statIndex; ?>-<?php echo $languageId; ?>">

                                                <?php echo CHtml::encode($languageName); ?>

                                                <?php if ($languageCode !== ''): ?>

                                                    <span>
                                                        <?php echo CHtml::encode(strtoupper($languageCode)); ?>
                                                    </span>

                                                <?php endif; ?>

                                            </button>

                                        <?php endforeach; ?>

                                    </div>


                                    <div class="stat-language-panels">

                                        <?php foreach ($languages as $languageIndex => $language): ?>

                                            <?php

                                            $languageId = (int) $language->id;

                                            $statTranslation =
                                                isset($statTranslations[$statId][$languageId])
                                                ? $statTranslations[$statId][$languageId]
                                                : null;

                                            $value = $statTranslation
                                                ? $statTranslation->value
                                                : '';

                                            $valueSize = $statTranslation
                                                ? $statTranslation->value_size
                                                : '';

                                            $label = $statTranslation
                                                ? $statTranslation->label
                                                : '';

                                            $labelSize = $statTranslation
                                                ? $statTranslation->label_size
                                                : '';

                                            ?>

                                            <div
                                                class="stat-language-panel <?php echo $languageIndex === 0 ? 'is-active' : ''; ?>"
                                                data-stat-language-panel="<?php echo $statIndex; ?>-<?php echo $languageId; ?>">

                                                <div class="form-grid">

                                                    <div class="form-group">

                                                        <label>
                                                            Valor
                                                        </label>

                                                        <input
                                                            type="text"
                                                            name="stats[<?php echo $statIndex; ?>][translations][<?php echo $languageId; ?>][value]"
                                                            class="form-control"
                                                            value="<?php echo CHtml::encode($value); ?>">

                                                    </div>


                                                    <div class="form-group form-group-small">

                                                        <label>
                                                            Tamaño del valor
                                                        </label>

                                                        <input
                                                            type="text"
                                                            name="stats[<?php echo $statIndex; ?>][translations][<?php echo $languageId; ?>][value_size]"
                                                            class="form-control"
                                                            value="<?php echo CHtml::encode($valueSize); ?>"
                                                            placeholder="Ej. 32px">

                                                    </div>


                                                    <div class="form-group">

                                                        <label>
                                                            Etiqueta
                                                        </label>

                                                        <input
                                                            type="text"
                                                            name="stats[<?php echo $statIndex; ?>][translations][<?php echo $languageId; ?>][label]"
                                                            class="form-control"
                                                            value="<?php echo CHtml::encode($label); ?>">

                                                    </div>


                                                    <div class="form-group form-group-small">

                                                        <label>
                                                            Tamaño de la etiqueta
                                                        </label>

                                                        <input
                                                            type="text"
                                                            name="stats[<?php echo $statIndex; ?>][translations][<?php echo $languageId; ?>][label_size]"
                                                            class="form-control"
                                                            value="<?php echo CHtml::encode($labelSize); ?>"
                                                            placeholder="Ej. 16px">

                                                    </div>

                                                </div>

                                            </div>

                                        <?php endforeach; ?>

                                    </div>

                                <?php endif; ?>

                            </div>

                        <?php endforeach; ?>

                    <?php endif; ?>

                </div>


                <div
                    id="stats-empty"
                    class="empty-state"
                    <?php echo !empty($stats) ? 'style="display:none;"' : ''; ?>>

                    <p>
                        Aún no has agregado ninguna estadística.
                    </p>

                    <button
                        type="button"
                        class="btn btn-secondary"
                        id="add-stat-empty">
                        <i class="fa fa-plus"></i>
                        Agregar primera estadística
                    </button>

                </div>

            </div>

        </div>


        <!-- =========================================================
	     GUARDAR
	========================================================= -->

        <div class="form-actions">

            <button
                type="submit"
                class="btn btn-primary"
                id="save-intro">
                <i class="fa fa-save"></i>

                <?php echo $isNewRecord
                    ? 'Crear sección'
                    : 'Guardar cambios';
                ?>

            </button>

        </div>

    </form>
    ```

</div>

<!-- =============================================================
     PLANTILLA PARA NUEVA ESTADÍSTICA
============================================================= -->

<script type="text/template" id="stat-template">

    <div
		class="stat-item"
		data-stat-index="__INDEX__"
	>

		<div class="stat-item-header">

			<div>

				<span class="stat-number">
					__NUMBER__
				</span>

				<strong>
					Estadística
				</strong>

			</div>


			<button
				type="button"
				class="btn btn-danger btn-remove-stat"
			>
				<i class="fa fa-trash"></i>
				Quitar
			</button>

		</div>


		<div class="stat-item-settings">

			<div class="form-group form-group-small">

				<label>
					Orden
				</label>

				<input
					type="number"
					name="stats[__INDEX__][sort_order]"
					class="form-control stat-sort-order"
					value="__INDEX__"
				>

			</div>


			<div class="form-group form-group-small">

				<label>
					Estado
				</label>

				<select
					name="stats[__INDEX__][is_active]"
					class="form-control"
				>

					<option value="1" selected>
						Activo
					</option>

					<option value="0">
						Inactivo
					</option>

				</select>

			</div>

		</div>


		<?php if (!empty($languages)): ?>

			<div class="stat-language-tabs">

				<?php foreach ($languages as $languageIndex => $language): ?>

					<?php

                    $languageId = (int) $language->id;

                    $languageCode = '';

                    if (isset($language->code)) {
                        $languageCode = $language->code;
                    } elseif (isset($language->language_code)) {
                        $languageCode = $language->language_code;
                    }

                    $languageName = '';

                    if (isset($language->name)) {
                        $languageName = $language->name;
                    } elseif (isset($language->language)) {
                        $languageName = $language->language;
                    } else {
                        $languageName = 'Idioma #' . $languageId;
                    }

                    ?>

					<button
						type="button"
						class="stat-language-tab <?php echo $languageIndex === 0 ? 'is-active' : ''; ?>"
						data-stat-language-tab="__INDEX__-<?php echo $languageId; ?>"
					>

						<?php echo CHtml::encode($languageName); ?>

						<?php if ($languageCode !== ''): ?>

							<span>
								<?php echo CHtml::encode(strtoupper($languageCode)); ?>
							</span>

						<?php endif; ?>

					</button>

				<?php endforeach; ?>

			</div>


			<div class="stat-language-panels">

				<?php foreach ($languages as $languageIndex => $language): ?>

					<?php
                    $languageId = (int) $language->id;
                    ?>

					<div
						class="stat-language-panel <?php echo $languageIndex === 0 ? 'is-active' : ''; ?>"
						data-stat-language-panel="__INDEX__-<?php echo $languageId; ?>"
					>

						<div class="form-grid">

							<div class="form-group">

								<label>
									Valor
								</label>

								<input
									type="text"
									name="stats[__INDEX__][translations][<?php echo $languageId; ?>][value]"
									class="form-control"
								>

							</div>


							<div class="form-group form-group-small">

								<label>
									Tamaño del valor
								</label>

								<input
									type="text"
									name="stats[__INDEX__][translations][<?php echo $languageId; ?>][value_size]"
									class="form-control"
									placeholder="Ej. 32px"
								>

							</div>


							<div class="form-group">

								<label>
									Etiqueta
								</label>

								<input
									type="text"
									name="stats[__INDEX__][translations][<?php echo $languageId; ?>][label]"
									class="form-control"
								>

							</div>


							<div class="form-group form-group-small">

								<label>
									Tamaño de la etiqueta
								</label>

								<input
									type="text"
									name="stats[__INDEX__][translations][<?php echo $languageId; ?>][label_size]"
									class="form-control"
									placeholder="Ej. 16px"
								>

							</div>

						</div>

					</div>

				<?php endforeach; ?>

			</div>

		<?php endif; ?>

	</div>

</script>

<style>
    .admin-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        margin-bottom: 24px;
        overflow: hidden;
    }

    .admin-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 20px 24px;
        border-bottom: 1px solid #e5e7eb;
    }

    .admin-card-header h2 {
        margin: 0 0 5px;
        font-size: 20px;
    }

    .admin-card-header p {
        margin: 0;
        color: #6b7280;
        font-size: 14px;
    }

    .admin-card-body {
        padding: 24px;
    }

    .form-row,
    .form-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 180px;
        gap: 20px;
    }

    .form-group-full {
        grid-column: 1 / -1;
    }

    .form-group-small {
        max-width: 180px;
    }

    .form-group label {
        display: block;
        margin-bottom: 7px;
        font-weight: 600;
        font-size: 13px;
    }

    .form-control {
        width: 100%;
        box-sizing: border-box;
        padding: 10px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        background: #fff;
        font-size: 14px;
    }

    .form-control:focus {
        outline: none;
        border-color: #111827;
    }

    .form-control-file {
        width: 100%;
        box-sizing: border-box;
        padding: 10px;
        border: 1px dashed #d1d5db;
        border-radius: 6px;
        background: #f9fafb;
        cursor: pointer;
    }

    .form-help {
        display: block;
        margin-top: 6px;
        color: #6b7280;
        font-size: 12px;
    }

    .current-image {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 12px;
        padding: 10px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background: #f9fafb;
    }

    .current-image img {
        width: 100px;
        height: 70px;
        object-fit: cover;
        border-radius: 5px;
    }

    .current-image-info {
        font-size: 13px;
        color: #6b7280;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    .form-control-title {
        min-height: 80px;
    }

    .language-tabs,
    .stat-language-tabs {
        display: flex;
        gap: 5px;
        border-bottom: 1px solid #e5e7eb;
        margin-bottom: 24px;
        overflow-x: auto;
    }

    .language-tab,
    .stat-language-tab {
        border: 0;
        background: transparent;
        padding: 10px 15px;
        cursor: pointer;
        color: #6b7280;
        border-bottom: 2px solid transparent;
        white-space: nowrap;
    }

    .language-tab span,
    .stat-language-tab span {
        margin-left: 5px;
        font-size: 11px;
        opacity: .65;
    }

    .language-tab.is-active,
    .stat-language-tab.is-active {
        color: #111827;
        border-bottom-color: #111827;
    }

    .language-panel,
    .stat-language-panel {
        display: none;
    }

    .language-panel.is-active,
    .stat-language-panel.is-active {
        display: block;
    }

    .stat-item {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        margin-bottom: 18px;
        overflow: hidden;
    }

    .stat-item-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 15px 18px;
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
    }

    .stat-item-header>div {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .stat-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #111827;
        color: #fff;
        font-size: 12px;
        font-weight: 600;
    }

    .stat-item-settings {
        display: flex;
        gap: 20px;
        padding: 18px;
        border-bottom: 1px solid #e5e7eb;
    }

    .stat-language-tabs {
        margin: 0;
        padding: 0 18px;
    }

    .stat-language-panels {
        padding: 20px 18px;
    }

    .empty-state {
        padding: 35px 20px;
        text-align: center;
        color: #6b7280;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        padding: 10px 0 40px;
    }

    .btn-danger {
        border: 1px solid #dc2626;
        background: #fff;
        color: #dc2626;
    }

    .btn-danger:hover {
        background: #dc2626;
        color: #fff;
    }

    @media (max-width: 768px) {

        .admin-card-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .form-row,
        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-group-small {
            max-width: none;
        }

        .stat-item-settings {
            flex-direction: column;
        }

    }
</style>

<script>
    (function() {

        'use strict';


        /*
         * =========================================================
         * PESTAÑAS DE IDIOMAS
         * =========================================================
         */

        function initLanguageTabs() {

            var tabs = document.querySelectorAll('[data-language-tab]');
            var panels = document.querySelectorAll('[data-language-panel]');

            Array.prototype.forEach.call(tabs, function(tab) {

                tab.addEventListener('click', function() {

                    var languageId = this.getAttribute('data-language-tab');

                    Array.prototype.forEach.call(tabs, function(item) {
                        item.classList.remove('is-active');
                    });

                    Array.prototype.forEach.call(panels, function(panel) {
                        panel.classList.remove('is-active');
                    });

                    this.classList.add('is-active');

                    var panel = document.querySelector(
                        '[data-language-panel="' + languageId + '"]'
                    );

                    if (panel) {
                        panel.classList.add('is-active');
                    }

                });

            });

        }


        /*
         * =========================================================
         * PESTAÑAS DE IDIOMA DE LAS ESTADÍSTICAS
         * =========================================================
         */

        function initStatLanguageTabs(container) {

            var tabs = container.querySelectorAll(
                '[data-stat-language-tab]'
            );

            var panels = container.querySelectorAll(
                '[data-stat-language-panel]'
            );

            Array.prototype.forEach.call(tabs, function(tab) {

                tab.addEventListener('click', function() {

                    var key = this.getAttribute(
                        'data-stat-language-tab'
                    );

                    Array.prototype.forEach.call(tabs, function(item) {
                        item.classList.remove('is-active');
                    });

                    Array.prototype.forEach.call(panels, function(panel) {
                        panel.classList.remove('is-active');
                    });

                    this.classList.add('is-active');

                    var panel = container.querySelector(
                        '[data-stat-language-panel="' + key + '"]'
                    );

                    if (panel) {
                        panel.classList.add('is-active');
                    }

                });

            });

        }


        /*
         * =========================================================
         * ACTUALIZAR NÚMEROS DE ESTADÍSTICAS
         * =========================================================
         */

        function updateStatNumbers() {

            var items = document.querySelectorAll(
                '#stats-container .stat-item'
            );

            Array.prototype.forEach.call(items, function(item, index) {

                var number = item.querySelector('.stat-number');

                if (number) {
                    number.textContent = index + 1;
                }

            });

        }


        /*
         * =========================================================
         * ESTADO VACÍO
         * =========================================================
         */

        function updateEmptyState() {

            var items = document.querySelectorAll(
                '#stats-container .stat-item'
            );

            var emptyState = document.getElementById('stats-empty');

            if (!emptyState) {
                return;
            }

            if (items.length === 0) {
                emptyState.style.display = '';
            } else {
                emptyState.style.display = 'none';
            }

        }


        /*
         * =========================================================
         * AGREGAR ESTADÍSTICA
         * =========================================================
         */

        function addStat() {

            var container = document.getElementById('stats-container');
            var template = document.getElementById('stat-template');

            if (!container || !template) {
                return;
            }

            var items = container.querySelectorAll('.stat-item');

            var index = items.length;

            var html = template.innerHTML
                .replace(/__INDEX__/g, index)
                .replace(/__NUMBER__/g, index + 1);

            var wrapper = document.createElement('div');

            wrapper.innerHTML = html;

            var stat = wrapper.firstElementChild;

            container.appendChild(stat);

            initStatLanguageTabs(stat);

            updateStatNumbers();
            updateEmptyState();

            stat.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });

        }


        /*
         * =========================================================
         * QUITAR ESTADÍSTICA
         * =========================================================
         */

        function removeStat(button) {

            var stat = button.closest('.stat-item');

            if (!stat) {
                return;
            }

            var confirmed = window.confirm(
                '¿Deseas quitar esta estadística?'
            );

            if (!confirmed) {
                return;
            }

            stat.remove();

            updateStatNumbers();
            updateEmptyState();

        }


        /*
         * =========================================================
         * EVENTOS
         * =========================================================
         */

        document.addEventListener('click', function(event) {

            var addButton = event.target.closest('#add-stat');

            if (addButton) {

                event.preventDefault();

                addStat();

                return;
            }


            var addEmptyButton = event.target.closest(
                '#add-stat-empty'
            );

            if (addEmptyButton) {

                event.preventDefault();

                addStat();

                return;
            }


            var removeButton = event.target.closest(
                '.btn-remove-stat'
            );

            if (removeButton) {

                event.preventDefault();

                removeStat(removeButton);

                return;
            }

        });


        /*
         * =========================================================
         * INICIALIZACIÓN
         * =========================================================
         */

        initLanguageTabs();


        var existingStats = document.querySelectorAll(
            '#stats-container .stat-item'
        );

        Array.prototype.forEach.call(
            existingStats,
            function(stat) {
                initStatLanguageTabs(stat);
            }
        );


        updateStatNumbers();

        updateEmptyState();


        /*
         * =========================================================
         * ENVÍO DEL FORMULARIO
         * =========================================================
         */

        var form = document.getElementById('intro-form');

        if (form) {

            form.addEventListener('submit', function() {

                var button = document.getElementById('save-intro');

                if (!button) {
                    return;
                }

                button.disabled = true;

                button.innerHTML =
                    '<i class="fa fa-spinner fa-spin"></i> Guardando...';

            });

        }


        /*
         * =========================================================
         * PREVISUALIZACIÓN DE IMAGEN
         * =========================================================
         */

        var imageInput = document.getElementById('about-image');

        if (imageInput) {

            imageInput.addEventListener('change', function() {

                if (!this.files || !this.files[0]) {
                    return;
                }

                var file = this.files[0];

                if (!file.type.match(/^image\//)) {

                    alert(
                        'El archivo seleccionado no es una imagen válida.'
                    );

                    this.value = '';

                    return;
                }

                var reader = new FileReader();

                reader.onload = function(event) {

                    var currentImage = document.querySelector(
                        '.current-image'
                    );

                    if (currentImage) {

                        var image = currentImage.querySelector('img');

                        if (image) {
                            image.src = event.target.result;
                        }

                        return;
                    }


                    var wrapper = document.querySelector(
                        '.image-upload-wrapper'
                    );

                    if (!wrapper) {
                        return;
                    }

                    var preview = document.createElement('div');

                    preview.className = 'current-image';

                    preview.innerHTML =
                        '<img src="' +
                        event.target.result +
                        '" alt="Vista previa">' +
                        '<div class="current-image-info">' +
                        '<span>Vista previa</span>' +
                        '</div>';

                    wrapper.insertBefore(
                        preview,
                        imageInput
                    );

                };

                reader.readAsDataURL(file);

            });

        }

    })();
</script>