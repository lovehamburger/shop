<?php


namespace app\common\event;

use think\Db;
use app\common\model\Goods;

class GoodsEvent extends BaseEvent
{

    public function setState($brandID, $state) {
        if ($state != 0 && $state != 1) {
            return array_err(1951299, '状态设置错误');
        }

        //检查数据是否存在
        $mGoods = new Goods();
        $brandRes = array();
        $flag = $this->checkGoodsID($brandID, $brandRes, true);

        if ($flag['code'] > 0) {
            return $flag;
        }

        if ($state == $brandRes[$brandID]['status']) {
            return array_err(1951296, '状态没有发生变化,不需要修改');
        }

        $editFlag = $mGoods->save([
            'status' => $state,
        ], ['id' => $brandID]);

        if ($editFlag === false) {
            return array_err(1951296, '修改状态失败');
        }

        return array_err(0, '修改状态成功');
    }

    /**
     * 检查品牌是否正确根据主键
     * @param $brandID
     * @param $brandRes
     * @param bool $lock
     * @return array
     */
    public function checkGoodsID($brandID, &$brandRes, $lock = false) {
        $mGoods = new Goods();
        if (empty($brandID)) {
            return array_err(1951298, '品牌标识不能为空');
        }
        $brandRes = $mGoods->getGoodsCateByKV($brandID, $lock);

        if (count($brandID) != count($brandRes)) {
            return array_err(1951297, '存在非法数据');
        }

        if (empty($brandRes)) {
            return array_err(1951296, '没有查找到您要的标识');
        }
        return array_err(0, 'success');
    }

    /**
     * 修改品牌数据
     * @param $brandID
     * @param $data
     * @return array
     */
    public function editGoods($brandID, $data) {
        $brandRes = array();
        $flag = $this->checkGoodsID($brandID, $brandRes, true);
        if ($flag['code'] > 0) {
            return $flag;
        }

        $flag = $this->checkGoodsData($data, $brandID);

        if ($flag['code'] > 0) {
            return $flag;
        }

        //这边判断数据是否有变化
        foreach ($data as $k => $v) {
            if ($v == $brandRes[$brandID][$k]) {
                unset($data[$k]);
            }
        }

        if (empty($data)) {
            return array_err(0, '数据没有发生变化无需要修改');
        }

        $mGoods = new Goods();

        $editFlag = $mGoods->save($data, ['id' => $brandID]);

        if ($editFlag === false) {
            return array_err(1951296, '修改品牌数据失败');
        }

        return array_err(0, '修改品牌数据成功');
    }


    /**
     * 数据排序
     * @param $data
     * @return array
     * @throws \Exception
     */
    public function setSort($data) {
        $brandRes = array();

        $brandID = array_keys($data);
        $flag = $this->checkGoodsID($brandID, $brandRes, true);
        if ($flag['code'] > 0) {
            return $flag;
        }
        
        $sortRes = array();
        foreach ($data as $k => $v) {
            if ($v == $brandRes[$k]['sort']) {
                continue;
            }
            $sortRes['id'] = $k;
            $sortRes['sort'] = $v;
            $editFlag = Db::name('category')->update($sortRes);

            if ($editFlag === false) {
                return array_err(1951296, '修改数据排序失败');
            }
        }

        return array_err(0, '修改排序数据成功');
    }

    /**
     * 添加品牌数据
     * @param $data
     * @return array
     */
    public function addGoods($data) {
        $flag = $this->checkGoodsData($data);

        if ($flag['code'] > 0) {
            return $flag;
        }

        $mGoods = new Goods();

        $editFlag = $mGoods->save($data);

        if ($editFlag === false) {
            return array_err(1951296, '添加品牌数据失败');
        }

        return array_err(0, '添加品牌数据成功');
    }

    /**
     * 删除品牌数据
     * @param $brandID
     * @return array
     */
    public function delGoods($brandID) {
        $brandRes = array();
        $flag = $this->checkGoodsID($brandID, $brandRes, true);

        if ($flag['code'] > 0) {
            return $flag;
        }

        $mGoods = new Goods();

        $delFlag = $mGoods::destroy($brandID);

        if ($delFlag === false) {
            return array_err(1951296, '删除品牌数据失败');
        }

        return array_err(0, '删除品牌数据成功');
    }

    public function checkCateGoryID($cateID,$lock = false,&$cateGoryRes){
        if(empty($cateID)){
            return array_err(19869, '分类标识不能为空');
        }

        $mGoods = new Goods();
        $cateGoryRes = $mGoods->getGoodsCateByKV($cateID,$lock);
        return array_err(0,'success');
    }

    public function checkGoodsData(&$data, $brandID = '') {

        if (empty($data['brand_name'])) {
            return array_err(1952199, '品牌名称不能为空');
        }

        if ($data['brand_state'] != 0 && $data['brand_name'] != 1) {
            return array_err(1952198, '品牌状态错误');
        }

        if (!count_words($data['brand_description'], 200, 'lt') && !empty($data['brand_description'])) {
            return array_err(1952197, '描述文字不能大于200');
        }

        if (!empty($data['brand_url'])) {
            $data['brand_url'] = right_url($data['brand_url']);
        }

        $param['brand_name'] = $data['brand_name'];

        if ($brandID) {
            $param['id'] = array('neq', $brandID);
        }

        $brandRes = Db::name('brand')->where($param)->find();

        if ($brandRes) {
            return array_err(1952196, '品牌名称已经被使用,请更换');
        }

        return array_err(0, 'success');
    }

    public function addCateGory($data) {
        $checkFlag = $this->_checkCateGory($data);
        if ($checkFlag['code'] > 0) {
            return $checkFlag;
        }

        $flag = Db::name('category')->insert($data);
        if ($flag === false) {
            return array_err(197315, '添加分类失败');
        }

        return array_err(0, '添加分类数据成功');
    }


    public function editCateGory($data,$cateGoryID) {
        $checkFlag = $this->_checkCateGory($data,$cateGoryID);
        if ($checkFlag['code'] > 0) {
            return $checkFlag;
        }

        $flag = Db::name('category')->where('id',$cateGoryID)->update($data);
        if ($flag === false) {
            return array_err(197315, '修改分类失败');
        }

        return array_err(0, '修改分类数据成功');
    }

    protected function _checkCateGory(&$data,$cateID = ''){
        if(empty($data['cate_name'])){
            return array_err(197319,'分类名称不能为空');
        }
        $mGoods = new Goods();

        if(!empty($data['pid'])){
            $cateRes = $mGoods->getGoodsCateByKV($data['pid']);
            if(empty($cateRes)){
                return array_err(197318, '请设置正确的上级分类');
            }
        }

        $param['cate_name'] = $data['cate_name'];
        $param['pid'] = $data['pid'];
        if($cateID){
            $param['id'] = array('neq', $cateID);
        }

        $cateGoryRes = $mGoods->cateGoryParam($param);

        if ($cateGoryRes) {
            return array_err(197317, '该分类下存在相同名称,请更换');
        }

        if($data['show_cate'] != 0 && $data['show_cate'] != 1){
            return array_err(197316, '是否开启设置错误,请核实');
        }

        return array_err(0,'success');
    }

}