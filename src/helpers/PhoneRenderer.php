<?php

/**
 * Created by PhpStorm.
 * User: Ondrej
 * Date: 16-May-17
 * Time: 20:12
 */
class PhoneRenderer
{
    public static function renderPhone($phoneNumber, $advertId = null, $itemProp = false)
    {
        $firstPart = substr($phoneNumber, 0, 4);
        $advertData = "data-advert-id='{$advertId}'";

        $itemprop = $itemProp? "itemprop=\"telephone\" content=\"{$phoneNumber}\"" : '';

        return "<a {$itemprop} href='#' data-phone='{$phoneNumber}' {$advertData} class='hidden-phone-number'><strong>{$firstPart}</strong> (zobrazit kontakt)</a>";
    }
}