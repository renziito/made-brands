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
                'class' => self::isActive(['cpanel', ['default'], 'index'])
            ],
            [
                'name'  => 'Branding',
                'link'  =>  Yii::app()->createurl('cpanel/branding'),
                'icon'  => 'fas fa-palette',
                'class' => self::isActive(['cpanel', ['branding'], '*'])
            ],
            [
                'name'  => 'Idiomas',
                'link'  =>  Yii::app()->createurl('cpanel/languages'),
                'icon'  => 'fas fa-language',
                'class' => self::isActive(['cpanel', ['languages'], '*'])
            ],
            [
                'name'  => 'Users',
                'link'  =>  Yii::app()->createurl('cpanel/users'),
                'icon'  => 'fas fa-users',
                'class' => self::isActive(['cpanel', ['users'], '*'])
            ],

            [
                'name'  => 'Catalogo',
                'link'  => 'javascript:;',
                'icon'  => 'fab fa-product-hunt',
                'class' => self::isActive(['cpanel', ['brands', 'categories', 'products'], '*']),
                'sub'   => [
                    [
                        'name'  => 'Marcas',
                        'icon'  => ' fas fa-tags',
                        'class' => self::isActive(['cpanel', ['brands'], '*']),
                        'link'  => Yii::app()->createurl('cpanel/brands'),
                    ],

                    [
                        'name'  => 'Categorias',
                        'icon'  => ' fas fa-th-large',
                        'class' => self::isActive(['cpanel', ['categories'], '*']),
                        'link'  => Yii::app()->createurl('cpanel/categories'),
                    ],

                    [
                        'name'  => 'Productos',
                        'icon'  => ' fas fa-box-open',
                        'class' => self::isActive(['cpanel', ['products'], '*']),
                        'link'  => Yii::app()->createurl('cpanel/products'),
                    ]

                ]
            ],
            [
                'name'  => 'Redes',
                'link'  =>  Yii::app()->createurl('cpanel/social'),
                'icon'  => 'fas fa-share-alt',
                'class' => self::isActive(['cpanel', ['social'], '*'])
            ],
            [
                'name'  => 'Fomularios',
                'icon'  => ' fas fa-edit',
                'class' => self::isActive(['cpanel', ['forms', 'respuesta'], '*']),
                'link'  => "javascript:;",
                'sub'   => [
                    [
                        'name'  => 'Plantillas',
                        'icon'  => ' fas fa-th-list',
                        'class' => self::isActive(['cpanel', ['forms'], '*']),
                        'link'  => Yii::app()->createurl('cpanel/forms'),
                    ],
                    [
                        'name'  => 'Respuestas',
                        'icon'  => ' fas fa-comment-dots',
                        'class' => self::isActive(['cpanel', ['respuesta'], '*']),
                        'link'  => Yii::app()->createurl('cpanel/respuesta'),
                    ]
                ]
            ],
            [
                'name'  => 'Secciones',
                'link'  => 'javascript:;',
                'icon'  => 'fas fa-copy',
                'class' => self::isActive(['cpanel', ['hero', 'intro', 'about', 'business', 'faq', 'footer'], '*']),
                'sub'   => [
                    [
                        'name'  => 'Hero',
                        'icon'  => ' fas fa-images',
                        'class' => self::isActive(['cpanel', ['hero'], '*']),
                        'link'  => Yii::app()->createurl('cpanel/hero'),
                    ],

                    [
                        'name'  => 'Intro',
                        'icon'  => ' fas fa-align-left',
                        'class' => self::isActive(['cpanel', ['intro'], '*']),
                        'link'  => Yii::app()->createurl('cpanel/intro'),
                    ],

                    [
                        'name'  => 'Somos',
                        'icon'  => ' fas fa-info-circle',
                        'class' => self::isActive(['cpanel', ['about'], '*']),
                        'link'  => Yii::app()->createurl('cpanel/about'),
                    ],

                    [
                        'name'  => 'Negocios',
                        'icon'  => ' fas fa-briefcase',
                        'class' => self::isActive(['cpanel', ['business'], '*']),
                        'link'  => Yii::app()->createurl('cpanel/business'),
                    ],

                    [
                        'name'  => 'FAQ',
                        'icon'  => ' fas fa-question-circle',
                        'class' => self::isActive(['cpanel', ['faq'], '*']),
                        'link'  => Yii::app()->createurl('cpanel/faq'),
                    ],
                    [
                        'name'  => 'Footer',
                        'link'  =>  Yii::app()->createurl('cpanel/footer'),
                        'icon'  => ' fas fa-window-maximize',
                        'class' => self::isActive(['cpanel', ['footer'], '*'])
                    ],

                ]
            ],
            [
                'name'  => 'Extras',
                'icon'  => ' fas fa-folder-plus',
                'class' => self::isActive(['cpanel', ['extras'], '*']),
                'link'  => Yii::app()->createurl('cpanel/extras'),
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
