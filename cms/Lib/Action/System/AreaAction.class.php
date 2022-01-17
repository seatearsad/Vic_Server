<?php
/*
 * 城市区域管理
 *
 */

class AreaAction extends BaseAction{
    public $mail;
	public function index(){
		$database_area = D('Area');
		if(!isset($_GET['type'])){
			if(!$this->config['many_city']){
				//if($_GET['type'] != 4) $_GET['type'] = 3; garfunkel modify
                if($_GET['type'] != 4) $_GET['type'] = 1;
				//$_GET['pid'] = !empty($_GET['pid']) ? $_GET['pid'] : $this->config['now_city'];
                $_GET['pid'] = !empty($_GET['pid']) ? $_GET['pid'] : 0;
			}else{
				if($this->config['now_province']){
					if(empty($_GET['type'])) $_GET['type'] = 2;
					$_GET['pid'] = !empty($_GET['pid']) ? $_GET['pid'] : $this->config['now_province'];
				}else{
					if(empty($_GET['type'])) $_GET['type'] = 1;
					$_GET['pid'] = !empty($_GET['pid']) ? $_GET['pid'] : 0;
				}
			}
		}
        $this->assign('type',$_GET['type']);
		$condition_area['area_pid'] = $_GET['pid'];
		$condition_area['area_type'] = $_GET['type'];
		$condition_area['is_open'] = array('neq',2);

		if($this->system_session['area_id']==$_GET['pid']||$this->system_session['area_id']=='0'){
			$this->assign('is_system',true);
		}
		$now_area = $database_area->field(true)->where(array('area_id'=>$_GET['pid']))->find();

		$this->assign('now_area',$now_area);
		if ($_GET['type']==3){
            $condition_parent['area_id'] = $now_area["area_pid"];
            $parent_area = $database_area->field(true)->where($condition_parent)->find();
            $this->assign('parent_area',$parent_area);
        }
		
		if($_GET['type'] == 4){
			$order = '`area_sort` DESC,`is_open` DESC,`first_pinyin` ASC';
		}else{
			$order = '`area_sort` DESC,`is_open` DESC,`area_id` ASC';
		}
		$area_list = $database_area->field(true)->where($condition_area)->order($order)->select();
		$this->assign('area_list',$area_list);
		switch($_GET['type']){
			case 1:
				$now_type_str = '省份';
				break;
			case 2:

				$now_type_str = '城市';
				break;
			case 3:
				$now_type_str = '区域';
				break;
			default:
				$now_type_str = '商圈';
		}
		$this->assign('now_type_str',$now_type_str);
        $this->assign('module_name','System');
		$this->display();
    }
    //	商场列表
    public function area_market(){
		$database_area = D('Area_market');
		$area_id = !empty($_GET['pid']) ? $_GET['pid'] : 0;
		$now_area = M('Area')->field(true)->where(array('area_id'=>$area_id))->find();
		
		$area_market	=	$database_area->order('market_sort desc')->where(array('area_id'=>$area_id))->select();
		if($area_market){
			foreach($area_market as $k=>$v){
				$area_market[$k]['img']	=	$this->config['site_url'].$v['img'];
			}
		}
		$this->assign('now_area',$now_area);
		$this->assign('area_list',$area_market);
		$this->assign('area_id',$area_id);
		$this->display();
    }
    //	添加商场
    public function add_market(){
    	if(IS_POST){
    		$image = D('Image')->handle($_POST['area_id'], 'market', 0, array('size' => 10), false);
    		if (!$image['error']) {
				$_POST = array_merge($_POST,$image['url']);
			} else {
				$this->frame_submit_tips(0,$image['msg']);
			}
			$farea	=	M('Area')->field(array('area_pid'))->where(array('area_id'=>$_POST['area_id']))->find();
			$area	=	M('Area')->field(array('area_pid'))->where(array('area_id'=>$farea['area_pid']))->find();
			$_POST['city_id']	=	$area['area_pid'];
			$long	=	explode(",",$_POST['long_lat']);
			$_POST['long']	=	$long[0];
			$_POST['lat']	=	$long[1];
			unset($_POST['long_lat']);
			$add	=	M('Area_market')->add($_POST);
			if($add){
				$this->frame_submit_tips(1,L('J_SUCCEED1'));
			}else{
				$this->frame_submit_tips(0,L('J_MODIFICATION_FAILED2'));
			}
		}else{
			$this->assign('bg_color','#F3F3F3');
			$this->display();
		}
    }
    //	修改商场
    public function edit_market(){
    	if(IS_POST){
    		$image = D('Image')->handle($_POST['area_id'], 'market', 0, array('size' => 10), false);
    		if(!$image['error']) {
				$_POST = array_merge($_POST,$image['url']);
			}
			$long	=	explode(",",$_POST['long_lat']);
			$_POST['long']	=	$long[0];
			$_POST['lat']	=	$long[1];
			unset($_POST['long_lat']);
			$save	=	M('Area_market')->where(array('market_id'=>$_GET['market_id']))->data($_POST)->save();
			if($save){
				$this->frame_submit_tips(1,L('J_SUCCEED1'));
			}else{
				$this->frame_submit_tips(0,L('J_MODIFICATION_FAILED2'));
			}
		}else{
			$market		=	M('Area_market')->where(array('market_id'=>$_GET['market_id']))->find();
			$this->assign('bg_color','#F3F3F3');
			$this->assign('market',$market);
			$this->display();
		}
    }
    //	删除商场
    public function del_market(){
    	$del	=	M('Area_market')->where(array('market_id'=>$_POST['market_id']))->delete();
    	if($del){
			$this->success(L('J_DELETION_SUCCESS'));
    	}else{
			$this->error(L('J_DELETION_FAILED_RETRY'));
    	}
    }
    //	介绍类型列表
    //public function add_market_type(){
//    	if(IS_POST){
//			$image = D('Image')->handle($_POST['area_id'], 'market', 0, array('size' => 10), false);
//    		if(!$image['error']) {
//				$_POST = array_merge($_POST,$image['url']);
//			}
//			$add	=	M('Area_market_type')->add($_POST);
//			if($add){
//				$this->success('添加成功！');
//			}else{
//				$this->error('添加失败！请重试~');
//			}
//    	}else{
//			$typeimg	=	M('Area_market_type')->field(array('pigcms_id','type_name','type_img'))->select();
//			if($typeimg){
//				foreach($typeimg as $k=>$v){
//					$typeimg[$k]['type_img']	=	$this->config['site_url'].$v['type_img'];
//				}
//			}
//			$this->assign('typeimg',$typeimg);
//			$this->display();
//    	}
//    }
	public function add(){
		$this->assign('bg_color','#F3F3F3');
		$this->display();
	}
	public function modify(){
		if(IS_POST){
            if (($_POST['is_pick_up']==1) && ($_POST['is_shipping']==1)){
                $_POST["bag_type"]=3;
            }else if ($_POST['is_pick_up']==1){
                $_POST["bag_type"]=1;
            }else if ($_POST['is_shipping']==1){
                $_POST["bag_type"]=2;
            }else{
                $_POST["bag_type"]=0;
            }
			$database_area = D('Area');
			$condition_area['area_url'] = $_POST['area_url'];
			if($database_area->where($condition_area)->find()){
				$this->error(L('TSUTA'));
			}
			if($database_area->data($_POST)->add()){
				import('ORG.Util.Dir');
				Dir::delDirnotself('./runtime');
				$this->success(L('J_SUCCEED1'));
			}else{
				$this->error(L('J_MODIFICATION_FAILED2'));
			}
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}
	public function edit(){
		$this->assign('bg_color','#F3F3F3');

		$database_area = D('Area');
		$condition_area['area_id'] = $_GET['area_id'];
		$now_area = $database_area->field(true)->where($condition_area)->find();
		if(empty($now_area)){
			$this->frame_error_tips('数据库中没有查询到该信息！');
		}
		$this->assign('now_area',$now_area);
		$this->display();
	}
	public function amend(){
		if(IS_POST){
			$database_area = D('Area');
			$condition_area['area_url'] = $_POST['area_url'];
            if (($_POST['is_pick_up']==1) && ($_POST['is_shipping']==1)){
                $_POST["bag_type"]=3;
            }else if ($_POST['is_pick_up']==1){
                $_POST["bag_type"]=1;
            }else if ($_POST['is_shipping']==1){
                $_POST["bag_type"]=2;
            }else{
                $_POST["bag_type"]=0;
            }

            $_POST['bag_url_show'] = $_POST['is_show_url'];

            $area = $database_area->where(array('area_id'=>$_POST['area_id']))->find();
			$area_type = $area['area_type'];

            //开放城市的送餐员招聘 发送通知邮件
			if($area['bag_is_recruit'] == 0 && $_POST['bag_is_recruit'] == 1){
                $this->sendMailToDeliver($_POST['area_id'],$area['area_name']);
            }
			if($database_area->data($_POST)->save()){
			    //当城市时间均为00:00:00时，为城市紧急状态将所有店铺设置为休假状态
                $config = D('Config')->where(array('name'=>'emergency_close_store'))->find();
                $close_arr = json_decode($config['value'],true);

			    if($_POST['begin_time'] == '00:00:00' && $_POST['end_time'] == '00:00:00'){
			        $store_id_list = array();
                    $store_list = D('Merchant_store')->where(array('city_id'=>$_POST['area_id'],'status'=>1,'store_is_close'=>0))->select();
                    foreach ($store_list as $c_store){
                        $store_id_list[] = $c_store['store_id'];
                    }
                    $close_arr[$_POST['area_id']] = $store_id_list;

                    D('Merchant_store')->where(array('store_id'=>array('in',$store_id_list)))->save(array('store_is_close'=>1));

                    D('Config')->where(array('name'=>'emergency_close_store'))->save(array('value'=>json_encode($close_arr)));
                }else{
                    $open_list = $close_arr[$_POST['area_id']];
                    if($open_list){
                        D('Merchant_store')->where(array('store_id'=>array('in',$open_list)))->save(array('store_is_close'=>0));
                        unset($close_arr[$_POST['area_id']]);
                        D('Config')->where(array('name'=>'emergency_close_store'))->save(array('value'=>json_encode($close_arr)));
                    }
                }

				import('ORG.Util.Dir');
				Dir::delDirnotself('./runtime');
				if ($area_type[0]['area_type']>3) {
                    $circles = D('group_store')->where(array('circle_id'=>$_POST['area_id']))->field('group_id')->select();
					if(!empty($circles)){
						$tmp = '';
						foreach ($circles as $v) {
							$tmp.= $v['group_id'].',';
						}
						$tmp = substr($tmp, 0,-1);
						$where['group_id'] = array('in',$tmp);
						if(!D('group')->where($where)->save(array('prefix_title'=>$_POST['area_name']))){
							$this->error(L('J_MODIFICATION_FAILED'));
						}
                    }
                }
				$this->success('Success');
			}else{
				$this->error(L('J_MODIFICATION_FAILED'));
			}
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}
	public function del(){
		if(IS_POST){
			$return = $this->recursive_del($_POST['area_id']);
			import('ORG.Util.Dir');
			Dir::delDirnotself('./runtime');
			$this->success(L('J_DELETION_SUCCESS'));
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}
	/* 递归删除分类下的子分类且删除自己 */
	protected function recursive_del($area_id){
		$database_area = D('Area');
		$condition_area['area_pid'] = $area_id;
		$now_area = $database_area->field('`area_id`')->where($condition_area)->select();
		if(is_array($now_area)){
			foreach($now_area as $key=>$value){
				$this->recursive_del($value['area_id']);
			}
		}

		$condition_del_area['area_id'] = $area_id;
		$database_area->where($condition_del_area)->setField('is_open',2);
	}
	public function ajax_province(){
		$database_area = D('Area');
		$condition_area['area_type'] = 1;
		$condition_area['is_open'] = 1;
		$province_list = $database_area->field('`area_id` `id`,`area_name` `name`')->where($condition_area)->order('`area_sort` DESC,`area_id` ASC')->select();
		if(count($province_list) == 1){
			$return['error'] = 2;
			$return['id'] = $province_list[0]['id'];
			$return['name'] = $province_list[0]['name'];
		}else if(!empty($province_list)){
			$return['error'] = 0;
			$return['list'] = $province_list;
		}else{
			$return['error'] = 1;
			$return['info'] = '没有开启了的省份！请先开启。';
		}
		exit(json_encode($return));
	}
	public function ajax_city(){
		$database_area = D('Area');
		$condition_area['area_pid'] = intval($_POST['id']);
		$condition_area['is_open'] = 1;
		$city_list = $database_area->field('`area_id` `id`,`area_name` `name`')->where($condition_area)->order('`area_sort` DESC,`area_id` ASC')->select();
		if(count($city_list) == 1 && !$_POST['type']){
			$return['error'] = 2;
			$return['id'] = $city_list[0]['id'];
			$return['name'] = $city_list[0]['name'];
		}else if(!empty($city_list)){
			$return['error'] = 0;
			$return['list'] = $city_list;
		}else{
			$return['error'] = 1;
			$return['info'] = '［ <b>'.$_POST['name'] .'</b> ］ 省份下没有已开启的城市！！！请先开启城市或删除此省份';
		}
		exit(json_encode($return));
	}
	public function ajax_area(){
		$database_area = D('Area');
		$condition_area['area_pid'] = intval($_POST['id']);
		$condition_area['is_open'] = 1;
		$area_list = $database_area->field('`area_id` `id`,`area_name` `name`')->where($condition_area)->order('`area_sort` DESC,`area_id` ASC')->select();
		if(!empty($area_list)){
			$return['error'] = 0;
			$return['list'] = $area_list;
		}else{
			$return['error'] = 1;
			$return['info'] = '［ <b>'.$_POST['name'] .'</b> ］ 城市下没有已开启的区域！请先开启区域或删除此城市';
		}
		exit(json_encode($return));
	}
	public function ajax_circle(){
		$database_area = D('Area');
		$condition_area['area_pid'] = intval($_POST['id']);
		$condition_area['is_open'] = 1;
		$circle_list = $database_area->field('`area_id` `id`,`area_name` `name`,`first_pinyin`')->where($condition_area)->order('`area_sort` DESC,`first_pinyin` ASC')->select();
		if(!empty($circle_list)){
			$tmp_list = array();
			foreach($circle_list as $key=>$value){
				if(empty($tmp_list[$value['first_pinyin']])){
					$circle_list[$key]['name'] = $value['first_pinyin'].'. '.$value['name'];
					$tmp_list[$value['first_pinyin']] = true;
				}else{
					$circle_list[$key]['name'] = '&nbsp;&nbsp;&nbsp;&nbsp;'.$value['name'];
				}
			}
			$return['error'] = 0;
			$return['list'] = $circle_list;
		}else{
			$return['error'] = 1;
			$return['info'] = '［ <b>'.$_POST['name'] .'</b> ］ 区域下没有已开启的商圈！请先开启商圈或删除此区域';
		}
		exit(json_encode($return));
	}
	public function ajax_market(){
		$database_area = D('Area_market');
		$condition_area['area_id'] = intval($_POST['id']);
		$condition_area['is_open'] = 1;
		$circle_list = $database_area->field('`market_id` `id`,`market_name` `name`')->where($condition_area)->order('`market_sort` DESC')->select();
		if(!empty($circle_list)){
			//$tmp_list = array();
//			foreach($circle_list as $key=>$value){
//				if(empty($tmp_list[$value['first_pinyin']])){
//					$circle_list[$key]['name'] = $value['first_pinyin'].'. '.$value['name'];
//					$tmp_list[$value['first_pinyin']] = true;
//				}else{
//					$circle_list[$key]['name'] = '&nbsp;&nbsp;&nbsp;&nbsp;'.$value['name'];
//				}
//			}
			$return['error'] = 0;
			$return['list'] = $circle_list;
		}else{
			$return['error'] = 1;
			$return['info'] = '［ <b>'.$_POST['name'] .'</b> ］ 商圈下没有已开启的商场！请先开启商场或删除此区域';
		}
		exit(json_encode($return));
	}

	public function admin()
	{
		$area_id = isset($_GET['area_id']) ? intval($_GET['area_id']) : 0;
		$area = D('Area')->field(true)->where(array('area_id' => $area_id, 'is_open' => 1))->find();
		if (empty($area)) {
			$this->error('不存在的区域或该区域没有被开通,请查证后重新操作~');
		}
		if ($area['area_type'] == 2) {
			$this->assign('title', '城市');
		} elseif ($area['area_type'] == 3) {
			$this->assign('title', '区域');
		}
		$admin = D('Admin')->field(true)->where(array('area_id' => $area_id))->select();

		$this->assign('admin', $admin);
		$this->display();
	}

	public function addadmin()
	{
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		$admin = D('Admin')->field(true)->where(array('id' => $id))->find();
		$this->assign('admin', $admin);
		$this->assign('bg_color','#F3F3F3');
		$this->display();
	}

	public function saveAdmin()
	{
		if(IS_POST){
			$database_area = D('Admin');
			$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
			$account = htmlspecialchars($_POST['account']);
			if($database_area->where("`id`<>'{$id}' AND `account`='{$account}'")->find()){
				$this->error(L('SIAEPRI'));
			}
			unset($_POST['id']);

			$area_id = isset($_POST['area_id']) ? intval($_POST['area_id']) : 0;
			$area = D('Area')->field(true)->where(array('area_id' => $area_id, 'is_open' => 1))->find();
			if (empty($area)) {
				$this->error('不存在的区域或该区域没有被开通,请查证后重新操作~');
			}
			if ($area['area_type'] == 2) {
				$_POST['level'] = 3;
			} elseif ($area['area_type'] == 3) {
				$_POST['level'] = 1;
			}
			if ($id) {
				if ($_POST['pwd']) {
					$_POST['pwd'] = md5($_POST['pwd']);
				} else {
					unset($_POST['pwd']);
				}
				$database_area->where(array('id' => $id))->data($_POST)->save();
				$this->success('Success');
			} else {
				if (empty($_POST['pwd'])) {
					$this->error(L('K_PASS_EMPTY'));
				}
				$_POST['pwd'] = md5($_POST['pwd']);
				if($database_area->data($_POST)->add()){
					$this->success(L('J_SUCCEED1'));
				}else{
					$this->error(L('J_MODIFICATION_FAILED2'));
				}
			}
		}else{
			$this->error('非法提交,请重新提交~');
		}

	}

    public function ajax_city_name(){
        $city_name = $_POST['city_name'];
        $where = array('area_name'=>$city_name,'area_type'=>2);
        $area = D('Area')->where($where)->find();
        $data = array();
        if($area){
            $data['area_id'] = 0;
            $data['city_id'] = $area['area_id'];
            $data['province_id'] = $area['area_pid'];

            $return['error'] = 0;
        }else{
            $return['error'] = 1;
        }
        $return['info'] = $data;
        exit(json_encode($return));
    }

    public function sendMailToDeliver($area_id,$area_name){
        $deliver_list = D("Deliver_user")->where(array('city_id'=>$area_id,'reg_status'=>1))->select();
        foreach ($deliver_list as $deliver){
            if($deliver['email'] != "") {
                $email = array(array("address"=>$deliver['email'],"userName"=>$deliver['name']));
                $title = "We’re accepting new couriers in ".$area_name;
                $body = $this->getMailBody($deliver['name'],$area_name);

                if(!$this->mail) $this->mail = $this->getMail();

                $this->mail->clearAddresses();
                foreach ($email as $address) {
                    $this->mail->addAddress($address['address'], $address['userName']);
                }

                $this->mail->isHTML(true);
                $this->mail->Subject = $title;
                $this->mail->Body    = $body;
                $this->mail->AltBody = '';

                $this->mail->send();
            }
        }
    }

    function getMail(){
        $config = D('Config')->get_config();
        $gmail_pwd = $config['gmail_password'];

        require './mailer/PHPMailer.php';
        require './mailer/SMTP.php';
        require './mailer/Exception.php';

        $mail = new PHPMailer\PHPMailer\PHPMailer();

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';                       // Specify main and backup SMTP servers. 这里改成smtp.gmail.com
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'donotreply.tutti@gmail.com';       // SMTP username 这里改成自己的gmail邮箱，最好新注册一个，因为后期设置会导致安全性降低
        $mail->Password = $gmail_pwd;                         // SMTP password 这里改成对应邮箱密码
        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465;

        $mail->setFrom('donotreply.tutti@gmail.com', 'Tutti');

        return $mail;
    }

    public function getMailBody($name,$city_name)
    {
        $body = "<p>Hi " . $name . ",</p>";
        $body .= "<p>&nbsp;</p>";
        $body .= "<p>You are receiving this email because you have previously applied to be a Tutti Courier in ".$city_name.", and we are now accepting new courier applications!</p>";
        $body .= "<p>&nbsp;</p>";
        $body .= "<p>Please follow this link to login and continue your application:<a href='https://tutti.app/wap.php?g=Wap&c=Deliver&a=login' target='_blank'>https://tutti.app/wap.php?g=Wap&c=Deliver&a=login</a></p>";
        $body .= "<p>&nbsp;</p>";
        $body .= "<p>You can also finish your application on our app (search “Tutti Courier” on the App Store or Google Play Store).</p>";
        $body .= "<p>&nbsp;</p>";
        $body .= "<p>For any questions, please contact us at 1-888-399-6668 or email <a href='mailto:hr@tutti.app'>hr@tutti.app</a>.</p>";
        $body .= "<p>&nbsp;</p>";
        $body .= "<p>Best regards,</p>";
        $body .= "<p>Tutti Courier Team</p>";

        return $body;
    }
}