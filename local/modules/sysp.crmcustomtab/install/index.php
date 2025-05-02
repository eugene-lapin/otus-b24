<?php

use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\EventManager;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\SystemException;
use Bitrix\Iblock\IblockTable;
use Bitrix\Main\IO\Directory;
use Bitrix\Main\IO\InvalidPathException;

class sysp_crmcustomtab extends CModule
{
    private $availableEntities = ['LEAD', 'DEAL', 'CONTACT', 'COMPANY'];
    public $MODULE_ID;
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $PARTNER_NAME;
    public $PARTNER_URI;

    public function __construct()
    {
        include __DIR__ . '/version.php';

        /** @var array $arModuleVersion array from version.php */
        $this->MODULE_ID = 'sysp.crmcustomtab';
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME = Loc::getMessage('MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('MODULE_DESCRIPTION');
        $this->PARTNER_NAME = Loc::getMessage('PARTNER_NAME');
        $this->PARTNER_URI = Loc::getMessage('PARTNER_URI');
    }

    public function DoInstall()
    {
        if (!$this->isVersionD7() || !$this->hasRequiredModules()) {
            throw new SystemException(Loc::getMessage('INSTALL_ERRORS'));
        }

        global $APPLICATION;
        $context = Application::getInstance()->getContext();
        $request = $context->getRequest();
        $step = $request->getPost("step");

        if ($step < 2) {
            $APPLICATION->IncludeAdminFile(
                Loc::getMessage('INSTALL_TITLE_STEP_1'),
                __DIR__ . '/step1.php'
            );
        } elseif ($step == 2) {
            $entityType = $request->getPost('ENTITY_TYPE');
            $doctorsIblockId = intval($request->getPost('DOCTORS_IBLOCK_ID'));
            $proceduresIblockId = intval($request->getPost('PROCEDURES_IBLOCK_ID'));
            $demoData = $request->getPost('DEMO_DATA');

            $errors = [];
            $validValues = [];

            if (!in_array($entityType, $this->availableEntities)) {
                $errors[] = Loc::getMessage('INSTALL_ERROR_ENTITY');
            } else {
                $validValues['ENTITY_TYPE'] = $entityType;
            }

            if (!IblockTable::getById($doctorsIblockId)->fetch()) {
                $errors[] = Loc::getMessage('INSTALL_ERROR_DOCTORS');
            } else {
                $validValues['DOCTORS_IBLOCK_ID'] = $doctorsIblockId;
            }

            if (!IblockTable::getById($proceduresIblockId)->fetch()) {
                $errors[] = Loc::getMessage('INSTALL_ERROR_PROCEDURES');
            } else {
                $validValues['PROCEDURES_IBLOCK_ID'] = $proceduresIblockId;
            }

            if (!empty($validValues)) {
                $validValues['DEMO_DATA'] = $demoData;
            }

            if (!empty($errors)) {
                $GLOBALS['ERRORS'] = implode('<br>', $errors);
                $GLOBALS['VALID_VALUES'] = $validValues;
                $APPLICATION->IncludeAdminFile(
                    Loc::getMessage('INSTALL_TITLE_STEP_1'),
                    __DIR__ . '/step1.php'
                );
                return;
            }

            ModuleManager::registerModule($this->MODULE_ID);

            Option::set($this->MODULE_ID, 'entity_type', $entityType);
            Option::set($this->MODULE_ID, 'doctors_iblock_id', $doctorsIblockId);
            Option::set($this->MODULE_ID, 'procedures_iblock_id', $proceduresIblockId);

            $this->installEvents();
            $this->installFiles();
        }
    }

    public function DoUninstall()
    {
        Option::delete($this->MODULE_ID);
        $this->unInstallEvents();
        $this->unInstallFiles();
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    public function installEvents(): void
    {
        $eventManager = EventManager::getInstance();

        $eventManager->registerEventHandler(
            'crm',
            'onEntityDetailsTabsInitialized',
            $this->MODULE_ID,
            '\\Sysp\\Crmcustomtab\\EventHandlers\\Crm',
            'updateTabs'
        );
    }

    public function unInstallEvents(): void
    {
        $eventManager = EventManager::getInstance();

        $eventManager->unRegisterEventHandler(
            'crm',
            'onEntityDetailsTabsInitialized',
            $this->MODULE_ID,
            '\\Sysp\\Crmcustomtab\\EventHandlers\\Crm',
            'updateTabs'
        );
    }

    public function installFiles($params = []): void
    {
        $components_distrib_path = __DIR__ . '/components';

        if (Directory::isDirectoryExists($components_distrib_path)) {
            $targetPath = $_SERVER['DOCUMENT_ROOT'] . '/local/components/sysp';

            if (!Directory::isDirectoryExists($targetPath)) {
                $d = new Directory($targetPath);
                $d->create();
                if (!$d->isExists()) {
                    throw new Exception('Error creating directory ' . $targetPath);
                }
            };

            CopyDirFiles($components_distrib_path, $targetPath, true, true);
        } else {
            throw new InvalidPathException($components_distrib_path);
        }
    }

    public function uninstallFiles(): void
    {
        $components_distrib_path = __DIR__ . '/components';
        $namespace_directory = $_SERVER['DOCUMENT_ROOT'] . '/local/components/sysp/';

        if (Directory::isDirectoryExists($components_distrib_path)) {
            $installed_components = new \DirectoryIterator($components_distrib_path);
            foreach ($installed_components as $component) {
                if ($component->isDir() && !$component->isDot()) {
                    $target_path = $namespace_directory . $component->getFilename();
                    if (Directory::isDirectoryExists($target_path)) {
                        Directory::deleteDirectory($target_path);
                    }
                }
            }
            
            $d = new Directory($namespace_directory);
            $children = $d->getChildren();
            if (empty($children)) {
                try {
                    $d->delete();
                } catch (\Exception $e) {
                    throw new SystemException($e->getMessage());
                }
            }
        } else {
            throw new InvalidPathException($namespace_directory);
        }
    }


    public function isVersionD7(): bool
    {
        return version_compare(
            ModuleManager::getVersion('main'),
            '20.00.00',
            '>='
        );
    }

    public function hasRequiredModules(): bool
    {
        return Loader::includeModule('crm') && Loader::includeModule('iblock');
    }
}