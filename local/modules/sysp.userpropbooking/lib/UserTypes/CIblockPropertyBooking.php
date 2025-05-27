<?php

namespace Sysp\UserPropBooking\UserTypes;

use Bitrix\Main;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Iblock;

class CIblockPropertyBooking
{
    /**
     * Метод возвращает массив описания собственного типа свойств
     * @return array
     */
    public static function GetUserTypeDescription(): array
    {
        echo "GetUserTypeDescription";

        return [
            'PROPERTY_TYPE' => Iblock\PropertyTable::TYPE_STRING,
            'USER_TYPE' => 'otus_booking',
            'DESCRIPTION' => Loc::getMessage('PROPERTY_DESCRIPTION'),
            'CLASS_NAME' => __CLASS__,
            'GetPublicViewHTML' => [__CLASS__, 'GetPublicViewHTML'],
            'GetPublicEditHTML' => [__CLASS__, 'GetPublicEditHTML'],
            'GetAdminListViewHTML' => [__CLASS__, 'GetAdminListViewHTML'],
            'GetPropertyFieldHtml' => [__CLASS__, 'GetPropertyFieldHtml'],
        ];
    }

    public static function GetPublicViewHTML($arProperty, $value, $arHtmlControl)
    {
//        echo '<pre>';
//        echo "arProperty:";
//        var_dump($arProperty);
//        echo "--------------------\n\n";
//        echo "value:";
//        var_dump($value);
//        echo "--------------------\n\n";
//        echo "arHtmlControl:";
//        var_dump($arHtmlControl);
//        echo "--------------------\n\n\n\n";
        return $arProperty["ELEMENT_ID"];
    }
    public static function GetPublicEditHTML($arProperty, $value, $arHtmlControl)
    {
        return '<span>' . Loc::getMessage('EDITING_IS_UNMEAN') . '</span>';
    }

    public static function GetAdminListViewHTML($arProperty, $value, $strHTMLControlName)
    {
        return '<span>' . Loc::getMessage('FIELD_IS_UNAVAILABLE') . '</span>';
    }

    public static function GetPropertyFieldHtml($arProperty, $value, $arHtmlControl)
    {
        return '<span class="main-grid-cell-content">' . Loc::getMessage('EDITING_IS_UNMEAN') . '</span>';
    }
}
