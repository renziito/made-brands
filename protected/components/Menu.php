<?php

/**
 * Description of Menu
 *
 * @author renziito
 */
class Menu
{

    public static function getMenu()
    {
        $menu = [
            [
                'name'  => 'Inicio',
                'link'  => Yii::app()->getBaseUrl(true),
                'icon'  => 'fas fa-home',
                'class' => self::isActive(['panel', ['default'], 'index'])
            ],
            [
                'name'  => 'Branding',
                'link'  =>  Yii::app()->createurl('panel/branding'),
                'icon'  => 'fas fa-palette',
                'class' => self::isActive(['panel', ['branding'], '*'])
            ],
            [
                'name'  => 'Idiomas',
                'link'  =>  Yii::app()->createurl('panel/languages'),
                'icon'  => 'fas fa-language',
                'class' => self::isActive(['panel', ['languages'], '*'])
            ],
            [
                'name'  => 'Users',
                'link'  =>  Yii::app()->createurl('panel/users'),
                'icon'  => 'fas fa-users',
                'class' => self::isActive(['panel', ['users'], '*'])
            ],

            [
                'name'  => 'Catalogo',
                'link'  => 'javascript:;',
                'icon'  => 'fab fa-product-hunt',
                'class' => self::isActive(['panel', ['brands', 'categories', 'products'], '*']),
                'sub'   => [
                    [
                        'name'  => 'Marcas',
                        'icon'  => ' fas fa-tags',
                        'class' => self::isActive(['panel', ['brands'], '*']),
                        'link'  => Yii::app()->createurl('panel/brands'),
                    ],

                    [
                        'name'  => 'Categorias',
                        'icon'  => ' fas fa-th-large',
                        'class' => self::isActive(['panel', ['categories'], '*']),
                        'link'  => Yii::app()->createurl('panel/categories'),
                    ],

                    [
                        'name'  => 'Productos',
                        'icon'  => ' fas fa-box-open',
                        'class' => self::isActive(['panel', ['products'], '*']),
                        'link'  => Yii::app()->createurl('panel/products'),
                    ]

                ]
            ],
            [
                'name'  => 'Redes',
                'link'  =>  Yii::app()->createurl('panel/social'),
                'icon'  => 'fas fa-share-alt',
                'class' => self::isActive(['panel', ['social'], '*'])
            ],
            [
                'name'  => 'Fomularios',
                'icon'  => ' fas fa-edit',
                'class' => self::isActive(['panel', ['forms', 'respuesta'], '*']),
                'link'  => "javascript:;",
                'sub'   => [
                    [
                        'name'  => 'Plantillas',
                        'icon'  => ' fas fa-th-list',
                        'class' => self::isActive(['panel', ['forms'], '*']),
                        'link'  => Yii::app()->createurl('panel/forms'),
                    ],
                    [
                        'name'  => 'Respuestas',
                        'icon'  => ' fas fa-comment-dots',
                        'class' => self::isActive(['panel', ['respuesta'], '*']),
                        'link'  => Yii::app()->createurl('panel/respuesta'),
                    ]
                ]
            ],
            [
                'name'  => 'Secciones',
                'link'  => 'javascript:;',
                'icon'  => 'fas fa-copy',
                'class' => self::isActive(['panel', ['hero', 'intro', 'about', 'business', 'faq', 'footer'], '*']),
                'sub'   => [
                    [
                        'name'  => 'Hero',
                        'icon'  => ' fas fa-images',
                        'class' => self::isActive(['panel', ['hero'], '*']),
                        'link'  => Yii::app()->createurl('panel/hero'),
                    ],

                    [
                        'name'  => 'Intro',
                        'icon'  => ' fas fa-align-left',
                        'class' => self::isActive(['panel', ['intro'], '*']),
                        'link'  => Yii::app()->createurl('panel/intro'),
                    ],

                    [
                        'name'  => 'Somos',
                        'icon'  => ' fas fa-info-circle',
                        'class' => self::isActive(['panel', ['about'], '*']),
                        'link'  => Yii::app()->createurl('panel/about'),
                    ],

                    [
                        'name'  => 'Negocios',
                        'icon'  => ' fas fa-briefcase',
                        'class' => self::isActive(['panel', ['business'], '*']),
                        'link'  => Yii::app()->createurl('panel/business'),
                    ],

                    [
                        'name'  => 'FAQ',
                        'icon'  => ' fas fa-question-circle',
                        'class' => self::isActive(['panel', ['faq'], '*']),
                        'link'  => Yii::app()->createurl('panel/faq'),
                    ],
                    [
                        'name'  => 'Footer',
                        'link'  =>  Yii::app()->createurl('panel/footer'),
                        'icon'  => ' fas fa-window-maximize',
                        'class' => self::isActive(['panel', ['footer'], '*'])
                    ],

                ]
            ],
            [
                'name'  => 'Extras',
                'icon'  => ' fas fa-folder-plus',
                'class' => self::isActive(['panel', ['extras'], '*']),
                'link'  => Yii::app()->createurl('panel/extras'),
            ],
        ];
        return $menu;
    }

    public static function isActive($p)
    {
        $m   = (Yii::app()->controller->module ? Yii::app()->controller->module->id : '');
        $c   = (Yii::app()->controller->id == 'site' ? '' : Yii::app()->controller->id);
        $a   = ($c == '' && Yii::app()->controller->action->id == 'index' ? '' : Yii::app()->controller->action->id);
        $non = [];
        if (is_array($p[2])) {
            foreach ($p[2] as $act) {
                $temp = explode("!", $act);
                if (count($temp) > 1) {
                    $non[] = $temp[1];
                }
            }
        } else {
            $temp = explode("!", $p[2]);
            if (count($temp) > 1) {
                $non[] = $temp[1];
            }
        }

        if (count($non) > 1) {
            $action = !(in_array($a, $non));
        } else {
            $action = $a == $p['2'];
        }
        $module     = (is_array($p['0'])) ? in_array($m, $p['0']) : $m == $p['0'];
        $controller = (in_array($c, $p['1']) || in_array('*', $p['1']));

        $result = ($module && $controller) && ($p['2'] == '*' ? true : $action);
        return ($result ? 'open active' : '');
    }
}
