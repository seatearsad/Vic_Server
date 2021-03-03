<?php
class System_couponModel extends Model{
    public function get_qrcode($id){
        $condition_store['coupon_id'] = $id;
        $qrcode_id = $this->field('`coupon_id`,`qrcode_id`')->where($condition_store)->find();
        if(empty($qrcode_id)){
            return false;
        }
        return $qrcode_id;
    }
    //保存优惠券二维码
    public function save_qrcode($id,$qrcode_id){
        $coupon_where['coupon_id'] = $id;
        $data_coupon['qrcode_id'] = $qrcode_id;
        if($this->where($coupon_where)->data($data_coupon)->save()){
            return(array('error_code'=>false));
        }else{
            return(array('error_code'=>true,'msg'=>'保存二维码至平台优惠券失败！请重试。'));
        }
    }

	public function get_coupon($coupon_id){
		$coupon = $this->field(true)->where(array('coupon_id'=>$coupon_id))->find();
		return $coupon;
	}


    //根据已领表id获取优惠券信息,不筛选时间等条件 链表查询
    public function get_coupon_info($coupon_id){
        $where['h.id']=$coupon_id;
        $where['h.is_use']= 0;
        $res = M('System_coupon_hadpull')->join('as h left join '.C('DB_PREFIX').'system_coupon c ON h.coupon_id=c.coupon_id')->field('h.id,c.coupon_id,c.name,c.des,c.des_detial,c.had_pull,c.num,c.limit,c.use_limit,c.order_money,c.discount as price,c.discount')->where($where)->find();
        return $res;
    }

    //获取该手机号码领取的优惠券数量
    public function get_coupon_count_by_phone($coupon_id,$phone){
        $where['coupon_id'] = $coupon_id;
        $where['phone'] = $phone;
        return M('System_coupon_hadpull')->where($where)->count();

    }

    //获取该手机号码领取的优惠券数量
    public function get_coupon_category_by_phone($uid){
        $where['uid'] = $uid;
        return M('System_coupon_hadpull')->where($where)->group('coupon_id')->select();

    }

    public function get_coupon_by_id($id){
        $where['c.end_time'] = array('gt',time());
        $where['c.status'] = array('neq',0);  //状态正常
        $where['h.id']=$id;
        $res = M('System_coupon_hadpull')->join('as h left join '.C('DB_PREFIX').'system_coupon c ON h.coupon_id=c.coupon_id')->field('h.id,c.coupon_id,h.phone,c.end_time,h.is_use ,c.discount as price,c.discount')->where($where)->find();
        return $res;
    }

    //获取适用不同分类的优惠券 接口
    public function get_coupon_list_by_type($type,$cat_id,$limit=6,$is_new=-1){
        if(!empty($type)){
            $where['cate_name'] = array(array('eq',$type),array('eq','all'), 'or');
        }
        if($is_new!=-1){
            $where['allow_new'] = $is_new;
        }
        $where['end_time'] = array('gt',time());
        $where['status'] = 1;
        $where['start_time'] = array('lt',time());
        $res = $this->where($where)->order('allow_new DESC,discount DESC')->limit($limit)->getField('coupon_id,name,img,had_pull,num,des,cate_name,cate_id,discount,order_money,start_time,end_time,status,allow_new');

        foreach($res as $key=>&$v){
            $v['cate_id'] = unserialize($v['cate_id']);
            if($cat_id!=0){
                if($v['cate_id']['cat_id']!=$cat_id) {
                    unset($res[$key]);
                    continue;
                }
            }
            $v['url'] = C('config.config_site_url').'/coupon/'.$v['coupon_id'].'.html';
        }
        return $res;
    }

    //获取可以领取的优惠券种类
    public function get_coupon_list(){
        $where['end_time'] = array('gt',time());
        $where['status'] = array('neq',0);
        //$where['start_time'] = array('lt',time());
        $res = $this->where($where)->order('coupon_id DESC,status ASC,allow_new DESC,discount DESC')->getField('coupon_id,name,img,had_pull,num,des,cate_name,cate_id,discount,order_money,start_time,end_time,last_time,status,allow_new,platform,wx_cardid,wx_ticket_addtime,cardsign');
        return $res;
    }

