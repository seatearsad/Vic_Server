<?php

class House_village_user_bindModel extends Model
{
    /*通过手机号自动绑定业主*/
    public function bind($uid, $phone)
    {
        $this->data(array('uid' => $uid))->where(array('phone' => $phone))->save();
    }

    public function get_user_bind_list($uid, $village_id)
    {
        $bind_list = $this->field(true)->where(array('uid' => $uid, 'village_id' => $village_id, 'parent_id' => 0,'status'=>1))->order('`pigcms_id` DESC')->select();
        return $bind_list;
    }


    public function get_family_user_bind_list($uid, $village_id)
    {
        $bind_list = $this->where(array('uid' => $uid, 'village_id' => $village_id , 'status'=>1, 'parent_id' => array('neq',0)))->order('`pigcms_id` DESC')->select();

        foreach($bind_list as &$val){
            $val['address'] = $this->where(array('pigcms_id'=>$val['parent_id']))->getField('address');
        }

        return $bind_list;
    }

    /*得到小区下所有的业主列表*/
    public function get_limit_list_page($village_id, $pageSize = 20, $condition_where = array())
    {
        if (!$village_id) {
            return null;
        }

        $return = array();
        $condition_where['village_id'] = $village_id;
        $condition_where['parent_id'] = 0;
        $count_user = $this->where($condition_where)->count();

        import('@.ORG.merchant_page');
        $p = new Page($count_user, $pageSize, 'page');
        $user_list = $this->field(true)->where($condition_where)->order('`pigcms_id` DESC')->limit($p->firstRow . ',' . $p->listRows)->select();

        $database_house_village_pay_order = D('House_village_pay_order');
        foreach($user_list as $Key=>$user){
            if($user['floor_id']){
                $floor_type = D('House_village_floor')->where(array('floor_id'=>$user['floor_id']))->getField('floor_type');
                $user_list[$Key]['floor_type_name'] = D('House_village_floor_type')->where(array('id'=>$floor_type))->getField('name');
            }

            $property_month_time_arr = $database_house_village_pay_order->where(array('village_id'=>$village_id,'paid'=>1,'uid'=>$user['uid']))->field('(property_month_num+presented_property_month_num) as property_month_time')->select();
            $user_list[$Key]['openid'] = D('User')->where(array('uid'=>$user['uid']))->getField('openid');
            $property_month_time = 0;
            foreach($property_month_time_arr as $row){
                $property_month_time += $row['property_month_time'];
            }
            $user_list[$Key]['property_month_time'] = $user['add_time'] + $property_month_time * 30 * 24 * 3600;

            $condition['parent_id'] = $user['pigcms_id'];
            $bind_list = $this->where($condition)->select();
            if($bind_list){
                $user_list[$Key]['bind_list'] = $bind_list;
            }
        }


        if ($user_list) {
            $return['totalPage'] = ceil($count_user / $pageSize);
            $return['user_count'] = count($user_list);
            $return['pagebar'] = $p->show();
            $return['user_list'] = $user_list;
        }

        return $return;
    }

    /*得到单个业主信息*/
    public function get_one($village_id, $value, $field = 'uid', $bind_uid = 0)
    {
        $condition_user['village_id'] = $village_id;
        $condition_user[$field] = $value;
        $now_user = $this->field(true)->where($condition_user)->find();
        // dump($this);
        if (!empty($now_user)) {
            $now_user['water_price'] = floatval($now_user['water_price']);
            $now_user['electric_price'] = floatval($now_user['electric_price']);
            $now_user['gas_price'] = floatval($now_user['gas_price']);
            $now_user['park_price'] = floatval($now_user['park_price']);
            $now_user['property_price'] = floatval($now_user['property_price']);
            if ($bind_uid) {
                $this->where(array('pigcms_id' => $now_user['pigcms_id']))->data(array('uid' => $bind_uid))->save();
            }
        }
        return $now_user;
    }

    /*得到单个业主信息*/
    public function get_one_by_bindId($pigcms_id)
    {
        $condition_user['pigcms_id'] = $pigcms_id;
        $now_user = $this->field(true)->where($condition_user)->find();
        if (!empty($now_user)) {
            $now_user['water_price'] = floatval($now_user['water_price']);
            $now_user['electric_price'] = floatval($now_user['electric_price']);
            $now_user['gas_price'] = floatval($now_user['gas_price']);
            $now_user['park_price'] = floatval($now_user['park_price']);
            $now_user['property_price'] = floatval($now_user['property_price']);
            /*if ($bind_uid) {
                $this->where(array('pigcms_id' => $now_user['pigcms_id']))->data(array('uid' => $bind_uid))->save();
            }*/

            if($now_user['parent_id']){
                $address = $this->where(array('pigcms_id'=>$now_user['parent_id']))->getField('address');
                $now_user['address'] = $address;
            }

        }
        return $now_user;
    }

