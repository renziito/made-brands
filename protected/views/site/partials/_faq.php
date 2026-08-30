<?php
/*
|--------------------------------------------------------------------------
| FAQ
|--------------------------------------------------------------------------
| Frequently Asked Questions
|--------------------------------------------------------------------------
*/

?>

<section
    id="faq"
    class="faq" style="background-color:<?= WebUtils::getSiteSetting('section_background_color') ?>">

    <div class="container">


        <!--
        |--------------------------------------------------------------------------
        | SECTION HEADER
        |--------------------------------------------------------------------------
        -->

        <div class="faq__header">

            <h2 class="faq__title" style="font-family:<?= WebUtils::getSiteSetting('heading_font_family') ?>">
                <?= WebUtils::getMenuItemByKey('frequently_asked_questions', $languageId)['label'] ?>
            </h2>


            <div class="faq__title-line"></div>

        </div>


        <!--
        |--------------------------------------------------------------------------
        | QUESTIONS
        |--------------------------------------------------------------------------
        -->

        <div
            class="faq__list"
            id="faqAccordion">

            <?php foreach ($faqItems as $index => $item): ?>

                <div class="faq__item">


                    <!--
                    |--------------------------------------------------------------------------
                    | QUESTION
                    |--------------------------------------------------------------------------
                    -->

                    <button
                        type="button"
                        class="faq__question collapsed"
                        data-toggle="collapse"
                        data-parent="#faqAccordion"
                        data-target="#<?php echo $item['id']; ?>"
                        aria-expanded="false"
                        aria-controls="<?php echo $item['id']; ?>">

                        <span class="faq__icon">

                            <i
                                class="<?php echo $item['icon']; ?>"
                                aria-hidden="true"></i>

                        </span>


                        <span class="faq__question-text">

                            <?php echo CHtml::encode(
                                $item['question']
                            ); ?>

                        </span>


                        <span class="faq__plus">

                            <i
                                class="fa fa-plus"
                                aria-hidden="true"></i>

                        </span>

                    </button>


                    <!--
                    |--------------------------------------------------------------------------
                    | ANSWER
                    |--------------------------------------------------------------------------
                    -->

                    <div
                        id="<?php echo $item['id']; ?>"
                        class="faq__answer collapse">

                        <div class="faq__answer-content" style="font-family:<?= WebUtils::getSiteSetting('body_font_family') ?>">

                            <?php echo CHtml::encode(
                                $item['answer']
                            ); ?>

                        </div>

                    </div>


                </div>

            <?php endforeach; ?>

        </div>

    </div>

</section>