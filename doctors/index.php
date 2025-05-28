<?php
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');

\Bitrix\Main\Page\Asset::getInstance()->addCss('/local/assets/css/doctors.css');
require_once 'router.php';

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
