<?php
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');

use Bitrix\Main\Entity\Query;
use Otus\Models\Doctors\PriceListTable;

global $APPLICATION;
$APPLICATION->SetTitle('Прайс-лист');
$APPLICATION->SetAdditionalCSS('/local/assets/css/pricelist.css');

$q = new Query(PriceListTable::getEntity());
$q->setSelect([
    '*',
    'DOCTOR_' => 'DOCTOR',
    'DOCTOR_NAME' => 'DOCTOR.ELEMENT.NAME',
    'PROCEDURE_' => 'PROCEDURE',
    'PROCEDURE_NAME' => 'PROCEDURE.ELEMENT.NAME',
]);
$q->setOrder(['PROCEDURE_NAME' => 'ASC']);
$q->setCacheTtl(600);
$q->cacheJoins(true);
$res = $q->exec();
$pricelist = $res->fetchAll();

if (!empty($pricelist)) {
    echo <<<END
    <table class="pricelist">
        <tr>
            <th>Процедура</th>
            <th>Врач</th>
            <th>Стоимость</th>
            <th>Рекомендации</th>
        </tr>
    END;

    foreach ($pricelist as $position) {
        echo <<<END
        <tr>
            <td>{$position['PROCEDURE_NAME']}</td>
            <td>
                <a href="/doctors/{$position['DOCTOR_NAME']}">
                    {$position['DOCTOR_LASTNAME']}
                    {$position['DOCTOR_FIRSTNAME']}
                    {$position['DOCTOR_PATRONYMIC']}
                </a>
            </td>
            <td>{$position['COST']}</td>
            <td>{$position['RECOMMENDATIONS']}</td>
        </tr>   
        END;
    }

    echo '</table>';
} else {
    'Прайс-лист пуст. Добавьте позиции в таблицу otus_price_list';
}

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
