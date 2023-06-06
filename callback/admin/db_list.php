<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/catalog/prolog.php");

global $APPLICATION;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Bitrix\Callback\CallbackTable;
use Bitrix\Main\UI\PageNavigation;
use Bitrix\Main\Grid\Options as GridOptions;
use Bitrix\Main\UI\Filter\Options as FilterOptions;

Loc::loadMessages(__FILE__);

if(!Loader::includeModule("callback")){
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
    ShowError(Loc::getMessage('CALLBACK_MODULE_IS_MISSING'));
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

$listId = 'callback_list';

//prepare grid nav and sorting
$grid_options = new GridOptions($listId);
$sort = $grid_options->GetSorting(['sort' => ['ID' => 'DESC'], 'vars' => ['by' => 'by', 'order' => 'order']]);
$nav_params = $grid_options->GetNavParams();

$nav = new PageNavigation($listId);
$nav->allowAllRecords(true)
    ->setPageSize($nav_params['nPageSize'])
    ->initFromUri();

//prepare grid filter
$filterOption = new FilterOptions($listId);
$filterData = $filterOption->getFilter([]);
$filter = [];
foreach ($filterData as $k => $v) {
    if($filterData['ID'])
        $filter['ID'] = $filterData['ID'];

    if($filterData['DATE_from'] && $filterData['DATE_to']){
        $filter['>=DATE'] = $filterData['DATE_from'];
        $filter['<=DATE'] = $filterData['DATE_to'];
    }

    if($filterData['NAME'])
        $filter['NAME'] = "%".$filterData['NAME']."%";

    if($filterData['PHONE'])
        $filter['PHONE'] = "%".$filterData['PHONE']."%";

    if($filterData['PAGE'])
        $filter['PAGE'] = "%".$filterData['PAGE']."%";

    if($filterData['TIME'])
        $filter['TIME'] = "%".$filterData['TIME']."%";

    if($filterData['DESCRIPTION'])
        $filter['DESCRIPTION'] = "%".$filterData['DESCRIPTION']."%";
}

$arList = [];
$dbData = CallbackTable::getList([
    'filter' => $filter,
    'offset' => $nav->getOffset(),
    'limit' => $nav->getLimit(),
    'order' => $sort['sort'],
    'count_total' => true
]);

$nav->setRecordCount($dbData->getCount());

while($arRow = $dbData->fetch()){
    $arList[] = [
        'data' => [
            'ID' => $arRow['ID'],
            'DATE' => $arRow['DATE']->getTimestamp(),
            'NAME' => $arRow['NAME'],
            'PHONE' => $arRow['PHONE'],
            'TIME' => $arRow['TIME'],
            'PAGE' => $arRow['PAGE'],
            'DESCRIPTION' => $arRow['DESCRIPTION'],
        ],
    ];
}

$APPLICATION->IncludeComponent('bitrix:main.ui.filter', '', [
    'FILTER_ID' => $listId,
    'GRID_ID' => $listId,
    'FILTER' => [
        ['id' => 'ID', 'name' => Loc::getMessage('CALLBACK_GRID_FIELD_ID'), 'type' => 'text', 'default' => true],
        ['id' => 'DATE', 'name' => Loc::getMessage('CALLBACK_GRID_FIELD_DATE'), 'type' => 'datetime', 'default' => true],
        ['id' => 'NAME', 'name' => Loc::getMessage('CALLBACK_GRID_FIELD_NAME'), 'type' => 'text', 'default' => true],
        ['id' => 'PHONE', 'name' => Loc::getMessage('CALLBACK_GRID_FIELD_PHONE'), 'type' => 'text', 'default' => true],
        ['id' => 'TIME', 'name' => Loc::getMessage('CALLBACK_GRID_FIELD_TIME'), 'type' => 'list', 'items' => \CCallback::GetAvailableTime(), 'default' => true],
        ['id' => 'PAGE', 'name' => Loc::getMessage('CALLBACK_GRID_FIELD_PAGE'), 'type' => 'text', 'default' => true],
        ['id' => 'DESCRIPTION', 'name' => Loc::getMessage('CALLBACK_GRID_FIELD_DESCRIPTION'), 'type' => 'text', 'default' => true],
    ],
    'ENABLE_LIVE_SEARCH' => false,
    'ENABLE_LABEL' => true
]);

$APPLICATION->IncludeComponent('bitrix:main.ui.grid', '', [
    'GRID_ID' => $listId,
    'COLUMNS' => [
        ['id' => 'ID', 'name' => Loc::getMessage('CALLBACK_GRID_FIELD_ID'), 'sort' => 'ID', 'default' => true],
        ['id' => 'DATE', 'name' => Loc::getMessage('CALLBACK_GRID_FIELD_DATE'), 'sort' => 'DATE', 'default' => true],
        ['id' => 'NAME', 'name' => Loc::getMessage('CALLBACK_GRID_FIELD_NAME'), 'sort' => 'NAME', 'default' => true],
        ['id' => 'PHONE', 'name' => Loc::getMessage('CALLBACK_GRID_FIELD_PHONE'), 'sort' => 'PHONE', 'default' => true],
        ['id' => 'TIME', 'name' => Loc::getMessage('CALLBACK_GRID_FIELD_TIME'), 'sort' => 'TIME', 'default' => true],
        ['id' => 'PAGE', 'name' => Loc::getMessage('CALLBACK_GRID_FIELD_PAGE'), 'sort' => 'PAGE', 'default' => true],
        ['id' => 'DESCRIPTION', 'name' => Loc::getMessage('CALLBACK_GRID_FIELD_DESCRIPTION'), 'sort' => 'DESCRIPTION', 'default' => true],
    ],
    'ROWS' => $arList,
    'SHOW_ROW_CHECKBOXES' => true,
    'NAV_OBJECT' => $nav,
    'AJAX_MODE' => 'Y',
    'AJAX_ID' => \CAjax::getComponentID('bitrix:main.ui.grid', '.default', ''),
    'PAGE_SIZES' => [
        ['NAME' => "5", 'VALUE' => '5'],
        ['NAME' => '10', 'VALUE' => '10'],
        ['NAME' => '20', 'VALUE' => '20'],
        ['NAME' => '50', 'VALUE' => '50'],
        ['NAME' => '100', 'VALUE' => '100']
    ],
    'AJAX_OPTION_JUMP'          => 'N',
    'SHOW_CHECK_ALL_CHECKBOXES' => false,
    'SHOW_ROW_ACTIONS_MENU'     => false,
    'SHOW_GRID_SETTINGS_MENU'   => true,
    'SHOW_NAVIGATION_PANEL'     => true,
    'SHOW_PAGINATION'           => true,
    'SHOW_SELECTED_COUNTER'     => true,
    'SHOW_TOTAL_COUNTER'        => true,
    'SHOW_PAGESIZE'             => true,
    'SHOW_ACTION_PANEL'         => true,
    'ALLOW_COLUMNS_SORT'        => true,
    'ALLOW_COLUMNS_RESIZE'      => true,
    'ALLOW_HORIZONTAL_SCROLL'   => true,
    'ALLOW_SORT'                => true,
    'ALLOW_PIN_HEADER'          => true,
    'AJAX_OPTION_HISTORY'       => 'N'
]);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php"); ?>