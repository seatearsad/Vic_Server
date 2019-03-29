<?php
/*
 * 首页
 *
 */
class IndexAction extends BaseAction {
    public function index(){
        header("location:".U('Shop/Index/index'));
		//顶部广告
		$index_top_adver = D('Adver')->get_adver_by_key('index_top');
		$this->assign('index_top_adver',$index_top_adver);

		//热门搜索词
    	$search_hot_list = D('Search_hot')->get_list(12);
    	$this->assign('search_hot_list',$search_hot_list);

		//右侧广告
		$index_right_adver = D('Adver')->get_adver_by_key('index_right',3);
		$this->assign('index_right_adver',$index_right_adver);

		//所有分类 包含2级分类
		$all_category_list = D('Group_category')->get_category();
		$this->assign('all_category_list',$all_category_list);

		//热门二级分类
		$hot_group_category = D('Group_category')->get_hot_category();
		$this->assign('hot_group_category',$hot_group_category);

		//所有区域
		$all_area_list = D('Area')->get_area_list();
		$this->assign('all_area_list',$all_area_list);

		//热门商圈
		$hot_circle_list = D('Area')->get_hot_circle_list();
		$this->assign('hot_circle_list',$hot_circle_list);

		//最新团购
		$new_group_list = D('Group')->get_group_list('new',12);
		$this->assign('new_group_list',$new_group_list);

		//手动首页排序团购
		$index_sort_group_list = D('Group')->get_group_list('index_sort',12);
		$this->assign('index_sort_group_list',$index_sort_group_list);

		//首页大分类下的团购列表
		$index_group_list = D('Group')->get_category_arr_group_list($all_category_list,12);
		
		$this->assign('index_group_list',$index_group_list);

		//活动列表
		if($this->config['activity_open']){
			$now_activity = D('Extension_activity')->where(array('begin_time'=>array('lt',$_SERVER['REQUEST_TIME']),'end_time'=>array('gt',$_SERVER['REQUEST_TIME'])))->order('`activity_id` ASC')->find();
			if($now_activity){
				// list($time_array['j'],$time_array['h'],$time_array['m'],$time_array['s']) = explode(' ',date('j H i s',$now_activity['end_time'] - $_SERVER['REQUEST_TIME']));
				$time = $now_activity['end_time'] - $_SERVER['REQUEST_TIME'];
				$time_array['j'] = floor($time/86400);
				$time_array['h'] = floor($time%86400/3600);
				$time_array['m'] = floor($time%86400%3600/60);
				$time_array['s'] = floor($time%86400%60);
				// $activity_list = D('Extension_activity_list')->field('`pigcms_id`,`name`,`title`,`index_pic`,`part_count`')->where(array('activity_term'=>$now_activity['activity_id'],'status'=>'1','is_finish'=>'0','index_sort'=>array('neq','0')))->order('`index_sort` DESC,`pigcms_id` DESC')->limit(6)->select();
				$activity_list = D('')->field('`eac`.`pigcms_id`,`eac`.`name`,`eac`.`title`,`eac`.`index_pic`,`eac`.`part_count`')->table(array(C('DB_PREFIX').'extension_activity_list'=>'eac',C('DB_PREFIX').'merchant'=>'m'))->where("`eac`.activity_term='{$now_activity['activity_id']}' AND `eac`.`status`='1' AND `eac`.`is_finish`='0' AND `eac`.`index_sort`>0 AND `eac`.`mer_id`=`m`.`mer_id` AND `m`.`city_id`='{$this->config['now_city']}'")->order('`eac`.`index_sort` DESC,`eac`.`pigcms_id` DESC')->limit(6)->select();
				if(empty($activity_list)){
					unset($now_activity);
				}
				$this->assign('now_activity',$now_activity);
				$this->assign('time_array',$time_array);

				// $activity_list = D('Extension_activity_list')->field('`pigcms_id`,`name`,`title`,`index_pic`,`part_count`')->where(array('activity_term'=>$now_activity['activity_id'],'status'=>'1','index_sort'=>array('neq','0')))->order('`index_sort` DESC,`pigcms_id` DESC')->limit(6)->select();

				$extension_image_class = new extension_image();
				foreach($activity_list as &$value){
					$value['index_pic'] = $this->config['site_url'].'/upload/activity/index_pic/'.$value['index_pic'];
					$value['url'] = $this->config['site_url'].'/activity/'.$value['pigcms_id'].'.html';
				}
				$this->assign('activity_list',$activity_list);
				$this->assign('activity_url',$this->config['site_url'].'/activity/');
			}

			//本站信息
			$index_site_info = S('index_site_info');
			if(empty($index_site_info)){
				$today_zero_time = strtotime(date('Y-m-d',$_SERVER['REQUEST_TIME']) . ' 00:00:00');
				$index_site_info = array();
				$index_site_info['user_count'] = D('User')->where(array('add_time'=>array('gt',$today_zero_time)))->count('uid');
				$index_site_info['merchant_count'] = D('Merchant')->where(array('reg_time'=>array('gt',$today_zero_time)))->count('mer_id');
				$index_site_info['merchant_store_count'] = D('Merchant_store')->where(array('last_time'=>array('gt',$today_zero_time)))->count('store_id');
				$index_site_info['group_count'] = D('Group')->where(array('last_time'=>array('gt',$today_zero_time)))->count('group_id');
				$index_site_info['meal_store_count'] = D('Merchant_store_meal')->where(array('last_time'=>array('gt',$today_zero_time)))->count('store_id');
				// dump($index_site_info);
			}
			$this->assign('index_site_info',$index_site_info);
		}
		//友情链接
		$flink_list = D('Flink')->get_flink_list();
		$this->assign('flink_list',$flink_list);

		$this->display();
    }

