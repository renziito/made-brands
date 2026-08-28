<?php

/**
 * @package Sismonitor\Components
 */
class Utils
{

    public static $busqueda = [
        'select'    => '*',
        'condition' => '',
        'params'    => [],
        'order'     => ''
    ];

    public static function show($data, $detenerProcesos = false, $titulo = 'Datos')
    {
        echo "<code><b>{$titulo} :</b></code>";
        echo "<pre>";
        print_r($data);
        echo '</pre>';
        if ($detenerProcesos) {
            die();
        }
    }

    public static function setBusqueda($buscar, $select = false, $order = false)
    {
        $condition = '';
        $params    = [];
        if (is_array($buscar)) {
            $and = 'AND ';
            foreach ($buscar as $key => $val) {
                $valor = Utils::reset_string($key);
                if (is_array($val)) {
                    $condition .= $val['query'] . $and;
                } else {
                    if ($val == ':isnull') {
                        $condition .= "{$key} is null {$and}";
                    } else {
                        if ($val == ':nonull') {
                            $condition .= "{$key} is not null {$and}";
                        } else {
                            $condition           .= "{$key} = :{$valor} {$and}";
                            $params[":{$valor}"] = $val;
                        }
                    }
                }
            }

            $condition                   = substr($condition, 0, - (strlen($and)));
            self::$busqueda['condition'] = $condition;
            self::$busqueda['params']    = $params;
        } else {
            $condition = $buscar;
            $params    = $buscar;
        }


        if ($order) {
            self::$busqueda['order'] = $order;
        }

        if ($select) {
            self::$busqueda['select'] = $select;
        }
    }

    public static function getBusqueda()
    {
        return self::$busqueda;
    }

    /**
     * Reinicia la cadena de caracteres raros.
     * @param string $string
     * @return string
     */
    public static function reset_string($string, $spaces = false)
    {

        $string = trim($string);

        $string = str_replace(
            array('Ã¡', 'Ã ', 'Ã¤', 'Ã¢', 'Âª', 'Ã', 'Ã€', 'Ã‚', 'Ã„'),
            array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
            $string
        );

        $string = str_replace(
            array('Ã©', 'Ã¨', 'Ã«', 'Ãª', 'Ã‰', 'Ãˆ', 'ÃŠ', 'Ã‹'),
            array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
            $string
        );

        $string = str_replace(
            array('Ã­', 'Ã¬', 'Ã¯', 'Ã®', 'Ã', 'ÃŒ', 'Ã', 'ÃŽ'),
            array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
            $string
        );

        $string = str_replace(
            array('Ã³', 'Ã²', 'Ã¶', 'Ã´', 'Ã“', 'Ã’', 'Ã–', 'Ã”'),
            array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
            $string
        );

        $string = str_replace(
            array('Ãº', 'Ã¹', 'Ã¼', 'Ã»', 'Ãš', 'Ã™', 'Ã›', 'Ãœ'),
            array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
            $string
        );

        $string = str_replace(
            array('Ã±', 'Ã‘', 'Ã§', 'Ã‡'),
            array('n', 'N', 'c', 'C'),
            $string
        );

        $string = str_replace(
            array(
                "\\",
                "Â¨",
                "Âº",
                "-",
                "~",
                "#",
                "@",
                "|",
                "!",
                "\"",
                "Â·",
                "$",
                "%",
                "&",
                "/",
                "(",
                ")",
                "?",
                "'",
                "Â¡",
                "Â¿",
                "^",
                "`",
                "+",
                "}",
                "{",
                "Â¨",
                "Â´",
                ">",
                "< ",
                ";",
                ",",
                ":",
                ".",
                " "
            ),
            '',
            $string
        );

        if ($spaces) {
            $string = str_replace(' ', '', $string);
        }

        return $string;
    }

