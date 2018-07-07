<?php
/*
 * 社区首页
 *
 */
class RepairAction extends BaseAction{
	protected $village_id;
	protected $village;
	
	public function _initialize(){
		parent::_initialize();
	
		$this->village_id = $this->house_session['village_id'];
		$this->village = D('House_village')->where(array('village_id'=>$this->village_id))->find();
		if(empty($this->village)){
			$this->error('该小区不存在！');
		}
	}
	
    public function index()
    {
		if($find_type = $_GET['find_type'] + 0){
			switch($find_type){
				case 1:
					$where['usernum'] = $_GET['find_value'];
					break;
				case 2:
					$where['phone'] = $_GET['find_value'];
					break;
				case 3:
					$where['address'] = $_GET['find_value'];
					break;
				default:
					break;
			}
		}


		$status = $_GET['status'] + 0;
		if($status > 0){
			$where['status'] = $status - 1;
		}

		$begin_time = 0;
		$end_time = 0;
		if(isset($_GET['begin_time']) && !empty($_GET['begin_time'])){
			$begin_time = strtotime($_GET['begin_time'] . '00:00:00');
		}

		if(isset($_GET['end_time']) && !empty($_GET['end_time'])){
			$end_time = strtotime($_GET['end_time'] . '23:59:59');
		}

		if(($begin_time > 0) && ($end_time > 0)){
			if($begin_time > $end_time){
				$this->error("结束时间应大于开始时间");
			}

			$where['begin_time'] = $begin_time;
			$where['end_time'] = $end_time;
		}elseif(isset($begin_time)){
			$where['begin_time'] = $begin_time;
		}elseif(isset($end_time)){
			$where['end_time'] = $end_time;
		}

		$where['village_id'] = $this->village_id;
		$where['type'] = 1;

		$order ='';
		if($_GET['time']){
			$order['time'] = $_GET['time'];
		}


		$repair_list = D('House_village_repair_list')->getlist($where , 20 , $order);
		//print_r($repair_list);
		$this->assign('repair_list', $repair_list);
		$this->display();
    }
	
    public function water()
    {
		$repair_list = D('House_village_repair_list')->getlist(array('village_id' => $this->village_id,'type'=>2));
		$this->assign('repair_list', $repair_list);
		$this->display();
    }
    
    public function suggess(){
    	$village_id = $this->village_id;
    	if($village_id){
    		$repair_list = D('House_village_repair_list')->getlist(array('village_id'=>$village_id,'type'=>3));
    		$this->assign('repair_list',$repair_list);
    	}
    	$this->display();
    }
    
    public function do_repair(){
    	if(IS_AJAX){
    		$village_id = $this->village_id;
    		$bind_id = $_POST['bind_id']?intval($_POST['bind_id']):0;
    		$cms_id = $_POST['cid']?intval($_POST['cid']):0;
    		if($bind_id && $village_id){
    			$data['village_id'] = $this->village_id;
    			$data['bind_id'] = $bind_id;
    			$data['pigcms_id'] = $cms_id;
    			
    			$result = D('House_village_repair_list')->where($data)->data(array('is_read'=>1))->save();
    			if($result !== false){
    				$this->ajaxReturn(array('error'=>0));
    			}
    			
    			$this->ajaxReturn(array('msg'=>'处理失败请重试','error'=>1));
    		}else{
    			$this->ajaxReturn(array('msg'=>'信息有误','error'=>1));
    		}
    		exit;
    	}else{
    		$this->display();
    	}
    }
    
