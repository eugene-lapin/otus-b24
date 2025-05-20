<?php

if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var StringUfComponent $component
 * @var array $arResult
 */

[$x, $y] = explode('|', $arResult['userField']['VALUE']);

$arResult['userField']['FORMATTED_VALUE'] = [
    'x' => $x,
    'y' => $y
];

/*echo '<pre>';
print_r($arResult);*/