<?php
/**
 * Created by PhpStorm.
 * User: garfunkel
 * Date: 2021/9/28
 * Time: 8:09 PM
 */

class DeliverectAction
{
    public function __construct()
    {
        file_put_contents("./deliverect_log.log",date("Y/m/d")."   ".date("h:i:sa")."   "."Deliverect" ."   ". $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'--'.json_encode($GLOBALS['HTTP_RAW_POST_DATA'])."\r\n",FILE_APPEND);
    }

    public function channelStatus(){
        //echo "channelStatus";
        $link_array = array(
            "statusUpdateURL"=> C('config.site_url')."/deliverect/statusUpdate",
            "menuUpdateURL"=> C('config.site_url')."/deliverect/menuUpdate",
            "snoozeUnsnoozeURL"=> C('config.site_url')."/deliverect/snoozeUnsnooze",
            "busyModeURL"=> C('config.site_url')."/deliverect/busyMode",
            "updatePrepTimeURL"=> C('config.site_url')."/deliverect/updatePrepTimeURL"
        );

        echo json_encode($link_array);
    }

    public function snoozeUnsnooze(){
        echo "snoozeUnsnooze";
    }

    public function menuUpdate(){
        echo "menuUpdate";
    }

    public function busyMode(){
        echo "busyMode";
    }

    public function statusUpdate(){
        echo "statusUpdate";
    }

    public function updatePrepTimeURL(){
        echo "updatePrepTimeURL";
    }
}