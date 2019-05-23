<?php


namespace app\common\event;

use think\Db;
use app\common\model\Brand;

class BrandEvent extends BaseEvent
{

    public function setState($brandID, $state) {
        if ($state != 0 && $state != 1) {
            return array_err(1951299, '状态设置错误');
        }

        //检查数据是否存在
        $mBrand = new Brand();
        $brandRes = array();
        $flag = $this->checkBrandID($brandID, $brandRes, true);

        if ($flag['code'] > 0) {
            return $flag;
        }

        if ($state == $brandRes[$brandID]['status']) {
            return array_err(1951296, '状态没有发生变化,不需要修改');
        }

        $editFlag = $mBrand->save([
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
    public function checkBrandID($brandID, &$brandRes, $lock = false) {
        $mBrand = new Brand();
        if (empty($brandID)) {
            return array_err(1951298, '品牌标识不能为空');
        }
        $brandRes = $mBrand->getBreadByKV($brandID, $lock);

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
    public function editBrand($brandID, $data) {
        $brandRes = array();
        $flag = $this->checkBrandID($brandID, $brandRes, true);
        if ($flag['code'] > 0) {
            return $flag;
        }

        $flag = $this->checkBrandData($data, $brandID);

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

        $mBrand = new Brand();

        $editFlag = $mBrand->save($data, ['id' => $brandID]);

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
        $flag = $this->checkBrandID($brandID, $brandRes, true);
        if ($flag['code'] > 0) {
            return $flag;
        }

        $mBrand = new Brand();
        $sortRes = array();
        //
        foreach ($data as $k => $v) {
            if ($v == $brandRes[$k]['sort']) {
                continue;
            }
            $sortRes[$k]['id'] = $k;
            $sortRes[$k]['sort'] = $v;
        }

        $editFlag = $mBrand->isUpdate()->saveAll($sortRes);

        if ($editFlag === false) {
            return array_err(1951296, '修改品牌数据失败');
        }

        return array_err(0, '修改品牌数据成功');
    }

    /**
     * 添加品牌数据
     * @param $brandID
     * @param $data
     * @return array
     */
    public function addBrand($data) {
        $flag = $this->checkBrandData($data);

        if ($flag['code'] > 0) {
            return $flag;
        }

        $mBrand = new Brand();

        $editFlag = $mBrand->save($data);

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
    public function delBrand($brandID) {
        $brandRes = array();
        $flag = $this->checkBrandID($brandID, $brandRes, true);

        if ($flag['code'] > 0) {
            return $flag;
        }

        $mBrand = new Brand();

        $delFlag = $mBrand::destroy($brandID);

        if ($delFlag === false) {
            return array_err(1951296, '删除品牌数据失败');
        }

        return array_err(0, '删除品牌数据成功');
    }

    public function checkBrandData(&$data, $brandID = '') {

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

}