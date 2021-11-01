<?php


namespace app\bis\controller;


use think\Controller;

class Login extends Controller
{
    public function index()
    {
        if (request()->isPost()) {
            $data = input('post.');
            //严格校验 比如validate
           $res = model('BisAccout')->get(['username'=>$data['username']]);

            if(!$res || $res->status !=1){
                $this ->error("改用户不存在，获取用户未被审核通过");
            }
            if($res->password !=md5($data['password'].$res->code)){

                $this ->error("密码错误");

            }
                model('BisAccout') ->updateById(['last_login_time'=>time()],$res->id);
                //保存用户信息 bis 作用域
                session('bisAccount',$res,'bis');
                return $this ->success('登录成功',url('index/index'));
        } else {

            //获取session
            $account = session('bisAccount','','bis');
            if($account && $account->id){
                return $this ->redirect(url('index/index'));
            }
            return $this->fetch();
        }
    }
    public function logout(){
        //清除session
        session(null,'bis');
        return $this->redirect(url('login/index'));
    }
}