    public function info()
    {
    	$bind_id = isset($_GET['bindid']) ? intval($_GET['bindid']) : 0;
    	$cms_id = isset($_GET['pid']) ? intval($_GET['pid']) : 0;
    	if ($bind_id && $cms_id) {
    		$condition['bind_id'] = $bind_id;
    		$condition['pigcms_id'] = $cms_id;
    		$condition['village_id'] = $this->village_id;
    		$repair = D('House_village_repair_list')->getlist($condition, 1);
			$repair = $repair['repair_list'][0];
			
    		$this->assign('repair', $repair);
    		if ($repair['r_status']) {
    			$worker = D('House_worker')->field(true)->where(array('wid' => $repair['wid'], 'village_id' => $this->village_id))->find();
    			$this->assign('worker', $worker);
    		} else {
	    		$type = $repair['r_type'] == 1 ? 1 : 0;
		    	$workers = D('House_worker')->field(true)->where(array('type' => $type, 'status' => 1, 'village_id' => $this->village_id))->select();
		    	$this->assign('workers', $workers);
    		}
    	}
    	$this->display();
    }
    
    
    public function village_suggest()
    {
		if($find_type = $_GET['find_type'] + 0){
			switch($find_type){
				case 1:
					$where['usernum'] = $_GET['find_value'];
					break;
				case 2:
					$where['phone'] = $_GET['find_value'];
					break;
				case 3:
					$where['address'] = $_GET['find_value'];
					break;
				default:
					break;
			}
		}


		$status = $_GET['status'] + 0;
		if($status > 0){
			$where['status'] = $status - 1;
		}

		$begin_time = 0;
		$end_time = 0;
		if(isset($_GET['begin_time']) && !empty($_GET['begin_time'])){
			$begin_time = strtotime($_GET['begin_time'] . '00:00:00');
		}

		if(isset($_GET['end_time']) && !empty($_GET['end_time'])){
			$end_time = strtotime($_GET['end_time'] . '23:59:59');
		}

		if(($begin_time > 0) && ($end_time > 0)){
			if($begin_time > $end_time){
				$this->error("结束时间应大于开始时间");
			}

			$where['begin_time'] = $begin_time;
			$where['end_time'] = $end_time;
		}elseif(isset($begin_time)){
			$where['begin_time'] = $begin_time;
		}elseif(isset($end_time)){
			$where['end_time'] = $end_time;
		}

		$where['village_id'] = $this->village_id;
		$where['type'] = 3;

		$order ='';
		if($_GET['time']){
			$order['time'] = $_GET['time'];
		}

		$database_house_village_repair_list = D('House_village_repair_list');
		$repair_list = $database_house_village_repair_list->getlist( $where , 20 , $order );
		

		$this->assign('repair_list', $repair_list);
        $this->display();
    }
    
    
    public function ajax_suggest_reply()
    {
        if (IS_AJAX) {
            $pigcms_id = isset($_POST['pigcms_id']) ? intval($_POST['pigcms_id']) : 0;
            $worker_id = isset($_POST['worker_id']) ? intval($_POST['worker_id']) : 0;
//             $reply_content = $this->_post('reply_content');
            $database_house_village_repair_list = D('House_village_repair_list');
            $repair = $database_house_village_repair_list->field(true)->where(array('pigcms_id' => $pigcms_id, 'village_id' => $this->village_id))->find();
            if (empty($repair)) {
                exit(json_encode(array('status' => 0,'msg' => '传递参数有误！')));
            }
            $worker = D('House_worker')->field(true)->where(array('wid' => $worker_id, 'village_id' => $this->village_id))->find();
            if (empty($worker)) {
                exit(json_encode(array('status' => 0, 'msg' => '工作人员不能为空！')));
            }
            
            $data['wid'] = $worker_id;
            $data['status'] = 1;
            $where['village_id'] = $this->village_id;
//             $data['reply_time'] = time();
//             $data['is_read'] = 1;
            $where['pigcms_id'] = $pigcms_id;
			if ($database_house_village_repair_list->where($where)->save($data)) {
				D('House_village_repair_log')->add_log(array('status' => 1, 'repair_id' => $pigcms_id, 'phone' => $worker['phone'], 'name' => $worker['name']));
				exit(json_encode(array('status'=>1,'msg'=>'提交成功！')));
			} else {
                exit(json_encode(array('status'=>0,'msg'=>'提交失败！')));
            }
        }else{
            $this->error_tips('访问页面有误！请重试~');
        }
    }


