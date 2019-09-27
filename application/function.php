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


function getKey($keyArray, $key, $value){
    $res = array();
    foreach($keyArray as $row){
        if($row[$key] == $value){
            $res = $row;
            break;
        }
    }
    return $res;
}


/**
 * 更新比对
 * @param array $dbArray 数据表数组
 * @param array $updatetArray 待更新数组
 * @param string $key 主键
 * @param array $compareCols 需比对的字段
 * @return mixed 比对数组
 */
function updateCompare($dbArray, $updatetArray, $key, $compareCols){
    //返回定义
    $updateArr = array();
    $insertArr = array();
    $deleteArr = array();

    //正向遍历
    foreach ($dbArray as $dbrow){
        //获取数组
        $tempArr = getKey($updatetArray, $key, $dbrow[$key]);

        //删除操作
        if(empty($tempArr)){
            $deleteArr['key'] = $key;
            $deleteArr[] = $dbrow[$key];
        }else{
            //比较键值
            foreach ($compareCols as $colName){
                //空值不比较
                if(empty($dbrow[$colName]) && empty($tempArr[$colName])){
                    continue;
                }
                if($dbrow[$colName] != $tempArr[$colName]){
                    $updateArr['key'] = $key;
                    $updateArr[$dbrow[$key]][$colName] = $tempArr[$colName];

                }
            }
        }
    }

    //反向遍历
    foreach ($updatetArray as $uprow){
        //获取数组
        $tempArr = getKey($dbArray, $key, $uprow[$key]);
        //新增操作
        if(empty($tempArr)){
            $insertArr[] = $uprow;
        }
    }

    $resArray = array();
    if(!empty($insertArr)){
        $resArray['A'] = $insertArr;
    }

    if(!empty($updateArr)){
        $resArray['M'] = $updateArr;
    }

    if(!empty($deleteArr)){
        $resArray['D'] = $deleteArr;
    }

    return $resArray;
}

/**
 * @return false|string
 */
function _NOW_TIME(){
    return date("Y-m-d H:i:s", time());
}