<?php

/*
 * This file is part of the overtrue/wechat.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/**
 * XML.php.
 *
 * @author    overtrue <i@overtrue.me>
 * @copyright 2015 overtrue <i@overtrue.me>
 *
 * @see      https://github.com/overtrue
 * @see      http://overtrue.me
 */

namespace app\common\event;
use think\Db;

class loginEvent extends BaseEvent
{
    public function checkLogin($data) {
        if (empty($data['userName'])) {
            return array_err(9876, '用户名不能为空');
        }

        if (empty($data['password'])) {
            return array_err(9875, '密码不能为空');
        }

        if (isset($data['vertify'])) {
            if (empty($data['vertify'])) {
                return array_err(9874, '验证码不能为空');
            }

            if (!checkVerify($data['vertify'], 'admin_login')) {
                return array_err(9873, '验证码错误');
            }
        }
        $where['user_name'] = $data['userName'];
        $where['password'] = fun_encrypt($data['password']);
        $adminInfo = Db::name('admin')->where($where)->find();

        if(!is_array($adminInfo)){
            return array_err(9872, '用户名或者密码不正确');
        }

        if($adminInfo['status'] == 0){
            return array_err(9871, '该用户已被冻结,请联系管理员');
        }
        $roleInfo = array();
        $roleInfo['role_name'] = empty($adminInfo['parent_id']) ? '创始人':'管理员';

        //将数据等写到session中 并进行验证
        return $this->writeUserInfo($adminInfo);
    }

    /**
     * 将用户数据写入到session
     * @param $adminInfo
     * @return array
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function writeUserInfo($adminInfo){
        $adminInfo['role_info'] = array();
        if($adminInfo['role_id'] > 0) {
            $roleRes = Db::name('auth_role')->where(array('id' => $adminInfo['role_id']))->find();
            if ($roleRes) {
                $roleInfo['role_name'] = $roleRes['name'];
                $roleInfo['cud'] = unserialize($roleRes['cud']);
                $roleInfo['permission'] = unserialize($roleRes['permission']);
            } else {
                return array_err(9871, '不存在该用户角色');
            }
            $adminInfo['role_info'] = $roleInfo;
        }

        //登录后修改数据
        $lastIP = request()->ip();
        $loginCnt = $adminInfo['login_cnt'] + 1;
        $lastLogin = get_time();
        //记录到session
        $editFlag = Db::name('admin')->where(array('admin_id'=>$adminInfo['admin_id']))->update([
            'last_login'  => $lastLogin,
            'last_ip' => $lastIP,
            'login_cnt' => $loginCnt,
        ]);

        if($editFlag === false){
            return array_err(9870, '修改用户登录数据失败');
        }

        $adminInfo['last_ip'] = $lastIP;
        $adminInfo['last_login'] = $lastLogin;
        session('admin_id',$adminInfo['admin_id']);
        session('admin_info',$adminInfo);
        session('admin_login_expire',get_time());//记录登录时间,便于设置有效期

        return array_err(0, 'success');
    }
}