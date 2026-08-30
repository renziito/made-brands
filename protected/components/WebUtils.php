<?php

class WebUtils
{
    public static function getHero($languageId)
    {
        return    HeroSlides::model()->with(array(
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
                    ?  $category->image
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
            array(':language' => $languageId,)
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
                ?  $section->image
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

                'question' => $translation
                    ? $translation->question
                    : '',

                'answer' => $translation
                    ? $translation->answer
                    : '',
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
                    ':languageId' => $languageId,
                ),
            ),
        ))->findAll($criteria);


        $result = array();

        foreach ($items as $item) {
            $result[] = array(
                'id' => $item->id,
                'icon' => $item->icon,
                'label' => $item->contactItemTranslations[0]->label,
                'label_size' => $item->contactItemTranslations[0]->label_size,
                'value' => $item->contactItemTranslations[0]->value,
                'value_size' => $item->contactItemTranslations[0]->value_size,
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
                'joinType' => 'INNER JOIN',
                'condition' => 'contactCtaTranslations.language_id = :languageId',
                'params' => array(
                    ':languageId' => $languageId,
                ),
            ),
        ))->find($criteria);

        return $contactCta;
    }

    public static function getSocialLinks()
    {
        $criteria = new CDbCriteria();

        $criteria->condition = 't.is_active = 1';
        $criteria->order = 't.sort_order ASC';

        return SocialLinks::model()->findAll($criteria);
    }

    public static function getSiteSetting($key, $default = null)
    {
        $setting = SiteSettings::model()->find(
            'LOWER(setting_key) = LOWER(:key)',
            array(
                ':key' => $key,
            )
        );

        if ($setting === null) {
            return $default;
        }

        return $setting->setting_value;
    }

    public static function getMenuItemByKey($key, $languageId)
    {
        $item = MenuItems::model()->with(array(
            'menuItemTranslations' => array(
                'condition' => 'menuItemTranslations.language_id = :language',
                'params' => array(
                    ':language' => (int) $languageId,
                ),
            ),
        ))->find(array(
            'condition' => '`key` = :key AND active = 1',
            'params' => array(
                ':key' => $key,
            ),
        ));

        if ($item === null) {
            return null;
        }

        $translation = null;

        if (!empty($item->menuItemTranslations)) {
            $translation = $item->menuItemTranslations[0];
        }

        return array(
            'id' => (int) $item->id,
            'key' => $item->key,
            'label' => $translation !== null
                ? $translation->label
                : '',
            'is_menu' => (int) $item->is_menu,
            'is_button' => (int) $item->is_button,
            'link' => $item->link,
            'sort_order' => (int) $item->sort_order,
        );
    }

    public static function getMenu($languageId)
    {
        $items = MenuItems::model()->with(array(
            'menuItemTranslations' => array(
                'condition' => 'menuItemTranslations.language_id = :language',
                'params' => array(
                    ':language' => (int) $languageId,
                ),
            ),
        ))->findAll(array(
            'condition' => 'active = 1 AND is_menu = 1',
            'order' => 'sort_order ASC',
        ));

        $menu = array();

        foreach ($items as $item) {
            $translation = null;

            if (!empty($item->menuItemTranslations)) {
                $translation = $item->menuItemTranslations[0];
            }
            $menu[] = array(
                'id' => (int) $item->id,
                'key' => $item->key,
                'label' => $translation !== null
                    ? $translation->label
                    : '',
                'is_menu' => (int) $item->is_menu,
                'is_button' => (int) $item->is_button,
                'link' => $item->link,
                'sort_order' => (int) $item->sort_order,
            );
        }

        return $menu;
    }
}
