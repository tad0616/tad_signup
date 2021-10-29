<?php
use Xmf\Request;
use XoopsModules\Tadtools\TadUpFiles;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tad_signup\Tad_signup_actions;
use XoopsModules\Tad_signup\Tad_signup_data;

/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'tad_signup_index.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';

/*-----------變數過濾----------*/
$op = Request::getString('op');
$id = Request::getInt('id');
$action_id = Request::getInt('action_id');
$accept = Request::getInt('accept');
$files_sn = Request::getInt('files_sn');
$pdf_setup_col = Request::getString('pdf_setup_col');
$file = Request::getWord('file', 'pdf');

/*-----------執行動作判斷區----------*/
switch ($op) {
    case "tufdl":
        $TadUpFiles = new TadUpFiles('tad_signup');
        $TadUpFiles->add_file_counter($files_sn);
        exit;

    //新增活動表單
    case 'tad_signup_actions_create':
        Tad_signup_actions::create();
        break;

    //新增活動資料
    case 'tad_signup_actions_store':
        $id = Tad_signup_actions::store();
        // header("location: {$_SERVER['PHP_SELF']}?id=$id");
        redirect_header($_SERVER['PHP_SELF'] . "?id=$id", 3, _MD_TAD_SIGNUP_CREATE_SUCCESS);
        exit;

    //修改用表單
    case 'tad_signup_actions_edit':
        Tad_signup_actions::create($id);
        $op = 'tad_signup_actions_create';
        break;

    //更新資料
    case 'tad_signup_actions_update':
        Tad_signup_actions::update($id);
        // header("location: {$_SERVER['PHP_SELF']}?id=$id");
        redirect_header($_SERVER['PHP_SELF'] . "?id=$id", 3, _MD_TAD_SIGNUP_UPDATE_SUCCESS);
        exit;

    //刪除資料
    case 'tad_signup_actions_destroy':
        Tad_signup_actions::destroy($id);
        // header("location: {$_SERVER['PHP_SELF']}");
        redirect_header($_SERVER['PHP_SELF'], 3, _MD_TAD_SIGNUP_DESTROY_SUCCESS);
        exit;

    //新增報名表單
    case 'tad_signup_data_create':
        Tad_signup_data::create($action_id);
        break;

    //新增報名資料
    case 'tad_signup_data_store':
        $id = Tad_signup_data::store();
        Tad_signup_data::mail($id, 'store');
        redirect_header("{$_SERVER['PHP_SELF']}?op=tad_signup_data_show&id=$id", 3, _MD_TAD_SIGNUP_APPLY_SUCCESS);
        break;

    //顯示報名表
    case 'tad_signup_data_show':
        Tad_signup_data::show($id);
        break;

    //修改報名表單
    case 'tad_signup_data_edit':
        Tad_signup_data::create($action_id, $id);
        $op = 'tad_signup_data_create';
        break;

    //更新報名資料
    case 'tad_signup_data_update':
        Tad_signup_data::update($id);
        Tad_signup_data::mail($id, 'update');
        redirect_header($_SERVER['PHP_SELF'] . "?op=tad_signup_data_show&id=$id", 3, _MD_TAD_SIGNUP_APPLY_UPDATE_SUCCESS);
        exit;

    //刪除報名資料
    case 'tad_signup_data_destroy':
        $uid = $_SESSION['can_add'] ? null : $xoopsUser->uid();
        $signup = Tad_signup_data::get($id, $uid);
        Tad_signup_data::destroy($id);
        Tad_signup_data::mail($id, 'destroy', $signup);
        redirect_header($_SERVER['PHP_SELF'] . "?id=$action_id", 3, _MD_TAD_SIGNUP_APPLY_DESTROY_SUCCESS);
        exit;

    //更改錄取狀態
    case 'tad_signup_data_accept':
        Tad_signup_data::accept($id, $accept);
        Tad_signup_data::mail($id, 'accept');
        redirect_header($_SERVER['PHP_SELF'] . "?id=$action_id", 3, _MD_TAD_SIGNUP_ACCEPT_SUCCESS);
        exit;

    // 複製活動
    case 'tad_signup_actions_copy':
        $new_id = Tad_signup_actions::copy($id);
        header("location: {$_SERVER['PHP_SELF']}?op=tad_signup_actions_edit&id=$new_id");
        exit;

    //修改報名表單(CSV)
    case 'tad_signup_data_preview_csv':
        Tad_signup_data::preview_csv($id);
        break;

    //批次匯入 CSV
    case 'tad_signup_data_import_csv':
        Tad_signup_data::import_csv($id);
        redirect_header("{$_SERVER['PHP_SELF']}?id=$id", 3, _MD_TAD_SIGNUP_IMPORT_SUCCESS);
        break;

    //修改報名表單(Excel)
    case 'tad_signup_data_preview_excel':
        Tad_signup_data::preview_excel($id);
        break;

    //批次匯入 Excel
    case 'tad_signup_data_import_excel':
        Tad_signup_data::import_excel($id);
        redirect_header("{$_SERVER['PHP_SELF']}?id=$id", 3, _MD_TAD_SIGNUP_IMPORT_SUCCESS);
        break;

    // 進行pdf的匯出設定
    case 'tad_signup_data_pdf_setup':
        Tad_signup_data::pdf_setup($id);
        break;

    //儲存pdf的匯出設定
    case 'tad_signup_data_pdf_setup_save':
        Tad_signup_data::pdf_setup_save($action_id, $pdf_setup_col);
        header("location: {$file}_signup.php?id=$action_id");
        exit;

    default:
        if (empty($id)) {
            Tad_signup_actions::index($xoopsModuleConfig['only_enable']);
            $op = 'tad_signup_actions_index';
        } else {
            Tad_signup_actions::show($id);
            $op = 'tad_signup_actions_show';
        }
        break;
}

/*-----------function區--------------*/

/*-----------秀出結果區--------------*/
unset($_SESSION['api_mode']);
$xoopsTpl->assign('toolbar', Utility::toolbar_bootstrap($interface_menu));
$xoopsTpl->assign('now_op', $op);
$xoTheme->addStylesheet(XOOPS_URL . '/modules/tad_signup/css/module.css');
require_once XOOPS_ROOT_PATH . '/footer.php';
