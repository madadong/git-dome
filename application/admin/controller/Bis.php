<?php


namespace app\admin\controller;

use think\Controller;

class Bis extends Controller
{
    private $obj;

    public function _initialize()
    {//initialize()方法是在任何方法执行之前，都要执行的，当然也包括 __construct构造函数。 也就是说如果存在_initialize()函数，调用对象的任何方法都会导致_initialize()函数的自动调用,而__construct()构造函数仅仅在创建对象的时候调用一次，跟其它方法调用没有关系。
        $this->obj =                                               ("Bis");
    }

    /**
 * 商户列表
 * @return mixed
 */
    public function index()
    {
        $bis = $this->obj->getBisByStatus(1);
        return $this->fetch('', ['bis' => $bis]);
    }
    /**
     * 入驻申请列表
     * @return mixed
     */
    public function apply()
    {
        $bis = $this->obj->getBisByStatus();
        return $this->fetch('', ['bis' => $bis]);
    }
    /**
     * 商户删除和不通过
     * @return mixed
     */
    public function dellist()
    {
        //$bis = $this->obj->getBisByStatus(' -1 or 2 ');
        $bis = model('bis')->where('status = -1 or status = 2 ')->paginate();
        echo model('bis')->getLastSql();
        return $this->fetch('', ['bis' => $bis]);
    }
    public function datail($id)
    {

        if (empty($id)) {
            return $this->error('error');
        }
        //获取一级城市的数据
        //$citys =db('city') ->where('status =1 and parent_id =0')->order('id = desc')->select();
        $citys = model('city')->getNormalCityByParentId();
        //获取一级分类
        $category = model('category')->getNormalFirstCategory();
        //获取商户信息
        $bisData = model('bis')->get($id);
        //总店信息
        $locatinoData = model('BisLocation')->get(['bis_id' => $id, 'is_main' => 1]);
        //账号信息
        $accountData = model('BisAccout')->get(['bis_id' => $id, 'is_main' => 1]);


        return $this->fetch('', [
            'citys' => $citys,
            'category' => $category,
            'bis' => $bisData,
            'BisLocation' => $locatinoData,
            'bisaccount' => $accountData,
        ]);
    }

    public function status()
    {
        $data = input('get.');
        //检验 小伙伴自行完成
//      $validate = validate('category');
//      if (!$validate->scene('status')->check($data)) {
//          $this->error($validate->getError());
//      }
        $res = $this->obj->save(['status' => $data['status']], ['id' => $data['id']]);
        $bisl = model('BisLocation')->save(['status' => $data['status']], ['bis_id' => $data['id']],['is_main'=>1]);
        $bisa = model('BisAccout')->save(['status' => $data['status']], ['bis_id' => $data['id']],['is_main'=>1]);
        if ($res && $bisl && $bisa) {
            //发送邮件
            //status 1
//            $url = request()->domain() . url('bis/register/waiting', ['id' => $bisId]);
//            $title = 'aying——o2o申请通知';
//            $content = "您提交的入驻申请需要等待平台方审核，您可以通过点击链接<a href='" . $url . "' target='_blank'>查看链接</a> 审核状态";
//            \phpmailer\Email::send($data['email'], $title, $content);
            $this->success('更新成功');
        } else {
            $this->error('更新失败');
        }
    }
}