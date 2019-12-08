<?php


namespace app\common\event;

use think\Db;
use app\common\model\Nav;

class NavEvent extends BaseEvent
{

    public function setState($navID, $state) {
        if ($state != 0 && $state != 1) {
            return array_err(1951299, '状态设置错误');
        }

        //检查数据是否存在
        $mNav = new Nav();
        $navRes = array();
        $flag = $this->checkNavID($navID, $navRes, true);

        if ($flag['code'] > 0) {
            return $flag;
        }

        if ($state == $navRes[$navID]['status']) {
            return array_err(1951296, '状态没有发生变化,不需要修改');
        }

        $editFlag = $mNav->save([
            'status' => $state,
        ], ['id' => $navID]);

        if ($editFlag === false) {
            return array_err(1951296, '修改状态失败');
        }

        return array_err(0, '修改状态成功');
    }
    /**
     * 检查导航是否正确根据主键
     * @param $navID
     * @param $navRes
     * @param bool $lock
     * @return array
     */
    public function checkNavID($navID, &$navRes, $lock = false) {
        $mNav = new Nav();
        if (empty($navID)) {
            return array_err(1951298, '导航标识不能为空');
        }
        $navRes = $mNav->getNavByKV($navID, $lock);
        if (count($navID) != count($navRes)) {
            return array_err(1951297, '存在非法数据');
        }

        if (empty($navRes)) {
            return array_err(1951296, '没有查找到您要的标识');
        }
        return array_err(0, 'success');
    }

    /**
     * 修改导航数据
     * @param $navID
     * @param $data
     * @return array
     */
    public function editNav($navID, $data) {
        $navRes = array();
        $flag = $this->checkNavID($navID, $navRes, true);
        if ($flag['code'] > 0) {
            return $flag;
        }

        $flag = $this->checkNavData($data, $navID);

        if ($flag['code'] > 0) {
            return $flag;
        }

        //这边判断数据是否有变化
        foreach ($data as $k => $v) {
            if ($v == $navRes[$navID][$k]) {
                unset($data[$k]);
            }
        }

        if (empty($data)) {
            return array_err(0, '数据没有发生变化无需要修改');
        }

        $mNav = new Nav();

        $editFlag = $mNav->save($data, ['id' => $navID]);

        if ($editFlag === false) {
            return array_err(1951296, '修改导航数据失败');
        }

        return array_err(0, '修改导航数据成功');
    }


    /**
     * 数据排序
     * @param $data
     * @return array
     * @throws \Exception
     */
    public function setSort($data) {
        $navRes = array();

        $navID = array_keys($data);
        $flag = $this->checkNavID($navID, $navRes, true);
        if ($flag['code'] > 0) {
            return $flag;
        }

        $mNav = new Nav();
        $sortRes = array();
        //
        foreach ($data as $k => $v) {
            if ($v == $navRes[$k]['sort']) {
                continue;
            }
            $sortRes[$k]['id'] = $k;
            $sortRes[$k]['sort'] = $v;
        }

        $editFlag = $mNav->isUpdate()->saveAll($sortRes);

        if ($editFlag === false) {
            return array_err(1951296, '修改导航数据失败');
        }

        return array_err(0, '修改导航数据成功');
    }

    /**
     * 添加导航数据
     * @param $data
     * @return array
     */
    public function addNav($data) {
        $flag = $this->checkNavData($data);

        if ($flag['code'] > 0) {
            return $flag;
        }

        $mNav = new Nav();

        $editFlag = $mNav->save($data);

        if ($editFlag === false) {
            return array_err(1951296, '添加导航数据失败');
        }

        return array_err(0, '添加导航数据成功');
    }

    /**
     * 删除导航数据
     * @param $navID
     * @return array
     */
    public function delNav($navID) {
        $navRes = array();
        $flag = $this->checkNavID($navID, $navRes, true);

        if ($flag['code'] > 0) {
            return $flag;
        }

        $mNav = new Nav();

        $delFlag = $mNav::destroy($navID);

        if ($delFlag === false) {
            return array_err(1951296, '删除导航数据失败');
        }

        return array_err(0, '删除导航数据成功');
    }

    public function checkNavData(&$data, $navID = '') {

        if (empty($data['nav_name'])) {
            return array_err(1952199, '导航名称不能为空');
        }

        if ($data['status'] != 0 && $data['status'] != 1) {
            return array_err(1952198, '导航状态错误');
        }


        if (!empty($data['nav_url'])) {
            $data['nav_url'] = right_url($data['nav_url']);
        }

        //这边判断打开方式和导航位置是否正确 todo

        $param['nav_name'] = $data['nav_name'];

        if ($navID) {
            $param['id'] = array('neq', $navID);
        }

        $navRes = Db::name('nav')->where($param)->find();

        if ($navRes) {
            return array_err(1952196, '导航名称已经被使用,请更换');
        }

        return array_err(0, 'success');

    }

}