    /*得到小区下所有的业主列表(绑定微信的)*/
    public function get_limit_list_open($village_id, $pageSize = 5)
    {
        if (!$village_id) {
            return null;
        }

        $return = array();

        $condition_table = array(C('DB_PREFIX') . 'house_village_user_bind' => 'b', C('DB_PREFIX') . 'user' => 'u');
        $condition_where = " b.uid = u.uid AND u.openid !='' AND b.uid>0 AND b.village_id=" . $village_id;
        $condition_field = ' distinct(u.openid), b.uid,u.openid ';
        // if($bigId !== 0){
        // $condition_where .= " AND b.pigcms_id<=".$bigId." AND b.pigcms_id>=".$smallId;
        // }
        $count_user = D('')->table($condition_table)->where($condition_where)->count('distinct(u.openid)');

        import('@.ORG.merchant_page');
        $p = new Page($count_user, $pageSize, 'page');
        $user_list = D('')->table($condition_table)->field($condition_field)->where($condition_where)->order('`b`.`pigcms_id` DESC')->limit($p->firstRow . ',' . $p->listRows)->select();

        if ($user_list) {
            $return['totalPage'] = ceil($count_user / $pageSize);
            $return['user_count'] = count($user_list);
            $return['pagebar'] = $p->show();
            $return['user_list'] = $user_list;
        }

        return $return;
    }

    /*得到小区下所有欠费业主列表(绑定微信的)*/
    public function get_pay_list_open($village_id, $pageSize = 20, $bigId = 0, $smallId = 0)
    {
        if (!$village_id) {
            return null;
        }

        $return = array();

        $condition_table = array(C('DB_PREFIX') . 'house_village_user_paylist' => 'b', C('DB_PREFIX') . 'user' => 'u');
        $condition_where = " b.uid = u.uid 	AND  u.openid !='' AND b.uid>0 AND b.village_id=" . $village_id;
        $condition_field = ' distinct(u.openid), b.uid,u.openid,b.address ';
        if ($bigId !== 0) {
            $condition_where .= " AND b.pigcms_id<=" . $bigId . " AND b.pigcms_id>=" . $smallId;
        }
        $count_user = D('')->table($condition_table)->where($condition_where)->count('distinct(u.openid)');

        import('@.ORG.merchant_page');
        $p = new Page($count_user, $pageSize, 'page');
        $user_list = D('')->table($condition_table)->field($condition_field)->where($condition_where)->order('`b`.`pigcms_id` DESC')->limit($p->firstRow . ',' . $p->listRows)->select();

        if ($user_list) {
            $return['totalPage'] = ceil($count_user / $pageSize);
            $return['user_count'] = count($user_list);
            $return['pagebar'] = $p->show();
            $return['user_list'] = $user_list;
        }

        return $return;
    }

    /*绑定家属*/
    public function house_village_my_bind_family_add($data)
    {
        if (!$data) {
            return array('status' => 0, 'msg' => '传递参数有误！');
        }

        $where['uid'] = $data['uid'];
        $where['phone'] = $data['phone'];
        $where['parent_id'] = $data['parent_id'];
        $count = $this->where($where)->count();
        if ($count > 0) {
            return array('status' => 0, 'msg' => '该手机号已经绑定');
        }

        $insert_id = $this->data($data)->add();
        if ($insert_id) {
            $sms_data['uid'] = $data['uid'];
            $sms_data['mobile'] = $data['phone'];
            $sms_data['sendto'] = 'user';
            $sms_data['type'] = 'bind_family';
            $sms_data['content'] = '手机号：' . $_SESSION['user']['phone'] . '已成功将您绑定为其家属！';
            //Sms::sendSms($sms_data);

            return array('status' => 1, 'msg' => '绑定成功');
        } else {
            return array('status' => 0, 'msg' => '绑定失败');
        }
    }

    /*家属列表*/
    public function house_village_my_bind_family_list($where)
    {
        if (!$where) {
            return false;
        }

        $field = array('pigcms_id', 'name', 'phone');
        $list = $this->where($where)->field($field)->select();
        if ($list) {
            return $list;
        } else {
            return false;
        }
    }


    //获取被绑定家属信息
    public function get_village_family_list($where)
    {
        if (!$where) {
            return false;
        }

        $info = $this->where($where)->select();
        print_r($info);
        exit;
    }


    //获取单个业主信息
    public function house_village_user_bind_detail($where, $field = true)
    {
        if (!$where) {
            return false;
        }

        $info = $this->field($field)->where($where)->find();

        if (!$info) {
            return array('status' => 0, 'info' => '没有查到相关业主');
        } else {
            return array('status' => 1, 'info' =>$info);
        }
    }
	
	//查询用户申请小区房间亲属/租客信息 - wangdong
	public function get_my_room_not_master($uid , $type , $room_str=""){
		
		$where = "`uid`=".$uid;
		if(!empty($room_str)) $where .= " AND vacancy_id not in ($room_str)"; //$condition['vacancy_id'] = array('not in' , $room_str);
		$where .= " AND ((type in ($type)) or (type=3 AND status in (0,2)))";
		
		$lists = $this->field(true)->where($where)->order('village_id DESC,floor_id ASC,parent_id ASC')->select();
		
		return $lists;
	}
	
	//业主下面的亲戚/租客列表 - wangdong
	public function get_my_room_user($pigcms_id){
	
		$condition['vacancy_id'] = $pigcms_id;
		$condition['status'] = array('in' , "1,2");
		$condition['type'] = array('in' , '1,2');
		$lists = $this->field(true)->where($condition)->select();
		return $lists;
		
	}
	
	
	
	
}

?>