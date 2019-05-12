<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
//错误码封装
function array_err($errCode, $errMsg) {
    return array('code' => $errCode, 'msg' => $errMsg);
}

//分页的限制
function dealPage(&$param, $defaultPage = 20) {
    if (empty($param['curr_page'])) {
        $param['curr_page'] = 1;
    }

    if (empty($param['page_count']) || $param['page_count'] < 0 || $param['page_count'] > 100) {
        $param['page_count'] = $defaultPage;
    }
}