<?php
/**
 * Created by PhpStorm.
 * User: Garfunkel
 * Date: 2018/8/20
 * Time: 14:57
 */

class IndexAction extends BaseAction
{
    public function index(){
        //顶部广告
        $head_adver = D('Adver')->get_adver_by_key('app_index_top',5);
        if(empty($head_adver)){
            $head_adver = D('Adver')->get_adver_by_key('wap_index_top',5);
        }
        if(!empty($head_adver)){
            $banner = array();
            foreach($head_adver as &$head_adver_value){
                //unset($head_adver_value['id'],$head_adver_value['bg_color'],$head_adver_value['cat_id'],$head_adver_value['status'],$head_adver_value['last_time'],$head_adver_value['sub_name']);
                $b_one = array();
                $b_one['id'] = $head_adver_value['id'];
                $b_one['url'] = $head_adver_value['pic'];
                $b_one['zt_url'] = $head_adver_value['url'];
                $b_one['title'] = $head_adver_value['name'];

                $banner[] = $b_one;
            }
            $arr['banner']['status'] = 1;
            $arr['banner']['info'] = $banner;
        }else{
            $arr['banner']['status'] = 0;
            $arr['banner']['info'] = array();
        }

        //获取店铺列表
        $page	=	$_GET['page']?$_GET['page']:0;
        $limit = $this->config['guess_num'];

        $order = 'juli';
        $deliver_type =  'all';

        if($_GET['lat'] != 'null' && $_GET['long'] != 'null'){
            $lat = $_POST['lat'];
            $long = $_POST['long'];
        }

        $cat_id = 0;
        $cat_fid = 0;

        $key = '';
        $where = array('deliver_type' => $deliver_type, 'order' => $order, 'lat' => $lat, 'long' => $long, 'cat_id' => $cat_id, 'cat_fid' => $cat_fid, 'page' => $page);
        $key && $where['key'] = $key;

        $shop_list = D('Merchant_store_shop')->get_list_arrange($where,3,1,$limit,$page);
//
//        foreach ($shop_list as $k => $v) {
//            $product_list = D('Shop_goods')->get_list_by_storeid($v['site_id']);
//            $shop_list[$k]['goods'] = $product_list;
//        }
        $arr['best']['status'] = 1;
        $arr['best']['info'] = $shop_list;

        $this->returnCode(0,'data',$arr);
    }

    public function getStore(){
        $sid = $_POST['sid'];
        $store = $this->loadModel()->get_store_by_id($sid);

        $this->returnCode(0,'info',$store);

    }
    public function loadModel(){
        $this_model = D('Store');

        return $this_model;
    }
    public function getStoreGoods(){
        $sid = $_POST['sid'];

        $t_store = $this->loadModel()->get_store_by_id($sid);
        //店铺状态
        $store['shopstatus'] = $t_store['is_close'] == 0 ? "1" : "0";
        $store['shopname'] = $t_store['site_name'];

        $group = $this->loadModel()->get_goods_group_by_storeId($sid);

        $goods = $this->loadModel()->get_goods_by_storeId($sid);

        $store['group'] = $this->loadModel()-> arrange_group_for_goods($group);
        $store['foods'] = $this->loadModel()-> arrange_goods_for_goods($goods);
        $store['count'] = count($store['foods']);

        $this->returnCode(0,'info',$store);
    }

    public function user_third_Login(){
        $userInfo = array("uid"=>"1","uname"=>"garfunkel","password"=>"123456","login_type"=>"1",
            "outsrc"=>"http://thirdqq.qlogo.cn/qqapp/1106028245/ED09815DE876D237105B7BF6F40DEFCA/100",
            "openid"=>"ED09815DE876D237105B7BF6F40DEFCA"
        );

        $this->returnCode(0,'info',$userInfo);
    }

    public function userLogin(){
        $userName = $_POST['userName'];
        $password = $_POST['password'];

        $userInfo = $this->loadModel()->getUserInfo($userName,$password);

        if($userInfo['msg'] != "")
            $code = 1;
        else
            $code = 0;

        $this->returnCode($code,'info',$userInfo,$userInfo['msg']);
    }

    public function getVerificationCode(){
        $phone = $_POST['phone'];
        $result = $this->loadModel()->sendVerificationCode($phone);

        if ($result['error_code'])
            $code = 1;
        else
            $code = 0;

        $this->returnCode($code,'info',array(),$result['msg']);
    }

    public function userReg(){
        $phone = $_POST['phone'];
        $vcode = $_POST['vcode'];
        $pwd = $_POST['password'];

        $result = $this->loadModel()->reg_phone_pwd_vcode($phone,$vcode,$pwd);

        if ($result['error_code'])
            $code = 1;
        else
            $code = 0;

        $this->returnCode($code,'info',$result,$result['msg']);
    }

    public function getCommentByStore(){
        $sid = $_POST['sid'];
        $type = $_POST['type'];

        $result = $this->loadModel()->get_comment_sid($sid,$type);

        $this->returnCode(0,'',$result,$result['msg']);
    }

    public function addCart(){
        $uid = $_POST['uid'];
        $fid = $_POST['fid'];
        $num = !empty($_POST['num']) ? $_POST['num']:1;

        D('Cart')->add_cart($uid,$fid,$num);

        $this->returnCode(0,'info',array(),'success');
    }

    public function getCart(){
        $uid = $_POST['uid'];

        $result = D('Cart')->get_cart($uid);

        $this->returnCode(0,'',$result,'success');
    }

    public function getUserDefaultAddress(){
        $uid = $_POST['uid'];

        $result = $this->loadModel()->getDefaultAdr($uid);

        var_dump($result);
    }
}