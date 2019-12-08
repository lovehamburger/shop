<?php
/**
 * 导航
 */

namespace app\admin\controller;

use think\Db;
use app\common\model\Nav as NavModel;
use app\common\event\NavEvent;
use app\common\util\FilesUtil;

class Nav extends Base
{
    /**
     * 导航列表页面
     * @return mixed
     */
    public function index() {
        return $this->fetch();
    }


    /**
     * 获取导航数据
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getData() {
        //todo权限
        $this->_inputAjax();
        $mNav = new NavModel();

        $param = json_decode_html(input('param'));
        $param['curr_page'] = input('page/d');
        $param['page_count'] = input('limit/d');
        //条数的限制
        //dealPage();
        $data = array_err(0, 'success');
        $count = $mNav->getNavByParamCnt($param);
        $data['count'] = $count;
        $linkRes = array();
        if ($count) {

            $linkRes = $mNav->getNavByParam($param);
        }

        $data['data'] = $linkRes;
        return $data;
    }

    /**
     * 修改导航状态
     * @return array
     */
    public function setState() {
        $this->_inputAjax();
        $state = input('state/d');
        $linkID = input('id/d');
        $mNav = new NavEvent();

        Db::startTrans();
        $flag = $mNav->setState($linkID, $state);

        if ($flag['code'] > 0) {
            Db::rollback();
        } else {
            Db::commit();
        }
        return $flag;
    }

    /**
     * 设置排序
     * @return array
     * @throws \Exception
     */
    public function setSort() {
        $this->_inputAjax();
        $sort = json_decode_html(input('sort'));
        Db::startTrans();
        $mNav = new NavEvent();

        $flag = $mNav->setSort($sort);
        if ($flag['code'] > 0) {
            Db::rollback();
        } else {
            Db::commit();
        }
        return $flag;
    }

    /**
     * 修改导航数据
     * @return array
     */
    public function editNav() {
        $this->_inputAjax();
        $data = json_decode_html(input('data'));
        $linkID = input('id/d');
        Db::startTrans();
        $mNav = new NavEvent();

        $flag = $mNav->editNav($linkID, $data);
        if ($flag['code'] > 0) {
            Db::rollback();
        } else {
            Db::commit();
        }
        return $flag;
    }

    /**
     * 添加导航
     * @return mixed
     */
    public function addNav() {
        $this->_inputAjax();
        $data = json_decode_html(input('data'));

        $mNav = new NavEvent();

        $flag = $mNav->addNav($data);

        return $flag;
    }

    /**
     * 删除导航
     * @return array
     */
    public function delNav() {
        $this->_inputAjax();
        $linkID = json_decode_html(input('id'));
        Db::startTrans();
        $mNav = new NavEvent();

        $flag = $mNav->delNav($linkID);
        if ($flag['code'] > 0) {
            Db::rollback();
        } else {
            Db::commit();
        }
        return $flag;
    }

    /**
     * 图片文件上传
     * @return array
     */
    public function upload() {
        $this->_inputAjax();
        $files = new FilesUtil();
        $files->size = config('uploads.link')['size'];
        $files->saveDir = config('uploads.link')['save_dir'];
        $files->ext = config('uploads.link')['ext'];
        $files->thumb = config('uploads.link')['thumb'];
        return $flag = $files->uploads('image');
    }
}
