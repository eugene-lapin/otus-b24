<?php

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Component\BaseUfComponent;
use Bitrix\Main\Localization\Loc;
use Otus\UserTypes\CoordsType;

\Bitrix\Main\Loader::includeModule('location');

/**
 * Class AddressUfComponent
 */
class AddressUfComponent extends BaseUfComponent
{
	protected static function getUserTypeId(): string
	{
		return CoordsType::USER_TYPE_ID;
	}

	public function getLocationModuleMessage(): string
	{
		return Loc::getMessage('FILEMAN_ADDRESS_UF_LOCATION_NOTICE');
	}
}
