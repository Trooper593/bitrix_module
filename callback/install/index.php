<?php
use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Loader;
use Bitrix\Main\Entity\Base;
use Bitrix\Callback\CallbackTable;

Loc::loadMessages(__FILE__);

class callback extends CModule
{
    public $MODULE_ID;
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $PARTNER_NAME;
    public $PARTNER_URI;

    function __construct()
    {
        $arModuleVersion = [];
        include __DIR__ . '/version.php';
        if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }
        $this->MODULE_ID = 'callback';
        $this->MODULE_NAME = Loc::getMessage('CALLBACK_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('CALLBACK_MODULE_DESCRIPTION');
        $this->PARTNER_NAME = Loc::getMessage('CALLBACK_MODULE_PARTNER_NAME');
        $this->PARTNER_URI = 'https://localhost';
    }

    public function DoUninstall()
    {
        $this->UnInstallDB();
        $this->UnInstallFiles();
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    public function InstallFiles()
	{
        \CopyDirFiles($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/callback/install/components", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/components", true, true);
        \CopyDirFiles($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/callback/install/admin', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin', true, true);

		return true;
	}

    public function UnInstallFiles()
    {
        \DeleteDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/callback/install/components", $_SERVER["DOCUMENT_ROOT"]."/bitrix/components");
        \DeleteDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/callback/install/admin", $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin");

        return true;
    }

    public function InstallDB()
    {
        if (Loader::includeModule($this->MODULE_ID))
            CallbackTable::getEntity()->createDbTable();
    }

    public function UnInstallDB()
    {

        if (Loader::includeModule($this->MODULE_ID)) {
            if (Application::getConnection()->isTableExists(Base::getInstance('\Bitrix\Callback\CallbackTable')->getDBTableName())) {
                $connection = Application::getInstance()->getConnection();
                $connection->dropTable(CallbackTable::getTableName());
            }
        }
    }

    public function DoInstall()
    {
        ModuleManager::registerModule($this->MODULE_ID);
        $this->InstallFiles();
        $this->InstallDB();
    }
}