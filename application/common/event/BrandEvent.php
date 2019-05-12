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

        if (empty($brandID)) {
            return array_err(1951298, '品牌标识不能为空');
        }

        //检查数据是否存在
        $mBrand = new Brand();

        $brandRes = $mBrand->getBreadByKV($brandID, true);

        if (empty($brandRes)) {
            return array_err(1951297, '没有查找到您要的标识');
        }

        if ($state == $brandRes[$brandID]['status']) {
            return array_err(1951296, '状态没有发生变化,不需要修改');
        }

        $editFlag = $mBrand->save([
            'status' => $state,
        ], ['id' => $brandID]);

        if($editFlag === false){
            return array_err(1951296, '修改状态失败');
        }

        return array_err(11, '修改状态成功');
    }

}