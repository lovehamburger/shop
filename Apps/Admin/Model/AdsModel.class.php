<?php
namespace Admin\Model;
use Admin\Model\BaseModel;
class AdsModel extends BaseModel{
	
	public function getPos($param){
		return M('adpos')->where()->page($param['curr_page'],$param['page_count'])->select();
	}

	public function getPosCount($param){
		return M('adpos')->where($param)->count();
	}
	/**
	 * 添加广告位
	 * @param [type] $data [description]
	 */
	public function setPos($data){
		return M('adpos')->add($data);
	}

	/**
	 * 修改广告位
	 * @param [type] $data [description]
	 */
	public function editPos($data){
		return M('adpos')->save($data);
	}

	/**
	 *获取指定广告位
	 */
	public function getPosById($posId,$lock){
		$where['id'] = $posIdL;
		if($lock){
			M('adpos')->lock(true)->where($where)->find();
		}else{
			M('adpos')->where($where)->find();
		}
	}
}