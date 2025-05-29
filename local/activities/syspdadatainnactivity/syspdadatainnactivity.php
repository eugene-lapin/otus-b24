<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Bizproc\Activity\BaseActivity;
use Bitrix\Bizproc\FieldType;
use Bitrix\Bizproc\Workflow\Type\GlobalConst;
use Bitrix\Main\ErrorCollection;
use Bitrix\Main\Localization\Loc;
use Bitrix\Bizproc\Activity\PropertiesDialog;

class CBPSyspDadataInnActivity extends BaseActivity
{
    /**
     * @see parent::_construct()
     * @param $name string Activity name
     */
    public function __construct($name)
    {
        parent::__construct($name);

        $this->arProperties = [
            'Inn' => '',

            // return
            'Company' => null,
        ];

        $this->SetPropertiesTypes([
            'Company' => ['Type' => FieldType::STRING],
        ]);
    }

    /**
     * Return activity file path
     * @return string
     */
    protected static function getFileName(): string
    {
        return __FILE__;
    }

    /**
     * @return ErrorCollection
     */
    protected function internalExecute(): ErrorCollection 
    {
        $errors = parent::internalExecute();

        $token = GlobalConst::getValue('Constant1748531329152') ?? "23a4b16bb1c80960d153de981f68558f59a7c786";
        $secret = GlobalConst::getValue('Constant1748531348185') ?? "03aa1af69beaf6ed8f5f91ad6e41ee8a2fb6f434";
        $dadata = new \Dadata\DadataClient($token, $secret);
        $response = $dadata->findById("party", $this->Inn);

        $companyName = 'Компания не найдена!';
        if(!empty($response) && isset($response[0]['value'])){
           $companyName = $response[0]['value'];
        }

        $this->preparedProperties['Company'] = $companyName;
        $this->log($this->preparedProperties['Company']);

        return $errors;
    }

    /**
     * @param PropertiesDialog|null $dialog
     * @return array[]
     */
    public static function getPropertiesDialogMap(?PropertiesDialog $dialog = null): array
    {
        $map = [
            'Inn' => [
                'Name' => Loc::getMessage('SYSPDADATAINN_FIELD_INN'),
                'FieldName' => 'Inn',
                'Type' => FieldType::STRING,
                'Required' => true,
                'Options' => [],
            ],
        ];
        return $map;
    }
}