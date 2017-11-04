<?php
namespace Admin\Controller;
use Admin\Controller\BaseController;
class ArticleController extends BaseController {

	public function index(){
		$this->display('Article/index');
	}

	public function list(){
		//权限和ajax验证
		$param['page'] = I('post.currPage',1);
		$param['limit'] = I('post.pageCount',10);
		$count = D('Article')->getArticleCount($param);
		if($count > 0){
			$res = array_err(0,'success');
			$res['data'] = D('Article')->getArticles($param);
			$res['count'] = $count;
			$this->ajaxReturn($res);
		}else{
			$this->ajaxReturn(array_err(0,'不存在文章列表'));
		}
	}

	public function publish(){
		$articleId = I('get.ArticleId');
		$articleCate = D('Article')->getCate();   
		if(!empty($articleId)){
			//检查
			$checkRes = A('Article','Event')->_checkArticleId($articleId);
			if($checkRes['err_code'] > 0) $this->error($checkRes['err_msg']);
			$this->assign('articleRes',$checkRes);
		}
		$this->assign('articleCate',$articleCate);
		$this->display('Article/add');
	}
	/**
	 * 文章添加
	 */
	public function add(){
		$this->_inputAjax();
		$data = [];
		$checkInfo = $this->_checkDate($data);
		if($checkInfo['err_code'] > 0){
			$this->ajaxReturn($checkInfo);
		}
		//添加
		$articleId = D('Article')->setArticle($data);
		if($articleId > 0){
			$this->ajaxReturn(array_err(0,'添加文章成功'));
		}
		$this->ajaxReturn(array_err(991,'添加文章失败'));
	}
	/**
	 * 文章修改
	 * @return [type] [description]
	 */
	public function edit(){
		$this->_inputAjax();
		$articleId = I('post.articleId');
		$checkRes = A('Article','Event')->_checkArticleId($articleId,true);
		if($checkRes['err_code'] > 0) $this->ajaxReturn($checkRes);
		$data = [];
		$checkInfo = $this->_checkDate($data,$articleId);
		if($checkInfo['err_code'] > 0){
			$this->ajaxReturn($checkInfo);
		}
		//修改
		$mArticle = D('Article');
		$mArticle->startTrans();
		$data['id'] = $articleId;
		$flag = $mArticle->editArticle($data);
		if($flag !== false){
			$mArticle->commit();
			if($checkRes['brand_logo']){
				if(file_exists($checkRes['brand_logo'])){
					unlink($checkRes['brand_logo']);
				}
			}
			$this->ajaxReturn(array_err(0,'修改文章成功'));
		}
		$mArticle->rollback();
		echo D()->getLastSql();
		$this->ajaxReturn(array_err(991,'修改文章失败'));
	}

	public function del(){
		$this->_inputAjax();
		$articleIdRes = json_decode(htmlspecialchars_decode(I('post.articleId')), true);
		$param['articleId'] = $articleIdRes;
		$mArticle = D('Article');
		$mArticle->startTrans();
		$res = $mArticle->getArticlesLock($param,true);
		if(count($res) != count($articleIdRes)) $this->ajaxReturn(array_err(776,'存在非法标识,请核实'));
		$flag = $mArticle->delArticle($param);

		if($flag === false){
			$mArticle->rollback();
			$this->ajaxReturn(array_err(555,'删除文章失败哦'));
		}
		foreach ($res as $key => $value) {
			if($value['brand_logo']){
				if(file_exists($value['brand_logo'])){
					unlink($value['brand_logo']);
				}
			}
		}
		$mArticle->commit();
		$this->ajaxReturn(array_err(0,'删除文章成功'));

	}



	public function _checkDate(&$data,$articleId){
		$cate_id = I('post.cate_id');
		$title = I('post.title');
		$article_desc = I('post.article_desc');
		if(empty($article_desc)) return array_err(999,'文章内容不能为空');
		if(empty($title)) return array_err(998,'文章标题不能为空');
		if(empty($cate_id)) return array_err(997,'文章栏目不能为空');
		#@todo验证栏目是否存在
		$data['cateid'] = $cate_id;
		$data['title'] = $title;
		$data['content'] = $article_desc;
		return array_err(0,'success');
	}
	/**
	 * 上传图片
	 * @return [type] [description]
	 */
	public function upload(){
		$info = upload(C('ADMIN_UPLOAD_BRAND'));
		$up_img=$info['brand_logo']['savepath'].$info['brand_logo']['savename'];
		//设置小图片
		$width = C('GOOD_BREAD.WIDTH');
		$height = C('GOOD_BREAD.HEIGHT');
		$open = C('ADMIN_UPLOAD_BRAND.rootPath').$up_img;
		$fileName = pathinfo($up_img ,PATHINFO_FILENAME);
		$saveDir = C('ADMIN_UPLOAD_BRAND.rootPath').$info['brand_logo']['savepath'];
		if(!is_dir($saveDir)) mkdir($saveDir);
		$save = $saveDir.$fileName.'.'.pathinfo($up_img ,PATHINFO_EXTENSION);
		setThumb($width,$height,$open,$save);
		echo"<script>imgid=parent.document.getElementById('imgid');imgid.src='".C('ADMIN_UPLOAD_BRAND.rootPath')."{$up_img}'</script>";//将图片显示到页面
		echo"<script>imgurl=parent.document.getElementById('imgurl');imgurl.value='{$save}'</script>"; 
	}
}