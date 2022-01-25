<?php
/**
 * Created by PhpStorm.
 * User: garfunkel
 * Date: 2021/12/27
 * Time: 3:46 PM
 */

class Deliver_bonusModel extends Model
{
    public function __construct() {
        parent::__construct();

        $this->checkAllList();
    }

    public function checkAllList(){
        $all_list = $this->where(array('status'=>1))->select();
        foreach ($all_list as $bonus){
            if(strtotime($bonus['expiry']) < time()){
                $this->where(array('id'=>$bonus['id']))->save(array('status'=>2));
            }
        }
    }
}