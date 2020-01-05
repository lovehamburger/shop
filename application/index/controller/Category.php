<?php

namespace app\index\controller;
use app\common\model\Category as mCategory;

class Category extends Base
{
    public function index() {
        return $this->fetch();
    }

    public function getCategotyParentTree() {
        $cateID = input('cat_id');
        $mCategory = new mCategory();
        $cateBrand = array_err(0,'success');
        $cateBrand['cat_id'] = $cateID;
        $cateBrand['data'] = $mCategory->getCategotyByID($cateID);
        return $cateBrand;
    }
}
