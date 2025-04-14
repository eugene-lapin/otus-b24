<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Курсы валют");
?><?$APPLICATION->IncludeComponent(
	"otus:currencies",
	"",
	Array(
		"CURRENCY" => "EUR"
	)
);?><?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>