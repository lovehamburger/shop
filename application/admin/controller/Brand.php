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
}
