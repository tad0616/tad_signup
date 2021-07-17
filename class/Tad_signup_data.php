<?php
// 如「模組目錄」= signup，則「首字大寫模組目錄」= Signup
// 如「資料表名」= actions，則「模組物件」= Actions

namespace XoopsModules\Tad_signup;

use XoopsModules\Tadtools\BootstrapTable;
use XoopsModules\Tadtools\FormValidator;
use XoopsModules\Tadtools\SweetAlert;
use XoopsModules\Tadtools\TadDataCenter;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tad_signup\Tad_signup_actions;

class Tad_signup_data
{
    //列出所有資料
    public static function index($action_id)
    {
        global $xoopsTpl;

        $all_data = self::get_all($action_id);
        $xoopsTpl->assign('all_data', $all_data);
    }

    //編輯表單
    public static function create($action_id, $id = '')
    {
        global $xoopsTpl, $xoopsUser;

        $uid = $_SESSION['can_add'] ? null : $xoopsUser->uid();
        //抓取預設值
        $db_values = empty($id) ? [] : self::get($id, $uid);
        if ($id and empty($db_values)) {
            redirect_header($_SERVER['PHP_SELF'] . "?id={$action_id}", 3, "查無報名無資料，無法修改");
        }

        foreach ($db_values as $col_name => $col_val) {
            $$col_name = $col_val;
            $xoopsTpl->assign($col_name, $col_val);
        }

        $op = empty($id) ? "tad_signup_data_store" : "tad_signup_data_update";
        $xoopsTpl->assign('next_op', $op);

        //套用formValidator驗證機制
        $formValidator = new FormValidator("#myForm", true);
        $formValidator->render();

        //加入Token安全機制
        include_once $GLOBALS['xoops']->path('class/xoopsformloader.php');
        $token = new \XoopsFormHiddenToken();
        $token_form = $token->render();
        $xoopsTpl->assign("token_form", $token_form);

        $action = Tad_signup_actions::get($action_id, true);
        if (time() > strtotime($action['end_date'])) {
            redirect_header($_SERVER['PHP_SELF'], 3, "已報名截止，無法再進行報名或修改報名");
        } elseif (count($action['signup']) >= $action['number']) {
            redirect_header($_SERVER['PHP_SELF'], 3, "人數已滿，無法再進行報名");
        }
        $xoopsTpl->assign("action", $action);

        $uid = $xoopsUser ? $xoopsUser->uid() : 0;
        $xoopsTpl->assign("uid", $uid);

        $TadDataCenter = new TadDataCenter('tad_signup');
        $TadDataCenter->set_col('id', $id);
        $signup_form = $TadDataCenter->strToForm($action['setup']);
        $xoopsTpl->assign("signup_form", $signup_form);
    }

    //新增資料
    public static function store()
    {
        global $xoopsDB;

        //XOOPS表單安全檢查
        Utility::xoops_security_check();

        $myts = \MyTextSanitizer::getInstance();

        foreach ($_POST as $var_name => $var_val) {
            $$var_name = $myts->addSlashes($var_val);
        }
        $action_id = (int) $action_id;
        $uid = (int) $uid;

        $sql = "insert into `" . $xoopsDB->prefix("Tad_signup_data") . "` (
        `action_id`,
        `uid`,
        `signup_date`
        ) values(
        '{$action_id}',
        '{$uid}',
        now()
        )";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        //取得最後新增資料的流水編號
        $id = $xoopsDB->getInsertId();

        $TadDataCenter = new TadDataCenter('tad_signup');
        $TadDataCenter->set_col('id', $id);
        $TadDataCenter->saveData();

