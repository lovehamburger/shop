<?php


namespace app\common\event;

use think\Db;
use app\common\model\Link;

class LinkEvent extends BaseEvent
{

    public function setState($linkID, $state) {
        if ($state != 0 && $state != 1) {
            return array_err(1951299, '状态设置错误');
        }

        //检查数据是否存在
        $mLink = new Link();
        $linkRes = array();
        $flag = $this->checkLinkID($linkID, $linkRes, true);

        if ($flag['code'] > 0) {
            return $flag;
        }

        if ($state == $linkRes[$linkID]['status']) {
            return array_err(1951296, '状态没有发生变化,不需要修改');
        }

        $editFlag = $mLink->save([
            'status' => $state,
        ], ['id' => $linkID]);

        if ($editFlag === false) {
            return array_err(1951296, '修改状态失败');
        }

        return array_err(0, '修改状态成功');
    }

    /**
     * 检查链接是否正确根据主键
     * @param $linkID
     * @param $linkRes
     * @param bool $lock
     * @return array
     */
    public function checkLinkID($linkID, &$linkRes, $lock = false) {
        $mLink = new Link();
        if (empty($linkID)) {
            return array_err(1951298, '链接标识不能为空');
        }
        $linkRes = $mLink->getLinkByKV($linkID, $lock);

        if (count($linkID) != count($linkRes)) {
            return array_err(1951297, '存在非法数据');
        }

        if (empty($linkRes)) {
            return array_err(1951296, '没有查找到您要的标识');
        }
        return array_err(0, 'success');
    }

    /**
     * 修改链接数据
     * @param $linkID
     * @param $data
     * @return array
     */
    public function editLink($linkID, $data) {
        $linkRes = array();
        $flag = $this->checkLinkID($linkID, $linkRes, true);
        if ($flag['code'] > 0) {
            return $flag;
        }

        $flag = $this->checkLinkData($data, $linkID);

        if ($flag['code'] > 0) {
            return $flag;
        }

        //这边判断数据是否有变化
        foreach ($data as $k => $v) {
            if ($v == $linkRes[$linkID][$k]) {
                unset($data[$k]);
            }
        }

        if (empty($data)) {
            return array_err(0, '数据没有发生变化无需要修改');
        }

        $mLink = new Link();

        $editFlag = $mLink->save($data, ['id' => $linkID]);

        if ($editFlag === false) {
            return array_err(1951296, '修改链接数据失败');
        }

        return array_err(0, '修改链接数据成功');
    }


    /**
     * 数据排序
     * @param $data
     * @return array
     * @throws \Exception
     */
    public function setSort($data) {
        $linkRes = array();

        $linkID = array_keys($data);
        $flag = $this->checkLinkID($linkID, $linkRes, true);
        if ($flag['code'] > 0) {
            return $flag;
        }

        $mLink = new Link();
        $sortRes = array();
        //
        foreach ($data as $k => $v) {
            if ($v == $linkRes[$k]['sort']) {
                continue;
            }
            $sortRes[$k]['id'] = $k;
            $sortRes[$k]['sort'] = $v;
        }

        $editFlag = $mLink->isUpdate()->saveAll($sortRes);

        if ($editFlag === false) {
            return array_err(1951296, '修改链接数据失败');
        }

        return array_err(0, '修改链接数据成功');
    }

    /**
     * 添加链接数据
     * @param $data
     * @return array
     */
    public function addLink($data) {
        $flag = $this->checkLinkData($data);

        if ($flag['code'] > 0) {
            return $flag;
        }

        $mLink = new Link();

        $editFlag = $mLink->save($data);

        if ($editFlag === false) {
            return array_err(1951296, '添加链接数据失败');
        }

        return array_err(0, '添加链接数据成功');
    }

    /**
     * 删除链接数据
     * @param $linkID
     * @return array
     */
    public function delLink($linkID) {
        $linkRes = array();
        $flag = $this->checkLinkID($linkID, $linkRes, true);

        if ($flag['code'] > 0) {
            return $flag;
        }

        $mLink = new Link();

        $delFlag = $mLink::destroy($linkID);

        if ($delFlag === false) {
            return array_err(1951296, '删除链接数据失败');
        }

        return array_err(0, '删除链接数据成功');
    }

    public function checkLinkData(&$data, $linkID = '') {

        if (empty($data['title'])) {
            return array_err(1952199, '链接名称不能为空');
        }

        if ($data['status'] != 0 && $data['status'] != 1) {
            return array_err(1952198, '链接状态错误');
        }

        if (!count_words($data['description'], 200, 'lt') && !empty($data['description'])) {
            return array_err(1952197, '描述文字不能大于200');
        }

        if (!empty($data['link_url'])) {
            $data['link_url'] = right_url($data['link_url']);
        }

        $param['title'] = $data['title'];

        if ($linkID) {
            $param['id'] = array('neq', $linkID);
        }

        $linkRes = Db::name('link')->where($param)->find();

        if ($linkRes) {
            return array_err(1952196, '链接名称已经被使用,请更换');
        }

        return array_err(0, 'success');

    }

}