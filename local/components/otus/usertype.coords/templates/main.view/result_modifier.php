<?php

/** @var array $arResult */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}

$pattern = $arResult['userField']['SETTINGS']['PATTERN'] ?? '##X##, ##Y##';

[$x, $y] = explode('|', $arResult['userField']['VALUE']);

$arResult['userField']['FORMATTED_VALUE'] = str_replace(
    ['##X##', '##Y##'],
    [$x, $y],
    $pattern
);
