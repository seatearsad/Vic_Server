<?php
/*
 * 积分
 *
 * @  Writers    Jaty
 * @  BuildTime  2014/12/29 16:09
 * 
 */

class CardAction extends  BaseAction{
    public function index(){
        if(empty($this->user_session)){
            $this->error_tips(L('_B_MY_LOGINFIRST_'));
        }

        if($_GET['order_id']){
            $this->assign('order_id',$_GET['order_id']);
        }

        $card_list = D('User_card')->getCardListByUid($this->user_session['uid']);
        foreach ($card_list as $k=>$item) {
            $card_list[$k]['expiry'] = transYM($item['expiry']);
        }
        $this->assign('card_list',$card_list);
        $this->display();
    }

    public function edit_card(){
        if($_POST){
            $data['name'] = $_POST['name'];
            $data['card_num'] = $_POST['card_num'];
            $data['expiry'] = transYM($_POST['expiry']);

            //如果 is_default 存在，清空之前的default
            if($_POST['is_default']){
                D('User_card')->clearIsDefaultByUid($this->user_session['uid']);
            }
            $data['is_default'] = $_POST['is_default'] ? $_POST['is_default'] : 0;

            $data['uid'] = $this->user_session['uid'];

            if($_POST['card_id'] && $_POST['card_id'] != ''){
                $data['id'] = $_POST['card_id'];
                D('User_card')->field(true)->where(array('id'=>$data['id']))->save($data);
                $this->success(L('_OPERATION_SUCCESS_'));
            }else{
                $isC = D('User_card')->getCardByUserAndNum($data['uid'],$data['card_num']);
                if($isC){
                    $this->error(L('_CARD_EXIST_'));
                }else{
                    $data['create_time'] = date("Y-m-d H:i:s");
                    D('User_card')->field(true)->add($data);
                    $this->success(L('_OPERATION_SUCCESS_'));
                }
            }
        }else if($_GET['card_id']){
            $card = D('User_card')->field(true)->where(array('id'=>$_GET['card_id']))->find();
            $this->assign('card',$card);
        }
    }

    public function set_default(){
        $id = $_POST['card_id'];
        D('User_card')->clearIsDefaultByUid($this->user_session['uid']);

        D('User_card')->field(true)->where(array('id'=>$id))->save(array('is_default'=>1));

        $this->success(L('_OPERATION_SUCCESS_'));
    }

    public function del_card(){
        if($_POST['card_id']){
            D('User_card')->field(true)->where(array('id'=>$_POST['card_id']))->delete();
            $this->success(L('_OPERATION_SUCCESS_'));
        }
        $this->error(L('_OPERATION_FAIL_'));
    }
}

//class PointAction extends BaseAction {
//    public function index(){
//
//		//导航条
//    	$web_index_slider = D('Slider')->get_slider_by_key('web_slider');
//    	$this->assign('web_index_slider',$web_index_slider);
//
//		//热门搜索词
//    	$search_hot_list = D('Search_hot')->get_list(12);
//    	$this->assign('search_hot_list',$search_hot_list);
//
//		//所有分类 包含2级分类
//		$all_category_list = D('Group_category')->get_category();
//		$this->assign('all_category_list',$all_category_list);
//
//		//余额记录列表
//		$this->assign(D('User_score_list')->get_list($this->now_user['uid']));
//
//		$this->display();
//    }
//
//	public function group_order_view(){
//
//		$now_order = D('Group_order')->get_order_detail_by_id($this->now_user['uid'],$_GET['order_id']);
//		if(empty($now_order)){
//			$this->error('当前订单不存在！');
//		}else{
//			$this->assign('now_order',$now_order);
//		}
//
//		$this->display();
//	}
//
//	public function meal_list()
//	{
//		$status = isset($_GET['status']) ? intval($_GET['status']) : -1;
//		$where = array('uid' => $this->now_user['uid'], 'status' => array('lt', 2));
//		if ($status == 0) {
//			$where['paid'] = 0;
//		} elseif ($status == 1) {
//			$where['status'] = 0;
//		} elseif ($status == 2) {
//			$where['status'] = 1;
//		}
//		$orders = M("Meal_order")->where($where)->order('order_id DESC')->select();
//		$tmp = array();
//		foreach ($orders as $o) {
//			$tmp[] = $o['store_id'];
//		}
//		if ($tmp) {
//			$store_image_class = new store_image();
//			$store = D('Merchant_store')->where(array('store_id' => array('in', $tmp)))->select();
//			$list = array();
//			foreach ($store as $v) {
//				$images = $store_image_class->get_allImage_by_path($v['pic_info']);
//				$v['image'] = $images ? array_shift($images) : array();
//				$list[$v['store_id']] = $v;
//			}
//		}
//
//		foreach ($orders as &$or) {
//			$or['image'] = isset($list[$or['store_id']]['image']) ? $list[$or['store_id']]['image'] : '';
//			$or['s_name'] = isset($list[$or['store_id']]['name']) ? $list[$or['store_id']]['name'] : '';
//			$or['url'] = C('config.site_url').'/meal/'.$or['store_id'].'.html';
//		}
//		$this->assign('order_list', $orders);
//		$this->assign('status', $status);
//
//		$this->display();
//	}
//
//	public function meal_order_view()
//	{
//		$now_order = D('Meal_order')->get_order_by_id($this->now_user['uid'],$_GET['order_id']);
//		$now_order['info'] = unserialize($now_order['info']);
//
//		if ($now_order['pay_type']) {
//			$now_order['pay_type_txt'] = D('Pay')->get_pay_name($now_order['pay_type'], 0);
//		}
//		if ($now_order['meal_pass']) {
//			$now_order['meal_pass_txt'] = preg_replace('#(\d{4})#','$1 ',$now_order['meal_pass']);
//		}
//		if (empty($now_order)) {
//			$this->error('当前订单不存在！');
//		} else {
//			$this->assign('now_order',$now_order);
//		}
//
//		$this->display();
//	}
//
//	public function meal_order_del()
//	{
//		$now_order = D('Meal_order')->get_order_by_id($this->now_user['uid'],$_GET['order_id']);
//		if(empty($now_order)){
//			$this->error('当前订单不存在！');
//		}else{
//			D('Meal_order')->where(array('uid' => $this->now_user['uid'], 'order_id' => $_GET['order_id']))->save(array('status' => 2));
//			$this->success('订单删除成功', U('User/Index/meal_list'));
//		}
//	}
//}