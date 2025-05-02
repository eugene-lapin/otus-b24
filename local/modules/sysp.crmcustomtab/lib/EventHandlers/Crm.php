<?php

namespace Sysp\CrmCustomTab\EventHandlers;

use Bitrix\Main\Event;
use Bitrix\Main\EventResult;
use Bitrix\Main\Localization\Loc;

class Crm
{
    public static function updateTabs(Event $event): EventResult
    {
        $entityTypeId = $event->getParameter('entityTypeID');
        $entityId = $event->getParameter('entityID');
        $tabs = $event->getParameter('tabs');

        $tabs[] = [
            'id' => 'pricelist_tab_' . $entityTypeId . '_' . $entityId,
            'name' => 'My Tab',
            'enabled' => true,
            'html' => '<h1>My Test Tab</h1>',
        ];

        return new EventResult(EventResult::SUCCESS, ['tabs' => $tabs,]);
    }
}