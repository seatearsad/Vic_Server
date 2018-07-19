<?php 
/*
 *2016年2月24日09:04:46
 *平台优惠券功能
 */
class CouponAction extends BaseAction {
		
		public function index(){
			if (!empty($_GET['keyword'])) {
				if ($_GET['searchtype'] == 'id') {
					$condition_coupon['id'] = $_GET['keyword'];
				} else if ($_GET['searchtype'] == 'name') {
					$condition_coupon['name'] = array('like', '%' . $_GET['keyword'] . '%');
				}
			}
			$condition_coupon['delete'] = 0;
			//排序 /*/
			$order_string = '`coupon_id` DESC';
			if($_GET['sort']){
				switch($_GET['sort']){
					case 'uid':
						$order_string = '`uid` DESC';
						break;
					case 'lastTime':
						$order_string = '`last_time` DESC';
						break;
					case 'money':
						$order_string = '`now_money` DESC';
						break;
					case 'score':
						$order_string = '`score_count` DESC';
						break;
				}
			}
			$coupon = M('System_coupon');
			$count_count = $coupon->where($condition_coupon)->count();
			import('@.ORG.system_page');
			$p = new Page($count_count, 15);
			$coupon_list = $coupon->field(true)->where($condition_coupon)->order($order_string)->limit($p->firstRow . ',' . $p->listRows)->select();

			foreach($coupon_list as $key=>&$v){
				$v['platform']=unserialize($v['platform']);
				if($v['cate_name']!='all'&&!empty($v['cate_id'])){
					$tmp = unserialize($v['cate_id']);
					$v['cate_id'] = $tmp['cat_name'];
				}
				if($v['end_time']<$_SERVER['REQUEST_TIME']){
					D('System_coupon')->where(array('coupon_id'=>$v['coupon_id']))->setField('status',2);
					$v['status']=2;
				}

			}
			$return =  D('System_coupon')->cate_platform();
			$this->assign("category",$return['category']);
			$this->assign("platform",$return['platform']);
			$this->assign('coupon_list',$coupon_list);
			$pagebar = $p->show();
			$this->assign('pagebar', $pagebar);
			$this->display();
		}

