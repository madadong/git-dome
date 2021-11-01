<?php


namespace app\common\model;


use think\Model;

class BisAccout extends BaseModel
{
        public function updateById($data,$id){
            //allowField 过滤data数组中 中非数据表的数据
            return $this ->allowField(true)->save($data,['id'=>$id]);
        }
}