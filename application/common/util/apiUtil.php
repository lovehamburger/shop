<?php

namespace app\common\util;


use app\common\model\Config;
use think\Db;

class apiUtil extends BaseUtil
{
    private $mSeqName = "";
    private $mbTrans = true;
    private $mSeqTable = "sequence";

    /**
     * Sequence constructor 构造函数
     * @param $seqName string 序列名称
     * @param bool $trans 是否需要事务，默认需要
     */
    function __construct($seqName, $trans = true){
        $this->mSeqName = $seqName;
        $this->mbTrans = $trans;
    }

    /**
     * 获取当前值
     */
    public function current_val(){
        $where = "seq_name = '$this->mSeqName'";
        $seq = Db::name($this->mSeqTable)->where($where)->find();
        return empty($seq) ? -1 : $seq["current_val"];
    }

    /**
     * 获取下个值
     * @param $format bool 是否0补齐
     * @param $nums int 每次获取的序列个数，默认为1
     */
    public function next_val($format = true, $nums = 1){
        $where = "seq_name = '$this->mSeqName'";
        if($this->mbTrans) Db::startTrans();
        $seq = Db::name($this->mSeqTable)->lock(true)->where($where)->find();
        if(empty($seq)){
            if($this->mbTrans) Db::rollback();
            return -1;
        }

        //更新SEQ
        if($seq["current_val"] + $seq["increment"] < $seq["min_val"] or $seq["current_val"] + $seq["increment"] > $seq["max_val"]){
            $nextVal = $seq["min_val"];
        }else{
            $nextVal = $seq["current_val"] + $seq["increment"];
        }

        $data = array(
            "current_val" => $nextVal,
            "update_date" => _NOW_TIME()
        );

        if(false === Db::name($this->mSeqTable)->where($where)->update($data)){
            if($this->mbTrans) Db::rollback();
            return -1;
        }else{
            if($this->mbTrans) Db::commit();

            //0补齐
            if($format){
                $nextVal = $seq["prefix"].date($seq["time_format"], time()).str_pad($nextVal, strlen($seq["max_val"]), "0", STR_PAD_LEFT);
            }

            return $nextVal;
        }

    }
}