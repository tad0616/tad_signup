<?php
use Xmf\Request;
use XoopsModules\Tad_signup\Tad_signup_api;

require_once dirname(dirname(__DIR__)) . '/mainfile.php';

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$token = Request::getString('token');
$action_id = Request::getInt('action_id');

$api = new Tad_signup_api($token);

switch ($op) {
    // 取得所有活動
    case 'tad_signup_actions_index':
        echo $api->tad_signup_actions_index($xoopsModuleConfig['only_enable']);
        break;

    // 取得活動所有報名資料
    case 'tad_signup_data_index':
        echo $api->tad_signup_data_index($action_id);
        break;

    default:
        echo $api->user();
        break;
}
