<?php

require_once(__DIR__ . '/crest.php');

const VALID_PROVIDER_TYPES = ['CALL', 'EMAIL', 'TASKS_TASK'];

$activityId = (int)$_REQUEST['data']['FIELDS']['ID'];

$arActivity = CRest::call('crm.activity.get', ['id' => $activityId]);

if (
    empty($arActivity['result'])
    || !in_array($arActivity['result']['PROVIDER_TYPE_ID'], VALID_PROVIDER_TYPES)) {
    return;
}

$arContacts = [];
$ownerId = $arActivity['result']['OWNER_ID'];
$ownerType = $arActivity['result']['OWNER_TYPE_ID'];

switch ($ownerType) {
    case 1: //LEAD
    case 2: //DEAL
    case 4: //COMPANY
        $arContacts = getEntityContacts($ownerType, $ownerId);
        break;
    case 3: //CONTACT
        $arContacts = [$ownerId];
        break;
}

if (count($arContacts) == 0) {
    return;
}

if (count($arContacts) == 1) {
    CRest::call('crm.contact.update', [
        'id' => $arContacts[0],
        'FIELDS' => [
            'UF_CRM_1750590207' => $arActivity['result']['CREATED'],
        ]
    ]);
} else {
    $batch = [];

    //чтобы не усложнять алгоритм, предполагаю, что к сущности не будет привязано больше 50 контактов ))
    foreach ($arContacts as $arContact) {
        $batch[] = [
            'method' => 'crm.contact.update',
            'params' => [
                'id' => $arContact,
                'FIELDS' => [
                    'UF_CRM_1750590207' => $arActivity['result']['CREATED'],
                ]
            ]
        ];
    }

    CRest::callBatch($batch, 1);
}

function getEntityContacts(string $entityTypeId, int $entityId): array
{
    $entityTypes = [
        1 => 'lead',
        2 => 'deal',
        3 => 'company',
    ];
    $entityType = $entityTypes[$entityTypeId] ?? null;

    if (!$entityType) {
        return [];
    }

    $result = CRest::call("crm.{$entityType}.contact.items.get", ['id' => $entityId]);

    return !empty($result['result'])
        ? array_column($result['result'], 'CONTACT_ID')
        : [];
}
