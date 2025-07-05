<?php
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');

global $APPLICATION;
$APPLICATION->SetTitle("Прайс-лист");
$APPLICATION->SetAdditionalCSS('/local/assets/css/pricelist.css');
?><?$APPLICATION->IncludeComponent(
	"sysp:pricelist",
	"",
Array()
);?><?php require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');?>