		public function add(){
			if(IS_POST){
				if(strtotime($_POST['end_time'])<strtotime($_POST['start_time'])||strtotime($_POST['end_time'])<time()||strtotime($_POST['start_time'])<strtotime(date('Y-m-d'))){
					$this->error('起始时间设置有误！');
				}
//				if($_POST['discount']>$_POST['order_money']){
//					$this->error('优惠金额不能大于最小订单金额！');
//				}
				if($_POST['limit']>$_POST['num']){
					$this->error('领取限制不能大于数量！');
				}
				if($_POST['use_limit']>$_POST['limit']||$_POST['use_limit']>$_POST['num']){
					$this->error('使用限制设置错误，不能大于领取限制和数量！');
				}
				if($_POST['cate_name']!='all'){
					if($_POST['cate_id']!=0){
						if($_POST['cate_name']=='meal'){
							$cate_id = D(ucfirst($_POST['cate_name']) . '_store_category')->field('cat_id,cat_name')->where(array('cat_id' => $_POST['cate_id']))->find();
						}else {
							$cate_id = D(ucfirst($_POST['cate_name']) . '_category')->field('cat_id,cat_name')->where(array('cat_id' => $_POST['cate_id']))->find();
						}
						$_POST['cate_id']=serialize($cate_id);
					}
				}else{
					$_POST['cate_id']=0;
				}
				$data['platform']=serialize($_POST['platform']);
				unset($_POST['dosubmit']);
				unset($_POST['platform']);
				$data = array_merge ($data,$_POST);
				$data['start_time']=strtotime($data['start_time']);
				$data['end_time']=strtotime($data['end_time'])+86399;//到 23:59:59
				$data['add_time']=$data['last_time']=time();
				if($id = D('System_coupon')->add($data)){
					if($_POST['sync_wx']) {
						import('@.ORG.weixincard');
						import('ORG.Net.Http');
						$http = new Http();
						$mode = D('Access_token_expires');
						$res = $mode->get_access_token();
						$param['logo_url'] = $this->config['site_logo'];
						$param['brand_name'] = substr($this->config['site_name'], 0, 12);
						$param['title'] = $_POST['name'];
						$param['color'] = $_POST['color'];
						$param['notice'] = $_POST['notice'];
						$param['phone'] = $this->config['site_phone'];
						$param['description'] = $_POST['des'];
						$param['begin_time'] = $data['start_time'];
						$param['end_time'] = $data['end_time'];
						$param['num'] = $_POST['num'];
						$param['limit'] = $_POST['limit'];
						$param['center_title'] = '立即使用';
						$param['center_sub_title'] = $_POST['center_sub_title'];
						$param['center_url'] = html_entity_decode($_POST['center_url']);
						$param['custom_url_name'] = $_POST['custom_url_name'];
						$param['custom_url'] = html_entity_decode($_POST['custom_url']);
						$param['custom_url_sub_title'] = $_POST['custom_url_sub_title'];
						$param['promotion_url'] = html_entity_decode($_POST['promotion_url']);
						$param['promotion_url_name'] = '更多优惠';
						$param['icon_url_list'] = $_POST['icon_url_list']; //封面图片
						$param['abstract'] = $_POST['abstract']; //封面图片
						$param['share_friends'] = $_POST['share_friends'];
						foreach ($_POST['image_url'] as $k => $v) {
							$text_image_list[] = array(
									'image_url' => $v,
									'text' => $_POST['text'][$k],
							);
						}

						$param['text_image_list'] = $text_image_list;
						$param['business_service'] = $_POST['business_service'];
						$param['least_cost'] = $_POST['order_money'] * 100;
						$param['reduce_cost'] = $_POST['discount'] * 100;
						$param['res'] = $res;
						$card = new Create_card($param);
						$cardinfo = $card->create();
						$ticket = $cardinfo['ticket'];
						$qrcode_url = $cardinfo['qrcode_url'];
						$return = $cardinfo['return'];

						$wx_data['sync_wx'] = $_POST['sync_wx'];
						unset($param['res']);
						$wx_data['wx_param'] = serialize($param);
						$errormsg = '';
						if ($return['errcode'] == 0) {
							$wx_data['wx_cardid'] = $return['card_id'];
							$wx_data['jsapi_ticket'] = $ticket['ticket'];
							$wx_data['expires_in'] = $ticket['expires_in'];
							$wx_data['wx_qrcode'] = $qrcode_url['show_qrcode_url'];
							$wx_data['wx_ticket_addtime'] = $_SERVER['REQUEST_TIME'];
							$wx_data['is_wx_card'] = 1;
							$wx_data['cardsign'] = sha1($wx_data['wx_ticket_addtime'] . $ticket['ticket'] . $return['card_id']);

							M('System_coupon')->where(array('coupon_id' => $id))->save($wx_data);
							$errormsg = $return['errmsg'];

						} else {
							$wx_data['is_wx_card'] = 0;
							$errormsg = $return['errmsg'];
							$wx_data['weixin_err'] = serialize($errormsg);
							M('System_coupon')->where(array('coupon_id' => $id))->save($wx_data);

						}
					}
					$this->success('添加优惠券成功！'.$errormsg);
				}else{
					$this->error('添加失败！');
				}
			}else {
				$return =  D('System_coupon')->cate_platform();
				$color_list =  D('System_coupon')->color_list();
				$this->assign("color_list",$color_list);
				$this->assign("category",$return['category']);
				$this->assign("platform",$return['platform']);
				$this->display();
			}
		}

