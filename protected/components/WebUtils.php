<?php

class WebUtils
{
    public static function getHero($languageId)
    {
        return HeroSlides::model()->with(array(
            'heroSlideTranslations' => array(
                'condition' => 'heroSlideTranslations.language_id = :language',
                'params' => array(
                    ':language' => $languageId,
                ),
            ),
        ))->findAll(array(
            'condition' => 't.is_active = :status',
            'params' => array(
                ':status' => 1,
            ),
            'order' => 't.sort_order ASC, t.id ASC',
        ));
    }


    public static function getIntro($languageId)
    {
        // ==========================================================
        // MISSION / INTRO
        // ==========================================================

        $introSection = IntroSections::model()->with(array(
            'introSectionTranslations' => array(
                'condition' => 'introSectionTranslations.language_id = :language',
                'params' => array(
                    ':language' => $languageId,
                ),
            ),
        ))->find(array(
            'condition' => 't.is_active = :status',
            'params' => array(
                ':status' => 1,
            ),
            'order' => 't.sort_order ASC, t.id ASC',
        ));


        $mission = array(
            'eyebrow' => '',
            'title' => '',
            'description' => '',
        );


        if ($introSection) {

            $translation = null;

            if (!empty($introSection->introSectionTranslations)) {
                $translation = $introSection->introSectionTranslations[0];
            }


            if ($translation) {

                $mission = array(
                    'eyebrow' => $translation->eyebrow,
                    'title' => $translation->title,
                    'description' => $translation->text,
                );
            }
        }


        // ==========================================================
        // ABOUT
        // ==========================================================

        $aboutSection = AboutSections::model()->with(array(
            'aboutSectionTranslations' => array(
                'condition' => 'aboutSectionTranslations.language_id = :language',
                'params' => array(
                    ':language' => $languageId,
                ),
            ),
        ))->find(array(
            'condition' => 't.is_active = :status',
            'params' => array(
                ':status' => 1,
            ),
            'order' => 't.sort_order ASC, t.id ASC',
        ));


        $about = array(
            'eyebrow' => '',
            'title' => '',
            'descriptions' => array(),
            'highlights' => array(),
            'image' => array(
                'src' => '',
                'alt' => '',
            ),
        );


        if ($aboutSection) {

            $aboutTranslation = null;

            if (!empty($aboutSection->aboutSectionTranslations)) {
                $aboutTranslation = $aboutSection->aboutSectionTranslations[0];
            }


            // ======================================================
            // ABOUT TRANSLATION
            // ======================================================

            if ($aboutTranslation) {

                $about['eyebrow'] = $aboutTranslation->eyebrow;

                $about['title'] = $aboutTranslation->title;

                $about['descriptions'] = array(
                    $aboutTranslation->text,
                    $aboutTranslation->secondary_text,
                );
            }


            // ======================================================
            // ABOUT IMAGE
            // ======================================================

            if (!empty($aboutSection->image)) {

                $about['image']['src'] = $aboutSection->image;
            }


            // ======================================================
            // ABOUT STATS
            // ======================================================

            $stats = AboutSectionStats::model()->with(array(
                'aboutSectionStatTranslations' => array(
                    'condition' => 'aboutSectionStatTranslations.language_id = :language',
                    'params' => array(
                        ':language' => $languageId,
                    ),
                ),
            ))->findAll(array(
                'condition' => 't.about_section_id = :aboutSectionId
				AND t.is_active = :status',
                'params' => array(
                    ':aboutSectionId' => $aboutSection->id,
                    ':status' => 1,
                ),
                'order' => 't.sort_order ASC, t.id ASC',
            ));


            // ======================================================
            // BUILD HIGHLIGHTS
            // ======================================================

            foreach ($stats as $stat) {

                $statTranslation = null;

                if (!empty($stat->aboutSectionStatTranslations)) {
                    $statTranslation = $stat->aboutSectionStatTranslations[0];
                }


                if (!$statTranslation) {
                    continue;
                }


                $about['highlights'][] = array(
                    'title' => $statTranslation->value,
                    'description' => $statTranslation->label,
                );
            }
        }


        // ==========================================================
        // FINAL RESULT
        // ==========================================================

        return array(
            'mission' => $mission,
            'about' => $about,
        );
    }