    /**
     * Funcion que limita los caracteres de una cadena.
     * @param type $string cadena de texto completa
     * @param type $limit cantidad de caracteres para limitar la cadena
     * @param string $ellipsis variable que indica como terminar el texto
     * @return string
     */
    public static function limitcharacters($string, $limit = 10, $ellipsis = "...")
    {
        $cadena = substr($string, 0, $limit);

        $longitud = strlen($string);

        if ($longitud > $limit) {
            return $cadena . $ellipsis;
        } else {
            return $cadena;
        }
    }

    public static function getMonth($mes)
    {
        $meses = [
            '',
            'Ene',
            'Feb',
            'Mar',
            'Abr',
            'May',
            'Jun',
            'Jul',
            'Ago',
            'Set',
            'Oct',
            'Nov',
            'Dic',
        ];
        return $meses[$mes];
    }

    public static function Slugify($string, $date = false, $short = false)
    {
        if ($string != "") {
            $characters = array(
                "Á" => "A",
                "Ç" => "c",
                "É" => "e",
                "Í" => "i",
                "Ñ" => "n",
                "Ó" => "o",
                "Ú" => "u",
                "á" => "a",
                "ç" => "c",
                "é" => "e",
                "í" => "i",
                "ñ" => "n",
                "ó" => "o",
                "ú" => "u",
                "à" => "a",
                "è" => "e",
                "ì" => "i",
                "ò" => "o",
                "ù" => "u"
            );
            if ($short) {
                $string = (strlen($string) > $short ? substr($string, 0, $short) : $string);
            }

            $string = str_replace([
                "\\",
                "Â¨",
                "Âº",
                "-",
                "~",
                "#",
                "@",
                "|",
                "!",
                "\"",
                "Â·",
                "$",
                "%",
                "&",
                "/",
                "(",
                ")",
                "?",
                "'",
                "Â¡",
                "Â¿",
                "^",
                "`",
                "+",
                "}",
                "{",
                "Â¨",
                "Â´",
                ">",
                "< ",
                ";",
                ",",
                ":",
                ".",
                " "
            ], '', $string);
            $string = strtr($string, $characters);
            $string = strtolower(trim($string));
            $string = preg_replace("/[^a-z0-9-]/", "-", $string);
            $string = preg_replace("/-+/", "-", $string);

            if (substr($string, strlen($string) - 1, strlen($string)) === "-") {
                $string = substr($string, 0, strlen($string) - 1);
            }
            $string .= ($date) ? date('dmH', strtotime($date)) : '';
        }

        return $string;
    }

    public static function getValueArr($array, $keys, $default = '')
    {
        if (!is_array($array)) {
            return $default;
        }

        $keys = explode('.', $keys);

        foreach ($keys as $key) {

            if (is_array($array) && array_key_exists($key, $array)) {
                $array = $array[$key];
            } else {
                return $default;
            }
        }

        return $array;
    }

