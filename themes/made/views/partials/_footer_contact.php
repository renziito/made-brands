<!--
|--------------------------------------------------------------------------
| CONTACT SECTION
|--------------------------------------------------------------------------
-->

<div class="footer-contact">

    <div class="container">

        <div class="footer-contact__content">


            <!--
            |--------------------------------------------------------------------------
            | CONTACT INFORMATION
            |--------------------------------------------------------------------------
            -->

            <div class="footer-contact__info">

                <?php foreach ($contactItems as $item): ?>

                    <div class="footer-contact__item">


                        <div class="footer-contact__icon">

                            <i
                                class="<?php echo CHtml::encode(
                                            $item['icon']
                                        ); ?>"
                                aria-hidden="true">
                            </i>

                        </div>


                        <div class="footer-contact__text">

                            <strong>

                                <?php echo CHtml::encode(
                                    $item['label']
                                ); ?>

                            </strong>


                            <?php foreach (
                                preg_split(
                                    '/\r\n|\r|\n/',
                                    $item['value']
                                ) as $value
                            ): ?>

                                <span>

                                    <?php echo CHtml::encode(
                                        trim($value)
                                    ); ?>

                                </span>

                            <?php endforeach; ?>

                        </div>


                    </div>

                <?php endforeach; ?>

            </div>


            <!--
            |--------------------------------------------------------------------------
            | CONTACT CTA
            |--------------------------------------------------------------------------
            -->

            <div class="footer-contact__cta">

                <a
                    href="<?php echo CHtml::encode(
                                $contactCta->url
                            ); ?>"
                    class="footer-contact__button"
                    style="
                        background-color:<?= WebUtils::getSiteSetting('cta_background_color') ?>!important;
                        color:<?= WebUtils::getSiteSetting('cta_text_color') ?> !important">


                    <span class="footer-contact__button-icon">

                        <i
                            class="<?php echo CHtml::encode(
                                        $contactCta->icon
                                    ); ?>"
                            aria-hidden="true">
                        </i>

                    </span>


                    <?php

                    $translation =
                        $contactCta->contactCtaTranslations[0];

                    ?>


                    <span class="footer-contact__button-content">

                        <strong>

                            <?php echo CHtml::encode(
                                $translation->title
                            ); ?>

                        </strong>


                        <?php if (!empty($translation->text)): ?>

                            <small>

                                <?php echo CHtml::encode(
                                    $translation->text
                                ); ?>

                            </small>

                        <?php endif; ?>

                    </span>


                </a>

            </div>


        </div>

    </div>

</div>