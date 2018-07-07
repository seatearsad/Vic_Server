<?php
class House_village_pay_orderModel extends Model{
	/*得到小区的新闻列表*/
	public function get_limit_list_page($column,$pageSize=20,$isSystem=false){
		if(!$column['village_id']){
			return null;
		}

    	$condition_table  = array(C('DB_PREFIX').'house_village_pay_order'=>'o',C('DB_PREFIX').'house_village_user_bind'=>'b');
    	$condition_where = " `o`.`village_id` = `b`.`village_id`  AND `o`.`bind_id` = `b`.`pigcms_id`  AND `o`.`village_id` =".$column['village_id'];
    	
    	
    	if($column['paid']){
    		$condition_where .= " AND `o`.`paid`= ".intval($column['paid']);
    	}
		if($column['pay_type']){
    		$condition_where .= " AND `o`.`pay_type`=0";
    	}
    	if($column['phone']){
			$condition_where .= " AND `b`.`phone` like '%".$column['phone']."%'";
		}
		if($column['order_name']){
			$condition_where .= " AND `o`.`order_name` like '%".$column['order_name']."%'";
		}

		if(isset($column['is_pay_bill'])){
			$condition_where .= " AND `o`.`is_pay_bill`= ".intval($column['is_pay_bill']);
		}

		if($column['pay_time_str']){
			$condition_where .= ' AND '.$column['pay_time_str'];
		}

    	$condition_field = '`b`.`name` AS `username` ,b.*,o.*';
    	$order = ' `o`.`order_id` DESC, `o`.`paid` ASC';
    	if($isSystem){
    		import('@.ORG.system_page');
    	}else{
    		import('@.ORG.merchant_page');
    	}
    	$count_order = D('')->table($condition_table)->where($condition_where)->count();
    	$p = new Page($count_order,$pageSize,'page');
    	$order_list = D('')->field($condition_field)->table($condition_table)->where($condition_where)->order($order)->limit($p->firstRow.','.$p->listRows)->select();
		$database_house_village_property_paylist = D('House_village_property_paylist');
		$pay_list = $database_house_village_property_paylist->where(array('village_id'=>$column['village_id']))->select();

		if(!empty($pay_list)){
			foreach($order_list as $Key=>$order){
				foreach($pay_list as $pay_info){
					if($order['order_id'] ==  $pay_info['order_id']){
						$order_list[$Key]['property_time_str'] = date('Y-m-d',$pay_info['start_time']) . '至' . date('Y-m-d',$pay_info['end_time']);
					}
				}
			}
		}


    	$total = D('')->field(' SUM(`o`.`money` ) AS totalMoney ')->table($condition_table)->where($condition_where)->find();
    	$already = D('')->field(' SUM(`o`.`money` ) AS readyMoney ')->table($condition_table)->where($condition_where." AND `o`.`is_pay_bill`=1 ")->find();
    	
    	$return['pagebar'] = $p->show();
    	$return['order_list'] = $order_list;
    	$return['totalMoney'] = $total;
    	$return['readyMoney'] = $already;
    	
    	return $return;
	}


	public function get_one($order_id){
		if(!$order_id){
			return false;
		}

		return $this->where(array('order_id'=>$order_id))->find();
	}
	
}