    public static function generateRandomString($length = 9)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $password = '';
        $max = strlen($chars) - 1;

        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[random_int(0, $max)];
        }

        return $password;
    }

    public static function getIcons()
    {
        return array(
            'Facebook' => 'fab fa-facebook-f',
            'Facebook Messenger' => 'fab fa-facebook-messenger',
            'Instagram' => 'fab fa-instagram',
            'Twitter' => 'fab fa-twitter',
            'LinkedIn' => 'fab fa-linkedin-in',
            'Pinterest' => 'fab fa-pinterest-p',
            'YouTube' => 'fab fa-youtube',
            'WhatsApp' => 'fab fa-whatsapp',
            'WhatsApp Square' => 'fab fa-whatsapp-square',
            'Telegram' => 'fab fa-telegram-plane',
            'Snapchat' => 'fab fa-snapchat-ghost',
            'Reddit' => 'fab fa-reddit-alien',
            'Reddit Square' => 'fab fa-reddit-square',
            'TikTok' => 'fab fa-tiktok',
            'Tumblr' => 'fab fa-tumblr',
            'Tumblr Square' => 'fab fa-tumblr-square',
            'Twitch' => 'fab fa-twitch',
            'Discord' => 'fab fa-discord',
            'Skype' => 'fab fa-skype',
            'Vimeo' => 'fab fa-vimeo-v',
            'Vimeo Square' => 'fab fa-vimeo-square',
            'Vine' => 'fab fa-vine',
            'Flickr' => 'fab fa-flickr',
            'Dribbble' => 'fab fa-dribbble',
            'Behance' => 'fab fa-behance',
            'Behance Square' => 'fab fa-behance-square',
            'Medium' => 'fab fa-medium-m',
            'Mastodon' => 'fab fa-mastodon',
            'GitHub' => 'fab fa-github',
            'GitLab' => 'fab fa-gitlab',
            'Bitbucket' => 'fab fa-bitbucket',
            'Stack Overflow' => 'fab fa-stack-overflow',
            'Stack Exchange' => 'fab fa-stack-exchange',
            'CodePen' => 'fab fa-codepen',
            'JSFiddle' => 'fab fa-jsfiddle',
            'Dev' => 'fab fa-dev',
            'Npm' => 'fab fa-npm',
            'Google' => 'fab fa-google',
            'Google Drive' => 'fab fa-google-drive',
            'Google Play' => 'fab fa-google-play',
            'Apple' => 'fab fa-apple',
            'Apple Pay' => 'fab fa-apple-pay',
            'Amazon' => 'fab fa-amazon',
            'Amazon Pay' => 'fab fa-amazon-pay',
            'Microsoft' => 'fab fa-microsoft',
            'Windows' => 'fab fa-windows',
            'Android' => 'fab fa-android',
            'Dropbox' => 'fab fa-dropbox',
            'Slack' => 'fab fa-slack',
            'Trello' => 'fab fa-trello',
            'Yelp' => 'fab fa-yelp',
            'TripAdvisor' => 'fab fa-tripadvisor',
            'Product Hunt' => 'fab fa-product-hunt',
            'Kickstarter' => 'fab fa-kickstarter-k',
            'Patreon' => 'fab fa-patreon',
            'PayPal' => 'fab fa-paypal',
            'Stripe' => 'fab fa-stripe',
            'Etsy' => 'fab fa-etsy',
            'Airbnb' => 'fab fa-airbnb',
            'Spotify' => 'fab fa-spotify',
            'SoundCloud' => 'fab fa-soundcloud',
            'Last.fm' => 'fab fa-lastfm',
            'Last.fm Square' => 'fab fa-lastfm-square',
            'Mixcloud' => 'fab fa-mixcloud',
            'Bandcamp' => 'fab fa-bandcamp',
            'Goodreads' => 'fab fa-goodreads',
            'Goodreads G' => 'fab fa-goodreads-g',
            'Quora' => 'fab fa-quora',
            'WordPress' => 'fab fa-wordpress',
            'WordPress Simple' => 'fab fa-wordpress-simple',
            'Blogger' => 'fab fa-blogger',
            'Blogger B' => 'fab fa-blogger-b',
            'Weibo' => 'fab fa-weibo',
            'Weixin' => 'fab fa-weixin',
            'Line' => 'fab fa-line',
            'Viber' => 'fab fa-viber',
            'VK' => 'fab fa-vk',
            'QQ' => 'fab fa-qq',
            'Odnoklassniki' => 'fab fa-odnoklassniki',
            'Odnoklassniki Square' => 'fab fa-odnoklassniki-square',
            'Raspberry Pi' => 'fab fa-raspberry-pi',
            'Internet Explorer' => 'fab fa-internet-explorer',
            'Firefox' => 'fab fa-firefox',
            'Chrome' => 'fab fa-chrome',
            'Safari' => 'fab fa-safari',
            'Edge' => 'fab fa-edge',
            'Opera' => 'fab fa-opera',
        );
    }
}
