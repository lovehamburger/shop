<?php
namespace Admin\Controller;
use Admin\Controller\BaseController;
class AdsController extends BaseController {

	public function index(){
		$this->display('Ads/posIndex');
	}

	public function ajaxPosList(){
		//$this->_inputAjax();
		$param = I('post.param');
		$res = array_err(0,'success');
		$res['count'] = D('Ads')->getPosCount($param);
		$res['data'] = array();
		if($res['count'] > 0){
			$res['data'] = D('Ads')->getPos($param);
		}
		$this->ajaxReturn($res);
	}
	/**
	 * 添加广告位
	 */
	public function setPos(){
		$this->_inputAjax();
		$data = [];
		$checkInfo = $this->_checkPos($data);
		if($checkInfo['err_code'] > 0 ) return  $this->ajaxReturn($checkInfo);
		$posId= D('Ads')->setPos($data);
		if($posId > 0){
			$this->ajaxReturn(array_err(0,'添加广告位成功'));
		}
		$this->ajaxReturn(array_err(778,'添加广告位失败'));
	}

	/**
	 * 修改广告位
	 */
	public function editPos(){
		$this->_inputAjax();
		$data = [];
		$posRes = [];
		$posId = I('post.posId');
		$checkPosId = $this->_checkPosId($postId,true,$posRes);
		if($checkPosId['err_code'] > 0 ) return  $this->ajaxReturn($checkPosId);
		$checkInfo = $this->_checkPos($data);
		if($checkInfo['err_code'] > 0 ) return  $this->ajaxReturn($checkInfo);
		M()->startStrans();
		$flag = D('Ads')->editPos($data);
		if($flag !== false){
			M()->commit();
			$this->ajaxReturn(array_err(0,'修改广告位成功'));
		}
		M()->rollback();
		$this->ajaxReturn(array_err(778,'修改广告位失败'));
	}

	protected function _checkPosId($postId,$lock = false,&$posRes){
		if(emoty($postId)) return array_err(99,'广告位标识不能为空');
		$res = D('Ads')->getPosById($posId,$lock);
		if(empty($res)) return array_err(98,'不存在你要的广告位数据，请核实');
		return array_err(0,'success');
	}

	protected function _checkPos(&$data,$postId = ''){
		$pname = I('post.pname');
		if(empty($pname)) return array_err(888,'广告位置名称不能为空');
		$width = I('post.width');
		if(empty($width)) return array_err(887,'广告位置宽度不能为空');
		if(!is_numeric($width) || $height <= 0) return array_err(887,'广告位置宽度必须是正整数');
		$height = I('post.height');
		if(empty($height)) return array_err(886,'广告位置高度不能为空');
		if(!is_numeric($height) || $height <= 0) return array_err(886,'广告位置高度必须是正整数');
		$param['pname'] = $pname;
		if($postId){
			$param['id'] = array('NEQ',$posId);
			$data['id'] = $posId;
		}
		$count = D('Ads')->getPosCount($param);
		if($count > 0) return array_err(554,'广告位置名称已经存在,请更换哦');
		$data['pname'] = $pname;
		$data['width'] = $width;
		$data['height'] = $height;
		return  array_err(0,'success');
	}
}