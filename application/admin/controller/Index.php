<?php


namespace app\admin\controller;


use think\Controller;

class Index extends Controller
{
    public function test(){
        \Map::getLngLat('广东广州棠东毓桂南街3巷');
    }
    public function map(){

       //return \Map::staticimage('广东广州棠东毓桂南街3巷');
       return \Map::staticimage('114.42,23.12');
    }
    public function index()
    {
        return $this->fetch();
    }

    public function welcome()
    {
        //\phpmailer\Email::send('751731202@qq.com','ayingssssss','dfdfadsafdfadsaf sucess测dfadsaf sucess测fdsafdas 对fdsafdas 对 sucess测fdsafdas 对adsaf sucess测fdsafdas 对对对国防生的国dasf 防生的分公司 试');
        return '欢迎来到aying后台';

        //return $this ->fetch();
    }

}