    public function  get_coupon_list_by_ids($ids){
        $where['coupon_id']=array('in',$ids);
        $res = $this->where($where)->getField('coupon_id,name,img,had_pull,num,des,cate_name,cate_id,discount,order_money,start_time,end_time,status,allow_new');
        return $res;
    }
    public function get_user_coupon_list($uid,$phone,$is_use='' ){
        //如果不是新用户 删除所有新用户使用的优惠券
        $is_new = D('User')->check_new($uid,'all');
        if(!$is_new){
            $list = D('System_coupon')->where(array('allow_new'=>1,'status'=>1))->select();

            foreach ($list as $c){
                D('System_coupon_hadpull')->where(array('uid'=>$uid,'coupon_id'=>$c['coupon_id']))->delete();
            }
        }

        //$where['c.end_time'] = array('gt',time());
        $where['c.status'] = array('neq',0);  //状态正常
        $where['h.uid'] = $uid;
        if(!empty($is_use)){
            if($is_use==1){
                $where['h.is_use'] = 0;
                $where['c.end_time'] = array('gt',time());
            }
        }
        $n = 1;
        $cate_platform = $this->cate_platform();
        $res = M('System_coupon_hadpull')->join('as h left join '.C('DB_PREFIX').'system_coupon c ON h.coupon_id=c.coupon_id')->field('h.id,h.use_time,c.cate_name as type,c.coupon_id,c.name,c.discount,h.phone,h.receive_time,c.platform,c.cate_name,c.cate_id,c.start_time,c.end_time,h.is_use,c.status,c.qrcode_id,c.des,c.des_detial,c.img,c.allow_new,c.order_money')->order('h.is_use ASC ,c.add_time DESC')->where($where)->select();

        foreach($res as &$v){
            if(empty($v['uid'])){
                M('System_coupon_hadpull')->where(array('id'=>$v['id']))->setField('uid',$uid);
            }
            if($v['is_use']==1&&$n==1){
                $v['line']=1;
                $n++;
            }
            $v['platform']=unserialize($v['platform']);
            if(!empty($v['cate_id'])) {
                $v['cate_id'] = unserialize($v['cate_id']);
            }
            foreach($v['platform'] as &$vv){
                $vv=$cate_platform['platform'][$vv];
            }
            if($v['cate_name']!='all') {
                $v['cate_name'] = $cate_platform['category'][$v['cate_name']];
            }
            $v['platform']=trim(implode(',',$v['platform']),',');
            if($v['end_time']<$_SERVER['REQUEST_TIME']&&$v['is_use']!=1){
                $v['is_use'] = 2;
            }

            if(C('DEFAULT_LANG') == 'zh-cn'){
                $v['discount_desc'] = replace_lang_str(L('_MAN_NUM_REDUCE_'),$v['order_money']).replace_lang_str(L('_MAN_REDUCE_NUM_'),$v['discount']);
            }else{
                $v['discount_desc'] = replace_lang_str(L('_MAN_NUM_REDUCE_'),$v['discount']).replace_lang_str(L('_MAN_REDUCE_NUM_'),$v['order_money']);
            }
        }
        return $res;
    }

