<?
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

$aMenu = array(
	"parent_menu" => "global_menu_services",
	"sort" => 100,
	"text" => Loc::getMessage('CALLBACK_MENU_TITLE'),
	"title"=> Loc::getMessage('CALLBACK_MENU_TITLE'),
	"icon" => "blog_menu_icon",
	"page_icon" => "blog_page_icon",
	"items_id" => "menu_callback",
	"items" => array(
		array(
			"text" => Loc::getMessage('CALLBACK_MENU_MAIN_TITLE'),
			"url" => "db_list.php?lang=".LANGUAGE_ID,
			"more_url" => array("db_list.php"),
			"title" => Loc::getMessage('CALLBACK_MENU_MAIN_TITLE'),
		)
	)
);

return $aMenu;
?>