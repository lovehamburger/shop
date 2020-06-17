<?php
namespace app\index\controller;

use app\common\model\Category;

class Index extends Base
{
    public function index() {
        echo "44";
        $mCategory = new Category();
        $this->recommendCat = $mCategory->getCommendPosGoods(2,5);
        return $this->fetch();
    }
}