		public function edit(){
			if(IS_POST){
				$add = pow(-1,(int)$_POST['add']);
				$_POST['num']+=$add*(int)$_POST['num_add'];//数量增减
				if((int)$_POST['num']<(int)$_POST['had_pull']){
					$this->error('更新优惠券数量有误，不能小于已领取的数量！');
				}
				$now_coupon = D('System_coupon')->where(array('coupon_id'=>$_POST['coupon_id']))->find();

				if ((int)$_POST['num'] > (int)$_POST['had_pull'] && (int)$_POST['status'] == 3) {
					if ($now_coupon['end_time'] > time()) {
						$_POST['status'] = 1;
					}
				}

				if ((int)$_POST['num'] <= (int)$_POST['had_pull']) {
					$_POST['status'] = 3;
				}

				unset($_POST['dosubmit']);
				$data = $_POST;
				$data['last_time']=time();
				if(D('System_coupon')->where(array('coupon_id'=>$_POST['coupon_id']))->save($data)){
					$num_add = $add>0?$_POST['num_add']:0;
					$num_less= $add<0?$_POST['num_add']:0;
					$errorms = D('System_coupon')->decrease_sku($num_add,$num_less,$_POST['coupon_id']);

					$this->success('保存成功！'.$errorms);
				}else{
					$this->error('保存失败！');
				}
			}else {
				$return =  D('System_coupon')->cate_platform(); //模板中定义相关中文名字
				$this->assign("category",$return['category']);
				$coupon = D('System_coupon')->where(array('coupon_id'=>$_GET['coupon_id']))->find();
				$coupon['now_num'] = $coupon['num'];
				$coupon['platform'] = unserialize($coupon['platform']);
				$wx_param = unserialize($coupon['wx_param']);
				if($wx_param){
					$coupon = array_merge($coupon,$wx_param);
				}

				foreach($coupon['platform'] as &$vv){
					$vv = $return['platform'][$vv];
				}
				$coupon['platform'] = implode(',',$coupon['platform']);
				$coupon['cate_name'] = $coupon['cate_name']=='all'?'全部类别':$return['category'][$coupon['cate_name']];
				if(empty($coupon['cate_id'])) {
					$coupon['cate_id'] = '全部分类';
				}else{
					$coupon['cate_id'] = unserialize($coupon['cate_id']);
					$coupon['cate_id'] = $coupon['cate_id']['cat_name'];
				}
				$color_list =  D('System_coupon')->color_list();
				$coupon['color'] = $color_list[$coupon['color']];
				$this->assign("coupon",$coupon);
				$this->display();
			}
		}
		
		public function ajax_ordertype_cateid(){

			if($_POST['order_type']=='meal'){
				$cate_id = D(ucfirst($_POST['order_type']) . '_store_category')->field('cat_id,cat_name')->where(array('cat_status' => 1,'cat_fid'=>0))->select();
			}else {
				$cate_id = D(ucfirst($_POST['order_type']) . '_category')->field('cat_id,cat_name')->where(array('cat_status' => 1,'cat_fid'=>0))->select();
			}
			echo json_encode($cate_id);
		}
		
		public  function  had_pull(){
			$order_string = 'h.receive_time DESC ,h.id DESC';
			$where['h.uid']=array('neq','');
			if(!empty($_GET['keyword'])){
				if ($_GET['searchtype'] == 'name') {
					$where['c.name'] =  array('like', "%".$_GET['keyword']."%");
				} elseif ($_GET['searchtype'] == 'nickname') {
					$where['u.nickname'] =array('like', "%".$_GET['keyword']."%");
				}
			}
			$coupon = M('System_coupon_hadpull');
			$count_count = $coupon->join('as h left join '.C('DB_PREFIX').'system_coupon c ON h.coupon_id = c.coupon_id')->join(C('DB_PREFIX').'user u ON h.uid = u.uid')->field('h.id,c.name,u.nickname,h.num,h.receive_time,h.is_use,h.phone')->where($where)->count();
			import('@.ORG.system_page');
			$p = new Page($count_count, 15);
			$coupon_list = $coupon->join('as h left join '.C('DB_PREFIX').'system_coupon c ON h.coupon_id = c.coupon_id')->join(C('DB_PREFIX').'user u ON h.uid = u.uid')->field('h.id,c.name,u.nickname,h.num,h.receive_time,h.is_use,h.phone')->where($where)->order($order_string)->limit($p->firstRow . ',' . $p->listRows)->select();

			$this->assign('coupon_list',$coupon_list);
			$pagebar = $p->show();
			$this->assign('pagebar', $pagebar);

			$this->display();
		}

		public function del(){
			if(IS_POST){
				if(!empty($_POST['coupon_id'])){
					if(D('System_coupon')->where(array('coupon_id'=>$_POST['coupon_id']))->setField('delete',1)){
						//dump(D());
						$this->success('删除成功');
					}else{
						$this->error('删除失败！');
					}
				}

			}
		}

	public function show(){
		$this->display();
	}

	public function see_qrcode(){
		$coupon = M('System_coupon')->where(array('coupon_id'=>$_GET['id']))->find();
		echo '<html><head><style>*{margin:0;padding:0;}</style></head><body><img src="'.$coupon['wx_qrcode'].'"/></body></html>';
	}
}