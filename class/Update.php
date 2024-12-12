<?php
namespace XoopsModules\Tad_signup;

class Update
{

    // 進行有無候補欄位檢查
    public static function chk_candidate()
    {
        global $xoopsDB;
        $sql = 'SELECT count(`candidate`) FROM ' . $xoopsDB->prefix('tad_signup_actions') . ' ';
        $result = $xoopsDB->query($sql);
        if (empty($result)) {
            return true;
        }

        return false;
    }

    // 執行新增候補欄位
    public static function go_candidate()
    {
        global $xoopsDB;
        $sql = 'ALTER TABLE ' . $xoopsDB->prefix('tad_signup_actions') . ' ADD `candidate` tinyint(3) unsigned NOT NULL';
        $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL . '/modules/system/admin.php?fct=modulesadmin', 30, $xoopsDB->error());
    }
}
