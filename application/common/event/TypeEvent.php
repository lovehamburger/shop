<?php


namespace app\common\event;

use think\Db;
use app\common\model\Type;
use app\common\model\Attr;

class TypeEvent extends BaseEvent
{

    public function setState($typeID, $state) {
        if ($state != 0 && $state != 1) {
            return array_err(1951299, '状态设置错误');
        }

        //检查数据是否存在
        $mType = new Type();
        $typeRes = array();
        $flag = $this->checkTypeID($typeID, $typeRes, true);

        if ($flag['code'] > 0) {
            return $flag;
        }

        if ($state == $typeRes[$typeID]['status']) {
            return array_err(1951296, '状态没有发生变化,不需要修改');
        }

        $editFlag = $mType->save([
            'status' => $state,
        ], ['id' => $typeID]);

        if ($editFlag === false) {
            return array_err(1951296, '修改状态失败');
        }

        return array_err(0, '修改状态成功');
    }

    /**
     * 检查类型是否正确根据主键
     * @param $typeID
     * @param $typeRes
     * @param bool $lock
     * @return array
     */
    public function checkTypeID($typeID, &$typeRes, $lock = false) {
        $mType = new Type();
        if (empty($typeID)) {
            return array_err(1951298, '类型标识不能为空');
        }
        $typeRes = $mType->getTypeByKV($typeID, $lock);

        if (count($typeID) != count($typeRes)) {
            return array_err(1951297, '存在非法数据');
        }

        if (empty($typeRes)) {
            return array_err(1951296, '没有查找到您要的标识');
        }
        return array_err(0, 'success');
    }

    /**
     * 修改类型数据
     * @param $typeID
     * @param $data
     * @return array
     */
    public function editType($typeID, $data) {
        $typeRes = array();
        $flag = $this->checkTypeID($typeID, $typeRes, true);
        if ($flag['code'] > 0) {
            return $flag;
        }

        $flag = $this->checkTypeData($data, $typeID);

        if ($flag['code'] > 0) {
            return $flag;
        }

        //这边判断数据是否有变化
        foreach ($data as $k => $v) {
            if ($v == $typeRes[$typeID]) {
                unset($data[$k]);
            }
        }

        if (empty($data)) {
            return array_err(0, '数据没有发生变化无需要修改');
        }

        $mType = new Type();
        $editFlag = $mType->save($data, ['id' => $typeID]);

        if ($editFlag === false) {
            return array_err(1951296, '修改类型数据失败');
        }

        return array_err(0, '修改类型数据成功');
    }


    /**
     * 数据排序
     * @param $data
     * @return array
     * @throws \Exception
     */
    public function setSort($data) {
        $typeRes = array();

        $typeID = array_keys($data);
        $flag = $this->checkTypeID($typeID, $typeRes, true);
        if ($flag['code'] > 0) {
            return $flag;
        }

        $mType = new Type();
        $sortRes = array();
        //
        foreach ($data as $k => $v) {
            if ($v == $typeRes[$k]['sort']) {
                continue;
            }
            $sortRes[$k]['id'] = $k;
            $sortRes[$k]['sort'] = $v;
        }

        $editFlag = $mType->isUpdate()->saveAll($sortRes);

        if ($editFlag === false) {
            return array_err(1951296, '修改类型数据失败');
        }

        return array_err(0, '修改类型数据成功');
    }

    /**
     * 添加类型数据
     * @param $data
     * @return array
     */
    public function addType($data) {
        $flag = $this->checkTypeData($data);

        if ($flag['code'] > 0) {
            return $flag;
        }

        $mType = new Type();

        $editFlag = $mType->save($data);

        if ($editFlag === false) {
            return array_err(1951296, '添加类型数据失败');
        }

        return array_err(0, '添加类型数据成功');
    }

    /**
     * 删除类型数据
     * @param $typeID
     * @return array
     */
    public function delType($typeID) {
        $typeRes = array();
        $flag = $this->checkTypeID($typeID, $typeRes, true);

        if ($flag['code'] > 0) {
            return $flag;
        }

        $mType = new Type();

        $delFlag = $mType::destroy($typeID);

        if ($delFlag === false) {
            return array_err(1951296, '删除类型数据失败');
        }

        return array_err(0, '删除类型数据成功');
    }

