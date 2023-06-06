<?php
namespace Bitrix\Callback;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Entity\DatetimeField;
use Bitrix\Main\Entity\Validator;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class CallbackTable extends DataManager
{
    public static function getTableName()
    {
        return 'callback_db';
    }

    public static function getMap()
    {
        return array(
            new IntegerField('ID', array(
                'autocomplete' => true,
                'primary' => true
            )),
            new DatetimeField('DATE',array(
                'required' => true)),
			new StringField('NAME', array(
                'required' => true,
                'title' => Loc::getMessage('CALLBACK_TABLE_FIELD_NAME'), 
                'validation' => function () {
                    return array(
                        new Validator\Length(2, 10),
                    );
                },
            )),
			new StringField('PHONE', array(
                'required' => true,
                'title' => Loc::getMessage('CALLBACK_TABLE_FIELD_PHONE'), 
                'validation' => function () {
                    return array(
                        new Validator\RegExp('/^\+7 \d{3} \d{3} \d{2} \d{2}$/')
                    );
                },
            )),
            new StringField('TIME', array(
                'title' => Loc::getMessage('CALLBACK_TABLE_FIELD_TIME'),
                'validation' => function () {
                    return array(
                        new Validator\Length(0, 10),
                    );
                },
            )),
			new StringField('PAGE', array(
                'required' => true,
                'title' => Loc::getMessage('CALLBACK_TABLE_FIELD_PAGE'), 
                'validation' => function () {
                    return array(
                        new Validator\Length(1, 255),
                    );
                },
            )),
			new StringField('DESCRIPTION', array(
                'title' => Loc::getMessage('CALLBACK_TABLE_FIELD_DESCRIPTION'), 
            )),
        );
    }
}