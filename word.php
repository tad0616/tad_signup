<?php
use Xmf\Request;
use XoopsModules\Tad_signup\Tad_signup_actions;
use XoopsModules\Tad_signup\Tad_signup_data;
use \PhpOffice\PhpWord\TemplateProcessor;
/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';
require_once XOOPS_ROOT_PATH . '/modules/tadtools/vendor/autoload.php';

if (!$_SESSION['can_add']) {
    redirect_header($_SERVER['PHP_SELF'], 3, _TAD_PERMISSION_DENIED);
}

$id = Request::getInt('id');
$action = Tad_signup_actions::get($id);
$signup = Tad_signup_data::get_all($action['id']);

$templateProcessor = new TemplateProcessor("signup.docx");
$templateProcessor->setValue('title', $action['title']);
$templateProcessor->setValue('detail', strip_tags($action['detail']));
$templateProcessor->setValue('action_date', $action['action_date']);
$templateProcessor->setValue('end_date', $action['end_date']);
$templateProcessor->setValue('number', $action['number']);
$templateProcessor->setValue('candidate', $action['candidate']);
$templateProcessor->setValue('signup', count($signup));
$templateProcessor->setValue('url', XOOPS_URL . "/modules/tad_signup/index.php?op=tad_signup_data_create&amp;action_id={$action['id']}");

$templateProcessor->cloneRow('id', count($signup));

$i = 1;
foreach ($signup as $id => $signup_data) {
    $iteam = [];
    foreach ($signup_data['tdc'] as $head => $user_data) {
        $iteam[] = $head . '：' . implode('、', $user_data);
    }
    $data = implode('<w:br/>', $iteam);

    if ($signup_data['accept'] === '1') {
        $iteam[] = _MD_TAD_SIGNUP_ACCEPT;
    } elseif ($signup_data['accept'] === '0') {
        $iteam[] = _MD_TAD_SIGNUP_NOT_ACCEPT;
    } else {
        $iteam[] = _MD_TAD_SIGNUP_ACCEPT_NOT_YET;
    }

    $templateProcessor->setValue("id#{$i}", $id);
    $templateProcessor->setValue("accept#{$i}", $accept);
    $templateProcessor->setValue("data#{$i}", $data);
    $i++;
}

header('Content-Type: application/vnd.ms-word');
header("Content-Disposition: attachment;filename={$action['title']}" . _MD_TAD_SIGNUP_APPLY_LIST . ".docx");
header('Cache-Control: max-age=0');
$templateProcessor->saveAs('php://output');
