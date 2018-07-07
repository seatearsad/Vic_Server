<?php
/*
 * 数据分析
 *
 * @  Writers    jun
 * @  BuildTime  2015/12/30 8:00
 * 
 */
class AnalysisAction extends BaseAction {
    //获取用户总数统计
    public function echart(){
        $this->assign('star_year',2015);
        $this->display();
    }
    public function userc(){
        $star_year='2015';
        $this->assign('star_year',$star_year);
        $this->display();
    }
    public function getuserc(){
        $result = D('Analysis')->get_count_byAreaId($_POST['area_id'],$_POST['type']);
        $result['note']="用户统计是按地区统计的，用户必须要有完整的配送地址信息";
        $this->ajaxReturn($result);
    }
    //粉丝排行统计
    public  function fanc(){
        $result = D('Analysis')->get_fan_count($_POST['area_id'],$_POST['type']);
        $result['note']="粉丝统计是统计关注每个商家的粉丝数量";
        $this->ajaxReturn(array('msg'=>$result['msg'],'area_pname'=>$result['area_pname'],'note'=>$result['note'],'error'=>''));
    }
    //获取商家总数统计
    public function merc(){
        $result = D('Analysis')->get_merchant_count($_POST['area_id'],$_POST['type']);
        $area_pname = empty($result['area_pname'])?"全国商家数量统计":$result['area_pname']."商家数量统计";
        $result['note']="按地区统计商家数量";
        $this->ajaxReturn(array('msg'=>$result['msg'],'area_pname'=>$area_pname,'note'=>$result['note'],'error'=>''));
    }
    //统计团购消费统计
    public function groupc(){
        $result = D('Analysis')->get_consumer($_POST['area_id'],$_POST['type'], 3,$_POST['year'],$_POST['month'],$_POST['period']);
        $result['area_pname'].=C('config.group_alias_name')."消费统计";
        $result['note']=C('config.group_alias_name')."消费统计按地区时间统计已消费的数据";
        $this->ajaxReturn($result);
    }
    //餐饮消费统计
    public function mealc(){
        $result = D('Analysis')->get_consumer($_POST['area_id'],$_POST['type'], 0,$_POST['year'],$_POST['month'],$_POST['period']);
        $result['area_pname'].=C('config.meal_alias_name')."消费统计";
        $result['note']=C('config.meal_alias_name')."消费统计按地区时间统计已消费的数据";

        $this->ajaxReturn($result);
    }

    //快店消费统计
    public function shopc(){
        $result = D('Analysis')->get_consumer($_POST['area_id'],$_POST['type'], 5,$_POST['year'],$_POST['month'],$_POST['period']);
        $result['area_pname'].=C('config.shop_alias_name')."消费统计";
        $result['note']=C('config.shop_alias_name')."消费统计按地区时间统计已消费的数据";
        $this->ajaxReturn($result);
    }

    //店铺消费统计
     public function storec(){
       $result = D('Analysis')->get_consumer($_POST['area_id'],$_POST['type'], 1,$_POST['year'],$_POST['month'],$_POST['period']);
       $result['area_pname'].=C('config.store_alias_name')."消费统计";
         $result['note']=C('config.store_alias_name')."消费统计按地区时间统计已消费的数据";
        $this->ajaxReturn($result);
    }
    //外卖消费统计
    public function waimaic(){
        $result = D('Analysis')->get_consumer($_POST['area_id'],$_POST['type'], 2,$_POST['year'],$_POST['month'],$_POST['period']);
        $result['area_pname'].=C('config.waimai_alias_name')."消费统计";
        $result['note']=C('config.waimai_alias_name')."消费统计按地区时间统计已消费的数据";
        $this->ajaxReturn($result);
    }
    //预约消费统计
    public function appointc(){
        $result = D('Analysis')->get_consumer($_POST['area_id'],$_POST['type'], 4,$_POST['year'],$_POST['month'],$_POST['period']);
        $result['area_pname'].=C('config.appoint_alias_name')."消费统计";
        $result['note']=C('config.appoint_alias_name')."消费统计按地区时间统计已消费的数据";
        $this->ajaxReturn($result);
    }
    //小区消费
    public function villagec(){
        $result = D('Analysis')->get_village_consumer($_POST['area_id'],$_POST['type'],$_POST['year'],$_POST['month'],$_POST['period']);
        $area_pname2 = empty($result['area_pname'])?"全国":$result['area_pname'];
        $area_pname = empty($result['area_pname'])?"全国小区缴费统计":$result['area_pname']."小区缴费统计";
        $result['note']="社区消费统计按地区时间统计社区缴费的数据";
        $this->ajaxReturn(array('msg'=>$result['msg'],'type_money'=>$result['type_money'],'area_pname'=>$area_pname,'area_pname2'=>$area_pname2,'error'=>''));
    }
	
//    
    //框架内菜单选项
    public function getmenu(){
        $this->ajaxReturn($this->menu());
    }
    public function menu(){
        $menu = array(
            'getuserc'=>'用户统计',
            'merc'=>'商家统计',
            'fanc'=>'商家粉丝排行',
            'groupc'=>C('config.group_alias_name').'消费统计',
            'mealc'=>C('config.meal_alias_name').'消费统计',
            'shopc'=>C('config.shop_alias_name').'消费统计',
            'storec'=>C('config.store_alias_name').'消费统计',
            'appointc'=>C('config.appoint_alias_name').'消费统计',
        );
        if (empty($this->config['is_cashier'])) {
            unset($menu['storec']);
        }
        if($this->config['house_open']){
            $menu['villagec'] = '小区缴费统计';
        }
        if($this->config['store_open_waimai']){
            $menu['waimaic'] = '外卖消费统计';
        }
        return $menu;
    }
}