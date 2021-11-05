<?php
use Xmf\Request;
use XoopsModules\Tad_signup\Tad_signup_actions;
use XoopsModules\Tad_signup\Tad_signup_data;

require_once __DIR__ . '/header.php';

if (!$_SESSION['can_add']) {
    redirect_header($_SERVER['PHP_SELF'], 3, _TAD_PERMISSION_DENIED);
}

$id = Request::getInt('id');
$type = Request::getString('type');

$action = Tad_signup_actions::get($id);

if ($action['uid'] != $xoopsUser->uid()) {
    redirect_header($_SERVER['PHP_SELF'], 3, _TAD_PERMISSION_DENIED);
}

$csv = [];
$head = Tad_signup_data::get_head($action);
$csv[] = implode(',', $head);

if ($type == 'signup') {
    $signup = Tad_signup_data::get_all($action['id']);
    foreach ($signup as $signup_data) {
        $iteam = [];
        foreach ($signup_data['tdc'] as $user_data) {
            $iteam[] = implode('|', $user_data);
        }

        if ($signup_data['accept'] === '1') {
            $iteam[] = _MD_TAD_SIGNUP_ACCEPT;
        } elseif ($signup_data['accept'] === '0') {
            $iteam[] = _MD_TAD_SIGNUP_NOT_ACCEPT;
        } else {
            $iteam[] = _MD_TAD_SIGNUP_ACCEPT_NOT_YET;
        }
        $iteam[] = $signup_data['signup_date'];
        $iteam[] = $signup_data['tag'];

        $csv[] = implode(',', $iteam);
    }
}

$content = implode("\n", $csv);
$content = mb_convert_encoding($content, 'Big5');

header("Content-type: text/csv");
header("Content-Disposition: attachment; filename= {$action['title']}" . _MD_TAD_SIGNUP_APPLY_LIST . ".csv");
echo $content;
exit;
