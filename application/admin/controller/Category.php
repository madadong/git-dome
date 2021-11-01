<?php


namespace app\admin\controller;


use think\Controller;

/**
 * Class Category
 * @package app\admin\controller
 * 分类模块
 */
class Category extends Controller
{
    private $obj;
    public function _initialize()
    {
        $this->obj = model('Category');
    }

    public function index()
    {
        $parentId = input('get.parent_id', 0, 'intval');
        //情空session
        session('parentIdSon',null);

        if($parentId == 0) {
            $category = $this->obj->getFirstCategory($parentId);
        }
        return $this->fetch('', ['category' => $category]);
    }
    public function indexSon()
    {
        $parentId = input('get.parent_id', 0, 'intval');
        if( session('parentIdSon')){$parentId=session('parentIdSon');}

        //查询子分类
            //进入子类页面后 保存session
            session('parentIdSon',$parentId);
            $category = $this->obj->getFirstCategorySon($parentId);

        return $this->fetch('index', ['category' => $category]);
    }
    public function add()
    {
        $category = $this->obj->getNOrmalFirstCategory();
        return $this->fetch('', ['category' => $category]);
    }

    /*
     *保存
     */
    public function save()
    {

        //判断

        if (!request()->isPost()) {
            $this->error('请求失败');
        }
        //效验

        $data = input('post.');

        $validate = validate('category');
        if (!$validate->scene('add')-> check($data)) {
            $this->error($validate->getError());
        }

        if (!empty($data['id'])) {

            return $this->update($data);
        }

        //把数据提交给model层
        $res = $this->obj->add($data);
        if ($res) {
            $this->success('新增成功');

        } else {
            $this->error('新增失败');
        }

    }

    /**
     * 编辑
     */
    public function edit()
    {
        $id = input('get.id');
        if (intval($id) < 1) {
            $this->error('参数不合法');
        }
        $category = $this->obj->get($id);

        $categorys = $this->obj->getNormalFirstCategory();
        return $this->fetch('', [
            'category' => $category,
            'categorys' => $categorys,
        ]);
    }

    /**
     * 更新
     * @param $data
     */
    public function update($data)
    {
        $res = $this->obj->save($data, ['id' => intval($data['id'])]);
        if ($res) {
            $this->success('更新成功');
        } else {
            $this->error('更新失败');
        }
    }

    /**
     * ajax排序
     * @param $id
     * @param $listorder
     */
    public function listorder($id, $listorder)
    {
//        echo $id;echo "***";
//        echo $listorder;die();
        $res = $this->obj->save(['listorder' => $listorder], ['id' => $id]);
        if ($res) {

           $this->result ($_SERVER['HTTP_REFERER'], 1, 'success');
        } else {
            $this->result($_SERVER['HTTP_REFERER'], 2, '更新失败');
        }
    }

    //修改状态
    public function status()
    {
        //print_r(input());
        $data = input('get.');
        $validate = validate('category');
        if (!$validate->scene('status')->check($data)) {
            $this->error($validate->getError());
        }
        $res = $this->obj->save(['status' => $data['status']], ['id' => $data['id']]);
        if ($res) {
            $this->success('更新成功');
        } else {
            $this->error('更新失败');
        }

    }
}