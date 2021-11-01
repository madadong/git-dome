<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function status($status)
{
    if ($status == 1) {
        $str = "<span class='label label-success redius'>正常</span>";
    } elseif ($status == 0) {
        $str = "<span class='label label-danger redius'>待审</span>";
    }elseif ($status == 2) {
        $str = "<span class='label label-danger redius'>不通过</span>";
    } else if($status == -1){
        $str = "<span class='label label-danger redius'>删除</span>";
    }
    return $str;

}

/**
 * @param $url
 * @param int $type 0get 1post
 * @param $data
 */
function doCutl($url,$type=0,$data=[]){
   $ch =curl_init();//初始化
    //设置选项
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_HEADER,0);
    if($type ==1){
        curl_setopt($ch,CURLOPT_POST,1);

    }
    //执行并获取内容
    $output = curl_exec($ch);
    //释放curl句柄
    curl_close($ch);

    return $output ;
}

/**
 * 自定义一个发送方法
 * @param $status
 * @param $message
 * @param array $data
 * @return \think\response\Json
 */
function show($status, $message, $data = array())
{
    $result = array(
        'status' => $status,
        'message' => $message,
        'data' => $data
    );
    return json($result);
}
function bisRegister($status){
     if($status ==1){
         $str = "入驻成功";
     }elseif($status == 0){
         $str = "待审核";
     }elseif ($status ==2){
         $str = "不符合，重新提交";
     }else{
         $str ="被删除";
     }
     return $str;
}

/**
 * @param $obj
 * @return string
 * 分页的封装
 */
function pagination($obj){
        if(!$obj){
            return'';
        }
        return' <div class="cl pd-5 bg-1 bk-gray mt-20 tp5-o2o">'.$obj->render().'</div>';
}

function getSeCityName($path) {
    if(empty($path)) {
        return '';
}
    if(preg_match('/,/', $path)) {
        $cityPath = explode(',', $path);
        $cityId = $cityPath[1];
    }else {
        $cityId = $path;
    }

    $city = model('City')->get($cityId);
    return $city->name;
}