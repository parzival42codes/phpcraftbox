<?php

class ResourcesIcons extends Base
{

    /**
     * @var array
     */
    protected static array $stdIcons
        = [
            'char_yes'   => '&#10003;',
            'char_no'    => 'X',
            //            'bust_in_silhouette' => '&#1F464;',
            'edit'       => '&#x1F58A;',
            'delete'     => '&#128465;',
            'spider_web' => '&#128376;',
            'switch_on'  => '<span class="icon--switch"><span class="icon--switch-on">&nbsp;</span>',
        ];

    /**
     * @var array
     */
    protected static array $registerIcons = [];

    public function __construct()
    {

    }

    public static function getIcon(string $icon): string
    {
        return (self::$registerIcons[$icon] ?? (self::$stdIcons[$icon] ?? ''));
    }

    public static function register(string $key, string $value):void
    {
        self::$registerIcons[$key] = $value;
    }

}
