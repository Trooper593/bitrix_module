<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Bitrix\Main\Context;
use Bitrix\Main\Type\DateTime;
use Bitrix\Callback\CallbackTable;

Loc::loadMessages(__FILE__);

class CallbackFormComponent extends \CBitrixComponent
{
	public function executeComponent()
	{
		if (!Loader::includeModule('callback'))
			return false;

        $request = Context::getCurrent()->getRequest();

        if($request->isPost())
        {
            $this->arResult['NAME'] = $name = htmlspecialchars($request->get('name'));
            $this->arResult['PHONE'] = $phone = htmlspecialchars($request->get('phone'));
            $this->arResult['TIME'] = $time = htmlspecialchars($request->get('time'));
            $this->arResult['DESCRIPTION'] = $description = htmlspecialchars($request->get('description'));

            $oDate = new DateTime();
            $res = CallbackTable::add([
                'DATE' => $oDate,
                'NAME' => $name,
                'PHONE' => $phone,
                'TIME' => $time,
                'PAGE' => $request->getRequestUri(),
                'DESCRIPTION' => $description
            ]);

            if(!$res->isSuccess())
                $this->arResult['ERRORS'] = $res->getErrorMessages();
            else
                $this->arResult['SUCCESS'] = 'Данные успешно отправлены!';

        }

        $this->arResult['TIME_OPTIONS'] = \CCallback::GetAvailableTime();

		$this->includeComponentTemplate();
		return $this->arResult;
	}
}