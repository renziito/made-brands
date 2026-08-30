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

                $about['image']['src'] =
                    $aboutSection->image;
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
                    $statTranslation =
                        $stat->aboutSectionStatTranslations[0];
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

    public function getBusinesses($languageId)
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
}
