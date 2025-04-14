<?php

use Bitrix\Main\Entity;
use Bitrix\Main\Loader;
use Bitrix\Currency\CurrencyTable;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

class CurrenciesComponent extends CBitrixComponent
{
    private function _checkModules()
    {
        if(!Loader::includeModule('currency')) {
            throw new \Exception("The modules necessary for the component's operation are not loaded");
        }

        return true;
    }

    public function executeComponent()
    {
        $this->_checkModules();

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
            ->setSelect(['CURRENCY', 'AMOUNT', 'NAME' => 'LANG.FULL_NAME',]);
        $result = $query->exec();

        $this->arResult['CURRENCY_LIST'] = [];
        while ($currency = $result->fetch()) {
            $this->arResult['CURRENCY_LIST'][$currency['CURRENCY']] = [
                'NAME' => $currency['NAME'],
                'AMOUNT' => floatval($currency['AMOUNT']) ?: 0
            ];
        }

        $this->IncludeComponentTemplate();
    }
}