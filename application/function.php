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

/**
 * html的json解码
 * @param string $value
 * @return array
 */
function json_decode_html($value) {
    $param = json_decode(htmlspecialchars_decode($value), true);
    return is_array($param) ? $param : array();
}

/**
 * 对链接补全
 * @param $url
 * @return string
 */
function right_url($url) {
    $preg = "/^http(s)?:\\/\\/.+/";
    if (preg_match($preg, $url)) {
        return $url;
    } else {
        return "http://" . $url;
    }
}

function count_words($str, $max = 10, $type = 'lt', $min = 1) {
    $len = mb_strlen($str, 'UTF-8');

    switch ($type) {
        case 'gt':
            return $len > $max ? 1 : 0;
        case 'egt':
            return $len >= $max ? 1 : 0;
        case 'eq':
            return $len == $max ? 1 : 0;
        case 'lt':
            return $len < $max ? 1 : 0;
        case 'elt':
            return $len <= $max ? 1 : 0;
        case 'between':
            return ($len > $min && $len < $max) ? 1 : 0;
    }
}