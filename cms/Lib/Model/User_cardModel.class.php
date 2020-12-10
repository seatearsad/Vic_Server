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

    /**
     * @param $uid
     * @param int $status 0需验证 1正常
     * @return mixed
     */
    public function getCardListByUid($uid,$status = -1){
        $where = array('uid'=>$uid);

        if($status != -1){
            $where['status'] = $status;
        }
        //is_default desc,
        $result = $this->field(true)->where($where)->order('id asc')->select();
        //过滤卡的验证时间
        $save_verification_day = 30;
        foreach ($result as &$v){
            if($v['verification_time'] != '' && $v['status'] == 1) {
                $veri_time = $v['verification_time'] + 30 * 24 * 60 * 60;
                if ($veri_time < time()) {
                    $v['status'] = 0;
                    $v['verification_time'] = '';
                    $this->field(true)->where(array('id'=>$v['id']))->save($v);
                }
            }
        }

        return $result;
    }

    public function clearIsDefaultByUid($uid){
        $where = array('uid'=>$uid);
        $data['is_default'] = 0;

        $this->field(true)->where($where)->save($data);
    }
}