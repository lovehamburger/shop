<?php
namespace app\index\controller;

use app\common\model\Category;

class Index extends Base
{
    public function index() {
        $mCategory = new Category();
        echo "999";
        $this->recommendCat = $mCategory->getCommendPosGoods(2,5);
        return $this->fetch();
    }
}