	public function repair_export(){
		if($find_type = $_GET['find_type'] + 0){
			switch($find_type){
				case 1:
					$where['usernum'] = $_GET['find_value'];
					break;
				case 2:
					$where['phone'] = $_GET['find_value'];
					break;
				case 3:
					$where['address'] = $_GET['find_value'];
					break;
				default:
					break;
			}
		}


		$status = $_GET['status'] + 0;
		if($status > 0){
			$where['status'] = $status - 1;
		}

		$begin_time = 0;
		$end_time = 0;
		if(isset($_GET['begin_time']) && !empty($_GET['begin_time'])){
			$begin_time = strtotime($_GET['begin_time'] . '00:00:00');
		}

		if(isset($_GET['end_time']) && !empty($_GET['end_time'])){
			$end_time = strtotime($_GET['end_time'] . '23:59:59');
		}

		if(($begin_time > 0) && ($end_time > 0)){
			if($begin_time > $end_time){
				$this->error("结束时间应大于开始时间");
			}

			$where['begin_time'] = $begin_time;
			$where['end_time'] = $end_time;
		}elseif(isset($begin_time)){
			$where['begin_time'] = $begin_time;
		}elseif(isset($end_time)){
			$where['end_time'] = $end_time;
		}

		$where['village_id'] = $this->village_id;

		$type = $_GET['type'] + 0;
		$where['type'] = $type;

		$repair_list = D('House_village_repair_list')->getlist($where,1000);
		$repair_list = $repair_list['repair_list'];

		if(count($repair_list) <= 0 ){
			$this->error('无数据导出！');
		}

		require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';

		if($type == 1){
			$title = $this->village['village_name'] . '社区-在线报修列表';
		}elseif($type == 3){
			$title = $this->village['village_name'] . '社区-投诉列表';
		}

		$objExcel = new PHPExcel();
		$objProps = $objExcel->getProperties();
		// 设置文档基本属性
		$objProps->setCreator($title);
		$objProps->setTitle($title);
		$objProps->setSubject($title);
		$objProps->setDescription($title);

		$length = ceil(count($repair_list)/1000);

		for ($i = 0; $i < $length; $i++) {
			$i && $objExcel->createSheet();
			$objExcel->setActiveSheetIndex($i);

			$objExcel->getActiveSheet()->setTitle('第' . ($i+1) . '个一千个用户');
			$objActSheet = $objExcel->getActiveSheet();

			$objActSheet->setCellValue('A1', '业主编号');
			$objActSheet->setCellValue('B1', '报修人');
			$objActSheet->setCellValue('C1', '联系号码');
			$objActSheet->setCellValue('D1', '状态');
			$objActSheet->setCellValue('E1', '报修内容');
			$objActSheet->setCellValue('F1', '报修时间');
			$objActSheet->setCellValue('G1', '报修地址');
			$objActSheet->setCellValue('H1', '处理人员');
			$objActSheet->setCellValue('I1', '处理人员手机号码');
			$objActSheet->setCellValue('J1', '回复内容');
			$objActSheet->setCellValue('K1', '回复时间');
			$objActSheet->setCellValue('L1', '评论内容');
			$objActSheet->setCellValue('M1', '评论时间');

			if (!empty($repair_list)) {
				$index = 2;

				$cell_list = range('A','M');
				foreach ($cell_list as $cell) {
					$objActSheet->getColumnDimension($cell)->setWidth(40);
				}

				if($type == 1){
					foreach ($repair_list as $value) {
						$objActSheet->setCellValueExplicit('A' . $index, $value['usernum']);
						$objActSheet->setCellValueExplicit('B' . $index, $value['name']);
						$objActSheet->setCellValueExplicit('C' . $index, $value['phone']);

						if($value['status'] == 0){
							$status_val = '未指派';
						}elseif($value['status'] == 1){
							$status_val = '已指派';
						}elseif($value['status'] == 2){
							$status_val = '已受理';
						}elseif($value['status'] == 3){
							$status_val = '已处理';
						}elseif($value['status'] == 4){
							$status_val = '业主已评价';
						}
						$objActSheet->setCellValueExplicit('D' . $index, $status_val);
						$objActSheet->setCellValueExplicit('E' . $index, $value['content']);
						$objActSheet->setCellValueExplicit('F' . $index, date('Y-m-d H:i:s',$value['time']));
						$objActSheet->setCellValueExplicit('G' . $index, $value['address']);

						$bind_id = $value['bind_id'];
						$cms_id = $value['pid'];
						if ($bind_id && $cms_id) {
							$condition['bind_id'] = $bind_id;
							$condition['pigcms_id'] = $cms_id;
							$condition['village_id'] = $this->village_id;
							$repair = D('House_village_repair_list')->getlist($condition, 1);
							$repair = $repair['repair_list'][0];

							if ($repair['status']) {
								$worker = D('House_worker')->field(true)->where(array('wid' => $repair['wid'], 'village_id' => $this->village_id))->find();
							}

							$objActSheet->setCellValueExplicit('H' . $index, $worker['name']);
							$objActSheet->setCellValueExplicit('I' . $index, $worker['phone']);
							$objActSheet->setCellValueExplicit('J' . $index, $repair['reply_content']);
							$objActSheet->setCellValueExplicit('K' . $index, $repair['reply_time']>0 ? date('Y-m-d H:i:s',$repair['reply_time']) : "");
							$objActSheet->setCellValueExplicit('L' . $index, $repair['comment']);
							$objActSheet->setCellValueExplicit('M' . $index, $repair['comment_time']>0 ? date('Y-m-d H:i:s',$repair['comment_time']) : "");
						}

						$index++;
					}
				}elseif($type == 3){

					foreach ($repair_list as $value) {
						$objActSheet->setCellValueExplicit('A' . $index, $value['usernum']);
						$objActSheet->setCellValueExplicit('B' . $index, $value['name']);
						$objActSheet->setCellValueExplicit('C' . $index, $value['phone']);

						if($value['status'] == 0){
							$status_val = '未受理';
						}elseif($value['status'] == 1){
							$status_val = '物业已受理';
						}elseif($value['status'] == 2){
							$status_val = '客服专员已受理';
						}elseif($value['status'] == 3){
							$status_val = '客服专员已处理';
						}elseif($value['status'] == 4){
							$status_val = '业主已评价';
						}

						$objActSheet->setCellValueExplicit('D' . $index, $status_val);
						$objActSheet->setCellValueExplicit('E' . $index, $value['content']);
						$objActSheet->setCellValueExplicit('F' . $index, date('Y-m-d H:i:s',$value['time']));
						$objActSheet->setCellValueExplicit('G' . $index, $value['address']);


						$bind_id = $value['bind_id'];
						$cms_id = $value['pid'];
						if ($bind_id && $cms_id) {
							$condition['bind_id'] = $bind_id;
							$condition['pigcms_id'] = $cms_id;
							$condition['village_id'] = $this->village_id;
							$repair = D('House_village_repair_list')->getlist($condition, 1);
							$repair = $repair['repair_list'][0];

							if ($repair['status']) {
								$worker = D('House_worker')->field(true)->where(array('wid' => $repair['wid'], 'village_id' => $this->village_id))->find();
							}

							$objActSheet->setCellValueExplicit('H' . $index, $worker['name']);
							$objActSheet->setCellValueExplicit('I' . $index, $worker['phone']);
							$objActSheet->setCellValueExplicit('J' . $index, $repair['reply_content']);
							$objActSheet->setCellValueExplicit('K' . $index, $repair['reply_time']>0 ? date('Y-m-d H:i:s',$repair['reply_time']) : "");
							$objActSheet->setCellValueExplicit('L' . $index, $repair['comment']);
							$objActSheet->setCellValueExplicit('M' . $index, $repair['comment_time']>0 ? date('Y-m-d H:i:s',$repair['comment_time']) : "");
						}

						$index++;
					}

				}
			}
			sleep(2);
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
			header('Content-Disposition:attachment;filename="'.$title.'_' . date("Y-m-d h:i:sa", time()) . '.xls"');
			header("Content-Transfer-Encoding:binary");
			$objWriter->save('php://output');
			exit();
		}
	}
}