        return $id;
    }

    //以流水號秀出某筆資料內容
    public static function show($id = '')
    {
        global $xoopsTpl, $xoopsUser;

        if (empty($id)) {
            return;
        }

        $uid = $_SESSION['can_add'] ? null : $xoopsUser->uid();

        $id = (int) $id;
        $data = self::get($id, $uid);
        if (empty($data)) {
            redirect_header($_SERVER['PHP_SELF'], 3, "查無報名資料，無法觀看");
        }

        $myts = \MyTextSanitizer::getInstance();
        foreach ($data as $col_name => $col_val) {
            $col_val = $myts->htmlSpecialChars($col_val);
            $xoopsTpl->assign($col_name, $col_val);
            $$col_name = $col_val;
        }

        $TadDataCenter = new TadDataCenter('tad_signup');
        $TadDataCenter->set_col('id', $id);
        $tdc = $TadDataCenter->getData();
        $xoopsTpl->assign('tdc', $tdc);

        $action = Tad_signup_actions::get($action_id, true);
        $xoopsTpl->assign("action", $action);

        $now_uid = $xoopsUser ? $xoopsUser->uid() : 0;
        $xoopsTpl->assign("now_uid", $now_uid);

        $SweetAlert = new SweetAlert();
        $SweetAlert->render("del_data", "index.php?op=tad_signup_data_destroy&action_id={$action_id}&id=", 'id');
    }

    //更新某一筆資料
    public static function update($id = '')
    {
        global $xoopsDB, $xoopsUser;

        //XOOPS表單安全檢查
        Utility::xoops_security_check();

        $myts = \MyTextSanitizer::getInstance();

        foreach ($_POST as $var_name => $var_val) {
            $$var_name = $myts->addSlashes($var_val);
        }
        $action_id = (int) $action_id;
        $uid = (int) $uid;

        $now_uid = $xoopsUser ? $xoopsUser->uid() : 0;

        $sql = "update `" . $xoopsDB->prefix("Tad_signup_data") . "` set
        `signup_date` = now()
        where `id` = '$id' and `uid` = '$now_uid'";
        if ($xoopsDB->queryF($sql)) {
            $TadDataCenter = new TadDataCenter('tad_signup');
            $TadDataCenter->set_col('id', $id);
            $TadDataCenter->saveData();
        } else {
            Utility::web_error($sql, __FILE__, __LINE__);
        }

        return $id;
    }

    //刪除某筆資料資料
    public static function destroy($id = '')
    {
        global $xoopsDB, $xoopsUser;

        if (empty($id)) {
            return;
        }

        $now_uid = $xoopsUser ? $xoopsUser->uid() : 0;

        $sql = "delete from `" . $xoopsDB->prefix("Tad_signup_data") . "`
        where `id` = '{$id}' and `uid`='$now_uid'";
        if ($xoopsDB->queryF($sql)) {
            $TadDataCenter = new TadDataCenter('tad_signup');
            $TadDataCenter->set_col('id', $id);
            $TadDataCenter->delData();
        } else {
            Utility::web_error($sql, __FILE__, __LINE__);
        }
    }

    //以流水號取得某筆資料
    public static function get($id = '', $uid = '')
    {
        global $xoopsDB;

        if (empty($id)) {
            return;
        }

        $and_uid = $uid ? "and `uid`='$uid'" : '';

        $sql = "select * from `" . $xoopsDB->prefix("Tad_signup_data") . "`
        where `id` = '{$id}' $and_uid";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $data = $xoopsDB->fetchArray($result);
        return $data;
    }

    //取得所有資料陣列
    public static function get_all($action_id = '', $uid = '', $auto_key = false)
    {
        global $xoopsDB, $xoopsUser;
        $myts = \MyTextSanitizer::getInstance();

        if ($action_id) {
            $sql = "select * from `" . $xoopsDB->prefix("Tad_signup_data") . "` where `action_id`='$action_id' order by `signup_date`";
        } else {
            if (!$_SESSION['can_add'] or !$uid) {
                $uid = $xoopsUser ? $xoopsUser->uid() : 0;
            }
            $sql = "select * from `" . $xoopsDB->prefix("Tad_signup_data") . "` where `uid`='$uid' order by `signup_date`";
        }

        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $data_arr = [];
        $TadDataCenter = new TadDataCenter('tad_signup');
        while ($data = $xoopsDB->fetchArray($result)) {
            $TadDataCenter->set_col('id', $data['id']);
            $data['tdc'] = $TadDataCenter->getData();
            $data['action'] = Tad_signup_actions::get($data['action_id'], true);

            if ($_SESSION['api_mode'] or $auto_key) {
                $data_arr[] = $data;
            } else {
                $data_arr[$data['id']] = $data;
            }
        }
        return $data_arr;
    }

    // 查詢某人的報名紀錄
    public static function my($uid)
    {
        global $xoopsTpl, $xoopsUser;

        $my_signup = self::get_all(null, $uid);
        $xoopsTpl->assign('my_signup', $my_signup);
        BootstrapTable::render();
    }

    // 更改錄取狀態
    public static function accept($id, $accept)
    {
        global $xoopsDB;

        if (!$_SESSION['can_add']) {
            redirect_header($_SERVER['PHP_SELF'], 3, "您沒有權限使用此功能");
        }

        $id = (int) $id;
        $accept = (int) $accept;

        $sql = "update `" . $xoopsDB->prefix("Tad_signup_data") . "` set
        `accept` = '$accept'
        where `id` = '$id'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    }

    //立即寄出
    public static function send($title = "無標題", $content = "無內容", $email = "")
    {
        global $xoopsUser;
        if (empty($email)) {
            $email = $xoopsUser->email();
        }
        $xoopsMailer = xoops_getMailer();
        $xoopsMailer->multimailer->ContentType = "text/html";
        $xoopsMailer->addHeaders("MIME-Version: 1.0");
        $header = '';
        return $xoopsMailer->sendMail($email, $title, $content, $header);
    }

    // 產生通知信
    public static function mail($id, $type, $signup = [])
    {
        global $xoopsUser;
        $id = (int) $id;
        if (empty($id)) {
            redirect_header($_SERVER['PHP_SELF'], 3, "無編號，無法寄送通知信");
        }
        $signup = $signup ? $signup : self::get($id, true);

        $now = date("Y-m-d H:i:s");
        $name = $xoopsUser->name();
        $name = $name ? $name : $xoopsUser->uname();

        $action = Tad_signup_actions::get($signup['action_id']);

        $member_handler = xoops_getHandler('member');
        $admUser = $member_handler->getUser($action['uid']);
        $adm_email = $admUser->email();

        if ($type == 'destroy') {
            $title = "「{$action['title']}」取消報名通知";
            $head = "<p>您於 {$signup['signup_date']} 報名「{$action['title']}」活動已於 {$now} 由 {$name} 取消報名。</p>";
            $foot = "欲重新報名，請連至 " . XOOPS_URL . "/modules/tad_signup/index.php?op=tad_signup_data_create&action_id={$action['id']}";
        } elseif ($type == 'store') {
            $title = "「{$action['title']}」報名完成通知";
            $head = "<p>您於 {$signup['signup_date']} 報名「{$action['title']}」活動已於 {$now} 由 {$name} 報名完成。</p>";
            $foot = "完整詳情，請連至 " . XOOPS_URL . "/modules/tad_signup/index.php?id={$signup['action_id']}";
        } elseif ($type == 'update') {
            $title = "「{$action['title']}」修改報名資料通知";
            $head = "<p>您於 {$signup['signup_date']} 報名「{$action['title']}」活動已於 {$now} 由 {$name} 修改報名資料如下：</p>";
            $foot = "完整詳情，請連至 " . XOOPS_URL . "/modules/tad_signup/index.php?id={$signup['action_id']}";
        } elseif ($type == 'accept') {
            $title = "「{$action['title']}」報名錄取狀況通知";
            if ($signup['accept'] == 1) {
                $head = "<p>您於 {$signup['signup_date']} 報名「{$action['title']}」活動經審核，<h2 style='color:blue'>恭喜錄取！</h2>您的報名資料如下：</p>";
            } else {
                $head = "<p>您於 {$signup['signup_date']} 報名「{$action['title']}」活動經審核，很遺憾的通知您，因名額有限，<span style='color:red;'>您並未錄取。</span>您的報名資料如下：</p>";
            }
            $foot = "完整詳情，請連至 " . XOOPS_URL . "/modules/tad_signup/index.php?id={$signup['action_id']}";

            $signupUser = $member_handler->getUser($signup['uid']);
            $email = $signupUser->email();
        }

        $content = self::mk_content($id, $head, $foot, $action);
        if (!self::send($title, $content, $email)) {
            redirect_header($_SERVER['PHP_SELF'], 3, "通知信寄發失敗！");
        }
        self::send($title, $content, $adm_email);
    }

    // 產生通知信內容
    public static function mk_content($id, $head = '', $foot = '', $action = [])
    {
        if ($id) {
            $TadDataCenter = new TadDataCenter('tad_signup');
            $TadDataCenter->set_col('id', $id);
            $tdc = $TadDataCenter->getData();

            $table = '<table class="table">';
            foreach ($tdc as $title => $signup) {
                $table .= "
                <tr>
                    <th>{$title}</th>
                    <td>";
                foreach ($signup as $i => $val) {
                    $table .= "<div>{$val}</div>";
                }

                $table .= "</td>
                </tr>";
            }
            $table .= '</table>';
        }

        $content = "
        <html>
            <head>
                <style>
                    .table{
                        border:1px solid #000;
                        border-collapse: collapse;
                        margin:10px 0px;
                    }

                    .table th, .table td{
                        border:1px solid #000;
                        padding: 4px 10px;
                    }

                    .table th{
                        background:#c1e7f4;
                    }

                    .well{
                        border-radius: 10px;
                        background: #fcfcfc;
                        border: 2px solid #cfcfcf;
                        padding:14px 16px;
                        margin:10px 0px;
                    }
                </style>
            </head>
            <body>
            $head
            <h2>{$action['title']}</h2>
            <div>活動日期：{$action['action_date']}</div>
            <div class='well'>{$action['detail']}</div>
            $table
            $foot
            </body>
        </html>
        ";
        return $content;
    }
}
