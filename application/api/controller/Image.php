<?php


namespace app\api\controller;


use think\Controller;

class Image extends Controller
{
 public  function upload(){

     $file = request()->file('file');
     $info =$file->move('upload');

     if($info && $info->getPathname()){
         return show(1, '图片上传成功', '/'.$info->getPathname());


     }

     return show(0,'图片上传失败');
 }
}