	public function group_index_sort(){
		$group_id = $_POST['id'];
		$database_index_group_hits = D('Index_group_hits_'.substr(dechex($group_id),-1));
		$data_index_group_hits['group_id'] = $group_id;
		$data_index_group_hits['ip']		= get_client_ip();
		if(!$database_index_group_hits->field('`group_id`')->where($data_index_group_hits)->find()){
			$condition_group['group_id'] = $group_id;
			if(M('Group')->where($condition_group)->setDec('index_sort')){
				$data_index_group_hits['time'] = $_SERVER['REQUEST_TIME'];
				$database_index_group_hits->data($data_index_group_hits)->add();
			}
		}
	}

	public function market_doc(){
        $this->display();
    }

    public function market_table(){
        if($_POST){
            $body = '<p>NAME: '.$_POST['name'].'</p>';
            $body += '<p>ADDRESS: '.$_POST['address'].'</p>';
            $body += '<p>CITY: '.$_POST['city'].'</p>';
            $body += '<p>POSTAL CODE: '.$_POST['postal_code'].'</p>';
            $body += '<p>EMAIL: '.$_POST['email'].'</p>';
            $body += '<p>PHONE: '.$_POST['phone'].'</p>';
            $body += '<p>CURRENT OCCUPATION: '.$_POST['occ'].'</p>';
            $body += '<p>NUMBER OF YEARS: '.$_POST['noy'].'</p>';
            $body += '<br>';
            $str = $_POST['dyoab'] == 1 ? 'Yes' : 'No';
            $body += '<p>DO YOU OWN A BUSINESS?: '.$str.'</p>';
            $body += '<p>IF YES,explain:'.$_POST['dyoab_ex'].'</p>';
            $income = array('Up to $50K','Over $50K to $75K','Over $75K to $120K','Over $120K');
            $body += '<p>'.$income[$_POST[cai]-1].'</p>';
            $body += '<br>';
            $body += '<p>NET WORTH: '.$_POST['net_worth'].'</p>';
            $body += '<p>If you go into business, what amount do you plan to invest?: '.$_POST['invest'].'</p>';
            $body += '<p>Your own capital: '.$_POST['capital'].'</p>';
            $body += '<p>Borrowed: '.$_POST['borrowed'].'</p>';
            $body += '<br>';
            $str = $_POST['dyoyh'] == 1 ? 'Yes' : 'No';
            $body += '<p>Do you own your home?: '.$str.'</p>';
            $str = $_POST['mortgage'] == 1 ? 'Yes' : 'No';
            $body += '<p>Mortgage?: '.$str.'</p>';
            $str = $_POST['hyegb'] == 1 ? 'Yes' : 'No';
            $body += '<p>Have you ever gone bankrupt?: '.$str.'</p>';
            $body += '<p>If you decide to move forward when can you start?: '.$_POST['when_start'].'</p>';
            $body += '<br>';
            $body += '<p>REFERENCES & CONTACT INFO</p>';
            $body += '<p>1. '.$_POST['raci_1'].'</p>';
            $body += '<p>2. '.$_POST['raci_2'].'</p>';
            $body += '<p>3. '.$_POST['raci_3'].'</p>';
            $body += '<br>';
            $body += '<p>COMMENTS: '.$_POST['comments'].'</p>';


            require './mailer/PHPMailer.php';
            require './mailer/SMTP.php';
            require './mailer/Exception.php';

            $mail = new PHPMailer\PHPMailer\PHPMailer();

            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers. 这里改成smtp.gmail.com
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'caesark882@gmail.com';                 // SMTP username 这里改成自己的gmail邮箱，最好新注册一个，因为后期设置会导致安全性降低
            $mail->Password = 'kkrzakbtivctdtdm';                           // SMTP password 这里改成对应邮箱密码
            $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 465;

            $mail->setFrom('caesark882@gmail.com', 'Caesark');
            $mail->addAddress('garfunkel@126.com', 'Garfunkel');
            $mail->addAddress('jheary@tutti.app', 'Heary');
            //$mail->addAddress('ellen@example.com');               // Name is optional
            //$mail->addReplyTo('info@example.com', 'Information');
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('bcc@example.com');

            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
            $mail->isHTML(true);
            $mail->Subject = 'Application Form';
            $mail->Body    = $body;
            $mail->AltBody = '';

            if(!$mail->send()) {
                //echo 'Message could not be sent.';
                //echo 'Mailer Error: ' . $mail->ErrorInfo;
                exit(json_encode(array('status'=>0)));
            } else {
                //echo 'Message has been sent';
                exit(json_encode(array('status'=>1)));
            }

            //exit(json_encode(array('status'=>1)));
        }else {
            $this->display();
        }
    }
}