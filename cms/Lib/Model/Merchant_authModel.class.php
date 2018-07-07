<?php
// 商家余额
class Merchant_authModel extends Model{
    //增加商家权限
    public function add_auth($mer_id,$auth_id){
        $now_merchant = D('Merchant')->get_info($mer_id);
        $now_merchant['menus'] = explode(',',$now_merchant['menus']);
        if(in_array($auth_id,$now_merchant['menus'])){
            return array('error_code'=>1,'msg'=>'权限已经存在了');
        }else{


            $now_merchant['menus']= array_merge($this->get_fid_menu($auth_id),$now_merchant['menus']);

            if(M('Merchant')->where(array('mer_id'=>$mer_id))->setField('menus',implode(',',$now_merchant['menus']))){
                return array('error_code'=>0,'msg'=>'权限更新成功');
            }else{

                return array('error_code'=>1,'msg'=>'权限更新失败');
            }
        }
    }

    public function get_fid_menu($fid,$tmp=array()){
        $menu = M('New_merchant_menu')->where(array('id'=>$fid))->find();
        $tmp [] = $menu['id'];
        if($menu['fid']!=0){
            $tmp  = $this->get_fid_menu($menu['fid'],$tmp);
        }
        return $tmp;
    }
}