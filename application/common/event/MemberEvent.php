<?php


namespace app\common\event;

use app\common\model\Level;
use think\Db;
use app\common\model\MemberLevel;

class MemberEvent extends BaseEvent
{
    
    /**
     * 添加商品类型的属性
     * @param $data
     * @return array
     */
    public function addLevel($data){
        $checkFlag = $this->checkLevel($data);

        if ($checkFlag['code'] > 0) {
            return $checkFlag;
        }

        $mMemberLevel = new MemberLevel();

        $editFlag = $mMemberLevel->save($data);

        if ($editFlag === false) {
            return array_err(1951296, '添加等级数据失败');
        }

        return array_err(0, '添加等级数据成功');
    }

    /**
     * 修改商品类型的属性
     * @param $data
     * @param $levelID
     * @return array
     */
    public function editLevel($data,$levelID){
        $levelRes = array();
        $flag = $this->checkLevelID($levelID, $levelRes, true);
        if ($flag['code'] > 0) {
            return $flag;
        }

        $checkFlag = $this->checkLevel($data,$levelID);

        if ($checkFlag['code'] > 0) {
            return $checkFlag;
        }

        //这边判断数据是否有变化
        foreach ($data as $k => $v) {
            if ($v == $levelRes[$levelID][$k]) {
                unset($data[$k]);
            }
        }

        if (empty($data)) {
            return array_err(9189, '数据没有发生变化无需要修改');
        }

        $mMemberLevel = new MemberLevel();
        $editFlag = $mMemberLevel->save($data, ['id' => $levelID]);

        if ($editFlag === false) {
            return array_err(1951296, '修改等级数据失败');
        }

        return array_err(0, '修改等级数据成功');
    }

    /**
     * 删除类型数据
     * @param $levelID
     * @return array
     */
    public function delMember($levelID) {
        $levelRes = array();
        $flag = $this->checkLevelID($levelID, $levelRes, true);

        if ($flag['code'] > 0) {
            return $flag;
        }

        $mMember = new MemberLevel();

        $delFlag = $mMember::destroy($levelID);

        if ($delFlag === false) {
            return array_err(1951296, '删除等级数据失败');
        }

        return array_err(0, '删除等级数据成功');
    }


    /**
     * 检查属性是否正确根据主键
     * @param $levelID
     * @param $levelRes
     * @param bool $lock
     * @return array
     */
    public function checkLevelID($levelID, &$levelRes, $lock = false) {
        $mMemberLevel = new MemberLevel();
        if (empty($levelID)) {
            return array_err(1951298, '属性标识不能为空');
        }
        $levelRes = $mMemberLevel->getMemberByKV($levelID, $lock,'id,member_name,member_values,member_type,type_id');

        if (count($levelID) != count($levelRes)) {
            return array_err(1951297, '存在非法数据');
        }

        if (empty($levelRes)) {
            return array_err(1951296, '没有查找到您要的标识');
        }
        return array_err(0, 'success');
    }


    public function checkLevel(&$data,$levelID = ''){
        if(empty($data['level_name'])){
            return array_err(19918, '等级名称不能为空');
        }

        if(!is_numeric($data['bom_point']) || !is_numeric($data['top_point'])){
            return array_err(19916, '等级上下线不能为空,且必须是数字');
        }

        if(!is_numeric($data['rate'])){
            return array_err(19916, '折扣率必须是数字');
        }

        if($data['rate'] < 0 || $data['rate'] > 100){
            return array_err(19916, '折扣率必须在0~100之间');
        }

        $mMemberLevel = new MemberLevel();

        if($levelID){
            $param['not_id'] = array('neq',$levelID);
        }

        $param['level_name'] = $data['level_name'];
        $levelRes = $mMemberLevel->getMemberByParam($param);

        if($levelRes){
            return array_err(19914, '该等级已存在相同的名称,请更换');
        }

        return array_err(0,'success');

    }

}