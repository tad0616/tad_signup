<?php

//回模組首頁
$interface_menu[_TAD_TO_MOD] = "index.php";
$interface_icon[_TAD_TO_MOD] = "fa-chevron-right";

$interface_menu[_MD_TAD_SIGNUP_MY_RECORD] = "my_signup.php";
$interface_icon[_MD_TAD_SIGNUP_MY_RECORD] = "fa-chevron-right";

//模組後台
if ($_SESSION[$is_admin]) {
    $interface_menu[_TAD_TO_ADMIN] = "admin/main.php";
    $interface_icon[_TAD_TO_ADMIN] = "fa-chevron-right";
}
