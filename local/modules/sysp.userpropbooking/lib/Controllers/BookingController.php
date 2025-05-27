<?php

namespace Sysp\UserPropBooking\Controllers;

use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Loader;

class BookingController extends Controller
{
    public function configureActions()
    {
        return [
            'create' => [
                'prefilters' => [],
            ],
        ];
    }

    public function createAction($dateTime, $doctorId, $procedureId)
    {
        return ['status' => 'success', 'id' => 1];
    }
}