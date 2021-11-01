<?php


namespace app\common\model;


use think\Model;

class Bis extends BaseModel
{
    /**
     * @param int $status 通过状态获取商家数据
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
        public function  getBisByStatus($status =0){
            $data = [
                'status' => $status,

            ];
            $order = [
                'id' => 'desc',
            ];
            return $this->where($data)->order($order)->paginate(15);
           // return $this->order($order)->paginate(1);
        }
}