    public static function getBusinesses($languageId)
    {
        $businessesModels = Businesses::model()->with(array(
            'businessTranslations' => array(
                'condition' => 'businessTranslations.language_id = :language',
                'params' => array(
                    ':language' => $languageId,
                ),
            ),
        ))->findAll(array(
            'condition' => 't.is_active = :status',
            'params' => array(
                ':status' => 1,
            ),
            'order' => 't.sort_order ASC, t.id ASC',
        ));


        $businesses = array();


        foreach ($businessesModels as $business) {

            $translation = null;

            if (!empty($business->businessTranslations)) {
                $translation = $business->businessTranslations[0];
            }


            $businesses[] = array(
                'id' => $business->id,

                'title' => $translation
                    ? $translation->name
                    : '',

                'description' => $translation
                    ? $translation->description
                    : '',

                'image' => !empty($business->image)
                    ? $business->image
                    : '',

                'alt' => $translation
                    ? $translation->name
                    : '',

                'icon' => $business->icon,

                'status' => (int) $business->is_active,

                'sort_order' => (int) $business->sort_order,
            );
        }


        return $businesses;
    }


    public static function getProductCategories($languageId)
    {
        $categoryModels = Categories::model()->with(array(
            'categoryTranslations' => array(
                'condition' => 'categoryTranslations.language_id = :language',
                'params' => array(
                    ':language' => $languageId,
                ),
            ),
        ))->findAll(array(
            'condition' => 't.is_active = :status
			AND t.is_featured = :featured',
            'params' => array(
                ':status' => 1,
                ':featured' => 1,
            ),
            'order' => 't.sort_order ASC, t.id ASC',
        ));


        $productCategories = array();


        foreach ($categoryModels as $category) {

            $translation = null;

            if (!empty($category->categoryTranslations)) {
                $translation = $category->categoryTranslations[0];
            }


            $productCategories[] = array(
                'id' => $category->id,

                'name' => $translation
                    ? $translation->name
                    : '',

                'slug' => $translation
                    ? strtolower(
                        preg_replace(
                            '/[^a-z0-9]+/',
                            '-',
                            trim($translation->name)
                        )
                    )
                    : '',

                'image' => !empty($category->image)
                    ? $category->image
                    : '',

                'alt' => $translation
                    ? $translation->name
                    : '',

                'status' => (int) $category->is_active,

                'featured' => (int) $category->is_featured,

                'sort_order' => (int) $category->sort_order,
            );
        }


        return $productCategories;
    }


    public static function getBrandSection($languageId)
    {
        $section = BrandsSection::model()->find(
            'language_id = :language',
            array(
                ':language' => $languageId,
            )
        );


        if (!$section) {

            return array(
                'image' => '',
                'eyebrow' => '',
                'title' => '',
                'text' => '',
                'featuredText' => '',
            );
        }


        return array(
            'image' => !empty($section->image)
                ? $section->image
                : '',

            'eyebrow' => $section->eyebrow,

            'title' => $section->title,

            'text' => $section->text,

            'featuredText' => $section->featured_label,
        );
    }


    public static function getFeaturedBrands()
    {
        $brandModels = Brands::model()->findAll(array(
            'condition' => 't.is_active = :status
			AND t.is_featured = :featured',

            'params' => array(
                ':status' => 1,
                ':featured' => 1,
            ),

            'order' => 't.sort_order ASC, t.id ASC',
        ));


        $featuredBrands = array();


        foreach ($brandModels as $brand) {

            $featuredBrands[] = array(
                'name' => $brand->name,

                'website_url' => $brand->website_url,

                'image' => !empty($brand->logo)
                    ? $brand->logo
                    : '',
            );
        }


        return $featuredBrands;
    }


    public static function getBrands()
    {
        $brandModels = Brands::model()->findAll(array(
            'condition' => 't.is_active = :status',

            'params' => array(
                ':status' => 1,
            ),

            'order' => 't.sort_order ASC, t.id ASC',
        ));


        $brands = array();


        foreach ($brandModels as $brand) {

            $brands[] = array(
                'name' => $brand->name,

                'image' => !empty($brand->logo)
                    ? $brand->logo
                    : '',
            );
        }


        return $brands;
    }


