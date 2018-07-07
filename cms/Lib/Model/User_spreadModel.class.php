<?php
// 推广分佣
class User_spreadModel extends Model{
    public function get_spread_user($now_user_openid,$uid){
        //$now_user_spread_from = M('User_spread us')->join(C('DB_PREFIX').'user u ON us.spread_openid  = u.openid')->where(array('us.openid'=>$now_user_openid))->find();
        $now_user_spread_user_list = M('User_spread')->join('as us left join '.C('DB_PREFIX').'user u ON us.openid  = u.openid')->where(array('us.spread_openid'=>$now_user_openid))->select();
        foreach($now_user_spread_user_list as &$v){
            $v['spread_count'] = M('User_spread')->where(array('spread_openid'=>$v['openid']))->count();
            $v['spread_money'] = M('User_spread_list')->where(array('get_uid'=>$v['uid'],'uid'=>$uid))->sum('money');
        }
        return array('spread_user_list'=>$now_user_spread_user_list);
    }

    //过户用户列表
    public function get_spread_change_user($uid){
        $change_user = M('User')->where(array('spread_change_uid'=>$uid))->select();
        foreach($change_user as &$v){
            $v['spread_count'] = M('User_spread')->where(array('spread_openid'=>$v['openid']))->count();
            $v['spread_money'] = M('User_spread_list')->where(array('uid'=>$v['uid'],'change_uid'=>$uid))->sum('money');
        }
        return array('spread_change_user_list'=>$change_user);
    }

}