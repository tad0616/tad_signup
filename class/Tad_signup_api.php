<?php
namespace XoopsModules\Tad_signup;

use XoopsModules\Tadtools\SimpleRest;
use XoopsModules\Tad_signup\Tad_signup_actions;
use XoopsModules\Tad_signup\Tad_signup_data;

require dirname(dirname(dirname(__DIR__))) . '/mainfile.php';

class Tad_signup_api extends SimpleRest
{
    public $uid = '';
    public $user = [];
    public $groups = [];
    private $token = '';

    public function __construct($token = '')
    {
        $this->token = $token;
        if (!isset($_SESSION['api_mode'])) {
            $_SESSION['api_mode'] = true;
        }

        if ($this->token) {
            $User = $this->getXoopsSUser($this->token);
            $this->uid = (int) $User['uid'];
            $this->groups = $User['groups'];
            $this->user = $User['user'];

            //判斷是否對該模組有管理權限 $_SESSION['tad_signup_adm']
            if (!isset($this->user['tad_signup_adm'])) {
                $this->user['tad_signup_adm'] = $_SESSION['tad_signup_adm'] = ($this->uid) ? $this->isAdmin('tad_signup') : false;
            }

            // 判斷有無開設活動的權限
            if (!isset($this->user['can_add'])) {
                $_SESSION['can_add'] = $this->user['can_add'] = $this->powerChk('tad_signup', '1');
            }

        }
    }

    // 傳回目前使用者資訊
    public function user()
    {
        $data = ['uid' => (int) $this->uid, 'groups' => $this->groups, 'user' => $this->user];
        return $this->encodeJson($data);
    }

    // 轉成 json
    private function encodeJson($responseData)
    {
        if (empty($responseData)) {
            $statusCode = 404;
            $responseData = array('error' => _TAD_EMPTY);
        } else {
            $statusCode = 200;
        }
        $this->setHttpHeaders($statusCode);

        $jsonResponse = json_encode($responseData, 256);
        return $jsonResponse;
    }

    // 取得所有活動
    public function tad_signup_actions_index($only_enable = true)
    {
        $actions = Tad_signup_actions::get_all($only_enable);
        return $this->encodeJson($actions);
    }

    // 取得活動所有報名資料
    public function tad_signup_data_index($action_id)
    {
        $action = Tad_signup_actions::get($action_id);
        $data = ($this->user['tad_signup_adm'] || ($this->user['can_add'] && $action['uid'] == $this->uid)) ? Tad_signup_data::get_all($action_id) : [];
        return $this->encodeJson($data);
    }

}