    public static function getFaqItems($languageId)
    {
        $faqModels = Faqs::model()->with(array(
            'faqTranslations' => array(
                'condition' => 'faqTranslations.language_id = :language',
                'params' => array(
                    ':language' => $languageId,
                ),
            ),
        ))->findAll(array(
            'condition' => 't.is_active = :status',
            'params' => array(
                ':status' => 1,
            ),
            'order' => 't.sort_order ASC, t.id ASC',
        ));


        $faqItems = array();


        foreach ($faqModels as $faq) {

            $translation = null;

            if (!empty($faq->faqTranslations)) {
                $translation = $faq->faqTranslations[0];
            }


            $faqItems[] = array(
                'id' => 'faq-' . $faq->id,

                'icon' => $faq->icon,

                'question' =>
                $translation
                    ? $translation->question
                    : '',

                'answer' =>
                $translation
                    ? $translation->answer
                    : '',

                'form_id' =>
                $translation
                    ? $translation->form_id
                    : null,

                'form_text' =>
                $translation
                    ? $translation->form_text
                    : null,
            );
        }


        return $faqItems;
    }


    public static function getContactItems($languageId)
    {
        $criteria = new CDbCriteria();

        $criteria->condition = 't.is_active = 1';
        $criteria->order = 't.sort_order ASC';

        $items = ContactItems::model()->with(array(
            'contactItemTranslations' => array(
                'joinType' => 'INNER JOIN',
                'condition' => 'contactItemTranslations.language_id = :languageId',
                'params' => array(
                    ':languageId' => (int) $languageId,
                ),
            ),
        ))->findAll($criteria);

        $result = array();

        foreach ($items as $item) {

            if (
                empty($item->contactItemTranslations) ||
                !isset($item->contactItemTranslations[0])
            ) {
                continue;
            }

            $translation =
                $item->contactItemTranslations[0];

            $result[] = array(
                'id' => (int) $item->id,
                'icon' => $item->icon,
                'label' => $translation->label,
                'label_size' => $translation->label_size,
                'value' => $translation->value,
                'value_size' => $translation->value_size,
            );
        }

        return $result;
    }


    public static function getContactCta($languageId)
    {
        $criteria = new CDbCriteria();

        $criteria->condition = 't.is_active = 1';


        $contactCta = ContactCta::model()->with(array(
            'contactCtaTranslations' => array(
                'joinType' => 'LEFT JOIN',

                'condition' =>
                'contactCtaTranslations.language_id = :languageId',

                'params' => array(
                    ':languageId' => (int) $languageId,
                ),
            ),
        ))->find($criteria);


        /*
     * ==========================================================
     * CONTACT CTA DOES NOT EXIST
     * ==========================================================
     */

        if ($contactCta === null) {

            $contactCta = new ContactCta();

            $contactCta->id = 0;

            $contactCta->is_active = 1;

            $contactCta->url = '#';

            $contactCta->icon = 'fa fa-arrow-right';


            $contactCta->contactCtaTranslations = array(
                self::createContactCtaPlaceholderTranslation(
                    0,
                    $languageId
                ),
            );


            return $contactCta;
        }


        /*
     * ==========================================================
     * TRANSLATION
     * ==========================================================
     */

        if (
            empty($contactCta->contactCtaTranslations) ||
            !isset(
                $contactCta->contactCtaTranslations[0]
            )
        ) {

            $contactCta->contactCtaTranslations = array(
                self::createContactCtaPlaceholderTranslation(
                    $contactCta->id,
                    $languageId
                ),
            );
        }


        return $contactCta;
    }


    /**
     * Creates a placeholder translation for Contact CTA.
     */
    private static function createContactCtaPlaceholderTranslation(
        $contactCtaId,
        $languageId
    ) {
        $translation =
            new ContactCtaTranslations();


        $translation->id =
            0;


        $translation->contact_cta_id =
            (int) $contactCtaId;


        $translation->language_id =
            (int) $languageId;


        $translation->title =
            '[contact_cta_title]';


        $translation->title_size =
            '';


        $translation->text =
            '[contact_cta_text]';


        $translation->text_size =
            '';


        $translation->button_text =
            '[contact_cta_button_text]';


        $translation->button_text_size =
            '';


        return $translation;
    }


