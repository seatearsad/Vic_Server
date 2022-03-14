<?php 
/*
 *2016年2月24日09:04:46
 *平台优惠券功能
 */
class CouponAction extends BaseAction {
		
		public function index(){
			if (!empty($_GET['keyword'])) {
				if ($_GET['searchtype'] == 'coupon_id') {
					$condition_coupon['coupon_id'] = $_GET['keyword'];
				} else if ($_GET['searchtype'] == 'name') {
					$condition_coupon['name'] = array('like', '%' . $_GET['keyword'] . '%');
				}else if($_GET['searchtype'] == 'code'){
                    $condition_coupon['notice'] = $_GET['keyword'];
                }
			}

            if($_GET['city_id']){
                $this->assign('city_id',$_GET['city_id']);
                if($_GET['city_id'] != 0){
                    $condition_coupon['city_id'] = $_GET['city_id'];
                }
            }else{
                $this->assign('city_id',0);
            }
			$condition_coupon['delete'] = 0;
			if($this->system_session['level'] == 3)
                $condition_coupon['city_id'] = $this->system_session['area_id'];
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
			$coupon = D('System_coupon');
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
					//D('System_coupon')->where(array('coupon_id'=>$v['coupon_id']))->setField('status',2);
					//$v['status']=2;
				}
				if($v['city_id'] != 0){
                    $city = D('Area')->where(array('area_id'=>$v['city_id']))->find();
                    $v['city_name'] = $city['area_name'];
                }else{
                    $v['city_name'] = 'All';
                }
			}
			$return =  D('System_coupon')->cate_platform();
			$this->assign("category",$return['category']);
			$this->assign("platform",$return['platform']);
			$this->assign('coupon_list',$coupon_list);
			$pagebar = $p->show2();
			$this->assign('pagebar', $pagebar);

            $city = D('Area')->where(array('area_type'=>2,'is_open'=>1))->select();
            $this->assign('city',$city);
            $this->assign('module_name','Market');
			$this->display();
		}

		public function add(){
			if(IS_POST){
				if(strtotime($_POST['end_time'])<strtotime($_POST['start_time'])||strtotime($_POST['end_time'])<time()||strtotime($_POST['start_time'])<strtotime(date('Y-m-d'))){
					$this->error(L('STRT_WRONG'));
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
				//garfunkel add
                if($_POST['notice']) {
                    $_POST['notice']=trim($_POST['notice']);
                    if (!empty(D('System_coupon')->field()->where(array('notice' => $_POST['notice']))->find()))
                        $this->error('领取口令已经存在，请使用其他口令');
                }else{
                    $this->error('领取口令不能为空！');
                }
				//
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

				//garfunkel add
                if($_POST['city_id']){
                    $data['city_id'] = $_POST['city_id'];
                }

                if($_POST['currency']){
                    if($_POST['currency'] == 2){
                        $data['city_id'] = $_POST['city_idss'];
                    }else{
                        $data['city_id'] = 0;
                    }
                }

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
					$this->success('Success!'.$errormsg);
				}else{
					$this->error('添加失败！');
				}
			}else {
				$return =  D('System_coupon')->cate_platform();
				$color_list =  D('System_coupon')->color_list();

				if($this->system_session['level'] == 3){
				    $area_id = $this->system_session['area_id'];
				    $city = D('Area')->where(array('area_id'=>$area_id))->find();
				    $this->assign('city',$city);
                }
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

				unset($_POST[' ']);
				$data = $_POST;
				$data['last_time']=time();
                //garfunkel add
                if($_POST['city_id']){
                    $data['city_id'] = $_POST['city_id'];
                }

                if($_POST['currency']){
                    if($_POST['currency'] == 2){
                        $data['city_id'] = $_POST['city_idss'];
                    }else{
                        $data['city_id'] = 0;
                    }
                }
				if(D('System_coupon')->where(array('coupon_id'=>$_POST['coupon_id']))->save($data)){
					$num_add = $add>0?$_POST['num_add']:0;
					$num_less= $add<0?$_POST['num_add']:0;
					$errorms = D('System_coupon')->decrease_sku($num_add,$num_less,$_POST['coupon_id']);

					$this->success('Success!'.$errorms);
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
				$coupon['cate_name'] = $coupon['cate_name']=='all'?'All':$return['category'][$coupon['cate_name']];
				if(empty($coupon['cate_id'])) {
					$coupon['cate_id'] = 'All';
				}else{
					$coupon['cate_id'] = unserialize($coupon['cate_id']);
					$coupon['cate_id'] = $coupon['cate_id']['cat_name'];
				}
				$color_list =  D('System_coupon')->color_list();
				$coupon['color'] = $color_list[$coupon['color']];

                if($this->system_session['level'] == 3){
                    $area_id = $this->system_session['area_id'];
                    $city = D('Area')->where(array('area_id'=>$area_id))->find();
                    $this->assign('city',$city);
                }
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
				if ($_GET['searchtype'] == 'nickname') {
					$where['u.nickname'] =array('like', "%".$_GET['keyword']."%");
				}elseif ($_GET['searchtype'] == 'uid'){
                    $where['h.uid'] = $_GET['keyword'];
                }elseif ($_GET['searchtype'] == 'cid'){
                    $where['c.coupon_id'] = $_GET['keyword'];
                    //$where['c.notice'] = $_GET['keyword'];
                }
			}
            if($this->system_session['level'] == 3) {
                $where['c.city_id'] = $this->system_session['area_id'];
            }
			$coupon = M('System_coupon_hadpull');
			$count_count = $coupon->join('as h left join '.C('DB_PREFIX').'system_coupon c ON h.coupon_id = c.coupon_id')->join(C('DB_PREFIX').'user u ON h.uid = u.uid')->field('h.id,c.name,u.nickname,h.num,h.receive_time,h.is_use,h.phone,h.admin_name')->where($where)->count();
			import('@.ORG.system_page');
			$p = new Page($count_count, 15);
			$coupon_list = $coupon->join('as h left join '.C('DB_PREFIX').'system_coupon c ON h.coupon_id = c.coupon_id')->join(C('DB_PREFIX').'user u ON h.uid = u.uid')->field('h.id,c.coupon_id,h.uid,c.name,u.nickname,h.num,h.receive_time,h.is_use,h.phone,h.admin_name')->where($where)->order($order_string)->limit($p->firstRow . ',' . $p->listRows)->select();

			$this->assign('coupon_list',$coupon_list);
			$pagebar = $p->show2();
			$this->assign('pagebar', $pagebar);

            $this->assign('module_name','Market');
			$this->display();
		}

		public function pull_export(){
            set_time_limit(0);
            require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
            $title = 'Claim List';
            $objExcel = new PHPExcel();
            $objProps = $objExcel->getProperties();
            // 设置文档基本属性
            $objProps->setCreator($title);
            $objProps->setTitle($title);
            $objProps->setSubject($title);
            $objProps->setDescription($title);

            $order_string = 'h.receive_time DESC ,h.id DESC';
            $where['h.uid']=array('neq','');
            if(!empty($_GET['keyword'])){
                if ($_GET['searchtype'] == 'nickname') {
                    $where['u.nickname'] =array('like', "%".$_GET['keyword']."%");
                }elseif ($_GET['searchtype'] == 'uid'){
                    $where['h.uid'] = $_GET['keyword'];
                }elseif ($_GET['searchtype'] == 'cid'){
                    $where['c.coupon_id'] = $_GET['keyword'];
                    //$where['c.notice'] = $_GET['keyword'];
                }
            }
            if($this->system_session['level'] == 3) {
                $where['c.city_id'] = $this->system_session['area_id'];
            }
            $coupon = M('System_coupon_hadpull');

            $coupon = M('System_coupon_hadpull');
            $count_count = $coupon->join('as h left join '.C('DB_PREFIX').'system_coupon c ON h.coupon_id = c.coupon_id')->join(C('DB_PREFIX').'user u ON h.uid = u.uid')->field('h.id,c.name,u.nickname,h.num,h.receive_time,h.is_use,h.phone,h.admin_name')->where($where)->count();

            $length = ceil($count_count / 1000);
            for ($i = 0; $i < $length; $i++) {
                $i && $objExcel->createSheet();
                $objExcel->setActiveSheetIndex($i);
                $objExcel->getActiveSheet()->setTitle(strval($i + 1));
                $objActSheet = $objExcel->getActiveSheet();
                $objExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $objExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $objExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $objExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $objExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $objExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $objExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                $objExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                $objExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                $objExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);

                $objActSheet->setCellValue('A1', 'ID');
                $objActSheet->setCellValue('B1', 'Coupon Name');
                $objActSheet->setCellValue('C1', 'Coupon ID');
                $objActSheet->setCellValue('D1', 'User Name');
                $objActSheet->setCellValue('E1', 'User ID');
                $objActSheet->setCellValue('F1', 'User E-mail');
                $objActSheet->setCellValue('G1', 'Quantity');//无
                $objActSheet->setCellValue('H1', 'Date');
                $objActSheet->setCellValue('I1', 'Admin');
                $objActSheet->setCellValue('J1', 'Status');

                $coupon_list = $coupon->join('as h left join '.C('DB_PREFIX').'system_coupon c ON h.coupon_id = c.coupon_id')->join(C('DB_PREFIX').'user u ON h.uid = u.uid')->field('h.id,c.coupon_id,h.uid,c.name,u.nickname,u.email,h.num,h.receive_time,h.is_use,h.phone,h.admin_name')->where($where)->order($order_string)->limit($i*1000 . ',1000')->select();

                $index = 2;
                foreach ($coupon_list as $value){
                    if($value['is_use'] == 1){
                        $statusName = "Used";
                    }elseif($value['is_use'] == 0){
                        $statusName = "Not Yet";
                    }elseif($value['is_use'] == 2){
                        $statusName = "Void";
                    }else{
                        $statusName = "";
                    }

                    $objActSheet->setCellValueExplicit('A' . $index, $value['id']);
                    $objActSheet->setCellValueExplicit('B' . $index, $value['name']);
                    $objActSheet->setCellValueExplicit('C' . $index, $value['coupon_id']);
                    $objActSheet->setCellValueExplicit('D' . $index, $value['nickname']);
                    $objActSheet->setCellValueExplicit('E' . $index, $value['uid']);
                    $objActSheet->setCellValueExplicit('F' . $index, $value['email']);
                    $objActSheet->setCellValueExplicit('G' . $index, $value['num']);
                    $objActSheet->setCellValueExplicit('H' . $index, date('Y-m-d',$value['receive_time']));
                    $objActSheet->setCellValueExplicit('I' . $index, $value['admin_name']);
                    $objActSheet->setCellValueExplicit('J' . $index, $statusName);

                    $index++;
                }

                sleep(2);
            }

            //输出
            $objWriter = new PHPExcel_Writer_Excel5($objExcel);
            ob_end_clean();
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
            header("Content-Type:application/force-download");
            header("Content-Type:application/vnd.ms-execl");
            header("Content-Type:application/octet-stream");
            header("Content-Type:application/download");
            header('Content-Disposition:attachment;filename="' . $title . '.xls"');
            header("Content-Transfer-Encoding:binary");
            $objWriter->save('php://output');
            exit();
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
