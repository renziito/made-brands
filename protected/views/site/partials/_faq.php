<?php
/*
|--------------------------------------------------------------------------
| FAQ
|--------------------------------------------------------------------------
| Frequently Asked Questions
|--------------------------------------------------------------------------
*/

$faqItems = array(
    array(
        'id'       => 'faq-1',
        'icon'     => 'fa-map-marker',
        'question' => '¿Dónde puedo comprar nuestros productos?',
        'answer'   => 'Puedes encontrar nuestros productos en los principales puntos de venta y canales de distribución donde están presentes nuestras marcas.'
    ),
    array(
        'id'       => 'faq-2',
        'icon'     => 'fa-hand-o-right',
        'question' => '¿Quieres vender nuestras marcas?',
        'answer'   => 'Si estás interesado en comercializar nuestras marcas, contáctanos y conversemos sobre las oportunidades disponibles para tu negocio.'
    ),
    array(
        'id'       => 'faq-3',
        'icon'     => 'fa-cube',
        'question' => '¿Tienes una marca? Hagámosla crecer en Uruguay',
        'answer'   => 'Trabajamos con marcas que buscan crecer y llegar a nuevos consumidores. Cuéntanos sobre tu marca y evaluemos juntos las oportunidades.'
    ),
    array(
        'id'       => 'faq-4',
        'icon'     => 'fa-plane',
        'question' => '¿Quieres exportar y distribuir tu marca en Uruguay?',
        'answer'   => 'Contamos con experiencia en distribución y comercialización para conectar marcas con nuevos mercados y oportunidades.'
    )
);
?>

<section
    id="faq"
    class="faq">

    <div class="container">


        <!--
        |--------------------------------------------------------------------------
        | SECTION HEADER
        |--------------------------------------------------------------------------
        -->

        <div class="faq__header">

            <h2 class="faq__title">
                Preguntas frecuentes
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
                                class="fa <?php echo $item['icon']; ?>"
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

                        <div class="faq__answer-content">

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