    public function checkTypeData(&$data, $typeID = '') {

        if (empty($data['type_name'])) {
            return array_err(1952199, '类型名称不能为空');
        }

        if (!count_words($data['type_name'], 30, 'lt')) {
            return array_err(1952197, '描述文字不能大于30');
        }

        $param['type_name'] = $data['type_name'];

        if ($typeID) {
            $param['id'] = array('neq', $typeID);
        }

        $typeRes = Db::name('type')->where($param)->find();

        if ($typeRes) {
            return array_err(1952196, '类型名称已经被使用,请更换');
        }

        return array_err(0, 'success');
    }

    /************************************************************************************************************
     *                                             类型属性
     ***********************************************************************************************************/

    /**
     * 添加商品类型的属性
     * @param $data
     * @return array
     */
    public function addAttr($data){
        $checkFlag = $this->checkAttr($data);

        if ($checkFlag['code'] > 0) {
            return $checkFlag;
        }

        $mAttr = new Attr();

        $editFlag = $mAttr->save($data);

        if ($editFlag === false) {
            return array_err(1951296, '添加属性数据失败');
        }

        return array_err(0, '添加属性数据成功');
    }

    /**
     * 修改商品类型的属性
     * @param $data
     * @param $attrID
     * @return array
     */
    public function editAttr($data,$attrID){
        $attrRes = array();
        $flag = $this->checkAttrID($attrID, $attrRes, true);
        if ($flag['code'] > 0) {
            return $flag;
        }

        $checkFlag = $this->checkAttr($data,$attrID);

        if ($checkFlag['code'] > 0) {
            return $checkFlag;
        }

        //这边判断数据是否有变化
        foreach ($data as $k => $v) {
            if ($v == $attrRes[$attrID]) {
                unset($data[$k]);
            }
        }
        if (empty($data)) {
            return array_err(0, '数据没有发生变化无需要修改');
        }

        $mAttr = new Attr();
        $editFlag = $mAttr->save($data, ['id' => $attrID]);

        if ($editFlag === false) {
            return array_err(1951296, '修改类型数据失败');
        }

        return array_err(0, '修改类型数据成功');
    }

    /**
     * 删除类型数据
     * @param $attrID
     * @return array
     */
    public function delAttr($attrID) {
        $attrRes = array();
        $flag = $this->checkAttrID($attrID, $attrRes, true);

        if ($flag['code'] > 0) {
            return $flag;
        }

        $mAttr = new Attr();

        $delFlag = $mAttr::destroy($attrID);

        if ($delFlag === false) {
            return array_err(1951296, '删除属性数据失败');
        }

        return array_err(0, '删除属性数据成功');
    }


    /**
     * 检查属性是否正确根据主键
     * @param $attrID
     * @param $attrRes
     * @param bool $lock
     * @return array
     */
    public function checkAttrID($attrID, &$attrRes, $lock = false) {
        $mAttr = new Type();
        if (empty($attrID)) {
            return array_err(1951298, '属性标识不能为空');
        }
        $attrRes = $mAttr->getAttrByKV($attrID, $lock);

        if (count($attrID) != count($attrRes)) {
            return array_err(1951297, '存在非法数据');
        }

        if (empty($attrRes)) {
            return array_err(1951296, '没有查找到您要的标识');
        }
        return array_err(0, 'success');
    }


    public function checkAttr(&$data,$attrID = ''){
        if(empty($data['attr_name'])){
            return array_err(19918, '属性名称不能为空');
        }

        if(empty($data['type_id'])){
            return array_err(19917, '商品类型不能为空');
        }

        if(empty($data['attr_values'])){
            return array_err(19916, '属性值不能为空');
        }

        //验证值是否合法 todo
        if($data['attr_type'] == 1){
            if (!count_words($data['type_name'], 30, 'lt')) {
                return array_err(1952197, '描述文字不能大于30');
            }
        }elseif ($data['attr_type'] == 2){
            str_replace("，",",",$data['attr_values']);
            $data['attr_values'] = trim($data['attr_values'],',');
        }else{
            return array_err(19915, '属性类型设置错误');
        }

        $mAttr = new Attr();
        if($attrID){
            $param['attr_id'] = array('not',$data['type_id']);
        }

        $param['type_id'] = $data['type_id'];
        $param['attr_name'] = $data['attr_name'];
        $attrRes = $mAttr->getAttrByTypeID($param);
        if($attrRes){
            return array_err(19914, '该商品类型下存在相同的名称属性,请更换');
        }

        return array_err(0,'success');

    }

}