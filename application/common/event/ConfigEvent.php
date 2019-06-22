<?php


namespace app\common\event;

use think\Db;
use think\Loader;
use app\common\model\Config;

class ConfigEvent extends BaseEvent
{

    public function setState($configID, $state) {
        if ($state != 0 && $state != 1) {
            return array_err(1951299, '状态设置错误');
        }

        //检查数据是否存在
        $mConfig = new Config();
        $configRes = array();
        $flag = $this->checkConfigID($configID, $configRes, true);

        if ($flag['code'] > 0) {
            return $flag;
        }

        if ($state == $configRes[$configID]['status']) {
            return array_err(1951296, '状态没有发生变化,不需要修改');
        }

        $editFlag = $mConfig->save([
            'status' => $state,
        ], ['id' => $configID]);

        if ($editFlag === false) {
            return array_err(1951296, '修改状态失败');
        }

        return array_err(0, '修改状态成功');
    }

    /**
     * 检查配置是否正确根据主键
     * @param $configID
     * @param $configRes
     * @param bool $lock
     * @return array
     */
    public function checkConfigID($configID, &$configRes, $lock = false) {
        $mConfig = new Config();
        if (empty($configID)) {
            return array_err(1951298, '配置标识不能为空');
        }
        $configRes = $mConfig->getConfigByKV($configID, $lock);

        if (count($configID) != count($configRes)) {
            return array_err(1951297, '存在非法数据');
        }

        if (empty($configRes)) {
            return array_err(1951296, '没有查找到您要的标识');
        }
        return array_err(0, 'success');
    }

    /**
     * 修改配置数据
     * @param $configID
     * @param $data
     * @return array
     */
    public function editConfig($configID, $data) {
        $configRes = array();
        $flag = $this->checkConfigID($configID, $configRes, true);
        if ($flag['code'] > 0) {
            return $flag;
        }

        $flag = $this->checkConfigData($data, $configID);

        if ($flag['code'] > 0) {
            return $flag;
        }

        //这边判断数据是否有变化
        foreach ($data as $k => $v) {
            if ($v == $configRes[$configID][$k]) {
                unset($data[$k]);
            }
        }

        if (empty($data)) {
            return array_err(0, '数据没有发生变化无需要修改');
        }

        $mConfig = new Config();

        $editFlag = $mConfig->save($data, ['id' => $configID]);

        if ($editFlag === false) {
            return array_err(1951296, '修改配置数据失败');
        }

        return array_err(0, '修改配置数据成功');
    }


    /**
     * 数据排序
     * @param $data
     * @return array
     * @throws \Exception
     */
    public function setSort($data) {
        $configRes = array();

        $configID = array_keys($data);
        $flag = $this->checkConfigID($configID, $configRes, true);
        if ($flag['code'] > 0) {
            return $flag;
        }

        $mConfig = new Config();
        $sortRes = array();
        //
        foreach ($data as $k => $v) {
            if ($v == $configRes[$k]['sort']) {
                continue;
            }
            $sortRes[$k]['id'] = $k;
            $sortRes[$k]['sort'] = $v;
        }

        $editFlag = $mConfig->isUpdate()->saveAll($sortRes);

        if ($editFlag === false) {
            return array_err(1951296, '修改配置数据失败');
        }

        return array_err(0, '修改配置数据成功');
    }

    /**
     * 添加配置数据
     * @param $data
     * @return array
     */
    public function addConfig($data) {
        $flag = $this->checkConfigData($data);

        if ($flag['code'] > 0) {
            return $flag;
        }

        $mConfig = new Config();

        $editFlag = $mConfig->save($data);

        if ($editFlag === false) {
            return array_err(1951296, '添加配置数据失败');
        }

        return array_err(0, '添加配置数据成功');
    }

    /**
     * 删除配置数据
     * @param $configID
     * @return array
     */
    public function delConfig($configID) {
        $configRes = array();
        $flag = $this->checkConfigID($configID, $configRes, true);

        if ($flag['code'] > 0) {
            return $flag;
        }

        $mConfig = new Config();

        $delFlag = $mConfig::destroy($configID);

        if ($delFlag === false) {
            return array_err(1951296, '删除配置数据失败');
        }

        return array_err(0, '删除配置数据成功');
    }

    public function checkConfigData(&$data, $configID = '') {

        if (empty($data['cname'])) {
            return array_err(1952199, '配置中文名称不能为空');
        }

        if ($data['conf_type'] != 1 && $data['conf_type'] != 2) {
            return array_err(1952198, '配置类型错误');
        }

        if ($data['status'] != 0 && $data['status'] != 1) {
            return array_err(1952198, '配置状态错误');
        }

        if (!in_array($data['form_type'] ,array('input','select','checkbox','radio','textarea','file'))) {//todo  这边的配置需要优化
            return array_err(1952198, '表单类型设置错误');
        }

        //这边如果是select,checkbox,radio的时候将存在的中文逗号替换成英文逗号
        if(in_array($data['form_type'] ,array('select','checkbox','radio'))){
            $data['values'] = trim(str_replace('，',',',$data['values']),',');
            $data['value'] = trim(str_replace('，',',',$data['value']),',');
        }else{
            $data['values'] = '';
            $data['value'] = '';
        }

        if(empty($data['ename'])){
            //vendor('PinYin.PinYin#class');这种调用composer的方式调用第三方扩展库
            Loader::import('PinYin\PinYin', EXTEND_PATH);//使用这种是将扩展库放在extent目录下的扩展库
            $data['ename'] = \PinYin::encode($data['cname']);//拼音的自动填写
        }

        $param['cname'] = $data['cname'];

        if ($configID) {
            $param['id'] = array('neq', $configID);
        }

        $configRes = Db::name('config')->where($param)->find();

        if ($configRes) {
            return array_err(1952196, '配置名称已经被使用,请更换');
        }

        return array_err(0, 'success');

    }

}