<?php

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');

$date = date('Y-m-d G:i:s');
file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/logs/debug.log', $date . PHP_EOL, FILE_APPEND);

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');