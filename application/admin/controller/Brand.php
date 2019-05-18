<?php
/**
 * 商品品牌
 */

namespace app\admin\controller;

use think\Db;
use app\common\model\Brand as BrandModel;
use app\common\event\BrandEvent;

class Brand extends Base
{
    /**
     * 品牌列表页面
     * @return mixed
     */
    public function index() {
        return $this->fetch();
    }


    /**
     * 获取品牌数据
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getData() {
        //todo权限
        $this->_inputAjax();
        $mBrand = new BrandModel();

        $param = json_decode_html(input('param'));
        $param['curr_page'] = input('page/d');
        $param['page_count'] = input('limit/d');
        //条数的限制
        //dealPage();
        $data = array_err(0, 'success');
        $count = $mBrand->getBrandByParamCnt($param);
        $data['count'] = $count;
        $brandRes = array();
        if ($count) {
            $brandRes = $mBrand->getBrandByParam($param);
        }

        $data['data'] = $brandRes;
        return $data;
    }

    /**
     * 修改品牌状态
     * @return array
     */
    public function setState(){
        $this->_inputAjax();
        $state = input('state/d');
        $brandID = input('id/d');
        $mBrand = new BrandEvent();

        Db::startTrans();
        $flag = $mBrand->setState($brandID,$state);

        if($flag['err_code'] > 0){
            Db::rollback();
        }else{
            Db::commit();
        }
        return $flag;
    }

    public function upload(){
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('image');

        // 移动到框架应用根目录/public/uploads/ 目录下
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                // 成功上传后 获取上传信息
                // 输出 jpg
                echo $info->getExtension();
                // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                echo $info->getSaveName();
                // 输出 42a79759f284b767dfcb2a0197904287.jpg
                echo $info->getFilename();
            }else{
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }
    }
}
