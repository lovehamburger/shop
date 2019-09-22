<?php

namespace app\common\util;


use app\common\model\Config;

class FilesUtil extends BaseUtil
{

    /**允许上传的文件大小5M
     * @var int
     */
    public $size = 5242880;

    /**
     * 上传是否支持缩略图
     * @var bool
     */
    public $thumb = false;

    /**
     * 上传支持的文件后缀
     * @var string
     */
    public $ext = "gif,png,jpg,jpeg,bmp";

    /**上传的根目录
     * @var string
     */
    public $rootDir = 'uploads';

    /**
     * 保存路径，如goods，实际保存路径为uploads/goods/YYYY/YYYYMMDD/xxx.gif
     * @var string
     */
    public $saveDir = "";

    /**
     * 上传的文件名规则
     * @var string
     */
    public $rule = 'md5';

    /**
     * 默认保存文件名
     * @var null
     */
    public $saveName = null;


    public function __construct() {
        parent::__construct();
    }

    /**
     *图片上传
     * @param string $key
     * @return array
     */
    public function uploads($key = "file") {
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file($key);
        $saveDir = 'public' . '/' . 'uploads' . '/' . $this->saveDir . '/' . date("Y", time());
        // 移动到框架应用根目录/public/uploads/ 目录下
        if ($file) {
            $info = $file->validate(['size' => $this->size, 'ext' => $this->ext])->rule($this->rule)->move($saveDir);
            if ($info) {
                // 成功上传后 获取上传信息
                // 输出 jpg
                $fileInfo = array_err(0, 'success');
                $fileInfo['file']['saveExt'] = $info->getExtension();
                $fileInfo['file']['saveName'] = $info->getSaveName();
                $fileInfo['file']['saveFilename'] = $info->getFilename();
                $fileInfo['file']['saveSize'] = $info->getInfo()['size'];
                $fileInfo['file']['saveFiles'] = '/' . $saveDir . '/' . $info->getSaveName();
                if ($this->thumb) {
                    $image = \think\Image::open('./' . $fileInfo['file']['saveFiles']);
                    $mConfig = new Config();
                    $thumbConfig = [goods_thumb_big, goods_thumb_mid, goods_thumb_sm];
                    $thumbConfigArr = $mConfig->getConfigByEn($thumbConfig);
                     echo'<pre>';
                         print_r(explode('\r',$info->getSaveName()));
                     echo'</pre>';
                    // 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.png
                    foreach ($thumbConfigArr as $k => $v) {
                        $thumbValues = explode(',', $v['value']);
                        $thumbFiles = './' . $saveDir . '/' . $v['values'];

                        $image->thumb($thumbValues[0], $thumbValues[1], \think\Image::THUMB_CENTER)->save($thumbFiles);
                    }
                }
                return $fileInfo;
            } else {
                // 上传失败获取错误信息
                return array_err(9999, $file->getError());
            }
        }
    }
}