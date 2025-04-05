<?php

namespace Otus\Models\Doctors;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;

class Model
{
    /**
     * @return array|null
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function getAll(): ?array
    {
        return DoctorsTable::getList([
                'select' => [
                    'LASTNAME',
                    'FIRSTNAME',
                    'PATRONYMIC',
                    'SLUG' => 'ELEMENT.NAME',
                ],
                'filter' => [
                    'ELEMENT.ACTIVE' => 'Y',
                ],
        ])->fetchAll();
    }

    /**
     * @return array|null
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function getAllProcedures(): ?array
    {
        return ProceduresTable::getList([
            'select' => [
                'ID' => 'IBLOCK_ELEMENT_ID',
                'NAME' => 'ELEMENT.NAME',
            ],
            'filter' => [
                'ELEMENT.ACTIVE' => 'Y',
            ],
            'order' => [
                'NAME' => 'ASC',
            ]
        ])->fetchAll();
    }

    /**
     * @param string $code
     * @return array|false
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function getDoctor(string $code): ?array
    {
        return DoctorsTable::getList([
            'select' => [
                'IBLOCK_ELEMENT_ID',
                'LASTNAME',
                'FIRSTNAME',
                'PATRONYMIC',
                'SLUG' => 'ELEMENT.NAME',
                'PROCS_IDS' => 'PROCEDURES',
                'PROCS' => 'PROCEDURES_ELEMENT_NAME',
            ],
            'filter' => [
                'ELEMENT.ACTIVE' => 'Y',
                '=SLUG' => $code,
            ],
        ])->fetch();
    }

    /**
     * @param array $data
     * @return string|null
     */
    public function add(array $data)
    {
        $slug = $this->translit_sef($data['LASTNAME']) ?? 'unnamed' . time();

        $result = DoctorsTable::add([
            'NAME' => $slug,
            'LASTNAME' => $data['LASTNAME'],
            'FIRSTNAME' => $data['FIRSTNAME'],
            'PATRONYMIC' => $data['PATRONYMIC'],
            'PROCEDURES' => $data['PROCS'],
        ]);

        return ($result) ? $slug : null;
    }

    /**
     * @param array $data
     * @return void
     * @throws LoaderException
     */
    public function update(array $data)
    {
        DoctorsTable::update($data['ID'], [
            'ELEMENT.NAME' => $data['SLUG'],
            'LASTNAME' => $data['LASTNAME'],
            'FIRSTNAME' => $data['FIRSTNAME'],
            'PATRONYMIC' => $data['PATRONYMIC'],
        ]);

        if (Loader::includeModule('iblock')) {
            $el = new \CIBlockElement();
            $el->SetPropertyValuesEx(
                $data['ID'],
                DoctorsTable::IBLOCK_ID,
                ['PROCEDURES' => $data['PROCS']]
            );
        }
    }

    /**
     * @param array $data
     * @return bool|null
     */
    public function addProc(array $data)
    {
        $result = ProceduresTable::add([
            'NAME' => $data['NAME'],
        ]);

        return $result ?: null;
    }

    /**
     * @param $value
     * @return string
     */
    private function translit_sef($value)
    {
        $converter = array(
            'а' => 'a',    'б' => 'b',    'в' => 'v',    'г' => 'g',    'д' => 'd',
            'е' => 'e',    'ё' => 'e',    'ж' => 'zh',   'з' => 'z',    'и' => 'i',
            'й' => 'y',    'к' => 'k',    'л' => 'l',    'м' => 'm',    'н' => 'n',
            'о' => 'o',    'п' => 'p',    'р' => 'r',    'с' => 's',    'т' => 't',
            'у' => 'u',    'ф' => 'f',    'х' => 'h',    'ц' => 'c',    'ч' => 'ch',
            'ш' => 'sh',   'щ' => 'sch',  'ь' => '',     'ы' => 'y',    'ъ' => '',
            'э' => 'e',    'ю' => 'yu',   'я' => 'ya',
        );

        $value = mb_strtolower($value);
        $value = strtr($value, $converter);
        $value = mb_ereg_replace('[^-0-9a-z]', '-', $value);
        $value = mb_ereg_replace('[-]+', '-', $value);
        $value = trim($value, '-');

        return $value;
    }
}