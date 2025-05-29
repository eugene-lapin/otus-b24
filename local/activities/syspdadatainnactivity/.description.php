<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use \Bitrix\Main\Localization\Loc;

$arActivityDescription = [
    "NAME" => Loc::getMessage("SYSPDADATAINN_DESCR_NAME"),
    "DESCRIPTION" => Loc::getMessage("SYSPDADATAINN_DESCR_DESCR"),
    "TYPE" => "activity",
    "CLASS" => "SyspDadataInnActivity",
    "JSCLASS" => "BizProcActivity",
    "CATEGORY" => [
        "ID" => "other",
    ],
    "RETURN" => [
        "Company" => [
            "NAME" => Loc::getMessage("SYSPDADATAINN_FIELD_COMPANY"),
            "TYPE" => "string",
        ],
    ],
];