    public function get_noworder_coupon_list($now_order,$order_type,$phone,$uid,$platform,$business_type){
        if($business_type){
            $order_type = $business_type;
            $now_order['total_money'] = $now_order['order_total_money'];
        }
        if($order_type == 'group'){
            $table = 'group';
        }else if($order_type == 'meal' || $order_type == 'food' || $order_type == 'foodPad' || $order_type == 'takeout'||$order_type=='foodshop'){
            $table = 'meal';
        }else if($order_type == 'appoint'){
            $table = 'appoint';
        }else if($order_type == 'shop' || $order_type == 'mall'){
            $table = 'shop';
        }else if($order_type == 'balance-appoint'){
            $table = 'appoint';
        }else if($order_type == 'store'){
            $table = 'store';
        }else{
            return array();
        }
        //$where['order_money'] = array('ELT',$now_order['total_money']);
        //garfunkel 修改优惠券选择金额
        //$where['order_money'] = array('ELT',$now_order['goods_price']);
        //$order_cate = D(ucfirst($table).'_order')->get_order_cate($now_order['order_id']);
        if($order_type!='store'){
            $order_cate = D(ucfirst($table).'_order')->get_order_cate($now_order['order_id']);
        }else{
            $order_cate =array('store');
        }

        $where['c.end_time'] = array('gt',time());
        $where['c.start_time'] = array('lt',time());
        $where['c.status'] = array('neq',0);  //状态正常
        $where['h.is_use'] = array('neq',1);  //状态正常

        $where['uid'] = $uid;
        $where['_string'] = "(c.cate_name='".$table."') OR (c.cate_name ='all')";
        $res = M('System_coupon_hadpull')->join('as h left join '.C('DB_PREFIX').'system_coupon c ON h.coupon_id=c.coupon_id')->field('h.id,c.coupon_id,c.name,c.order_money,c.discount,h.phone,h.receive_time,c.platform,c.cate_name,c.cate_id,c.start_time,c.end_time,h.is_use ,c.status,c.qrcode_id,c.des,c.des_detial,c.img,c.allow_new')->order('c.order_money Asc, h.is_use ASC ,c.discount DESC,c.add_time DESC')->where($where)->select();

        foreach($res as $key=>&$v){
            $flag = false;
            $v['platform']= unserialize($v['platform']);
            $v['cate_id'] = empty($v['cate_id'])?'0':unserialize($v['cate_id']);
            foreach($v['platform'] as $vp){
                if($vp==$platform) {
                   $flag = true;
                }
            }
            if(!$flag){
                unset($res[$key]);
                continue;
            }
            if(!empty($v['cate_id'])) {
                $cate_arr1 = array_diff($v['cate_id'],$order_cate);
                $cate_arr2 = array_diff($v['cate_id'],$order_cate);
                if(!empty($cate_arr1) || !empty($cate_arr2)){
                    unset($res[$key]);
                    continue;
                }
            }
            $cate_platform = $this->cate_platform();
            foreach($v['platform'] as &$vv){
                $vv=$cate_platform['platform'][$vv];
            }
            if($v['cate_name']!='all') {
                $v['cate_name'] = $cate_platform['category'][$v['cate_name']];
            }
            $v['platform']=trim(implode(',',$v['platform']),',');
            if($v['end_time']<$_SERVER['REQUEST_TIME']&&$v['is_use']!=1){
                $v['is_use'] = 2;
            }
            if($v['order_money'] < $now_order['goods_price']){
                $v['is_use'] = 1;
            }
        }//var_dump($res);die();
        return $res;
    }

    public function cate_platform(){
        $category=array('group'=>C('config.group_alias_name'),'meal'=>C('config.meal_alias_name'),'appoint'=>C('config.appoint_alias_name'),'shop'=>C('config.shop_alias_name'),'all'=>'All');
        $platform=array('wap'=>'WAP','app'=>'App','weixin'=>'WeChat');
        return array('category'=>$category,'platform'=>$platform);
    }