    public static function getSocialLinks()
    {
        $criteria =
            new CDbCriteria();


        $criteria->condition =
            't.is_active = 1';


        $criteria->order =
            't.sort_order ASC';


        return SocialLinks::model()->findAll(
            $criteria
        );
    }


    public static function getSiteSetting(
        $key,
        $default = null
    ) {
        $setting =
            SiteSettings::model()->find(
                'LOWER(setting_key) = LOWER(:key)',
                array(
                    ':key' =>
                    $key,
                )
            );


        if ($setting === null) {

            return $default;
        }


        return $setting->setting_value;
    }


    public static function getMenuItemByKey(
        $key,
        $languageId
    ) {
        $item =
            MenuItems::model()->with(array(
                'menuItemTranslations' => array(
                    'condition' =>
                    'menuItemTranslations.language_id = :language',

                    'params' => array(
                        ':language' =>
                        (int) $languageId,
                    ),
                ),
            ))->find(array(
                'condition' =>
                '`key` = :key AND active = 1',

                'params' => array(
                    ':key' =>
                    $key,
                ),
            ));


        /*
     * If the menu item does not exist, return a placeholder.
     */

        if ($item === null) {

            return array(
                'id' =>
                0,

                'key' =>
                $key,

                'label' =>
                '[' . $key . ']',

                'is_menu' =>
                0,

                'is_button' =>
                0,

                'link' =>
                '',

                'sort_order' =>
                0,
            );
        }


        $translation =
            null;


        if (
            !empty($item->menuItemTranslations)
        ) {

            $translation =
                $item->menuItemTranslations[0];
        }


        /*
     * If the menu item exists but the translation does not,
     * return a visible placeholder.
     */

        $label =
            $translation !== null
            ? trim(
                (string)
                $translation->label
            )
            : '';


        if (
            $label === ''
        ) {

            $label =
                '[' . $key . ']';
        }


        return array(
            'id' =>
            (int) $item->id,

            'key' =>
            $item->key,

            'label' =>
            $label,

            'is_menu' =>
            (int) $item->is_menu,

            'is_button' =>
            (int) $item->is_button,

            'link' =>
            $item->link,

            'sort_order' =>
            (int) $item->sort_order,
        );
    }


    public static function getMenu($languageId)
    {
        $items =
            MenuItems::model()->with(array(
                'menuItemTranslations' => array(
                    'condition' =>
                    'menuItemTranslations.language_id = :language',

                    'params' => array(
                        ':language' =>
                        (int) $languageId,
                    ),
                ),
            ))->findAll(array(
                'condition' =>
                'active = 1 AND is_menu = 1',

                'order' =>
                'sort_order ASC',
            ));


        $menu =
            array();


        foreach (
            $items
            as $item
        ) {

            $translation =
                null;


            if (
                !empty($item->menuItemTranslations)
            ) {

                $translation =
                    $item->menuItemTranslations[0];
            }


            $menu[] =
                array(
                    'id' =>
                    (int) $item->id,

                    'key' =>
                    $item->key,

                    'label' =>
                    $translation !== null
                        ? $translation->label
                        : '',

                    'is_menu' =>
                    (int) $item->is_menu,

                    'is_button' =>
                    (int) $item->is_button,

                    'link' =>
                    $item->link,

                    'sort_order' =>
                    (int) $item->sort_order,
                );
        }


        return $menu;
    }


    public static function getActiveLanguages()
    {
        return Languages::model()->findAll(
            array(
                'condition' =>
                'is_active = 1',

                'order' =>
                'sort_order ASC, id ASC',
            )
        );
    }


    public static function getHeroHtml($languageId)
    {
        $heroSlides =
            self::getHero(
                $languageId
            );


        return Yii::app()->controller->renderPartial(
            'partials/_hero',
            array(
                'heroSlidesModels' =>
                $heroSlides,

                'languageId' =>
                $languageId
            ),
            true
        );
    }


