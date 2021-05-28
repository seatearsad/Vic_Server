<?php
/**
 * Created by PhpStorm.
 * User: garfunkel
 * Date: 2021/5/28
 * Time: 3:50 PM
 */

class DataAction extends BaseAction
{
    protected $export_menu;
    public function __construct(){
        parent::__construct();
        $this->export_menu = array(
            "order"=>L('ORDER_EXPORT'),
            "sales"=>L('SALES_EXPORT'),
            "store"=>L('STORE_EXPORT'),
            "order_store"=>L('ORDER_STORE_EXPORT'),
            "store_ranking"=>L('STORE_RANKING_EXPORT'),
            "store_info"=>L('STORE_INFO_EXPORT'),
            "courier_pay"=>L('COURIER_PAY_EXPORT'),
            "courier_info"=>L('COURIER_INFO_EXPORT'),
            "order_courier"=>L('ORDER_COURIER_EXPORT'),
            "user"=>L('USER_EXPORT'),
            "user_ranking"=>L('USER_RANKING_EXPORT')
        );

        $this->assign("export_menu",$this->export_menu);
    }
    public function export(){
        $this->assign('status_list', D('Shop_order')->getStatusNewList());

        $city = D('Area')->where(array('area_type'=>2,'is_open'=>1))->select();
        $this->assign('city',$city);

        $pay_method = D('Config')->get_pay_method('','',0);
        foreach ($pay_method as $k=>&$v){
            switch ($k){
                case 'offline':
                    $v['name'] = 'Cash';
                    break;
                case 'alipay':
                    $v['name'] = 'AliPay';
                    break;
                case 'weixin':
                    $v['name'] = 'Wechat Pay';
                    break;
                default:
                    break;
            }
        }
        $this->assign('pay_method',$pay_method);
        $this->display();
    }
}