    //检查平台优惠券状态
    public function check_coupon($record_id, $mer_id, $uid,$refund = false)
    {
        $now_merchant = M('Merchant')->field(true)->where(array('mer_id'=>$mer_id,'status'=>'1'))->find();
        if(empty($now_merchant)){
            return array('error_code' => 1, 'msg' => '商家暂时歇业');
        }
        $condition_coupon_record = array('id' => $record_id, 'wecha_id' => $uid);
        if(empty($refund)){
            $condition_coupon_record['is_use'] = '0';
        }else{
            $condition_coupon_record['is_use'] = '1';
        }
        $now_coupon_record = M("System_coupon_hadpull")->field(true)->where($condition_coupon_record)->find();
        if (empty($now_coupon_record)) {
            return array('error_code' => 1, 'msg' => '优惠券不可用');
        }
        $now = time();
        $now_coupon = $this->field(true)->where(array('coupon_id' => $now_coupon_record['coupon_id'],'start_time'=>array('lt', $now),'end_time'=>array('gt', $now)))->find();
        if (empty($now_coupon_record)) {
            return array('error_code' => 1, 'msg' => '优惠券已被商家取消了');
        }
        return array('error_code' => 0, 'msg' => '优惠券可用','coupon'=>$now_coupon);
    }

    //消费平台优惠券记录在表中 record_id 是 hadpull 表中的id
    public function user_coupon($record_id,$order_id,$order_type, $mer_id, $uid)
    {
        $result = $this->check_coupon($record_id, $mer_id, $uid);
        if ($result['error_code']) {
            return $result;
        }
        $now = time();
        $result_ = M("System_coupon_hadpull")->where(array('id' => $record_id))->save(array('use_time' => $now, 'is_use' => '1'));
        if (empty($result_)) {
            return array('error_code' => 1, 'msg' => '优惠券使用失败');
        }
        $now_coupon_record = M("System_coupon_hadpull")->field(true)->where(array('id' => $record_id))->find();

        if($result['coupon']['is_wx_card']){
            import('ORG.Net.Http');
            $mode = D('Access_token_expires');
            $res = $mode->get_access_token();
            $wx_date['code'] = $now_coupon_record['wx_card_code'];
            $return = httpRequest('https://api.weixin.qq.com/card/code/consume?access_token=' . $res['access_token'], 'post', json_encode($wx_date, JSON_UNESCAPED_UNICODE));
            $return = json_decode($return[1], true);

        }
        $arr = array();
        $arr['coupon_id']  	= $now_coupon_record['coupon_id'];
        $arr['order_type']	= $order_type;
        $arr['order_id']	= $order_id;
        $arr['hadpull_id']	= $record_id;
        $arr['uid']	= $uid;
        $arr['num']	= 1;
        $arr['use_time']	= $now;

        M('System_coupon_use_list')->add($arr);
        return array('error_code' => 0, 'msg' => '优惠券使用成功');
    }