    public static function getIntroHtml($languageId)
    {
        $introContent =
            self::getIntro(
                $languageId
            );


        return Yii::app()->controller->renderPartial(
            'partials/_intro',
            array(
                'introContent' =>
                $introContent,

                'languageId' =>
                $languageId
            ),
            true
        );
    }


    public static function getBusinessHtml($languageId)
    {
        $businesses =
            self::getBusinesses(
                $languageId
            );


        return Yii::app()->controller->renderPartial(
            'partials/_business',
            array(
                'businesses' =>
                $businesses,

                'languageId' =>
                $languageId,
            ),
            true
        );
    }


    public static function getProductsHtml($languageId)
    {
        $featuredCategories =
            self::getProductCategories(
                $languageId
            );


        return Yii::app()->controller->renderPartial(
            'partials/_products',
            array(
                'featuredCategories' =>
                $featuredCategories,

                'languageId' =>
                $languageId,
            ),
            true
        );
    }


    /**
     * Renders the public product catalog for AJAX language changes.
     *
     * The controller already contains the complete catalog data
     * preparation logic, including:
     *
     * - categories
     * - subcategories
     * - brands
     * - translated products
     * - filters
     * - pagination
     * - selected product
     *
     * We reuse that logic through getProductosViewData()
     * and only render the catalog partial here.
     */
    public static function getProductosHtml($languageId)
    {
        $controller =
            Yii::app()->controller;


        $viewData =
            $controller->getProductosViewData();


        $viewData['languageId'] =
            $languageId;


        return $controller->renderPartial(
            'partials/_productos_catalog',
            $viewData,
            true
        );
    }


    public static function getClientsHtml($languageId)
    {
        $brandSection =
            self::getBrandSection(
                $languageId
            );


        $featuredBrands =
            self::getFeaturedBrands();


        $brands =
            self::getBrands();


        return Yii::app()->controller->renderPartial(
            'partials/_clients',
            array(
                'brandSection' =>
                $brandSection,

                'featuredBrands' =>
                $featuredBrands,

                'brands' =>
                $brands,

                'languageId' =>
                $languageId,
            ),
            true
        );
    }


    public static function getFaqHtml($languageId)
    {
        $faqItems =
            self::getFaqItems(
                $languageId
            );


        return Yii::app()->controller->renderPartial(
            'partials/_faq',
            array(
                'faqItems' =>
                $faqItems,

                'languageId' =>
                $languageId,
            ),
            true
        );
    }


    public static function getMenuHtml($languageId)
    {
        $menuItems =
            self::getMenu(
                $languageId
            );


        $languageCode =
            Yii::app()->session->get(
                'language',
                'es'
            );


        $languages =
            self::getActiveLanguages();


        $isHome =
            Yii::app()->controller->getRoute()
            ===
            'site/index';


        $sectionUrl =
            function ($section) use ($isHome) {

                return $isHome
                    ? '#' . $section
                    : Yii::app()->controller->createUrl(
                        'site/index'
                    ) . '#' . $section;
            };


        return Yii::app()->controller->renderFile(
            Yii::app()->theme->viewPath .
                '/partials/_site_menu.php',

            array(
                'menuItems' =>
                $menuItems,

                'isHome' =>
                $isHome,

                'languageCode' =>
                $languageCode,

                'languages' =>
                $languages,

                'sectionUrl' =>
                $sectionUrl,
            ),

            true
        );
    }


    public static function getFooterContactHtml(
        $languageId
    ) {
        $contactItems =
            self::getContactItems(
                $languageId
            );


        $contactCta =
            self::getContactCta(
                $languageId
            );


        return Yii::app()->controller->renderFile(
            Yii::app()->theme->viewPath .
                '/partials/_footer_contact.php',

            array(
                'contactItems' =>
                $contactItems,

                'contactCta' =>
                $contactCta,

                'languageId' =>
                $languageId,
            ),

            true
        );
    }


    public static function getCopyrightHtml(
        $languageId
    ) {
        return Yii::app()->controller->renderFile(
            Yii::app()->theme->viewPath .
                '/partials/_footer_copyright.php',

            array(
                'languageId' =>
                $languageId,
            ),

            true
        );
    }
}
