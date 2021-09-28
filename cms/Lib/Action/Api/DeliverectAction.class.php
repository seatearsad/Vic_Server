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
        file_put_contents("./deliverect_log.log",date("Y/m/d")."   ".date("h:i:sa")."   "."Deliverect" ."   ". $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'--'.json_encode($_POST)."\r\n",FILE_APPEND);
    }

    public function channelStatus(){
        echo "channelStatus";
    }

    public function snooze(){
        echo "snooze";
    }

    public function storeMenu(){
        echo "storeMenu";
    }

    public function busyMode(){
        echo "busyMode";
    }

    public function orderStatus(){
        echo "orderStatus";
    }

    public function prepTime(){
        echo "prepTime";
    }
}