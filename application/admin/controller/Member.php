<?php
/**
 * 会员
 */

namespace app\admin\controller;

use think\Db;
use app\common\model\MemberLevel as MemberLevelModel;
use app\common\model\Attr;
use app\common\event\MemberEvent;
use app\common\util\FilesUtil;

class Member extends Base
{
    /**addlevel
     * 会员等级列表页面
     * @return mixed
     */
    public function level() {
        return $this->fetch();
    }


    /**
     * 获取会员等级数据
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getData() {
        //todo权限
        $this->_inputAjax();
        $mMemberLevel = new MemberLevelModel();

        $param = json_decode_html(input('param'));
        $param['curr_page'] = input('page/d');
        $param['page_count'] = input('limit/d');
        //条数的限制
        //dealPage();
        $data = array_err(0, 'success');
        $count = $mMemberLevel->getLevelByParamCnt($param);
        $data['count'] = $count;
        $MemberLevelRes = array();
        if ($count) {
            $MemberLevelRes = $mMemberLevel->getLevelByParam($param);
        }

        $data['data'] = $MemberLevelRes;
        return $data;
    }

    /**
     * 修改会员等级数据
     * @return array
     */
    public function editlevel() {
        $this->_inputAjax();
        $data = json_decode_html(input('data'));
        $levelID = input('id/d');
        Db::startTrans();
        $mMember = new MemberEvent();

        $flag = $mMember->editlevel($levelID, $data);
        if ($flag['code'] > 0) {
            Db::rollback();
        } else {
            Db::commit();
        }
        return $flag;
    }

    public function addLevel() {
        $this->_inputAjax();
        $data = json_decode_html(input('data'));

        $mMember = new MemberEvent();

        $flag = $mMember->addLevel($data);

        return $flag;
    }

    /**
     * 删除会员等级
     * @return array
     */
    public function delLevel() {
        $this->_inputAjax();
        $MemberID = json_decode_html(input('id'));
        Db::startTrans();
        $mMember = new MemberEvent();

        $flag = $mMember->delLevel($MemberID);
        if ($flag['code'] > 0) {
            Db::rollback();
        } else {
            Db::commit();
        }
        return $flag;
    }
    
}
