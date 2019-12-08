<?php


namespace app\common\event;

use think\Db;
use app\common\model\Recpos;

class RecposEvent extends BaseEvent
{

    public function setState($recposID, $state) {
        if ($state != 0 && $state != 1) {
            return array_err(1951299, '状态设置错误');
        }

        //检查数据是否存在
        $mRecpos = new Recpos();
        $recposRes = array();
        $flag = $this->checkRecposID($recposID, $recposRes, true);

        if ($flag['code'] > 0) {
            return $flag;
        }

        if ($state == $recposRes[$recposID]['status']) {
            return array_err(1951296, '状态没有发生变化,不需要修改');
        }

        $editFlag = $mRecpos->save([
            'status' => $state,
        ], ['id' => $recposID]);

        if ($editFlag === false) {
            return array_err(1951296, '修改状态失败');
        }

        return array_err(0, '修改状态成功');
    }

    /**
     * 检查推荐位是否正确根据主键
     * @param $recposID
     * @param $recposRes
     * @param bool $lock
     * @return array
     */
    public function checkRecposID($recposID, &$recposRes, $lock = false) {
        $mRecpos = new Recpos();
        if (empty($recposID)) {
            return array_err(1951298, '推荐位标识不能为空');
        }
        $recposRes = $mRecpos->getRecposByKV($recposID, $lock);

        if (count($recposID) != count($recposRes)) {
            return array_err(1951297, '存在非法数据');
        }

        if (empty($recposRes)) {
            return array_err(1951296, '没有查找到您要的标识');
        }
        return array_err(0, 'success');
    }

    /**
     * 修改推荐位数据
     * @param $recposID
     * @param $data
     * @return array
     */
    public function editRecpos($recposID, $data) {
        $recposRes = array();
        $flag = $this->checkRecposID($recposID, $recposRes, true);
        if ($flag['code'] > 0) {
            return $flag;
        }

        $flag = $this->checkRecposData($data, $recposID);

        if ($flag['code'] > 0) {
            return $flag;
        }

        //这边判断数据是否有变化
        foreach ($data as $k => $v) {
            if ($v == $recposRes[$recposID][$k]) {
                unset($data[$k]);
            }
        }

        if (empty($data)) {
            return array_err(0, '数据没有发生变化无需要修改');
        }

        $mRecpos = new Recpos();

        $editFlag = $mRecpos->save($data, ['id' => $recposID]);

        if ($editFlag === false) {
            return array_err(1951296, '修改推荐位数据失败');
        }

        return array_err(0, '修改推荐位数据成功');
    }


    /**
     * 数据排序
     * @param $data
     * @return array
     * @throws \Exception
     */
    public function setSort($data) {
        $recposRes = array();

        $recposID = array_keys($data);
        $flag = $this->checkRecposID($recposID, $recposRes, true);
        if ($flag['code'] > 0) {
            return $flag;
        }

        $mRecpos = new Recpos();
        $sortRes = array();
        //
        foreach ($data as $k => $v) {
            if ($v == $recposRes[$k]['sort']) {
                continue;
            }
            $sortRes[$k]['id'] = $k;
            $sortRes[$k]['sort'] = $v;
        }

        $editFlag = $mRecpos->isUpdate()->saveAll($sortRes);

        if ($editFlag === false) {
            return array_err(1951296, '修改推荐位数据失败');
        }

        return array_err(0, '修改推荐位数据成功');
    }

    /**
     * 添加推荐位数据
     * @param $data
     * @return array
     */
    public function addRecpos($data) {
        $flag = $this->checkRecposData($data);

        if ($flag['code'] > 0) {
            return $flag;
        }

        $mRecpos = new Recpos();

        $editFlag = $mRecpos->save($data);

        if ($editFlag === false) {
            return array_err(1951296, '添加推荐位数据失败');
        }

        return array_err(0, '添加推荐位数据成功');
    }

    /**
     * 删除推荐位数据
     * @param $recposID
     * @return array
     */
    public function delRecpos($recposID) {
        $recposRes = array();
        $flag = $this->checkRecposID($recposID, $recposRes, true);

        if ($flag['code'] > 0) {
            return $flag;
        }

        $mRecpos = new Recpos();

        $delFlag = $mRecpos::destroy($recposID);

        if ($delFlag === false) {
            return array_err(1951296, '删除推荐位数据失败');
        }

        return array_err(0, '删除推荐位数据成功');
    }

    public function checkRecposData(&$data, $recposID = '') {

        if (empty($data['rec_name'])) {
            return array_err(1952199, '推荐位名称不能为空');
        }

        if ($data['status'] != 0 && $data['status'] != 1) {
            return array_err(1952198, '推荐位状态错误');
        }

        if ($data['rec_type'] != 2 && $data['rec_type'] != 1) {
            return array_err(1952198, '推荐位类型错误');
        }

        $param['rec_name'] = $data['rec_name'];

        if ($recposID) {
            $param['id'] = array('neq', $recposID);
        }

        $recposRes = Db::name('recpos')->where($param)->find();

        if ($recposRes) {
            return array_err(1952196, '推荐位名称已经被使用,请更换');
        }

        return array_err(0, 'success');

    }

}