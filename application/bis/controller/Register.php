<?php


namespace app\bis\controller;


use think\Controller;

class Register extends Controller
{
    private $obj;

    public function _initialize()
    {
        $this->obj = model('city');
    }

    public function index()
    {
        //获取一级城市的数据
        //$citys =db('city') ->where('status =1 and parent_id =0')->order('id = desc')->select();
        $citys = $this->obj->getNormalCityByParentId();
        //获取一级分类
        $category = model('category')->getNormalFirstCategory();

        return $this->fetch('', [
            'citys' => $citys,
            'category' => $category,
        ]);
    }

    //获取二级城市
    public function getCityByParentId()
    {
        $id = input('post.id');
        if (!$id) {
            $this->error('id不合法');
        }
        $citys = $this->obj->getNormalCityByParentId($id);
        if (!$citys) {
            $this->result('', 0, 'error');
        }
        $this->result($citys, 1, 'success');
    }

    /**
     * 获取二级分类
     */
    public function getCategoryByParentId()
    {
        $id = input('post.id');
        if (!$id) {
            $this->error('id不合法');
        }
        $catgory = model('category')->getNormalFirstCategory($id);

        if (!$catgory) {
            $this->result('', 0, 'error');
        }
        $this->result($catgory, 1, 'success');
    }

    public function add()
    {
        if (!request()->isPost()) {
            $this->error('请求错误');

        }
        //获取表单的值
        $data = input('post.');


        //检验数据
        $vaildate = validate('Bis');
        if (!$vaildate->scene('add')->check($data)) {
            // $this->error($vaildate->getError());
        }

        //获取金纬度
        $lnglat = \Map::getLngLat($data['address']);
        if (empty($lnglat) || $lnglat['status'] != 0 || $lnglat['result']['precise'] != 1) {
            $this->error('无法获取数据,或者地址填写不正确');
        }
        //判断提交的用户是否存在
       $accountResult = model('BisAccout')->get(['username'=>$data['username']]);
        if ($accountResult){
            $this ->error('该用户存在，请重新申请');
        }
        //商户基本信息入库
        $bisData = [
            'name' => $data['name'],
            'city_id' => $data['city_id'],
            'city_path' => empty($data['se_city_id']) ? $data['city_id'] : $data['city_id'] . ',' . $data['se_city_id'],
            'logo' => $data['logo'],
            'licence_logo' => $data['licence_logo'],
            'description' => empty($data['description']) ? '' : $data['description'],
            'bank_info' => $data['bank_info'],
            'bank_user' => $data['bank_user'],
            'bank_name' => $data['bank_name'],
            'faren' => $data['faren'],
            'faren_tel' => $data['faren_tel'],
            'email' => $data['email'],
        ];
        // db('bis')->save()
        $bisId = model('Bis')->add($bisData);

        //总店的相关信息检验
        $data['cat']='';
        if (!empty($data['se_category_id'])) {
            $data['cat'] = implode('|', $data['se_category_id']);
        }

        $locationData = [
            'bis_id' => $bisId,
            'name' => $data['name'],
            'tel' => $data['tel'],
            'contact' => $data['contact'],
            'category_id' => $data['category_id'],
            'category_path' => $data['category_id'] . ',' . $data['cat'],
            'city_id' => $data['city_id'],
            'city_path' => empty($data['se_city_id']) ? $data['city_id'] : $data['city_id'] . ',' . $data['se_city_id'],
            'api_address' => $data['address'],
            'open_time' => $data['open_time'],
            'content' => empty($data['content']) ? '' : $data['content'],
            'is_main' => 1,//代表的是总店信息
            'xpoint' => empty($lnglat['result']['location']['lng']) ? '' : $lnglat['result']['location']['lng'],
            'ypoint' => empty($lnglat['result']['location']['lat']) ? '' : $lnglat['result']['location']['lat'],
        ];
        $locationId = model('BisLocation')->add($locationData);

        //账户的相关信息检验
        //自动生成 密码的加盐字符串
        $data['code'] = mt_rand(100, 10000);
        $accounData = [
            'bis_id' => $bisId,
            'username' => $data['username'],
            'password' => md5($data['password'] . $data['code']),
            'code' => $data['code'],
            'is_main' => 1,//代表是总管理员
        ];
        $accountId = model('BisAccout')->add($accounData);
        if (!$accountId) {
            $this->error('申请失败');
        }
        //发送邮件通知申请成功
        $url = request()->domain() . url('bis/register/waiting', ['id' => $bisId]);
        $title = 'aying——o2o申请通知';
        $content = "您提交的入驻申请需要等待平台方审核，您可以通过点击链接<a href='" . $url . "' target='_blank'>查看链接</a> 审核状态";
        \phpmailer\Email::send($data['email'], $title, $content);
        $this->success('申请成功',url('register/waiting',['id'=>$bisId]));
    }

    public function waiting($id)
    {
            if(empty($id)){
                $this->error('error');

            }
            $datail =  model('Bis')->get($id);

            return $this ->fetch('',['datail'=>$datail]);

    }

}