<?php

if (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
    require_once(__DIR__ . '/../../vendor/autoload.php');
}

if (file_exists(__DIR__ . '/src/autoloader.php')) {
    require_once (__DIR__ . '/src/autoloader.php');
}

\Bitrix\Main\UI\Extension::load(['otus.workday_confirm']);