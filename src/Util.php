<?php

namespace NinthCube;

class Util
{
    /*
     * constant tester
     */
    public static function constant($c, $default = "")
    {
        //return defined($c) ? $c : $default;
        return get_defined_constants()[$c] ?? $default;
    }

    /*
     * make a simple password
     */
    public static function generate_password($length = 16): ?string
    {
        $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_:(){}[]0123456789;';
        $max = strlen($str);
        $length = (int) $length;
        if ($length < 1) {
            $length = random_int(8, 12);
        }
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $str[random_int(0, $max - 1)];
        }
        return $password;
    }

    /*
     * gets a contrasting text color based on background color
     */
    public static function get_contrast_color($hex_color): string
    {
        // ensure the hex color has the octothorp
        if ('#' != substr($hex_color, 0, 1)) {
            $hex_color = "#".$hex_color;
        }

        // calculate relative luminance of the input color
        $R1 = hexdec(substr($hex_color, 1, 2));
        $G1 = hexdec(substr($hex_color, 3, 2));
        $B1 = hexdec(substr($hex_color, 5, 2));

        $luminance = 0.2126 * pow($R1 / 255, 2.2) +
                     0.7152 * pow($G1 / 255, 2.2) +
                     0.0722 * pow($B1 / 255, 2.2);

        // contrast ratio against black (luminance 0)
        $contrastRatio = (int)(($luminance + 0.05) / 0.05);

        // if contrast is more than 5, return black, otherwise, return white
        return ($contrastRatio > 5) ? '#000' : '#fff';
    }

    /*
     * a display function for join a list for display in english (WITH Oxford comma)
     */
    public static function join_with_and($array): string
    {
        return join(', and ', array_filter(array_merge(array(join(', ', array_slice($array, 0, -1))), array_slice($array, -1)), 'strlen'));
    }

    /*
     * enlongify a non-base number
     */
    public static function longify($input): string
    {
        return base_convert($input, 36, 10);
    }

    /*
     * CSS minify function
     * accepts CSS as a string
     * credit: https://gist.github.com/Rodrigo54/93169db48194d470188f
     */
    public static function minify_css($input): string
    {
        if (trim($input) === "") {
            return $input;
        }
        return preg_replace(
            array(
                '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')|\/\*(?!\!)(?>.*?\*\/)|^\s*|\s*$#s',
                '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~]|\s(?![0-9\.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si',
                '#(?<=[\s:])(0)(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)#si',
                '#:(0\s+0|0\s+0\s+0\s+0)(?=[;\}]|\!important)#i',
                '#(background-position):0(?=[;\}])#si',
                '#(?<=[\s:,\-])0+\.(\d+)#s',
                '#(\/\*(?>.*?\*\/))|(?<!content\:)([\'"])([a-z_][a-z0-9\-_]*?)\2(?=[\s\{\}\];,])#si',
                '#(\/\*(?>.*?\*\/))|(\burl\()([\'"])([^\s]+?)\3(\))#si',
                '#(?<=[\s:,\-]\#)([a-f0-6]+)\1([a-f0-6]+)\2([a-f0-6]+)\3#i',
                '#(?<=[\{;])(border|outline):none(?=[;\}\!])#',
                '#(\/\*(?>.*?\*\/))|(^|[\{\}])(?:[^\s\{\}]+)\{\}#s'
            ),
            array(
                '$1',
                '$1$2$3$4$5$6$7',
                '$1',
                ':0',
                '$1:0 0',
                '.$1',
                '$1$3',
                '$1$2$4$5',
                '$1$2$3',
                '$1:0',
                '$1$2'
            ),
            $input
        );
    }

    /*
     * create a hexadecimal security "hash"
     */
    public static function new_hash($len = 29): string
    {
        if ($len < 2) {
            $len = 20;
        }
        return preg_replace("/[^A-Za-z0-9 ]/", '', base64_encode(random_bytes($len)));
    }

    /*
     * encode an email address for public display
     */
    public static function obfuscate($email): string
    {
        $encoded_email = '';
        $b = strlen($email);
        for ($a = 0; $a < $b; $a++) {
            $encoded_email .= '&#'.(random_int(0, 1) == 0 ? 'x'.dechex(ord($email[$a])) : ord($email[$a])) . ';';
        }
        return (string) $encoded_email;
    }

    /*
     * convert a number to its ordinal form
     */
    public static function ordinal($number, $format = true)
    {
        $ends = array('th','st','nd','rd','th','th','th','th','th','th');
        if ((($number % 100) >= 11) && (($number % 100) <= 13)) {
            return $format ? number_format($number). 'th' : $number.'th';
        } else {
            return $format ? number_format($number). $ends[$number % 10] : $number.$ends[$number % 10];
        }
    }

    /*
     * date format function
     */
    public static function preferred_date_format($mydate, $fmt = null)
    {
        if (!defined('SETLIST_DATE_FORMAT')) {
            define('SETLIST_DATE_FORMAT', 'Y-m-d');
        }
        if (!$fmt) {
            $fmt = SETLIST_DATE_FORMAT;
        }
        $DateObj = \DateTime::createFromFormat('Y-m-d', $mydate);
        if ($DateObj === false) {
            return $mydate;
        }
        return $DateObj->format($fmt);
    }

    /*
     * shorten an integer
     */
    public static function shortify($input): string
    {
        return (isset($input)) ? base_convert($input, 10, 36) : '';
    }

    /*
     * create a URL-friendly slug from a string
     */
    public static function slugify($str): string
    {
        if ($str === null || $str === '') {
            return '';
        }

        // Use intl transliterator if available, fall back to manual map
        if (function_exists('transliterator_transliterate')) {
            $str = transliterator_transliterate('Any-Latin; Latin-ASCII', $str);
        } else {
            $unwanted_array = [
                'Š' => 'S', 'š' => 's', 'Ž' => 'Z', 'ž' => 'z',
                'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A',
                'Ç' => 'C', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E',
                'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
                'Đ' => 'D', 'đ' => 'd', 'Ł' => 'L', 'ł' => 'l',
                'Ñ' => 'N', 'ñ' => 'n',
                'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'ő' => 'o',
                'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'ű' => 'u',
                'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss',
                'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'a',
                'ç' => 'c', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e',
                'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
                'ð' => 'o', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o',
                'ù' => 'u', 'ú' => 'u', 'û' => 'u',
                'ý' => 'y', 'þ' => 'b', 'ÿ' => 'y',
                'ğ' => 'g', 'Ğ' => 'G', 'ş' => 's', 'Ş' => 'S',
                'İ' => 'I', 'ı' => 'i',
            ];
            $str = strtr($str, $unwanted_array);
        }

        $str = preg_replace('/[^a-zA-Z0-9 -]/', '', $str);
        $str = strtolower(str_replace(' ', '-', trim($str)));
        $str = preg_replace('/-+/', '-', $str);
        $str = trim($str, '-');
        return $str;
    }

    /*
     * helps to prevent weird chars that come from copy/paste
     */
    public static function specialchars_cleaner($string): string
    {
        $quotes = array(
            "\xC2\xAB"   => '"', // « (U+00AB) in UTF-8
            "\xC2\xBB"   => '"', // » (U+00BB) in UTF-8
            "\xE2\x80\x98" => "'", // ' (U+2018) in UTF-8
            "\xE2\x80\x99" => "'", // ' (U+2019) in UTF-8
            "\xE2\x80\x9A" => "'", // ‚ (U+201A) in UTF-8
            "\xE2\x80\x9B" => "'", // ‛ (U+201B) in UTF-8
            "\xE2\x80\x9C" => '"', // " (U+201C) in UTF-8
            "\xE2\x80\x9D" => '"', // " (U+201D) in UTF-8
            "\xE2\x80\x9E" => '"', // „ (U+201E) in UTF-8
            "\xE2\x80\x9F" => '"', // ‟ (U+201F) in UTF-8
            "\xE2\x80\xB9" => "'", // ‹ (U+2039) in UTF-8
            "\xE2\x80\xBA" => "'", // › (U+203A) in UTF-8
        );
        $string = strtr($string, $quotes);

        $search = ["â€™" => "&rsquo;", chr(145) => "'", chr(146) => "'", chr(147) => '"', chr(148) => '"', chr(151) => ' - ' ];
        $string = str_replace(array_keys($search), $search, $string);

        return (string) $string;
    }

    /*
     * truncate_text
     * Only truncates if the savings are meaningful (more than 10 characters).
     * Otherwise returns the full text to avoid pointless "..." truncation.
     */
    public static function truncate_text($text, $length = 50, $strip_html = false): string
    {
        $mylength = strlen($text);
        if ($strip_html) {
            $mylength = strlen(strip_tags($text));
        }
        // don't bother truncating if we'd only save a few characters
        $buffer = 10;
        if ($mylength <= $length + $buffer) {
            return $text;
        }
        $text .= " ";
        $text = substr($text, 0, $length);
        $text = substr($text, 0, strrpos($text, ' '));
        $text .= "...";
        return $text;
    }

    /*
     * convert youtube URL into an embed
     */
    public static function youtube_embed(?string $str, int $w = 560, int $h = 315): string
    {
        if ($str === null || trim($str) === '') {
            return '';
        }

        // Extract video ID from common YouTube URL formats
        $pattern = '/(?:youtube\.com\/watch\?.*?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/i';

        if (preg_match($pattern, $str, $matches)) {
            $videoId = $matches[1];
            return '<div class="video-container">'
                . '<iframe width="' . $w . '" height="' . $h . '"'
                . ' src="https://www.youtube.com/embed/' . htmlspecialchars($videoId) . '"'
                . ' frameborder="0" allowfullscreen></iframe>'
                . '</div>';
        }

        return $str;
    }
}
