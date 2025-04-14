<?php

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

use Bitrix\Main\Grid\Options as GridOptions;
use Bitrix\Main\UI\Filter\Options as FilterOptions;
use Bitrix\Main\Localization\Loc;

$gridId = 'currency_rate_grid';
$filterId = 'currency_rate_filter';

$currencyList = [];
foreach ($arResult['CURRENCY_LIST'] as $code => $data) {
    $currencyList[$code] = $data['NAME'];
}

$APPLICATION->IncludeComponent('bitrix:main.ui.filter', '', [
    'FILTER_ID' => $filterId,
    'GRID_ID' => $gridId,
    'FILTER' => [
        [
            'id' => 'CURRENCY',
            'name' => Loc::getMessage("CURRENCY"),
            'type' => 'list',
            'items' => $currencyList,
            'params' => [
                'multiple' => 'N'
            ],
        ],
    ],
    'ENABLE_LIVE_SEARCH' => false,
    'ENABLE_LABEL' => true,
]);

$filterOptions = new FilterOptions($filterId);
$filterData = $filterOptions->getFilter([]);
$selectedCurrency = $filterData['CURRENCY'] ?: $arParams['CURRENCY'];
$gridOptions = new GridOptions($gridId);
$gridColumns = [
    ['id' => 'CURRENCY', 'name' => Loc::getMessage('CURRENCY_COLUMN'), 'sort' => 'CURRENCY', 'default' => true],
    ['id' => 'AMOUNT', 'name' => Loc::getMessage('AMOUNT_COLUMN'), 'sort' => 'AMOUNT', 'default' => true],
];
$gridData = [
    [
        'data' => [
            'CURRENCY' => $selectedCurrency,
            'AMOUNT' => $arResult['CURRENCY_LIST'][$selectedCurrency]['AMOUNT'],
        ],
    ],
];

$APPLICATION->IncludeComponent(
    'bitrix:main.ui.grid',
    '',
    [
        'GRID_ID' => $gridId,
        'COLUMNS' => $gridColumns,
        'ROWS' => $gridData,
        'NAV_OBJECT' => null,
        'AJAX_MODE' => 'Y',
        'AJAX_ID' => \CAjax::getComponentID('bitrix:main.ui.grid', '.default', ''),
        'AJAX_OPTION_JUMP' => 'N',
        'AJAX_OPTION_HISTORY' => 'N',
        'ALLOW_COLUMNS_SORT' => false,
        'ALLOW_COLUMNS_RESIZE' => true,
        'ALLOW_HORIZONTAL_SCROLL' => true,
        'ALLOW_SORT' => false,
        'ALLOW_PIN_HEADER' => false,
        'SHOW_ROW_CHECKBOXES' => false,
        'SHOW_CHECK_ALL_CHECKBOXES' => false,
        'SHOW_ROW_ACTIONS_MENU' => false,
        'SHOW_GRID_SETTINGS_MENU' => false,
        'SHOW_NAVIGATION_PANEL' => false,
        'SHOW_PAGINATION' => false,
        'SHOW_SELECTED_COUNTER' => false,
        'SHOW_TOTAL_COUNTER' => false,
        'SHOW_PAGESIZE' => false,
        'SHOW_ACTION_PANEL' => false,
    ]
);

//\Bitrix\Main\Diag\Debug::dump($arParams);
//\Bitrix\Main\Diag\Debug::dump($arResult);
?>

<script>
    BX.ready(function(){
        filterInstance = BX.Main.filterManager.getById('<?= $filterId ?>');
        filterInstance.getApi().setFields({"CURRENCY": "<?= $selectedCurrency ?>"});
    });
</script>