    //领取方法
    public function had_pull($coupon_id,$uid,$card_code,$admin_name=""){
        $where['coupon_id']=$coupon_id;
        $coupon = $this->get_coupon($coupon_id);
        $is_new = D('User')->check_new($uid,$coupon['cate_name']);

        if(empty($coupon)){
            return array('error_code'=>1,'coupon'=>$coupon,'msg'=>L('_NOT_EXCHANGE_CODE_'));
        }else if($coupon['allow_new']&&!$is_new){
            return array('error_code'=>4,'coupon'=>$coupon,'msg'=>L('_COUPON_ERROR_IS_NEW_'));
        }else if($coupon['end_time']<$_SERVER['REQUEST_TIME']){
            $this->where($where)->setField('status',2);
            return array('error_code'=>2,'coupon'=>$coupon,'msg'=>L('_COUPON_ERROR_EXPIRE_'));
        }else if($coupon['status']==0){
            return array('error_code'=>1,'coupon'=>$coupon,'msg'=>L('_NOT_EXCHANGE_CODE_'));
        }else if($coupon['status']==2){
            return array('error_code'=>2,'coupon'=>$coupon,'msg'=>L('_COUPON_ERROR_EXPIRE_'));
        }else if($coupon['num']===$coupon['had_pull']||$coupon['status']==3){
            $this->field(true)->where($where)->setField('status',3);
            return array('error_code'=>3,'coupon'=>$coupon,'msg'=>L('_COUPON_ERROR_MAX'));
        }else{
            $hadpull = M('System_coupon_hadpull');
            $hadpull_count = $hadpull->where(array('uid'=>$uid,'coupon_id'=>$coupon_id))->count();
            if($hadpull_count<$coupon['limit']) {
                if ($this->where($where)->setInc('had_pull')) {
                    $this->where($where)->setField('last_time',$_SERVER['REQUEST_TIME']);
                    $data['coupon_id'] = $coupon_id;
                    $data['num'] = 1;
                    $data['receive_time'] =$_SERVER['REQUEST_TIME'];
                    $data['status'] = 0;
                    if($card_code){
                        $data['wx_card_code']  = $card_code;
                    }
                    $data['uid']  = $uid;
                    $data['admin_name'] = $admin_name;
                    $coupon = $this->get_coupon($coupon_id);
                    if ($hadId = $hadpull->add($data)) {
                        if($now_user = M('User')->where(array('uid'=>$uid))->find()){
                            $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                            $cate_platform = $this->cate_platform();
                            $model->sendTempMsg('TM00251', array('href' => C('config.site_url').'/'.U('Wap/My/card_list',array('carpon_type'=>'system')), 'wecha_id' => $now_user['openid'], 'first' =>  '您成功领取了'.$cate_platform['category'][$coupon['cate_name']].'优惠券', 'toName' => $now_user['nickname'], 'gift' => $coupon['name'],'time'=>date("Y年m月d日 H:i"), 'remark' => '有效期'.date("Y-m-d",$coupon['start_time']).' 至 '.date("Y-m-d",$coupon['end_time'])));
                        }
                        $coupon['has_get'] = $hadpull_count+1;
                        $coupon['id'] = $hadId;
                        $coupon['is_use'] = 0;
                        return array('error_code'=>0,'coupon'=>$coupon);
                    }
                } else {
                    return array('error_code'=>1,'coupon'=>$coupon,'msg'=>L('_NOT_EXCHANGE_CODE_'));
                }
            }else{
                return array('error_code'=>5,'coupon'=>$coupon,'msg'=>L('_COUPON_ERROR_MAX'));
            }
        }
    }

    //获取该用户领取的优惠券数量
    public function get_coupon_count_by_uid($coupon_id,$uid){
        $where['coupon_id'] = $coupon_id;
        $where['uid'] = $uid;
        return M('System_coupon_hadpull')->where($where)->count();
    }

    //减少卡券库存
    public function decrease_sku($add,$less,$coupon_id){
        $now_coupon  =$this->where(array('coupon_id'=>$coupon_id))->find();
        if(!$now_coupon['is_wx_card']){
            return ;
        }
        import('ORG.Net.Http');
        $res = D('Access_token_expires')->get_access_token();
        //修改库存
        $wx_data['card_id'] = $now_coupon['wx_cardid'];
        $wx_data['increase_stock_value'] = $add;
        $wx_data['reduce_stock_value'] = $less;
        $update_wx_card = httpRequest('https://api.weixin.qq.com/card/modifystock?access_token='.$res['access_token'],'post',json_encode($wx_data,JSON_UNESCAPED_UNICODE));
        $update_wx_card = json_decode($update_wx_card[1],true);
        $errorms = $update_wx_card['errmsg'];
        return $errorms;
    }

    //卡券颜色组合
    public function color_list(){
        return array(
            "Color010"=>"#63b359",
            "Color020"=>"#2c9f67",
            "Color030"=>"#509fc9",
            "Color040"=>"#5885cf",
            "Color050"=>"#9062c0",
            "Color060"=>"#d09a45",
            "Color070"=>"#e4b138",
            "Color080"=>"#ee903c",
            "Color081"=>"#f08500",
            "Color082"=>"#a9d92d",
            "Color090"=>"#dd6549",
            "Color100"=>"#cc463d",
            "Color101"=>"#cf3e36",
            "Color102"=>"#5E6671",
        );
    }
}