<?php

/*
|--------------------------------------------------------------------------
| MENU URL HELPER
|--------------------------------------------------------------------------
*/

$sectionUrl = function ($section) use ($isHome) {

    return $isHome
        ? '#' . $section
        : Yii::app()->controller->createUrl('site/index') . '#' . $section;
};

?>


<nav class="site-menu">

    <?php foreach ($menuItems as $menuItem): ?>

        <?php

        $url = $sectionUrl(
            $menuItem['link']
        );


        if (
            str_starts_with(
                $menuItem['link'],
                "#"
            )
        ) {

            $url = $menuItem['link'];
        }

        ?>


        <?php if ($menuItem['is_button']): ?>

            <a
                href="<?= CHtml::encode($url); ?>"
                class="header-contact-button"
                style="
                    background-color:<?= WebUtils::getSiteSetting('contact_button_background_color') ?>!important;
                    color:<?= WebUtils::getSiteSetting('contact_button_text_color') ?> !important">

                <?= CHtml::encode($menuItem['label']); ?>

            </a>

        <?php else: ?>

            <a
                href="<?= CHtml::encode($url); ?>">

                <?= CHtml::encode($menuItem['label']); ?>

            </a>

        <?php endif; ?>

    <?php endforeach; ?>


    <!--
    |--------------------------------------------------------------------------
    | LANGUAGES
    |--------------------------------------------------------------------------
    | Solo mostramos los idiomas cuando existen
    | al menos dos idiomas activos.
    |--------------------------------------------------------------------------
    -->

    <?php if (count($languages) > 1): ?>

        <div class="header-languages">

            <?php foreach ($languages as $activeLanguage): ?>

                <?php

                $activeLanguageCode = strtolower(
                    trim(
                        $activeLanguage->code
                    )
                );


                $isCurrentLanguage =
                    $activeLanguageCode ===
                    strtolower($languageCode);

                ?>

                <a
                    href="#"
                    class="header-language-button<?= $isCurrentLanguage ? ' active' : ''; ?>"
                    data-language="<?= CHtml::encode($activeLanguageCode); ?>"
                    aria-label="<?= CHtml::encode($activeLanguage->name); ?>">

                    <?= CHtml::encode(
                        strtoupper(
                            $activeLanguageCode
                        )
                    ); ?>

                </a>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</nav>