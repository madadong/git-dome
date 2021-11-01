<?php


namespace app\common\model;


use think\Model;

class City extends Model
{
    /**
     * @param int $parent_id 根据父类id查询 默认为0
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getNormalCityByParentId($parent_id =0)
    {
        $data = [
            'status' => 1,
            'parent_id' => $parent_id,

        ];
        $order = [
            'id' => 'desc',
        ];
        return $this->where($data)->order($order)->select();
    }
}