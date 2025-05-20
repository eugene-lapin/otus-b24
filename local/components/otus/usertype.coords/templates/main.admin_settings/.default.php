<?php

if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arResult
 * @var array $additionalParameters
 */
$additionalParameters = $arResult['additionalParameters'];

/*echo "<pre>";
print_r($arParams);
print_r($arResult);*/
?>

<tr>
    <td>
        Паттерн вывода:
    </td>
    <td>
        <input
            type="text"
            name="<?= $additionalParameters['NAME'] ?>[PATTERN]"
            size="20"
            maxlength="225"
            value="<?= $arResult['userField'][$additionalParameters['NAME']]['PATTERN'] ?>"
        >
    </td>
</tr>