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
    public function getSystemMessage($from,$version,$city_id,$lat,$lng){
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

        $city = D("Area")->where(array("area_id"=>$city_id))->find();

        $where['city_id'] = array('in',array(0,$city_id));

        $where['begin_time'] = array('elt',time());
        $where['end_time'] = array('gt',time());

        $list = $this->where($where)->order('sort desc')->select();

        $newList = array();
        if($city['range_type'] == 2){
            import('@.ORG.RegionalCalu.RegionalCalu');
            $region = new RegionalCalu();
            $is_continue = $region->checkCity($city,$lng,$lat);

            foreach ($list as &$v){
                if($v['city_id'] == $city_id){
                    if(($is_continue && $v['in_area'] == 1) || (!$is_continue && $v['in_area'] == 0)){
                        $newList[] = $v;
                    }
                }else{
                    $newList[] = $v;
                }
            }
        }else{
            $newList = $list;
        }

        $message = null;
        if(count($newList) > 0) {
            switch ($from) {
                case 0:
                    $message = $newList[0];
                    break;
                case 1:
                    $message = $this->get_iOSMessage($newList,$version);
                    break;
                case 2:
                    $message = $this->getAndroidMessage($newList,$version);
                    break;
                default:
                    break;
            }
        }

        if($message && $message['type'] == 1){
            $message['content'] = C('config.site_url').$message['content'];
        }

        return $message;
    }


    public function get_iOSMessage($list,$version){
        foreach ($list as $v){
            //首先判断是否包含此版本号
            if($v['version'] == "0"){
                return $v;
            }else{
                $version_arr = explode("|",$v['version']);
                $version = intval(str_replace(".","",$version));
                $checkVersion = intval(str_replace(".","",$version_arr[0]));
                if($version <= $checkVersion){
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
                $version_arr = explode("|",$v['version']);
                if(count($version_arr) == 1){
                    if(intval(str_replace(".","",$version)) <= intval(str_replace(".","",$version_arr[0]))){
                        return $v;
                    }
                }else {
                    if ($version_arr[1] && intval(str_replace(".", "", $version)) <= intval(str_replace(".", "", $version_arr[1]))) {
                        return $v;
                    }
                }
            }
        }
    }
}