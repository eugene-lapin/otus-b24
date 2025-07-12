<?php

use Bitrix\Main\EventManager;

if (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
    require_once(__DIR__ . '/../../vendor/autoload.php');
}

if (file_exists(__DIR__ . '/src/autoloader.php')) {
    require_once (__DIR__ . '/src/autoloader.php');
}

//\Bitrix\Main\UI\Extension::load(['otus.workday_confirm']);

EventManager::getInstance()->addEventHandler(
    'iblock',
    'OnAfterIBlockElementUpdate',
    ['Otus\EventHandlers\Iblock', 'updateDealAfterRequestChange']
);

EventManager::getInstance()->addEventHandler(
    'crm',
    'OnAfterCrmDealUpdate',
    ['Otus\EventHandlers\Crm', 'updateRequestsAfterDealChange']
);

EventManager::getInstance()->addEventHandlerCompatible(
    'rest',
    'OnRestServiceBuildDescription',
    ['Otus\EventHandlers\RestDoctors', 'OnRestServiceBuildDescriptionHandler']
);
