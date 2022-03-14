<?php
/**
 * Created by PhpStorm.
 * User: garfunkel
 * Date: 2022/3/12
 * Time: 8:45 AM
 */

class System_messageModel extends Model
{
    public function __construct($name = '', $tablePrefix = '', $connection = '')
    {
        parent::__construct($name, $tablePrefix, $connection);

        $where['status'] = 1;
        $where['end_time'] = array('lt',time());
        $this->where($where)->setField('status',2);
    }

    /**
     * @param $from 0Wap 1iOS 2Android
     * @param $version App的版本号
     * @param $city_id 城市id
     * @return array
     */
    public function getSystemMessage($from,$version,$city_id){
        $where = array('status'=>1);
        switch ($from){
            case 0:
                $where['is_wap'] = 1;
                break;
            case 1:
                $where['is_ios'] = 1;
                break;
            case 2:
                $where['is_android'] = 1;
                break;
            default:
                break;
        }

        $where['city_id'] = array('in',array(0,$city_id));

        $list = $this->where($where)->order('sort desc')->select();

        $message = null;
        if(count($list) > 0) {
            switch ($from) {
                case 0:
                    $message = $list[0];
                    break;
                case 1:
                    $message = $this->get_iOSMessage($list,$version);
                    break;
                case 2:
                    $message = $this->getAndroidMessage($list,$version);
                    break;
                default:
                    break;
            }
        }

        return $message;
    }


    public function get_iOSMessage($list,$version){
        foreach ($list as $v){
            //首先判断是否包含此版本号
            if($v['version'] == 0){
                return $v;
            }else{
                $version_arr = explode($v['version'],"|");
                if(str_replace(".","",$version) <= str_replace(".","",$version_arr[0])){
                    return $v;
                }
            }
        }
    }

    public function getAndroidMessage($list,$version){
        foreach ($list as $v){
            //首先判断是否包含此版本号
            if($v['version'] == 0){
                return $v;
            }else{
                $version_arr = explode($v['version'],"|");
                if(count($version_arr) == 1){
                    if(str_replace(".","",$version) <= str_replace(".","",$version_arr[0])){
                        return $v;
                    }
                }else {
                    if ($version_arr[1] && str_replace(".", "", $version) <= str_replace(".", "", $version_arr[1])) {
                        return $v;
                    }
                }
            }
        }
    }
}