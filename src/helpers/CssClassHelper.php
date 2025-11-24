<?php
/**
 * Created by PhpStorm.
 * User: ondrejbohac
 * Date: 14.08.16
 * Time: 14:36
 */

namespace Portal\Helpers\CssClassHelper;


class CssClassHelper
{
    private static $propertyCSSIcons = array(
        2 => 'icon-sale',
        3 => 'icon-rental',
        5 => 'icon-auction',
        7  => 'icon-appartment',
        8  => 'icon-house',
        9  => 'icon-recreation',
        10 => '',
        11 => 'icon-commerce',
        12 => 'icon-land',
        13 => 'icon-others'
    );

    public static function cssIcon($propertyKey)
    {
        if(isset(self::$propertyCSSIcons[$propertyKey]))
        {
            return self::$propertyCSSIcons[$propertyKey];
        }

        return '';
    }
}