<?php
/**
 * 作品信息Model
 * User: chenlin15
 * Date: 17/3/3
 * Time: 10:24
 */
require_once 'Model.php';

class WorksModel extends Model {
    protected $_tbName = '`tblWorks`';

    public function getWorkInfos($condition, $page = 1, $pagesize = 15) {
        $sql = 'SELECT `wid`, `title`, `content`, `create_time`, `create_uid`, `comment_num`, `collection_num`, `type`, `pic`, `brower_num`, `status`, `vote_num`, `title_key`, `file_url`
                FROM ' . $this->_tbName;
        $whereArr = array();
        if (!empty($condition['wid'])) {
            if (is_array($condition['wid'])) {
                $whereArr['wid'] = '`wid` IN (' . implode(', ' , $condition['wid']) . ')';
            } else {
                $whereArr['wid'] = '`wid` = ' . $condition['wid'];
            }
            if (!empty($whereArr)) {
                $sql .= ' WHERE ' . implode(' AND ', $whereArr);
            }
            $sql .= ' ORDER BY `create_time` DESC LIMIT ' . ($page - 1) * $pagesize . ', ' . $pagesize;
            $res = $this->Query($sql);
            return $res;
        }
        if (!empty($condition['title_key'])) {
            $whereArr['title_key'] = "`title_key` = '" . $condition['title_key'] . "'";
        }
        if (!empty($condition['create_uid'])) {
            $whereArr['create_uid'] = '`create_uid` = ' . $condition['create_uid'];
        }
        if (!empty($condition['type'])) {
            $whereArr['type'] = '`type` = ' . $condition['type'];
        }
        if (!empty($condition['op']) && $condition['op'] == 'exp') {
            $whereArr['op'] = '`type` < 20';
        }else {
            $whereArr['op'] = '`type` >= 20';
        }
        if (!empty($condition['status'])) {
            $whereArr['status'] = '`status` = ' . $condition['status'];
        }
        if (!empty($whereArr)) {
            $sql .= ' WHERE ' . implode(' AND ', $whereArr);
        }
        $sql .= ' ORDER BY `create_time` DESC LIMIT ' . ($page - 1) * $pagesize . ', ' . $pagesize;
        $res = $this->Query($sql);
        return $res;
    }

    public function getWorkInfosByWids($wids) {
        if (empty($wids)) {
            return false;
        }
        $res = $this->getWorkInfos(array('wid' => $wids, 'status' => 1));
        $result = array();
        if (!empty($res)) {
            foreach ($res as $v) {
                $result[$v['wid']] = $v;
            }
        }
        return $result;
    }

    /**
     * @param $addArr
     * @return bool 成功返回id
     */
    public function addWork($addArr) {
        if (empty($addArr)) {
            return false;
        }
        $res = $this->insert($addArr);
        if (false === $res) {
            return false;
        }
        return $res;
    }

    public function getTotalOfWorks($condition) {
        $sql = 'SELECT COUNT(`wid`) AS `total`
                FROM ' . $this->_tbName;
        $whereArr = array();
        if (!empty($condition['wid'])) {
            if (is_array($condition['wid'])) {
                $whereArr['wid'] = '`wid` IN (' . implode(', ' , $condition['wid']) . ')';
            } else {
                $whereArr['wid'] = '`wid` = ' . $condition['wid'];
            }
        }
        if (!empty($condition['title_key'])) {
            $whereArr['title_key'] = "`title_key` = '" . $condition['title_key'] . "'";
        }
        if (!empty($condition['op']) && $condition['op'] == 'exp') {
            $whereArr['op'] = '`type` < 20';
        } else {
            $whereArr['op'] = '`type` >= 20';
        }
        if (!empty($condition['create_uid'])) {
            $whereArr['create_uid'] = '`create_uid` = ' . $condition['create_uid'];
        }
        if (!empty($condition['type'])) {
            $whereArr['type'] = '`type` = ' . $condition['type'];
        }
        if (!empty($condition['status'])) {
            $whereArr['status'] = '`status` = ' . $condition['status'];
        }
        if (!empty($whereArr)) {
            $sql .= ' WHERE ' . implode(' AND ', $whereArr);
        }
        $res = $this->Query($sql);
        return current($res);
    }
}