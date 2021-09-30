<?php
/**
 * Created by PhpStorm.
 * User: garfunkel
 * Date: 2021/9/28
 * Time: 8:09 PM
 */

class DeliverectAction
{
    private $site_url;
    private $link_type = 1;
    public function __construct()
    {
        file_put_contents("./deliverect_log.log",date("Y/m/d")."   ".date("h:i:sa")."   "."Deliverect" ."   ". $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'--'.json_encode(file_get_contents("php://input"))."\r\n",FILE_APPEND);
    }

    public function channelStatus(){
        $config = D('Config')->where(array('name'=>'site_url'))->find();
        $this->site_url = $config['value'];
        //echo "channelStatus";
        $link_array = array(
            "statusUpdateURL"=> $this->site_url."/deliverect/statusUpdate",
            "menuUpdateURL"=> $this->site_url."/deliverect/menuUpdate",
            "snoozeUnsnoozeURL"=> $this->site_url."/deliverect/snoozeUnsnooze",
            "busyModeURL"=> $this->site_url."/deliverect/busyMode",
            "updatePrepTimeURL"=> $this->site_url."/deliverect/updatePrepTimeURL"
        );

        $data = json_decode(file_get_contents("php://input"),true);
        $store_id = $data['channelLocationId'];
        $link_id = $data['channelLinkId'];
        $status = $data['status'];

        $link_status = 0;
        switch ($status){
            case 'register':
                $link_status = 1;
                break;
            case 'active':
                $link_status = 2;
                break;
            case 'inactive':
                $link_status = 3;
                break;

            default:
                break;
        }

        $updateData['link_type'] = $this->link_type;
        $updateData['link_id'] = $link_id;
        $updateData['link_status'] =$link_status;

        D('Merchant_store')->where(array('store_id'=>$store_id))->save($updateData);

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