<?php
/**
 * Created by PhpStorm.
 * User: Garfunkel
 * Date: 2018/9/4
 * Time: 16:53
 */
class CouponAction extends BaseAction{
    public function index(){
        if($_GET['coupon_type']=='mer') {
            $coupon_list = D('Card_new_coupon')->get_user_all_coupon_list($this->user_session['uid']);
            $this->assign('cate_platform', D('Card_new_coupon')->cate_platform());
        }else{
            $coupon_list=array();

            $coupon_list = D('System_coupon')->get_user_coupon_list($this->user_session['uid'], $this->user_session['phone']);

            $this->assign('cate_platform', D('System_coupon')->cate_platform());
        }
        $tmp = array();
        foreach ($coupon_list as $key => $v) {
            $v['name'] = lang_substr($v['name'],C('DEFAULT_LANG'));
            $v['des'] = lang_substr($v['des'],C('DEFAULT_LANG'));
            if (!empty($tmp[$v['is_use']][$v['coupon_id']])) {
                $tmp[$v['is_use']][$v['coupon_id']]['get_num']++;
            } else {
                $tmp[$v['is_use']][$v['coupon_id']] = $v;
                $mer = M('Merchant')->where(array('mer_id'=>$v['mer_id']))->find();
                $tmp[$v['is_use']][$v['coupon_id']]['merchant']=$mer['name'];
                $tmp[$v['is_use']][$v['coupon_id']]['get_num'] = 1;
                switch($v['type']){
                    case 'all':
                        $url = $this->config['site_url'].'/wap.php';
                        break;
                    case 'group':
                        $url = $this->config['site_url'].'/wap.php?g=Wap&c=Group&a=index';
                        break;
                    case 'meal':
                        $url = $this->config['site_url'].'/wap.php?g=Wap&c=Meal_list&a=index';
                        break;
                    case 'appoint':
                        $url = $this->config['site_url'].'/wap.php?g=Wap&c=Appoint&a=index';
                        break;
                    case 'shop':
                        $url = $this->config['site_url'].'/wap.php?g=Wap&c=Shop&a=index';
                        break;
                }
                $tmp[$v['is_use']][$v['coupon_id']]['url'] = $url;
            }

        }
        $this->assign('coupon_list', $tmp);

        $this->display();
    }

    public function exchange(){
        $this->display();
    }
    public  function exchangeCode(){
        $code = $_POST['code'];
        $uid = $this->user_session['uid'];

        $coupon = D('System_coupon')->field(true)->where(array('notice'=>$code))->find();
        $cid = $coupon['coupon_id'];

        if($cid){
            $l_id = D('System_coupon_hadpull')->field(true)->where(array('uid'=>$uid,'coupon_id'=>$cid))->find();

            if($l_id == null)
                $result = D('System_coupon')->had_pull($cid,$uid);
            else
                exit(json_encode(array('error_code'=> 1,'msg'=>L('_AL_EXCHANGE_CODE_'))));
        }else{
            exit(json_encode(array('error_code'=> 1,'msg'=>L('_NOT_EXCHANGE_CODE_'))));
        }

        echo json_encode($result);
    }
}