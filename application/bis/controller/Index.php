<?php


namespace app\bis\controller;


use app\bis\controller\Base;

class Index extends Base
{
    public function index()
    {
        return $this->fetch();
    }
}