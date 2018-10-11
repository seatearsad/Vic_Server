<?php
/**
 * Created by PhpStorm.
 * User: Garfunkel
 * Date: 2018/10/9
 * Time: 15:32
 */

class User_cardModel extends Model
{
    public function getCardByUserAndNum($uid,$num){
        $where = array('uid'=>$uid,'card_num' => $num);
        $result = $this->field(true)->where($where)->select();

        if(count($result) > 0)
            return true;
        else
            return false;
    }

    public function getCardListByUid($uid){
        $where = array('uid'=>$uid);

        $result = $this->field(true)->where($where)->order('is_default desc,id asc')->select();

        return $result;
    }

    public function clearIsDefaultByUid($uid){
        $where = array('uid'=>$uid);
        $data['is_default'] = 0;

        $this->field(true)->where($where)->save($data);
    }
}