<?php
use XoopsModules\Tadtools\Utility;
//載入XOOPS主設定檔（必要）

require_once dirname(dirname(__DIR__)) . '/mainfile.php';
// require_once __DIR__ . '/preloads/autoloader.php';

//判斷是否對該模組有管理權限 $_SESSION['tad_signup_adm']
if (!isset($_SESSION['tad_signup_adm'])) {
    $_SESSION['tad_signup_adm'] = ($xoopsUser) ? $xoopsUser->isAdmin() : false;
}

// 判斷有無開設活動的權限
if (!isset($_SESSION['can_add'])) {
    $_SESSION['can_add'] = Utility::power_chk('tad_signup', '1');
}

//回模組首頁
$interface_menu[_MD_TAD_SIGNUP_INDEX] = "index.php";
$interface_icon[_MD_TAD_SIGNUP_INDEX] = "fa-check-square-o";

$interface_menu[_MD_TAD_SIGNUP_MY_RECORD] = "my_signup.php";
$interface_icon[_MD_TAD_SIGNUP_MY_RECORD] = "fa-bars";
