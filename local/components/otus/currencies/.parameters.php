<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Entity;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Currency\CurrencyTable;

if(!Loader::includeModule('currency')) {
    throw new \Exception("The modules necessary for the component's operation are not loaded");
}

$query = new Entity\Query(CurrencyTable::getEntity());
$query
    ->registerRuntimeField(
        'LANG',
        [
            'data_type' => 'Bitrix\Currency\CurrencyLangTable',
            'reference' => [
                '=this.CURRENCY' => 'ref.CURRENCY',
                '=ref.LID' => ['?', LANGUAGE_ID],
            ],
            'join_type' => 'LEFT'
        ]
    )
    ->setSelect(['CURRENCY', 'NAME' => 'LANG.FULL_NAME',]);
$result = $query->exec();

$currencyList = [];
while ($currency = $result->fetch()) {
    $currencyList[$currency['CURRENCY']] = $currency['NAME'];
}

$arComponentParameters = [
    "PARAMETERS" => [
        "CURRENCY" => [
            "PARENT" => "BASE",
            "NAME" => Loc::getMessage("CURRENCY"),
            "TYPE" => "LIST",
            "VALUES" => $currencyList,
            "DEFAULT" => "USD",
        ]
    ]
];