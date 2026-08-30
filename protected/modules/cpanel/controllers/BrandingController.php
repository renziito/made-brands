<?php

class BrandingController extends Controller
{
    /**
     * Branding settings.
     */
    private $brandingSettings = array(
        'general' => array(
            'site_name',
            'tagline',
            'tagline_menu',
            'tagline_footer',
            'logo_menu_size',
            'logo_footer_size',
            'full_sheet'
        ),

        'typography' => array(
            'font_family',
            'logo_font_family',
            'heading_font_family',
            'eyebrow_font_family',
            'body_font_family',
            'button_font_family',
        ),

        'text' => array(
            'heading_color',
            'eyebrow_color',
            'body_text_color',
            'separator_color',
        ),

        'buttons' => array(
            'contact_button_background_color',
            'contact_button_text_color',
            'category_button_background_color',
            'category_button_text_color',
            'cta_background_color',
            'cta_text_color',
        ),

        'backgrounds' => array(
            'body_background_color',
            'header_background_color',
            'section_background_color',
            'section_alt_background_color',
            'footer_background_color',
        ),
    );


    /**
     * Branding index.
     *
     * URL:
     * /cpanel/branding
     */
    public function actionIndex()
    {
        $keys = array();

        foreach ($this->brandingSettings as $sectionSettings) {
            $keys = array_merge($keys, $sectionSettings);
        }

        $settings = SiteSettings::model()->findAllByAttributes(
            array(
                'setting_key' => $keys,
            )
        );

        $branding = array();

        foreach ($settings as $setting) {
            $branding[$setting->setting_key] = $setting->setting_value;
        }

        $this->render('index', array(
            'branding' => $branding,
        ));
    }


    /**
     * Returns the Google Fonts catalog.
     *
     * This endpoint is consumed by the Branding font selectors.
     */
    public function actionFonts()
    {
        if (!Yii::app()->request->isAjaxRequest) {
            throw new CHttpException(400, 'Invalid request.');
        }

        header('Content-Type: application/json; charset=UTF-8');


        /*
		 * Put your Google Fonts API key in:
		 *
		 * protected/config/params.php
		 *
		 * 'googleFontsApiKey' => 'YOUR_API_KEY'
		 */
        $apiKey = isset(Yii::app()->params['googleFontsApiKey'])
            ? Yii::app()->params['googleFontsApiKey']
            : null;


        if (!$apiKey) {

            echo CJSON::encode(array(
                'success' => false,
                'message' => 'Google Fonts API key is not configured.',
            ));

            Yii::app()->end();
        }


        $url = 'https://www.googleapis.com/webfonts/v1/webfonts'
            . '?sort=popularity'
            . '&fields=items(family,category,variants)'
            . '&key=' . urlencode($apiKey);


        $ch = curl_init($url);

        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
            ),
        ));


        $response = curl_exec($ch);

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $curlError = curl_error($ch);

        curl_close($ch);


        if ($response === false || $curlError) {

            echo CJSON::encode(array(
                'success' => false,
                'message' => 'No se pudo conectar con Google Fonts.',
            ));

            Yii::app()->end();
        }


        if ($httpCode < 200 || $httpCode >= 300) {

            echo CJSON::encode(array(
                'success' => false,
                'message' => 'Google Fonts respondió con un error.',
            ));

            Yii::app()->end();
        }


        $data = CJSON::decode($response);


        if (!isset($data['items']) || !is_array($data['items'])) {

            echo CJSON::encode(array(
                'success' => false,
                'message' => 'No se encontraron fuentes de Google Fonts.',
            ));

            Yii::app()->end();
        }


        $fonts = array();

        foreach ($data['items'] as $font) {

            if (!isset($font['family'])) {
                continue;
            }

            $fonts[] = array(
                'family' => $font['family'],
                'category' => isset($font['category'])
                    ? $font['category']
                    : '',
                'variants' => isset($font['variants'])
                    ? $font['variants']
                    : array(),
            );
        }


        echo CJSON::encode(array(
            'success' => true,
            'fonts' => $fonts,
        ));

        Yii::app()->end();
    }


    /**
     * Save a Branding section through AJAX.
     */
    public function actionEdit()
    {
        if (!Yii::app()->request->isAjaxRequest) {
            throw new CHttpException(400, 'Invalid request.');
        }

        header('Content-Type: application/json; charset=UTF-8');

        $section = Yii::app()->request->getPost('section');


        if (!$section || !isset($this->brandingSettings[$section])) {

            echo CJSON::encode(array(
                'success' => false,
                'message' => 'Sección de Branding no válida.',
            ));

            Yii::app()->end();
        }


        $attributes = $this->brandingSettings[$section];

        $transaction = Yii::app()->db->beginTransaction();


        try {

            foreach ($attributes as $key) {

                if (!isset($_POST[$key])) {
                    continue;
                }


                $setting = SiteSettings::model()->findByAttributes(
                    array(
                        'setting_key' => $key,
                    )
                );


                if ($setting === null) {

                    throw new Exception(
                        'La configuración "' . $key . '" no existe.'
                    );
                }


                $setting->setting_value = $_POST[$key];


                if (!$setting->save()) {

                    throw new Exception(
                        'No se pudo guardar "' . $key . '".'
                    );
                }
            }


            $transaction->commit();


            echo CJSON::encode(array(
                'success' => true,
                'message' => 'Branding guardado correctamente.',
                'section' => $section,
            ));
        } catch (Exception $e) {

            if ($transaction->getActive()) {
                $transaction->rollback();
            }


            echo CJSON::encode(array(
                'success' => false,
                'message' => $e->getMessage(),
            ));
        }


        Yii::app()->end();
    }
}
