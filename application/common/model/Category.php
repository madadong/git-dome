<?php


namespace app\common\model;


use think\Model;

class Category extends Model
{
    protected $autoWriteTimestamp = true;

    public function add($data)
    {
        $data['status'] = 1;
        //$data['create_time'] = time();
        return $this->save($data);
    }

    /**
     * 获取分类信息 用于添加分类时候
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getNormalFirstCategory($parentId=0)
    {
        $data = [
            'status' => 1,
            'parent_id' => $parentId,

        ];
        $order = [
            'id' => 'desc',
        ];
        return $this->where($data)->order($order)->select();
    }

    /**
     * 查询分类的详细信息
     * @param int $parentId 父id
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getFirstCategory($parentId=0)
    {

        $data = [
            'status' => ['neq', -1],
            'parent_id' => $parentId,

        ];
        $order = [
            'listorder' => 'desc',
            'id' => 'desc',

        ];
        //paginate 分页
        $result = $this->where($data)->order($order)->paginate(2);
        //echo $this->getLastSql();
        return $result;
    }
    //子分页处理
    public function getFirstCategorySon($parentId)
    {


        $data = [
            'status' => ['neq', -1],
            'parent_id' => $parentId,

        ];
        $order = [
            'listorder' => 'desc',
            'id' => 'desc',

        ];
        //paginate 分页
        $result = $this->where($data)->order($order)->paginate(5);
        //echo $this->getLastSql();
        return $result;
    }
}