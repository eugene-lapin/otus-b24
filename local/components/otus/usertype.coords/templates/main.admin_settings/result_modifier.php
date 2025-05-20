<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

if (!isset($arResult['userField']) || count($arResult['userField']) == 0) {
    $arResult['userField']['SETTINGS']['PATTERN'] = '##X##, ##Y##';
}