<?php

namespace Otus\UserTypes;

use Bitrix\Main\UserField\Types\BaseType;
use Bitrix\Main\UserField\Types\StringType;

class CoordsType extends BaseType
{
    public const USER_TYPE_ID = 'otus_coords';
    public const RENDER_COMPONENT = 'otus:usertype.coords';

    public static function getDescription(): array {
        return [
            'DESCRIPTION' => 'Otus Координаты',
            'BASE_TYPE' => \CUserTypeManager::BASE_TYPE_STRING,
        ];
    }

    public static function getDbColumnType(): string
    {
        return 'tinytext';
    }

    public static function prepareSettings(array $userField): array
    {
        return [
            'PATTERN' => $userField['SETTINGS']['PATTERN'] ?? null,
        ];
    }
}