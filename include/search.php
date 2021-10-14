<?php
//搜尋程式

function tad_signup_search($queryarray, $andor, $limit, $offset, $userid)
{
    global $xoopsDB;
    $myts = \MyTextSanitizer::getInstance();
    if (is_array($queryarray)) {
        foreach ($queryarray as $k => $v) {
            $arr[$k] = $myts->addSlashes($v);
        }
        $queryarray = $arr;
    } else {
        $queryarray = [];
    }
    $sql = "SELECT `id`,`title`,`action_date`, `uid` FROM " . $xoopsDB->prefix("tad_signup_actions") . " WHERE 1";
    if ($userid != 0) {
        $sql .= " AND uid=" . $userid . " ";
    }
    if (is_array($queryarray) && $count = count($queryarray)) {
        $sql .= " AND ((`title` LIKE '%{$queryarray[0]}%'  OR `detail` LIKE '%{$queryarray[0]}%' )";
        for ($i = 1; $i < $count; $i++) {
            $sql .= " $andor ";
            $sql .= "(`title` LIKE '%{$queryarray[$i]}%' OR  `detail` LIKE '%{$queryarray[$i]}%' )";
        }
        $sql .= ") ";
    }
    $sql .= "ORDER BY  `action_date` DESC";
    $result = $xoopsDB->query($sql, $limit, $offset);
    $ret = array();
    $i = 0;
    while ($myrow = $xoopsDB->fetchArray($result)) {
        $ret[$i]['image'] = "images/signup.png";
        $ret[$i]['link'] = "index.php?id=" . $myrow['id'];
        $ret[$i]['title'] = $myrow['title'];
        $ret[$i]['time'] = strtotime($myrow['action_date']);
        $ret[$i]['uid'] = $myrow['uid'];
        $i++;
    }
    return $ret;
}
