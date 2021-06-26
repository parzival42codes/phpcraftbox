<?php

class CoreClassesHelperText
{

    static string $convertArray = '';

    public static function convertView(string $content, ?string $find = null, bool $whiteSpacePre = true): string
    {
        $content = '<div style="display:inline-block;' . ($whiteSpacePre === true ? 'white-space:pre-wrap;' : '') . '">' . htmlentities($content) . '</div>';
        $content = strtr($content,
                         [
                             '{' => '&#123;',
                             '}' => '&#125;',
                         ]);
        if ($find !== null) {
            $content = strtr($content,
                             [
                                 $find => '<strong>' . $find . '</strong>'
                             ]);
        }


        return $content;
    }

    //                    <td>' . self::convertArrayView(($backtrace['args'] ?? [])) . '</td>
//    public static function convertArrayView($array) {
//        self::$convertArray = '';
//        self::convertArrayViewWorkArray($array);
//        return '<div class="ViewhelperArrayView">' . self::$convertArray . '</div>';
//    }
//
//    protected static function convertArrayViewWorkArray($array, $level = 0) {
//        if (count($array) > 0) {
//            foreach ($array as $key => $value) {
//                if (is_array($value)) {
//                    self::$convertArray .= '<div class="ViewhelperArrayViewHeader">' . str_repeat('&nbsp;', $level) . $key . '</div>';
//                    self::convertArrayViewWorkArray($value, $level++);
//                } elseif (is_object($value)) {
//                    self::$convertArray .= '<div class="ViewhelperArrayViewObject grid row">' . str_repeat('&nbsp;', $level) . get_class($value) . '</div><div class="ViewhelperArrayViewObjectContent grid row"><pre>' . var_export(($value), true) . '</pre></div>';
//                    //self::convertArrayViewWorkArray(get_object_vars($value), $level++)
//                } else {
//                    self::$convertArray .= '<div class="ViewhelperArrayViewContent grid row"><div class="grid grid-col-3-12">' . $key . '</div><div class="grid grid-col-9-12">' . $value . '</div></div>';
//                }
//            }
//        }
//    }
    public static function getTextShyWrap(string $filename): string
    {
        return preg_replace('@([\/\\\.\#\_])@si',
                            '$1&shy;',
                            $filename);
    }

}
