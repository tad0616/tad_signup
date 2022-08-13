<?php
use XoopsModules\Tadtools\Utility;
//載入XOOPS主設定檔（必要）
require_once '../../mainfile.php';
require_once 'preloads/autoloader.php';
//載入自訂的共同函數檔
require_once 'function.php';

//判斷是否對該模組有管理權限 $_SESSION['tad_signup_adm']
$is_admin = basename(__DIR__) . '_adm';
if (!isset($_SESSION[$is_admin])) {
    $_SESSION[$is_admin] = ($xoopsUser) ? $xoopsUser->isAdmin() : false;
}

// 判斷有無開設活動的權限
if (!isset($_SESSION['can_add'])) {
    $_SESSION['can_add'] = Utility::power_chk('tad_signup', '1');
}

//載入工具選單設定檔（亦可將 interface_menu.php 的內容複製到此檔下方，並刪除 interface_menu.php）
require_once 'interface_menu.php';
