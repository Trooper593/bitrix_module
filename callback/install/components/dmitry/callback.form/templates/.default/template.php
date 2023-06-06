<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?php \CJSCore::Init(['masked_input']); ?>

<div id="callback-form-wrapper">
    <h4>Заказать звонок</h4>
    <?if(count($arResult['ERRORS']) > 0):?>
        <div class="errors"><?=implode("<br/>", $arResult['ERRORS'])?></div>
    <?endif?>
    <?if($arResult['SUCCESS']):?>
        <div class="success"><?=$arResult['SUCCESS']?></div>
    <?else:?>
        <form name="callbackform" method="post" action="<?=POST_FORM_ACTION_URI ?>">
            <?=bitrix_sessid_post()?>
            <div>
                <label for="name">Имя<span class="required">*</span></label>
                <input name="name" type="text" value="<?=$arResult['NAME']?>" maxlength="10"/>
            </div>
            <div>
                <label for="phone">Телефон<span class="required">*</span></label>
                <input name="phone" type="text" value="<?=$arResult['PHONE']?>"/>
            </div>
            <div>
                <label for="time">Время</label>
                <select name="time">
                    <option value="">--Время не выбрано--</option>
                    <?foreach($arResult['TIME_OPTIONS'] as $key => $val):?>
                        <option <?=$arResult['TIME'] === $key ? 'selected' : ''?> value="<?=$key?>"><?=$val?></option>
                    <?endforeach?>
                </select>
            </div>
            <div>
                <label for="description">Текст</label>
                <textarea name="description"><?=$arResult['DESCRIPTION']?></textarea>
            </div>
            <div>
                <label></label>
                <input type="submit" value="Отправить">
            </div>
        </form>
    <?endif?>
</div>




