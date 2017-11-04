<?php
namespace Admin\Model;
use Admin\Model\BaseModel;
class ArticleModel extends BaseModel{
	/**
	 * 查找品牌单条数据
	 * @param [type] $data [description]
	 */
	public function getArticle($param,$lock = false,$field = '*'){
		if($lock){
			return $this->where($param)->lock(true)->field($field)->find();
		}else{
			return $this->where($param)->field($field)->find();
		}
	}

	/**
	 * 获取所有的品牌列表
	 * @return [type] [description]
	 */
	public function getArticles($param){
		$join = 'INNER JOIN sp_category  ON sp_category.id=sp_article.cateid';
		return $this->where($this->_makeParam($param))->field('sp_article.*,sp_category.catename')
		->join($join)->page($param['page'],$param['limit'])->select();
	}

	/**
	 * 获取所有的品牌列表
	 * @return [type] [description]
	 */
	public function getBrandsLock($param = '',$lock = false){
		if($lock){
			return $this->lock(true)->where($this->_makeParam($param))->select();
		}else{
			return $this->where($this->_makeParam($param))->select();
		}
		
	}

	/**
	 * 删除品牌
	 */
	public function delBrand($param){
		return $this->where($this->_makeParam($param))->delete();
	}

	/**
	 * 获取所有的品牌列表
	 * @return [type] [description]
	 */
	public function getArticleCount($param){
		return $this->where($this->_makeParam($param))
		->count();
	}

	/**
	 * 添加品牌数据
	 * @param [type] $data [description]
	 */
	public function setArticle($data){
		return $this->add($data);
	}

	/**
	 * 修改品牌
	 */
	public function editArticle($data){
		return $this->save($data);
	}

	/**
	 * 获取所有的文章栏目
	 */
	public function getCate(){
		return M('category')->select();
	}




	public function _makeParam($param,$prefix = ''){
		$code = array();
		if(!empty($param['title'])){
			$code[$prefix.'title'] = $param['title'];
		}
		if(!empty($param['brandId'])){
			$code[$prefix.'id'] = $this->getIDParamExt($param['brandId']);
		}
		return $code;
	}
}