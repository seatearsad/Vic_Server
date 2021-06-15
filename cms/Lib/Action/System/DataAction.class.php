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
            //"store"=>L('STORE_EXPORT'),
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
        $status_list = array(
            1=>'Complete',
            2=>'Cancelled'
        );
        $this->assign('status_list', $status_list);

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

    public function order(){
        set_time_limit(0);
        require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
        $title = 'Order Export';
        $objExcel = new PHPExcel();
        $objProps = $objExcel->getProperties();
        // 设置文档基本属性
        $objProps->setCreator($title);
        $objProps->setTitle($title);
        $objProps->setSubject($title);
        $objProps->setDescription($title);

        // 设置当前的sheet

//        $where_store = null;
//        if(!empty($_GET['keyword']) && $_GET['searchtype'] == 's_name'){
//            $where_store['name'] = array('like', '%'.$_GET['keyword'].'%');
//        }

        if ($this->system_session['area_id']) {
            $area_index = $this->system_session['level'] == 1 ? 'area_id' : 'city_id';
            $where_store[$area_index] = $this->system_session['area_id'];
        }

        $store_ids = array();
        $where = array();
        $condition_where = 'WHERE 1=1';
//        if ($where_store) {
//            $stores = D('Merchant_store')->field('store_id')->where($where_store)->select();
//            foreach ($stores as $row) {
//                $store_ids[] = $row['store_id'];
//            }
//            if ($store_ids) {
//                $where['store_id'] = array('in', $store_ids);
//                //                 $condition_where .= ' AND o.store_id IN ('.explode(',',$store_ids).')';
//                $condition_where .= ' AND oo.store_id IN ('.implode(',',$store_ids).')';//implode,explode
//            }
//        }

        if(!empty($_GET['keyword'])){
            if ($_GET['searchtype'] == 'real_orderid') {
                $where['real_orderid'] = htmlspecialchars($_GET['keyword']);
                $condition_where .= ' AND oo.real_orderid = "'. htmlspecialchars($_GET['keyword']).'"';
            } elseif ($_GET['searchtype'] == 'orderid') {
                $where['orderid'] = htmlspecialchars($_GET['keyword']);
                $tmp_result = M('Tmp_orderid')->where(array('orderid'=>$where['orderid']))->find();
                unset($where['orderid']);
                $where['order_id'] = $tmp_result['order_id'];
                $condition_where .= ' AND oo.order_id = '. $tmp_result['order_id'];
            } elseif ($_GET['searchtype'] == 'name') {
                $where['username'] = htmlspecialchars($_GET['keyword']);
                $condition_where .=  ' AND oo.username = "'.  htmlspecialchars($_GET['keyword']).'"';
            } elseif ($_GET['searchtype'] == 'phone') {
                $where['userphone'] = htmlspecialchars($_GET['keyword']);
                $condition_where .= ' AND oo.userphone = "'.  htmlspecialchars($_GET['keyword']).'"';
            }elseif ($_GET['searchtype'] == 'third_id') {
                $where['third_id'] =$_GET['keyword'];
                $condition_where .= ' AND oo.third_id = "'.  $_GET['keyword'].'"';
            }elseif($_GET['searchtype'] == 'sid'){
                $where['o.store_id'] = htmlspecialchars($_GET['keyword']);
                $condition_where .= ' AND oo.store_id = "'.  $_GET['keyword'].'"';
            }elseif($_GET['searchtype'] == 'id'){
                $where['o.uid'] = htmlspecialchars($_GET['keyword']);
                $condition_where .= ' AND oo.uid = "'.  $_GET['keyword'].'"';
            }

        }

        $status = isset($_GET['status']) ? intval($_GET['status']) : -1;
        $type = isset($_GET['type']) && $_GET['type'] ? $_GET['type'] : '';
        $sort = isset($_GET['sort']) && $_GET['sort'] ? $_GET['sort'] : '';
        $pay_type = isset($_GET['pay_type']) && $_GET['pay_type'] ? $_GET['pay_type'] : '';
        if ($sort != 'DESC' && $sort != 'ASC') $sort = '';
        if ($type != 'price' && $type != 'pay_time') $type = '';

//        if($status == 100){
//            $where['paid'] = 0;
//            $condition_where .= ' AND oo.paid=0';
//            //$condition_where .= ' AND paid=0';
//        }else
        //新版导出
        if ($status != -1) {
            if($status == 1) {
                $where['o.status'] = array('between',array(2,3));
                $condition_where .= ' AND (`oo`.`status`=2 OR `oo`.`status`=3)';
            }else if($status == 2){
                $where['o.status'] = array('between',array(4,5));
                $condition_where .= ' AND (`oo`.`status`=4 OR `oo`.`status`=5)';
            }
        }

        if($pay_type&&$pay_type!='balance'){
            if($pay_type == "offline"){
                $where['_string'] = "(`pay_type`='offline' OR `pay_type`='Cash')";
                $condition_where .= ' AND (`oo`.`pay_type`="offline" OR `oo`.`pay_type`="Cash")';
            }else {
                $where['pay_type'] = $pay_type;
                $condition_where .= ' AND oo.pay_type="' . $pay_type . '"';
            }
        }else if($pay_type=='balance'){
            $where['_string'] .= "(`pay_type`<>'Cash' and `pay_type`<>'offline') and (`balance_pay`<>0 OR `merchant_balance` <> 0 )";
            $condition_where .= ' AND (`oo`.`pay_type`<>"offline" and `oo`.`pay_type`<>"Cash") and (`oo`.`balance_pay`<>0 OR `oo`.`merchant_balance` <> 0 )';
        }

        if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error("结束时间应大于开始时间");
            }
            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $where['_string'] .=( $where['_string']?' AND ':''). " (create_time BETWEEN ".$period[0].' AND '.$period[1].")";
            $condition_where .=  " AND (oo.create_time BETWEEN ".$period[0].' AND '.$period[1].")";
            //$condition_where .=  " AND (create_time BETWEEN ".$period[0].' AND '.$period[1].")";
        }

        if($_GET['city_id']){
            $condition_where .= " AND ss.city_id=".$_GET['city_id'];
            $where['s.city_id'] = $_GET['city_id'];
        }else{
            //$store_where = "";
        }
        $condition_where.=" AND oo.is_del=0";
        //$condition_where.=" AND is_del=0";
        $where['is_del'] = 0;
        //$coupon_list = M('New_event_user')->join('as u left join '.C('DB_PREFIX').'new_event_coupon as c ON u.event_coupon_id=c.id')->field('u.*')->where($where)->select();
        $count = D('Shop_order')->join('as o left join '.C('DB_PREFIX').'merchant_store as s ON o.store_id=s.store_id')->where($where)->count();
        //var_dump($count);die();
        $length = ceil($count / 1000);
        for ($i = 0; $i < $length; $i++) {
            $i && $objExcel->createSheet();
            $objExcel->setActiveSheetIndex($i);

            $objExcel->getActiveSheet()->setTitle('第' . ($i+1) . '个一千个订单信息');
            $objActSheet = $objExcel->getActiveSheet();
            $objExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('Y')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('Z')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('AA')->setAutoSize(true);
            //$objExcel->getActiveSheet()->getColumnDimension('AB')->setAutoSize(true);

            $objActSheet->setCellValue('A1', 'Order # 订单编号');
            $objActSheet->setCellValue('B1', 'User ID');
            $objActSheet->setCellValue('C1', 'Order Status订单状态');
            $objActSheet->setCellValue('D1', 'Store Name店铺名称');
            //$objActSheet->setCellValue('E1', '商品名称');
            //$objActSheet->setCellValue('F1', '数量');
            //$objActSheet->setCellValue('G1', '单价');
            $objActSheet->setCellValue('E1', 'Subtotal商品总价（税前）');
            $objActSheet->setCellValue('F1', 'Tax on Subtotal商品税费');
            $objActSheet->setCellValue('G1', 'Delivery Fee配送费');
            $objActSheet->setCellValue('H1', 'Tax on Delivery Fee配送费税');
            $objActSheet->setCellValue('I1', 'Packing Fee打包费');
            $objActSheet->setCellValue('J1', 'Tax on Packing Fee打包费税');
            $objActSheet->setCellValue('K1', 'Bottle Deposit');
            $objActSheet->setCellValue('L1', 'Service Fee服务费');
            $objActSheet->setCellValue('M1', 'Order Amount订单总价');
            $objActSheet->setCellValue('N1', 'Tips小费');
            $objActSheet->setCellValue('O1', 'Delivery Discount配送费优惠');
            $objActSheet->setCellValue('P1', 'Coupon优惠卷');
            $objActSheet->setCellValue('Q1', '(Merchant Discount商家优惠)');
            $objActSheet->setCellValue('R1', 'Amount Paid实付总价');
            $objActSheet->setCellValue('S1', 'Payment Info支付情况');
            $objActSheet->setCellValue('T1', 'Payment Time支付时间');
            $objActSheet->setCellValue('U1', 'Complete Time送达时间');
            $objActSheet->setCellValue('V1', '出餐时间');
            $objActSheet->setCellValue('W1', 'User Name客户姓名');
            $objActSheet->setCellValue('X1', 'User Phone Number客户电话');
            $objActSheet->setCellValue('Y1', 'Address客户地址');
            $objActSheet->setCellValue('Z1', '配送费优惠类型');
            $objActSheet->setCellValue('AA1', 'Food Prep出餐时间');

            $sql = "SELECT o.*, d.name as good_name,d.price as good_price ,d.unit,d.cost_price, d.num as good_num,d.tax_num,d.deposit_price FROM (select oo.*,ss.tax_num as store_tax,ss.name AS store_name from pigcms_shop_order as oo left join pigcms_merchant_store as ss on ss.store_id=oo.store_id ".$condition_where." LIMIT ". $i*1000 .",1000)o LEFT JOIN pigcms_shop_order_detail AS d ON `d`.`order_id`=`o`.`order_id` ORDER BY o.order_id DESC";

            $result_list = D()->query($sql);
            fdump(D()->getDbError());
            //var_dump($result_list);die;
            $tmp_id = 0;
            if (!empty($result_list)) {
                $curr_order = '';
                $record_list = array();

                $curr_tax = 0;
                $curr_deposit = 0;

                $curr_num = 0;
                foreach ($result_list as $k=>$v){
                    if($curr_order != $v['order_id']){
                        if($curr_order == ''){
                            $curr_order = $v['order_id'];
                        }else{
                            $record_list[$curr_order]['goods_tax'] = $curr_tax;
                            $record_list[$curr_order]['deposit_price'] = $curr_deposit;

                            $curr_order = $v['order_id'];
                        }

                        $curr_tax = $v['good_price']*$v['good_num']*$v['tax_num']/100;
                        $curr_deposit = $v['good_num']*$v['deposit_price'];
                    }else{
                        $curr_tax += $v['good_price']*$v['good_num']*$v['tax_num']/100;
                        $curr_deposit += $v['good_num']*$v['deposit_price'];
                    }

                    $curr_num++;

                    if($curr_num == count($result_list)) {
                        $record_list[$curr_order]['goods_tax'] = $curr_tax;
                        $record_list[$curr_order]['deposit_price'] = $curr_deposit;
                    }
                    //如果订单未支付，暂修改订单状态为100
                    if($v['paid'] == 0){
                        $result_list[$k]['status'] = 100;
                    }
                }


                $index = 2;
                foreach ($result_list as $value) {
                    if($tmp_id == $value['real_orderid']){
//                        $objActSheet->setCellValueExplicit('A' . $index, '');//订单编号
//                        $objActSheet->setCellValueExplicit('B' . $index, '');
//                        $objActSheet->setCellValueExplicit('C' . $index, '');
//                        $objActSheet->setCellValueExplicit('D' . $index, '');
//                        $objActSheet->setCellValueExplicit('E' . $index, $value['good_name']);//商品名称
//                        $objActSheet->setCellValueExplicit('F' . $index, $value['good_num'],PHPExcel_Cell_DataType::TYPE_NUMERIC);//数量
//                        $objActSheet->setCellValueExplicit('G' . $index, $value['good_price'],PHPExcel_Cell_DataType::TYPE_NUMERIC);//单价
//                        $objActSheet->setCellValueExplicit('H' . $index, '',PHPExcel_Cell_DataType::TYPE_NUMERIC);
//                        $objActSheet->setCellValueExplicit('I' . $index, '',PHPExcel_Cell_DataType::TYPE_NUMERIC);
//                        $objActSheet->setCellValueExplicit('J' . $index, '',PHPExcel_Cell_DataType::TYPE_NUMERIC);
//                        $objActSheet->setCellValueExplicit('K' . $index, '',PHPExcel_Cell_DataType::TYPE_NUMERIC);
//                        $objActSheet->setCellValueExplicit('L' . $index, '');
//                        $objActSheet->setCellValueExplicit('M' . $index, '');
//                        $objActSheet->setCellValueExplicit('N' . $index, '');
//                        $objActSheet->setCellValueExplicit('O' . $index, '');
//                        $objActSheet->setCellValueExplicit('P' . $index,'');
//                        $objActSheet->setCellValueExplicit('Q' . $index, '');
//                        $objActSheet->setCellValueExplicit('R' . $index, '');
//                        $objActSheet->setCellValueExplicit('S' . $index, '');
//                        $objActSheet->setCellValueExplicit('T' . $index, '');
//                        $objActSheet->setCellValueExplicit('U' . $index, '');
//                        $objActSheet->setCellValueExplicit('V' . $index, '');
//                        $objActSheet->setCellValueExplicit('W' . $index, '');
//                        $objActSheet->setCellValueExplicit('X' . $index, '');
//                        $objActSheet->setCellValueExplicit('Y' . $index, '');
//                        $objActSheet->setCellValueExplicit('Z' . $index, '');
//                        $objActSheet->setCellValueExplicit('AA' . $index, '');
//                        $objActSheet->setCellValueExplicit('AB' . $index, '');
//                        $objActSheet->setCellValueExplicit('AC' . $index, '');
//                        $index++;
                    }else{
                        $objActSheet->setCellValueExplicit('A' . $index, $value['real_orderid']);//订单编号
                        $objActSheet->setCellValueExplicit('B' . $index, $value['uid']);//User ID
                        $objActSheet->setCellValueExplicit('C' . $index, D('Shop_order')->new_status_list[$value['status']]);//订单状态
                        $objActSheet->setCellValueExplicit('D' . $index, $value['store_name']);//店铺名称
                        //$objActSheet->setCellValueExplicit('E' . $index, $value['good_name']);//商品名称
                        //$objActSheet->setCellValueExplicit('F' . $index, $value['good_num'],PHPExcel_Cell_DataType::TYPE_NUMERIC);//数量
                        //$objActSheet->setCellValueExplicit('G' . $index, $value['good_price'],PHPExcel_Cell_DataType::TYPE_NUMERIC);//单价
                        $objActSheet->setCellValueExplicit('E' . $index, $value['goods_price'],PHPExcel_Cell_DataType::TYPE_NUMERIC);//商品总价（税前）
                        $objActSheet->setCellValueExplicit('F' . $index, floatval(sprintf("%.2f", $record_list[$value['order_id']]['goods_tax'])),PHPExcel_Cell_DataType::TYPE_NUMERIC);//商品税费
                        $objActSheet->setCellValueExplicit('G' . $index, $value['freight_charge'],PHPExcel_Cell_DataType::TYPE_NUMERIC);//配送费
                        $objActSheet->setCellValueExplicit('H' . $index, floatval(sprintf("%.2f", $value['freight_charge'] * $value['store_tax']/100)),PHPExcel_Cell_DataType::TYPE_NUMERIC);//配送费(税费)
                        $objActSheet->setCellValueExplicit('I' . $index, $value['packing_charge'],PHPExcel_Cell_DataType::TYPE_NUMERIC);//打包费
                        $objActSheet->setCellValueExplicit('J' . $index, floatval(sprintf("%.2f", $value['packing_charge'] * $value['store_tax']/100)),PHPExcel_Cell_DataType::TYPE_NUMERIC);//打包费(税费)
                        $objActSheet->setCellValueExplicit('K' . $index, floatval(sprintf("%.2f", $record_list[$value['order_id']]['deposit_price'])),PHPExcel_Cell_DataType::TYPE_NUMERIC);//Bottle Deposit
                        $objActSheet->setCellValueExplicit('L' . $index, $value['service_fee'],PHPExcel_Cell_DataType::TYPE_NUMERIC);//服务费
                        $objActSheet->setCellValueExplicit('M' . $index, floatval(sprintf("%.2f", $value['total_price'])),PHPExcel_Cell_DataType::TYPE_NUMERIC);//订单总价
                        $objActSheet->setCellValueExplicit('N' . $index, floatval(sprintf("%.2f",$value['tip_charge'])),PHPExcel_Cell_DataType::TYPE_NUMERIC);//小费
                        $objActSheet->setCellValueExplicit('O' . $index, floatval(sprintf("%.2f",$value['delivery_discount'])),PHPExcel_Cell_DataType::TYPE_NUMERIC);//减免配送费
                        $objActSheet->setCellValueExplicit('P' . $index, floatval(sprintf("%.2f",$value['coupon_price'])),PHPExcel_Cell_DataType::TYPE_NUMERIC);//优惠券金额
                        $objActSheet->setCellValueExplicit('Q' . $index, floatval(sprintf("%.2f",$value['merchant_reduce'])),PHPExcel_Cell_DataType::TYPE_NUMERIC);//商家优惠
                        $objActSheet->setCellValueExplicit('R' . $index, floatval($value['total_price'] + $value['tip_charge'] - $value['coupon_price'] - $value['delivery_discount'] - $value['merchant_reduce']),PHPExcel_Cell_DataType::TYPE_NUMERIC);//实付总价
                        $objActSheet->setCellValueExplicit('S' . $index, D('Pay')->get_pay_name($value['pay_type'], $value['is_mobile_pay'], $value['paid']));//支付情况
                        $objActSheet->setCellValueExplicit('T' . $index, $value['pay_time'] ? date('Y-m-d H:i:s', $value['pay_time']) : '');//支付时间
                        $objActSheet->setCellValueExplicit('U' . $index, $value['use_time'] ? date('Y-m-d H:i:s', $value['use_time']) : '');//送达时间
                        $objActSheet->setCellValueExplicit('V' . $index, $value['dining_time'],PHPExcel_Cell_DataType::TYPE_NUMERIC);//出餐时间
                        $objActSheet->setCellValueExplicit('W' . $index, $value['username']);//客户姓名
                        $objActSheet->setCellValueExplicit('X' . $index, $value['userphone'] . ' ');//客户电话
                        $objActSheet->setCellValueExplicit('Y' . $index, $value['address'] . ' ');//客户地址
                        $objActSheet->setCellValueExplicit('Z' . $index, $value['delivery_discount_event']);
                        $objActSheet->setCellValueExplicit('AA' . $index, $value['dining_time']);


                        $index++;
                    }
                    $tmp_id = $value['real_orderid'];
                }
            }
            sleep(2);
        }
        //输出
        $objWriter = new PHPExcel_Writer_Excel5($objExcel);
        ob_end_clean();
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header('Content-Disposition:attachment;filename="'.$title.'_' . date("Y-m-d h:i:sa", time()) . '.xls"');
        header("Content-Transfer-Encoding:binary");
        $objWriter->save('php://output');
        exit();
    }

    public function sales(){
        set_time_limit(0);
        require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
        $title = 'Sales Summary';

        if(!$_GET['begin_time'] || !$_GET['end_time']){
            $this->error(L('J_SPECIFY_TIME'));
        }else{
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error("结束时间应大于开始时间");
            }

            $condition_where ="where o.is_del=0  AND (o.status=2 or o.status=3) and paid=1";
            $title .= '('.$_GET['begin_time'].' - '.$_GET['end_time'].')';
            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $condition_where .=  " AND (o.create_time BETWEEN ".$period[0].' AND '.$period[1].")";
        }

        if($_GET['city_id']){
            $condition_where .= " AND s.city_id=".$_GET['city_id'];
        }

        $sql = "SELECT  o.*, m.name AS merchant_name,g.name as good_name,g.tax_num as good_tax,g.deposit_price,s.tax_num as store_tax,s.proportion as store_pro,d.price as good_price ,d.unit,d.cost_price, d.num as good_num, s.name AS store_name FROM " . C('DB_PREFIX') . "shop_order AS o LEFT JOIN " . C('DB_PREFIX') . "merchant_store AS s ON s.store_id=o.store_id LEFT JOIN " . C('DB_PREFIX') . "merchant AS m ON `s`.`mer_id`=`m`.`mer_id` LEFT JOIN " . C('DB_PREFIX') . "shop_order_detail AS d ON `d`.`order_id`=`o`.`order_id`  LEFT JOIN " . C('DB_PREFIX') . "shop_goods AS g ON `g`.`goods_id`=`d`.`goods_id` ".$condition_where." ORDER BY o.order_id DESC";

        $result_list = D()->query($sql);

        //计算订单税费及押金
        $all_tax = 0;
        $all_deposit = 0;
        $all_record = array();
        $record_id = '';
        $freight_tax = 0;
        $packing_tax = 0;
        $total_tax = 0;
        $curr_cash = 0;
        //subtotal
        $total_goods_price = 0;
        $total_goods_tax = 0;
        $total_freight_price = 0;
        $total_freight_tax = 0;
        $total_packing_price = 0;
        $total_packing_tax = 0;
        $total_all_tax = 0;
        $total_deposit = 0;
        $total_all_price = 0;
        $total_cash = 0;
        $total_reduce = 0;
        //商品抽成总数
        $total_goods_price_pro = 0;
        //商品税点抽成总数
        $total_goods_tax_pro = 0;
        //订单总数
        $order_count = 0;
        //小费总数
        $total_tip = 0;
        //优惠券使用金额总数
        $total_coupon_discount = 0;
        //配送费减免总数
        $total_delivery_discount = 0;

        //结束循环后是否存储最后一张订单，如果最后一张是代客下单为 false;
        $is_last = true;
        $last_pro = 0;
        foreach ($result_list as &$val){
            $curr_order = $val['real_orderid'];
            if($val['uid'] == 0){//当用户ID(uid)为0时 -- 代客下单
                //记录上一张订单的税费和押金
                $all_record[$curr_order]['all_tax'] = $val['discount_price'];
                $all_record[$curr_order]['all_deposit'] = $val['packing_charge'];
                $val['packing_charge'] = 0;
                $all_record[$curr_order]['freight_tax'] = $val['freight_charge']*$val['store_tax']/100;
                $all_record[$curr_order]['packing_tax'] = $val['packing_charge']*$val['store_tax']/100;
                $all_record[$curr_order]['total_tax'] = $val['discount_price'] + ($val['freight_charge']+$val['packing_charge'])*$val['store_tax']/100;

                $all_record[$curr_order]['cash'] = $val['price'];
                $is_last = false;

                $total_goods_price += $val['goods_price'];
                $total_goods_price_pro += ($val['goods_price'] - $val['merchant_reduce']) * $val['store_pro'] / 100;
                $total_goods_tax += $val['discount_price'];
                $total_goods_tax_pro += $val['discount_price'] * $val['store_pro'] / 100;
                $total_freight_price += $val['freight_charge'];
                $total_freight_tax += $val['freight_charge']*$val['store_tax']/100;
                $total_packing_price += $val['packing_charge'];
                $total_packing_tax += $val['packing_charge']*$val['store_tax']/100;
                $total_all_tax += $all_record[$curr_order]['total_tax'];
                $total_deposit += $all_record[$curr_order]['all_deposit'];
                $total_all_price += $val['price'];
                $total_cash += $val['price'];
                $total_tip += $val['tip_charge'];
                $total_reduce += $val['merchant_reduce'];
                $total_coupon_discount += $val['coupon_price'];
                $total_delivery_discount += $val['delivery_discount'];

                $order_count++;
            }else{
                if($curr_order != $record_id){
                    $order_count++;
                    //记录上一张订单的税费和押金
                    if($record_id != '') {
                        $all_record[$record_id]['all_tax'] = $all_tax;
                        $all_record[$record_id]['all_deposit'] = $all_deposit;
                        $all_record[$record_id]['total_tax'] = $all_tax + $all_record[$record_id]['freight_tax'] + $all_record[$record_id]['packing_tax'];

                        $total_goods_tax += $all_tax;
                        $total_goods_tax_pro += $all_tax * $val['store_pro'] / 100;
                        $total_deposit += $all_deposit;
                        $total_all_tax += $all_record[$record_id]['total_tax'];
                    }
                    //记录最新订单的基本数值
                    $total_goods_price += $val['goods_price'];
                    $total_goods_price_pro += ($val['goods_price'] - $val['merchant_reduce']) * $val['store_pro'] / 100;
                    $total_freight_price += $val['freight_charge'];
                    $total_freight_tax += $val['freight_charge']*$val['store_tax']/100;
                    $total_packing_price += $val['packing_charge'];
                    $total_packing_tax += $val['packing_charge']*$val['store_tax']/100;
                    $total_all_price += $val['price'];
                    $total_tip += $val['tip_charge'];
                    $total_reduce += $val['merchant_reduce'];
                    $total_coupon_discount += $val['coupon_price'];
                    $total_delivery_discount += $val['delivery_discount'];

                    $all_record[$curr_order]['freight_tax'] = $val['freight_charge']*$val['store_tax']/100;
                    $all_record[$curr_order]['packing_tax'] = $val['packing_charge']*$val['store_tax']/100;
                    if($val['pay_type'] == 'offline' || $val['pay_type'] == 'cash') {
                        $all_record[$curr_order]['cash'] = $val['price'];
                        $total_cash += $val['price'];
                    }

                    //清空商品税费
                    $all_tax = 0;//($val['freight_charge'] + $val['packing_charge'])*$val['store_tax']/100;
                    //清空押金
                    $all_deposit = 0;
                }

                $all_tax += $val['good_price'] * $val['good_tax']/100*$val['good_num'];
                $all_deposit += $val['deposit_price']*$val['good_num'];
                $total_tax = $all_tax + ($val['freight_charge']+$val['packing_charge'])*$val['store_tax']/100;

                $record_id = $curr_order;
                $is_last = true;
                $last_pro = $val['$all_tax'];
            }
        }
        //记录最后一张订单
        if ($is_last){
            $all_record[$record_id]['all_tax'] = $all_tax;
            $all_record[$record_id]['all_deposit'] = $all_deposit;
            $all_record[$record_id]['total_tax'] = $total_tax;

            $total_goods_tax += $all_tax;
            $total_goods_tax_pro += $all_tax * $last_pro / 100;
            $total_deposit += $all_deposit;
            $total_all_tax += $total_tax;
        }

        $objExcel = new PHPExcel();
        $objProps = $objExcel->getProperties();
        // 设置文档基本属性
        $objProps->setCreator($title);
        $objProps->setTitle($title);
        $objProps->setSubject($title);
        $objProps->setDescription($title);

        $objExcel->createSheet();
        $objExcel->setActiveSheetIndex(0);
        $objExcel->getActiveSheet()->setTitle('Order Total');
        $objActSheet = $objExcel->getActiveSheet();
        $objExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);

        $objActSheet->setCellValue('A1', '# of Orders');
        $objActSheet->setCellValue('B1', 'Subtotal');
        $objActSheet->setCellValue('C1', 'Tax on Subtotal');
        $objActSheet->setCellValue('D1', 'Delivery Fee');
        $objActSheet->setCellValue('E1', 'Tax on Delivery Fee');
        $objActSheet->setCellValue('F1', 'Commission on Subtotal');
        $objActSheet->setCellValue('G1', 'Commission on Subtotal Tax');//无
        $objActSheet->setCellValue('H1', 'Order Amount');
        $objActSheet->setCellValue('I1', 'Tips');
        $objActSheet->setCellValue('J1', 'Coupon');
        $objActSheet->setCellValue('K1', 'Free Delivery');
        $index = 2;
        $objActSheet->setCellValueExplicit('A' . $index, $order_count,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objActSheet->setCellValueExplicit('B' . $index, floatval(sprintf("%.2f", $total_goods_price)),PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objActSheet->setCellValueExplicit('C' . $index, floatval(sprintf("%.2f", $total_goods_tax)),PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objActSheet->setCellValueExplicit('D' . $index, floatval(sprintf("%.2f", $total_freight_price)),PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objActSheet->setCellValueExplicit('E' . $index, floatval(sprintf("%.2f", $total_freight_tax)),PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objActSheet->setCellValueExplicit('F' . $index, floatval(sprintf("%.2f", $total_goods_price_pro)),PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objActSheet->setCellValueExplicit('G' . $index, floatval(sprintf("%.2f", $total_goods_tax_pro)),PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objActSheet->setCellValueExplicit('H' . $index, floatval(sprintf("%.2f", $total_all_price)),PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objActSheet->setCellValueExplicit('I' . $index, floatval(sprintf("%.2f", $total_tip)),PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objActSheet->setCellValueExplicit('J' . $index, floatval(sprintf("%.2f", $total_coupon_discount)),PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objActSheet->setCellValueExplicit('K' . $index, floatval(sprintf("%.2f", $total_delivery_discount)),PHPExcel_Cell_DataType::TYPE_NUMERIC);

        //输出
        $objWriter = new PHPExcel_Writer_Excel5($objExcel);
        ob_end_clean();
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header('Content-Disposition:attachment;filename="'.$title.'.xls"');
        header("Content-Transfer-Encoding:binary");
        $objWriter->save('php://output');
        exit();
    }

    public function store_ranking(){
        set_time_limit(0);
        require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
        $title = 'Store Ranking';

        if(!$_GET['begin_time'] || !$_GET['end_time']){
            $this->error(L('J_SPECIFY_TIME'));
        }else{
            if (strtotime($_GET['begin_time']." 00:00:00")>strtotime($_GET['end_time']." 23:59:59")) {
                $this->error("结束时间应大于开始时间");
            }

            $condition_where ="where o.is_del=0  AND (o.status=2 or o.status=3) and o.paid=1";
            $title .= '('.$_GET['begin_time'].' - '.$_GET['end_time'].')';
            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $condition_where .=  " AND (o.create_time BETWEEN ".$period[0].' AND '.$period[1].")";
        }

        if($_GET['city_id']){
            $condition_where .= " AND s.city_id=".$_GET['city_id'];
            $city = D('Area')->where(array('area_id'=>$_GET['city_id']))->find();
            $title .= ' - '.$city['area_name'];

            $where['city_id'] = $_GET['city_id'];
        }

        $sql = "SELECT COUNT(o.order_id) as count,SUM(o.price) as sum,s.`name` as store_name,s.store_id,s.proportion FROM " . C('DB_PREFIX') . "merchant_store AS s LEFT JOIN " . C('DB_PREFIX') . "shop_order AS o ON s.store_id=o.store_id ".$condition_where." GROUP BY s.store_id ORDER BY SUM(o.price) DESC";
        $list = D()->query($sql);

        $where['status'] = 1;
        $store_list = D('Merchant_store')->where($where)->select();

        $objExcel = new PHPExcel();
        $objProps = $objExcel->getProperties();
        // 设置文档基本属性
        $objProps->setCreator($title);
        $objProps->setTitle($title);
        $objProps->setSubject($title);
        $objProps->setDescription($title);

        $objExcel->createSheet();
        $objExcel->setActiveSheetIndex(0);
        $objExcel->getActiveSheet()->setTitle('Ranking');
        $objActSheet = $objExcel->getActiveSheet();
        $objExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);

        $objActSheet->setCellValue('A1', '# of Orders');
        $objActSheet->setCellValue('B1', 'Sales (Order Amount)');
        $objActSheet->setCellValue('C1', 'Store Name');
        $objActSheet->setCellValue('D1', 'Store ID');
        $objActSheet->setCellValue('E1', 'Commission %');
        $index = 2;

        $store_id_list = array();
        foreach ($list as $store) {
            $objActSheet->setCellValueExplicit('A' . $index, $store['count'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
            $objActSheet->setCellValueExplicit('B' . $index, floatval(sprintf("%.2f", $store['sum'])), PHPExcel_Cell_DataType::TYPE_NUMERIC);
            $objActSheet->setCellValueExplicit('C' . $index, $store['store_name']);
            $objActSheet->setCellValueExplicit('D' . $index, $store['store_id']);
            $objActSheet->setCellValueExplicit('E' . $index, $store['proportion']."%");
            $index++;

            $store_id_list[] = $store['store_id'];
        }

        foreach ($store_list as $store){
            if(!in_array($store['store_id'],$store_id_list)){
                $merchant = D('Merchant')->where(array('mer_id'=>$store['mer_id']))->find();
                if($merchant['status'] == 1) {
                    $objActSheet->setCellValueExplicit('A' . $index, 0, PHPExcel_Cell_DataType::TYPE_NUMERIC);
                    $objActSheet->setCellValueExplicit('B' . $index, 0.00, PHPExcel_Cell_DataType::TYPE_NUMERIC);
                    $objActSheet->setCellValueExplicit('C' . $index, $store['name']);
                    $objActSheet->setCellValueExplicit('D' . $index, $store['store_id']);
                    $objActSheet->setCellValueExplicit('E' . $index, $store['proportion']."%");
                    $index++;
                }
            }
        }

        //输出
        $objWriter = new PHPExcel_Writer_Excel5($objExcel);
        ob_end_clean();
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header('Content-Disposition:attachment;filename="'.$title.'.xls"');
        header("Content-Transfer-Encoding:binary");
        $objWriter->save('php://output');
        exit();
    }

    public function user_ranking(){
        set_time_limit(0);
        require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
        $title = 'User Ranking';

        if(!$_GET['begin_time'] || !$_GET['end_time']){
            $this->error(L('J_SPECIFY_TIME'));
        }else{
            if (strtotime($_GET['begin_time']." 00:00:00")>strtotime($_GET['end_time']." 23:59:59")) {
                $this->error("结束时间应大于开始时间");
            }

            $condition_where ="where o.is_del=0  AND (o.status=2 or o.status=3) and o.paid=1";
            $title .= '('.$_GET['begin_time'].' - '.$_GET['end_time'].')';
            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $condition_where .=  " AND (o.create_time BETWEEN ".$period[0].' AND '.$period[1].")";
        }

        if($_GET['city_id']){
            $condition_where .= " AND s.city_id=".$_GET['city_id'];
            $city = D('Area')->where(array('area_id'=>$_GET['city_id']))->find();
            $title .= ' - '.$city['area_name'];

            $where['city_id'] = $_GET['city_id'];
        }

        $where['uid'] = array('neq',0);

        $sql = "SELECT COUNT(o.order_id) as count,SUM(o.price) as sum,o.username,u.add_time,u.uid FROM " . C('DB_PREFIX') . "shop_order AS o LEFT JOIN " . C('DB_PREFIX') . "merchant_store AS s ON s.store_id=o.store_id LEFT JOIN " . C('DB_PREFIX') . "user as u ON u.uid=o.uid ".$condition_where." GROUP BY o.uid ORDER BY SUM(o.price) DESC";
        $list = D()->query($sql);

        $objExcel = new PHPExcel();
        $objProps = $objExcel->getProperties();
        // 设置文档基本属性
        $objProps->setCreator($title);
        $objProps->setTitle($title);
        $objProps->setSubject($title);
        $objProps->setDescription($title);

        $objExcel->createSheet();
        $objExcel->setActiveSheetIndex(0);
        $objExcel->getActiveSheet()->setTitle('Ranking');
        $objActSheet = $objExcel->getActiveSheet();
        $objExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

        $objActSheet->setCellValue('A1', '# of Orders');
        $objActSheet->setCellValue('B1', 'Sales (Order Amount)');
        $objActSheet->setCellValue('C1', 'Customer Name');
        $objActSheet->setCellValue('D1', 'User ID');
        $objActSheet->setCellValue('E1', 'Registration Time');
        $index = 2;

        foreach ($list as $store) {
            $objActSheet->setCellValueExplicit('A' . $index, $store['count'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
            $objActSheet->setCellValueExplicit('B' . $index, floatval(sprintf("%.2f", $store['sum'])), PHPExcel_Cell_DataType::TYPE_NUMERIC);
            $objActSheet->setCellValueExplicit('C' . $index, $store['username']);
            $objActSheet->setCellValueExplicit('D' . $index, $store['uid']);
            $objActSheet->setCellValueExplicit('E' . $index, date('Y-m-d',$store['add_time']));
            $index++;
        }

        //输出
        $objWriter = new PHPExcel_Writer_Excel5($objExcel);
        ob_end_clean();
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header('Content-Disposition:attachment;filename="'.$title.'.xls"');
        header("Content-Transfer-Encoding:binary");
        $objWriter->save('php://output');
        exit();
    }

    public function user() {
        if(!$_GET['begin_time'] || !$_GET['end_time']){
            $this->error(L('J_SPECIFY_TIME'));
        }else {
            set_time_limit(0);
            require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
            $title = 'User Info';
            $objExcel = new PHPExcel();
            $objProps = $objExcel->getProperties();
            // 设置文档基本属性
            $objProps->setCreator($title);
            $objProps->setTitle($title);
            $objProps->setSubject($title);
            $objProps->setDescription($title);

            // 设置当前的sheet
            $begin_time = strtotime($_GET['begin_time']." 00:00:00");
            $end_time = strtotime($_GET['end_time']." 23:59:59");

            $where['add_time'] = array('between',array($begin_time,$end_time));

            if($_GET['status'] != -1){
                $where['status'] = $_GET['status'];
            }

            $database_user = D('User');
            $count_user = $database_user->where($where)->count();

            $length = ceil($count_user / 1000);
            for ($i = 0; $i < $length; $i++) {
                $i && $objExcel->createSheet();
                $objExcel->setActiveSheetIndex($i);

                //$objExcel->getActiveSheet()->setTitle('第' . ($i + 1) . '个一千个用户');
                $objExcel->getActiveSheet()->setTitle( strval($i*1000+1).' - '.strval($i*1000+1000));
                $objActSheet = $objExcel->getActiveSheet();

                $objActSheet->setCellValue('A1', 'User ID');
                $objActSheet->setCellValue('B1', 'Name');
                //$objActSheet->setCellValue('C1', ' ');
                $objActSheet->setCellValue('C1', 'Phone');
                $objActSheet->setCellValue('D1', 'Email');
//                $objActSheet->setCellValue('F1', '省份');
//                $objActSheet->setCellValue('G1', '城市');
//                $objActSheet->setCellValue('H1', 'QQ');
                $objActSheet->setCellValue('E1', 'Registration Time');
//                $objActSheet->setCellValue('J1', '注册IP');
//                $objActSheet->setCellValue('K1', '最后登录时间');
//                $objActSheet->setCellValue('L1', '最后登录IP');
//                $objActSheet->setCellValue('M1', $this->config['score_name']);
//                $objActSheet->setCellValue('N1', '余额');
//                $objActSheet->setCellValue('O1', '不可提现的余额');
//                $objActSheet->setCellValue('P1', '是否手机认证');
//                $objActSheet->setCellValue('Q1', '是否关注公众号');
//                $objActSheet->setCellValue('R1', '账号是否正常');


                $user_list = $database_user->field(true)->where($where)->limit($i * 1000 . ',1000')->order('add_time desc')->select();
                if (!empty($user_list)) {
                    import('ORG.Net.IpLocation');
                    $IpLocation = new IpLocation();
                    $index = 2;
                    foreach ($user_list as $value) {

                        $objActSheet->setCellValueExplicit('A' . $index, $value['uid']);
                        $objActSheet->setCellValueExplicit('B' . $index, $value['nickname']);
                        //$objActSheet->setCellValueExplicit('C' . $index, $value['truename']);
                        $objActSheet->setCellValueExplicit('C' . $index, $value['phone'] . ' ');
                        $objActSheet->setCellValueExplicit('D' . $index, $value['email']);
                        //$sex = $value['sex'] == 0 ? '未知' : ($value['sex'] == 1 ? '男' : '女');
                        //$objActSheet->setCellValueExplicit('E' . $index, $sex);

//                        $objActSheet->setCellValueExplicit('F' . $index, $value['province']);
//                        $objActSheet->setCellValueExplicit('G' . $index, $value['city']);
//                        $objActSheet->setCellValueExplicit('H' . $index, $value['qq'] . ' ');
                        $objActSheet->setCellValueExplicit('E' . $index, date('Y-m-d H:i:s', $value['add_time']));

//                        $last_location = $IpLocation->getlocation(long2ip($value['add_ip']));
//                        $add_ip = iconv('GBK', 'UTF-8', $last_location['country']);
//                        $objActSheet->setCellValueExplicit('J' . $index, $add_ip);
//
//                        $objActSheet->setCellValueExplicit('K' . $index, date('Y-m-d H:i:s', $value['last_time']));
//
//                        $last_location = $IpLocation->getlocation(long2ip($value['last_ip']));
//                        $last_ip = iconv('GBK', 'UTF-8', $last_location['country']);
//                        $objActSheet->setCellValueExplicit('L' . $index, $last_ip);
//
//                        $objActSheet->setCellValueExplicit('M' . $index, $value['score_count'] . ' ');
//                        $objActSheet->setCellValueExplicit('N' . $index, $value['now_money'] . ' ');
//                        $objActSheet->setCellValueExplicit('O' . $index, $value['score_recharge_moeny'] . ' ');
//                        $is_check_phone = $value['is_check_phone'] == 0 ? '否' : '是';
//                        $objActSheet->setCellValueExplicit('P' . $index, $is_check_phone);
//                        $is_follow = $value['is_follow'] ? '是' : '否';
//                        $objActSheet->setCellValueExplicit('Q' . $index, $is_follow);
//                        $status = $value['status'] ? '正常' : '禁用';
//                        $objActSheet->setCellValueExplicit('R' . $index, $status);

                        $index++;
                    }
                }
                sleep(2);
            }
            //输出
            $objWriter = new PHPExcel_Writer_Excel5($objExcel);
            ob_end_clean();
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
            header("Content-Type:application/force-download");
            header("Content-Type:application/vnd.ms-execl");
            header("Content-Type:application/octet-stream");
            header("Content-Type:application/download");
            //header('Content-Disposition:attachment;filename="' . $title . '_' . date("Y-m-d h:i:sa", time()) . '.xls"');
            header('Content-Disposition:attachment;filename="' . $title . '_' . $_GET['begin_time'].' - '.$_GET['end_time']. '.xls"');
            header("Content-Transfer-Encoding:binary");
            $objWriter->save('php://output');
            exit();
        }
    }

    public function order_store()
    {
        set_time_limit(0);
        require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
        $title = 'Orders by Store';
        $objExcel = new PHPExcel();
        $objProps = $objExcel->getProperties();
        // 设置文档基本属性
        $objProps->setCreator($title);
        $objProps->setTitle($title);
        $objProps->setSubject($title);
        $objProps->setDescription($title);

        // 设置当前的sheet

        $where = array();
        //$condition_where = 'WHERE o.store_id = '.$_GET['store_id'];
        //$where['store_id'] =$_GET['store_id'];


        if(!empty($_GET['keyword'])){
            if ($_GET['searchtype'] == 'sid') {
                $where['store_id'] = htmlspecialchars($_GET['keyword']);
                $condition_where = 'WHERE o.store_id = "'. htmlspecialchars($_GET['keyword']).'"';
            }
        }else{
            $this->error("Please Input Store ID");
        }

//        $status = isset($_GET['status']) ? intval($_GET['status']) : -1;
//        $type = isset($_GET['type']) && $_GET['type'] ? $_GET['type'] : '';
//        $sort = isset($_GET['sort']) && $_GET['sort'] ? $_GET['sort'] : '';
//        $pay_type = isset($_GET['pay_type']) && $_GET['pay_type'] ? $_GET['pay_type'] : '';
//        if ($sort != 'DESC' && $sort != 'ASC') $sort = '';
//        if ($type != 'price' && $type != 'pay_time') $type = '';
//
//        if($status == 100){
//            $where['paid'] = 0;
//            $condition_where .= ' AND o.paid=0';
//        }else if ($status != -1) {
//            $where['status'] = $status;
//            $condition_where .= ' AND o.status='.$status;
//        }else if($status == -1){
//            $where['status'] = array(array('gt', 1), array('lt', 4));
//            $condition_where .= ' AND (o.status=2 or o.status=3)';
//        }
//
//        if($pay_type&&$pay_type!='balance'){
//            $where['pay_type'] = $pay_type;
//            $condition_where .= ' AND o.pay_type="'.$pay_type.'"';
//        }else if($pay_type=='balance'){
//            $where['_string'] = "(`balance_pay`<>0 OR `merchant_balance` <> 0 )";
//            $condition_where .= ' AND (`o`.`balance_pay`<>0 OR `o`.`merchant_balance` <> 0 )';
//        }

        if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
            if (strtotime($_GET['begin_time']." 00:00:00")>strtotime($_GET['end_time']." 23:59:59")) {
                $this->error("结束时间应大于开始时间");
            }
            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $where['_string'] =( $where['_string']?' AND ':''). " (create_time BETWEEN ".$period[0].' AND '.$period[1].")";
            $condition_where .=  " AND (o.create_time BETWEEN ".$period[0].' AND '.$period[1].")";
        }
        $condition_where.=" AND o.is_del=0";
        $where['is_del'] = 0;
        $condition_where .= " AND (o.status BETWEEN 2 AND 3)";
        $where['status'] = array('between',array(2,3));
        $count = D('Shop_order')->where($where)->count();

        $length = ceil($count / 1000);
        for ($i = 0; $i < $length; $i++) {
            $i && $objExcel->createSheet();
            $objExcel->setActiveSheetIndex($i);
            $objExcel->getActiveSheet()->setTitle('第' . ($i+1) . '个一千个订单信息');
            $objActSheet = $objExcel->getActiveSheet();
            $objExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);

            $objActSheet->setCellValue('A1', 'Order ID|订单编号');
            $objActSheet->setCellValue('B1', 'Item|商品名称');
            $objActSheet->setCellValue('C1', 'Quantity|数量');
            $objActSheet->setCellValue('D1', 'Price|单价');
            $objActSheet->setCellValue('E1', 'Customer Name|客户姓名');
            $objActSheet->setCellValue('F1', 'Subtotal|商品总价（税前）');//无
            $objActSheet->setCellValue('G1', 'Tax on Subtotal|商品税费');
            $objActSheet->setCellValue('H1', 'Packing Fee|包装费');
            $objActSheet->setCellValue('I1', 'Tax on Packing Fee|包装税费');
            $objActSheet->setCellValue('J1', 'Bottle Deposit');
            $objActSheet->setCellValue('K1', 'Merchant Discount|商家优惠');
            $objActSheet->setCellValue('L1', 'Time|时间');

            //$sql = "SELECT  o.*, m.name AS merchant_name,d.name as good_name,d.price as good_price ,d.unit,d.cost_price, d.num as good_num, s.name AS store_name FROM " . C('DB_PREFIX') . "shop_order AS o INNER JOIN " . C('DB_PREFIX') . "merchant_store AS s ON s.store_id=o.store_id INNER JOIN " . C('DB_PREFIX') . "merchant AS m ON `s`.`mer_id`=`m`.`mer_id` INNER JOIN " . C('DB_PREFIX') . "shop_order_detail AS d ON `d`.`order_id`=`o`.`order_id` ".$condition_where." ORDER BY o.order_id DESC LIMIT " . $i * 1000 . ",1000";
            $sql = "SELECT  o.*, m.name AS merchant_name,g.name as good_name,g.tax_num as good_tax,g.deposit_price,s.tax_num as store_tax,d.price as good_price ,d.unit,d.cost_price, d.num as good_num, s.name AS store_name FROM " . C('DB_PREFIX') . "shop_order AS o LEFT JOIN " . C('DB_PREFIX') . "merchant_store AS s ON s.store_id=o.store_id LEFT JOIN " . C('DB_PREFIX') . "merchant AS m ON `s`.`mer_id`=`m`.`mer_id` LEFT JOIN " . C('DB_PREFIX') . "shop_order_detail AS d ON `d`.`order_id`=`o`.`order_id`  LEFT JOIN " . C('DB_PREFIX') . "shop_goods AS g ON `g`.`goods_id`=`d`.`goods_id` ".$condition_where." ORDER BY o.order_id DESC LIMIT " . $i * 1000 . ",1000";

            $result_list = D()->query($sql);
            //计算订单税费及押金
            $all_tax = 0;
            $all_deposit = 0;
            $all_record = array();
            $record_id = '';
            $freight_tax = 0;
            $packing_tax = 0;
            $total_tax = 0;
            $curr_cash = 0;
            //subtotal
            $total_goods_price = 0;
            $total_goods_tax = 0;
            $total_freight_price = 0;
            $total_freight_tax = 0;
            $total_packing_price = 0;
            $total_packing_tax = 0;
            $total_all_tax = 0;
            $total_deposit = 0;
            $total_all_price = 0;
            $total_cash = 0;
            $total_merchant_reduce = 0;

            //结束循环后是否存储最后一张订单，如果最后一张是代客下单为 false;
            $is_last = true;
            foreach ($result_list as &$val){
                $curr_order = $val['real_orderid'];
                if($val['uid'] == 0){//当用户ID(uid)为0时 -- 代客下单
                    //记录上一张订单的税费和押金
                    $all_record[$curr_order]['all_tax'] = $val['discount_price'];
                    $all_record[$curr_order]['all_deposit'] = $val['packing_charge'];
                    $val['packing_charge'] = 0;
                    $all_record[$curr_order]['freight_tax'] = $val['freight_charge']*$val['store_tax']/100;
                    $all_record[$curr_order]['packing_tax'] = $val['packing_charge']*$val['store_tax']/100;
                    $all_record[$curr_order]['total_tax'] = $val['discount_price'] + ($val['freight_charge']+$val['packing_charge'])*$val['store_tax']/100;

                    $all_record[$curr_order]['cash'] = $val['price'];
                    $is_last = false;

                    $total_goods_price += $val['goods_price'];
                    $total_goods_tax += $val['discount_price'];
                    $total_freight_price += $val['freight_charge'];
                    $total_freight_tax += $val['freight_charge']*$val['store_tax']/100;
                    $total_packing_price += $val['packing_charge'];
                    $total_packing_tax += $val['packing_charge']*$val['store_tax']/100;
                    $total_all_tax += $all_record[$curr_order]['total_tax'];
                    $total_deposit += $all_record[$curr_order]['all_deposit'];
                    $total_all_price += $val['price'];
                    $total_cash += $val['price'];
                    $total_merchant_reduce += $val['merchant_reduce'];
                }else{
                    if($curr_order != $record_id){
                        //记录上一张订单的税费和押金
                        if($record_id != '') {
                            $all_record[$record_id]['all_tax'] = $all_tax;
                            $all_record[$record_id]['all_deposit'] = $all_deposit;
                            $all_record[$record_id]['total_tax'] = $all_tax + $all_record[$record_id]['freight_tax'] + $all_record[$record_id]['packing_tax'];

                            $total_goods_tax += $all_tax;
                            $total_deposit += $all_deposit;
                            $total_all_tax += $all_record[$record_id]['total_tax'];
                        }
                        //记录最新订单的基本数值
                        $total_goods_price += $val['goods_price'];
                        $total_freight_price += $val['freight_charge'];
                        $total_freight_tax += $val['freight_charge']*$val['store_tax']/100;
                        $total_packing_price += $val['packing_charge'];
                        $total_packing_tax += $val['packing_charge']*$val['store_tax']/100;
                        $total_all_price += $val['price'];
                        $total_merchant_reduce += $val['merchant_reduce'];

                        $all_record[$curr_order]['freight_tax'] = $val['freight_charge']*$val['store_tax']/100;
                        $all_record[$curr_order]['packing_tax'] = $val['packing_charge']*$val['store_tax']/100;
                        if($val['pay_type'] == 'offline' || $val['pay_type'] == 'cash') {
                            $all_record[$curr_order]['cash'] = $val['price'];
                            $total_cash += $val['price'];
                        }

                        //清空商品税费
                        $all_tax = 0;//($val['freight_charge'] + $val['packing_charge'])*$val['store_tax']/100;
                        //清空押金
                        $all_deposit = 0;
                    }

                    $all_tax += $val['good_price'] * $val['good_tax']/100*$val['good_num'];
                    $all_deposit += $val['deposit_price']*$val['good_num'];
                    $total_tax = $all_tax + ($val['freight_charge']+$val['packing_charge'])*$val['store_tax']/100;

                    $record_id = $curr_order;
                    $is_last = true;
                }
            }
            //记录最后一张订单
            if ($is_last){
                $all_record[$record_id]['all_tax'] = $all_tax;
                $all_record[$record_id]['all_deposit'] = $all_deposit;
                $all_record[$record_id]['total_tax'] = $total_tax;

                $total_goods_tax += $all_tax;
                $total_deposit += $all_deposit;
                $total_all_tax += $total_tax;
            }

            ////
            $tmp_id = 0;
            if (!empty($result_list)) {
                $index = 1;
                foreach ($result_list as $value) {
                    if($tmp_id == $value['real_orderid']){
                        $objActSheet->setCellValueExplicit('A' . $index, '');//Order Number|订单编号
                        $objActSheet->setCellValueExplicit('B' . $index, $value['good_name']);//Item|商品名称
                        $objActSheet->setCellValueExplicit('C' . $index, $value['good_num'],PHPExcel_Cell_DataType::TYPE_NUMERIC);//数量
                        $objActSheet->setCellValueExplicit('D' . $index, $value['good_price'],PHPExcel_Cell_DataType::TYPE_NUMERIC);//单价
                        $objActSheet->setCellValueExplicit('E' . $index, '');//客户姓名
                        $objActSheet->setCellValueExplicit('F' . $index, '',PHPExcel_Cell_DataType::TYPE_NUMERIC);//商品总价（税前）//无
                        $objActSheet->setCellValueExplicit('G' . $index, '',PHPExcel_Cell_DataType::TYPE_NUMERIC);//商品总税
                        $objActSheet->setCellValueExplicit('H' . $index, '',PHPExcel_Cell_DataType::TYPE_NUMERIC);//Packing Fee|包装费
                        $objActSheet->setCellValueExplicit('I' . $index, '',PHPExcel_Cell_DataType::TYPE_NUMERIC);//Tax on Packing Fee
                        $objActSheet->setCellValueExplicit('J' . $index, '',PHPExcel_Cell_DataType::TYPE_NUMERIC);//Bottle Deposit
                        $objActSheet->setCellValueExplicit('K' . $index, '');//商家优惠
                        $objActSheet->setCellValueExplicit('L' . $index, '');//时间
                        $index++;
                    }else{
                        $index++;
                        $objActSheet->setCellValueExplicit('A' . $index, $value['order_id']);//订单编号
                        $objActSheet->setCellValueExplicit('B' . $index, $value['good_name']);//商品名称
                        $objActSheet->setCellValueExplicit('C' . $index, $value['good_num'],PHPExcel_Cell_DataType::TYPE_NUMERIC);//数量
                        $objActSheet->setCellValueExplicit('D' . $index, $value['good_price'],PHPExcel_Cell_DataType::TYPE_NUMERIC);//单价
                        $objActSheet->setCellValueExplicit('E' . $index, $value['username']);//客户姓名
                        $objActSheet->setCellValueExplicit('F' . $index, $value['goods_price'],PHPExcel_Cell_DataType::TYPE_NUMERIC);//商品总价（税前）
                        $objActSheet->setCellValueExplicit('G' . $index, floatval(sprintf("%.2f", $all_record[$value['real_orderid']]['all_tax'])),PHPExcel_Cell_DataType::TYPE_NUMERIC);//商品税
                        $objActSheet->setCellValueExplicit('H' . $index, floatval(sprintf("%.2f", $value['packing_charge'])),PHPExcel_Cell_DataType::TYPE_NUMERIC);//Packing Fee|包装费
                        $objActSheet->setCellValueExplicit('I' . $index, floatval(sprintf("%.2f", $all_record[$value['real_orderid']]['packing_tax'])),PHPExcel_Cell_DataType::TYPE_NUMERIC);//Packing Fee|包装费
                        $objActSheet->setCellValueExplicit('J' . $index, floatval(sprintf("%.2f", $all_record[$value['real_orderid']]['all_deposit'])),PHPExcel_Cell_DataType::TYPE_NUMERIC);//Bottle Deposit
                        $objActSheet->setCellValueExplicit('K' . $index, floatval(sprintf("%.2f",$value['merchant_reduce'])),PHPExcel_Cell_DataType::TYPE_NUMERIC);//商家优惠
                        $objActSheet->setCellValueExplicit('L' . $index, $value['use_time'] ? date('Y-m-d H:i:s', $value['use_time']) : '');//送达时间
                        $index++;
                    }
                    $tmp_id = $value['real_orderid'];

                }
                //添加最后一行 subtotal
                $objActSheet->setCellValueExplicit('A' . $index, 'Subtotal');//订单编号
                $objActSheet->setCellValueExplicit('B' . $index, '');//商品名称
                $objActSheet->setCellValueExplicit('C' . $index, '');//数量
                $objActSheet->setCellValueExplicit('D' . $index, '');//单价
                $objActSheet->setCellValueExplicit('E' . $index, '');//客户姓名
                $objActSheet->setCellValueExplicit('F' . $index, floatval(sprintf("%.2f", $total_goods_price)),PHPExcel_Cell_DataType::TYPE_NUMERIC);//商品总价（税前）////无
                $objActSheet->setCellValueExplicit('G' . $index, floatval(sprintf("%.2f", $total_goods_tax)),PHPExcel_Cell_DataType::TYPE_NUMERIC);//商品总税
                $objActSheet->setCellValueExplicit('H' . $index, floatval(sprintf("%.2f", $total_packing_price)),PHPExcel_Cell_DataType::TYPE_NUMERIC);//Packing Fee|包装费
                $objActSheet->setCellValueExplicit('I' . $index, floatval(sprintf("%.2f", $total_packing_tax)),PHPExcel_Cell_DataType::TYPE_NUMERIC);//Tax on Packing Fee|包装费税
                $objActSheet->setCellValueExplicit('J' . $index, floatval(sprintf("%.2f", $total_deposit)),PHPExcel_Cell_DataType::TYPE_NUMERIC);//Bottle Deposit
                $objActSheet->setCellValueExplicit('K' . $index, floatval(sprintf("%.2f", $total_merchant_reduce)),PHPExcel_Cell_DataType::TYPE_NUMERIC);//商家优惠
                $objActSheet->setCellValueExplicit('L' . $index, '');//送达时间
            }
            sleep(2);
        }
        //输出
        $objWriter = new PHPExcel_Writer_Excel5($objExcel);
        ob_end_clean();
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header('Content-Disposition:attachment;filename="'.$title.'_' . date("Y-m-d h:i:sa", time()) . '.xls"');
        header("Content-Transfer-Encoding:binary");
        $objWriter->save('php://output');
        exit();
    }

    public function courier_info(){
        if($_GET['status'] != -1){
            $where['status'] = $_GET['status'];
        }

        $where['group'] = 1;

        $list = D('Deliver_user')->where($where)->select();
        foreach ($list as &$deliver){
            $area = D('Area')->where(array('area_id'=>$deliver['city_id']))->find();
            $deliver['city_name'] = $area['area_name'];

            $other = D('Deliver_img')->where(array('uid'=>$deliver['uid']))->find();
            $deliver['sin_num'] = $other['sin_num'];

            $deliver['status_name'] = $deliver['status'] == 1 ? 'Active' : 'Inactive';
        }

        require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
        $title = 'Courier Info';
        $objExcel = new PHPExcel();
        $objProps = $objExcel->getProperties();
        // 设置文档基本属性
        $objProps->setCreator($title);
        $objProps->setTitle($title);
        $objProps->setSubject($title);
        $objProps->setDescription($title);

        $objExcel->createSheet();
        $objExcel->setActiveSheetIndex(0);

        $objExcel->getActiveSheet()->setTitle($title);
        $objActSheet = $objExcel->getActiveSheet();

        $objActSheet->setCellValue('A1', 'Driver ID');
        $objActSheet->setCellValue('B1', 'Status');
        $objActSheet->setCellValue('C1', 'First Name');
        $objActSheet->setCellValue('D1', 'Last Name');
        $objActSheet->setCellValue('E1', 'Phone #');
        $objActSheet->setCellValue('F1', 'Email Address');
        $objActSheet->setCellValue('G1', 'City');
        $objActSheet->setCellValue('H1', 'Address');
        $objActSheet->setCellValue('I1', 'Date of Birth');
        $objActSheet->setCellValue('J1', 'SIN#');

        $index = 2;
        foreach ($list as $k=>$v){
//            $show_list[$k]['total'] = $v['tip'] + $v['freight'] - $v['cash'];
            $objActSheet->setCellValueExplicit('A'.$index,$v['uid']);
            $objActSheet->setCellValueExplicit('B'.$index,$v['status_name']);
            $objActSheet->setCellValueExplicit('C'.$index,$v['name']);
            $objActSheet->setCellValueExplicit('D'.$index,$v['family_name']);
            $objActSheet->setCellValueExplicit('E'.$index,$v['phone']);
            $objActSheet->setCellValueExplicit('F'.$index,$v['email']);
            $objActSheet->setCellValueExplicit('G'.$index,$v['city_name']);
            $objActSheet->setCellValueExplicit('H'.$index,$v['site']);
            $objActSheet->setCellValueExplicit('I'.$index,$v['birthday']);
            $objActSheet->setCellValueExplicit('J'.$index,$v['sin_num']);
            $index++;
        }


        $objWriter = new PHPExcel_Writer_Excel5($objExcel);
        ob_end_clean();
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header('Content-Disposition:attachment;filename="'.$title.'_' . date("Y-m-d h:i:s", time()) . '.xls"');
        header("Content-Transfer-Encoding:binary");
        $objWriter->save('php://output');
        exit();
    }

    public function courier_pay(){
        $b_date = $_GET['begin_time'].' 00:00:00';
        $e_date = $_GET['end_time'].' 24:00:00';

        $b_time = strtotime($b_date);
        $e_time = strtotime($e_date);

        $sql = "SELECT s.order_id, s.create_time,s.uid,s.freight_charge, u.name,u.family_name,u.city_id as user_city_id, u.phone,u.remark,o.tip_charge,o.price,o.pay_type,o.coupon_price,o.delivery_discount,o.merchant_reduce FROM " . C('DB_PREFIX') . "deliver_supply AS s INNER JOIN " . C('DB_PREFIX') . "merchant_store AS m ON m.store_id=s.store_id LEFT JOIN " . C('DB_PREFIX') . "deliver_user AS u ON s.uid=u.uid LEFT JOIN " . C('DB_PREFIX') . "shop_order AS o ON s.order_id=o.order_id";

        $sql .= ' where s.status = 5 and s.create_time >='.$b_time.' and s.create_time <='.$e_time.' and o.is_del = 0';
        $sql .= ' order by s.uid';

        $list = D()->query($sql);

        $show_list = array();

        foreach ($list as $k=>$v){
            $area = D('Area')->where(array('area_id'=>$v['user_city_id']))->find();
            //$show_list[$v['uid']] = array();
            $show_list[$v['uid']]['id'] = $v['uid'];
            $show_list[$v['uid']]['name'] = $v['name'];
            $show_list[$v['uid']]['family_name'] = $v['family_name'];
            $show_list[$v['uid']]['city_name'] = $area['area_name'];
            $show_list[$v['uid']]['phone'] = $v['phone'];
            $show_list[$v['uid']]['remark'] = $v['remark'];
            $show_list[$v['uid']]['order_num'] = $show_list[$v['uid']]['order_num'] ? $show_list[$v['uid']]['order_num']+ 1 : 1;
            $show_list[$v['uid']]['tip'] = $show_list[$v['uid']]['tip'] ? $show_list[$v['uid']]['tip'] + $v['tip_charge'] : $v['tip_charge'];
            $show_list[$v['uid']]['freight'] = $show_list[$v['uid']]['freight'] ? $show_list[$v['uid']]['freight'] + $v['freight_charge'] : $v['freight_charge'];
            if($v['pay_type'] == 'offline' || $v['pay_type'] == 'Cash'){//统计现金
                if($v['coupon_price'] > 0) $v['price'] = $v['price'] - $v['coupon_price'];
                if($v['delivery_discount'] > 0) $v['price'] = $v['price'] - $v['delivery_discount'];
                if($v['merchant_reduce'] > 0) $v['price'] = $v['price'] - $v['merchant_reduce'];
                $show_list[$v['uid']]['cash'] = $show_list[$v['uid']]['cash'] ? $show_list[$v['uid']]['cash'] + $v['price'] : $v['price'];
            }
        }

        require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
        $title = 'Courier Payroll';
        $objExcel = new PHPExcel();
        $objProps = $objExcel->getProperties();
        // 设置文档基本属性
        $objProps->setCreator($title);
        $objProps->setTitle($title);
        $objProps->setSubject($title);
        $objProps->setDescription($title);

        $objExcel->createSheet();
        $objExcel->setActiveSheetIndex(0);

        $objExcel->getActiveSheet()->setTitle($title);
        $objActSheet = $objExcel->getActiveSheet();

        $objActSheet->setCellValue('A1', 'ID');
        $objActSheet->setCellValue('B1', 'First Name');
        $objActSheet->setCellValue('C1', 'Last Name');
        $objActSheet->setCellValue('D1', 'Phone');
        $objActSheet->setCellValue('E1', 'City');
        $objActSheet->setCellValue('F1', '#of orders');
        $objActSheet->setCellValue('G1', 'Total Tip');
        $objActSheet->setCellValue('H1', 'Total Delivery Fee');
        $objActSheet->setCellValue('I1', 'Total Cash');
        $objActSheet->setCellValue('J1', 'Total');
        $objActSheet->setCellValue('K1', 'Notes');

        $index = 2;
        foreach ($show_list as $k=>$v){
//            $show_list[$k]['total'] = $v['tip'] + $v['freight'] - $v['cash'];
            $objActSheet->setCellValueExplicit('A'.$index,$v['id']);
            $objActSheet->setCellValueExplicit('B'.$index,$v['name']);
            $objActSheet->setCellValueExplicit('C'.$index,$v['family_name']);
            $objActSheet->setCellValueExplicit('D'.$index,$v['phone']);
            $objActSheet->setCellValueExplicit('E'.$index,$v['city_name']);
            $objActSheet->setCellValueExplicit('F'.$index,$v['order_num']);
            $objActSheet->setCellValueExplicit('G'.$index,sprintf("%.2f", $v['tip']));
            $objActSheet->setCellValueExplicit('H'.$index,sprintf("%.2f", $v['freight']));
            $objActSheet->setCellValueExplicit('I'.$index,sprintf("%.2f", $v['cash']));
            $objActSheet->setCellValueExplicit('J'.$index,sprintf("%.2f",$v['tip'] + $v['freight'] - $v['cash']));
            $objActSheet->setCellValueExplicit('K'.$index,$v['remark']);
            $index++;
        }


        $objWriter = new PHPExcel_Writer_Excel5($objExcel);
        ob_end_clean();
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header('Content-Disposition:attachment;filename="'.$title.'_' . date("Y-m-d h:i:s", time()) . '.xls"');
        header("Content-Transfer-Encoding:binary");
        $objWriter->save('php://output');
        exit();
    }

    public function store_info(){
        if($_GET['status'] != -1){
            $where['s.status'] = $_GET['status'];
        }

        if($_GET['city_id'] != 0){
            $where['s.city_id'] = $_GET['city_id'];
        }

        $where['m.status'] = 1;

        $list = D('Merchant_store')->field('s.*,m.name as m_name,m.email as m_email,c.area_name as city_name')->join(' as s left join '.C('DB_PREFIX').'merchant as m on m.mer_id=s.mer_id left join '.C('DB_PREFIX').'area as c on c.area_id=s.city_id')->where($where)->select();
        //var_dump($list);die();
        require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
        $title = 'Store Info';
        $objExcel = new PHPExcel();
        $objProps = $objExcel->getProperties();
        // 设置文档基本属性
        $objProps->setCreator($title);
        $objProps->setTitle($title);
        $objProps->setSubject($title);
        $objProps->setDescription($title);

        $objExcel->createSheet();
        $objExcel->setActiveSheetIndex(0);

        $objExcel->getActiveSheet()->setTitle($title);
        $objActSheet = $objExcel->getActiveSheet();

        $objActSheet->setCellValue('A1', 'Store ID');
        $objActSheet->setCellValue('B1', 'Store Name');
        $objActSheet->setCellValue('C1', 'Phone');
        $objActSheet->setCellValue('D1', 'City');
        $objActSheet->setCellValue('E1', 'Address');
        $objActSheet->setCellValue('F1', 'Commission');
        $objActSheet->setCellValue('G1', 'Affiliated Merchant');
        $objActSheet->setCellValue('H1', 'Merchant Email');

        $index = 2;
        foreach ($list as $k=>$v){
//            $show_list[$k]['total'] = $v['tip'] + $v['freight'] - $v['cash'];
            $objActSheet->setCellValueExplicit('A'.$index,$v['store_id']);
            $objActSheet->setCellValueExplicit('B'.$index,$v['name']);
            $objActSheet->setCellValueExplicit('C'.$index,$v['phone']);
            $objActSheet->setCellValueExplicit('D'.$index,$v['city_name']);
            $objActSheet->setCellValueExplicit('E'.$index,$v['adress']);
            $objActSheet->setCellValueExplicit('F'.$index,$v['proportion']);
            $objActSheet->setCellValueExplicit('G'.$index,$v['m_name']);
            $objActSheet->setCellValueExplicit('H'.$index,$v['m_email']);
            $index++;
        }


        $objWriter = new PHPExcel_Writer_Excel5($objExcel);
        ob_end_clean();
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header('Content-Disposition:attachment;filename="'.$title.'_' . date("Y-m-d h:i:s", time()) . '.xls"');
        header("Content-Transfer-Encoding:binary");
        $objWriter->save('php://output');
        exit();
    }

    public function order_courier()
    {
        set_time_limit(0);

        if(!empty($_GET['keyword'])){
            if ($_GET['searchtype'] == 'uid') {
                $uid = $_GET['keyword'];
            }
        }else{
            $this->error("Please Input Store ID");
        }

        //$uid = isset($_GET['uid']) ? intval($_GET['uid']) : 0;
        $begin_time = isset($_GET['begin_time']) ? htmlspecialchars($_GET['begin_time']) : '';
        $end_time = isset($_GET['end_time']) ? htmlspecialchars($_GET['end_time']) : '';
        $condition_user = array('mer_id' => 0, 'uid' => $uid);
        $user = D('Deliver_user')->field(true)->where($condition_user)->find();
        if (empty($user)) $this->error('不存在的配送员');


        require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';

        if ($begin_time && $end_time) {
            //$title = '【' . $user['name'] . '】在' . $begin_time . '至' . $end_time . '时间段的配送记录列表';
            $title = $user['name'] . '\'s Orders by Courier(' . $begin_time . '-' . $end_time . ')';
        } else {
            $title = $user['name'] . '\'s Orders by Courier';
        }
        $objExcel = new PHPExcel();
        $objProps = $objExcel->getProperties();
        // 设置文档基本属性
        $objProps->setCreator($title);
        $objProps->setTitle($title);
        $objProps->setSubject($title);
        $objProps->setDescription($title);

        // 设置当前的sheet
        $sql_count = "SELECT count(1) AS count FROM " . C('DB_PREFIX') . "merchant_store AS m INNER JOIN " . C('DB_PREFIX') . "deliver_supply AS s ON m.store_id=s.store_id";
        $sql_count .= ' WHERE s.type=0 AND s.uid=' . $uid;
        if ($begin_time && $end_time) {
            $sql_count .= ' AND s.start_time>' . strtotime($begin_time." 00:00:00") . ' AND s.start_time<' . strtotime($end_time." 23:59:59");
        }

        $res_count = D()->query($sql_count);
        $count_order = isset($res_count[0]['count']) ? $res_count[0]['count'] : 0;

        $length = ceil($count_order / 1000);
        for ($i = 0; $i < $length; $i++) {
            $i && $objExcel->createSheet();
            $objExcel->setActiveSheetIndex($i);

            $objExcel->getActiveSheet()->setTitle('第' . ($i+1) . '个一千配送订单');
            $objActSheet = $objExcel->getActiveSheet();

            $objActSheet->setCellValue('A1', 'Order ID');
            $objActSheet->setCellValue('B1', 'Type');
            $objActSheet->setCellValue('C1', 'Store');
            $objActSheet->setCellValue('D1', 'Customer');
            $objActSheet->setCellValue('E1', 'Phone');
            $objActSheet->setCellValue('F1', 'Address');
            $objActSheet->setCellValue('G1', 'Payment');
            $objActSheet->setCellValue('H1', 'Order Amount');
            $objActSheet->setCellValue('I1', 'Delivery Fee');
            $objActSheet->setCellValue('J1', 'Tips');
            $objActSheet->setCellValue('K1', 'Cash Due');
            $objActSheet->setCellValue('L1', 'Total');
            $objActSheet->setCellValue('M1', 'Status');
            $objActSheet->setCellValue('N1', 'Acceptance Time');
            $objActSheet->setCellValue('O1', 'Complete Time');


            $sql = "SELECT s.`supply_id`, s.order_id, s.item, s.name as username, s.phone as userphone, m.name as storename, s.money, s.start_time, s.end_time, s.aim_site, s.pay_type, s.paid, s.status, s.deliver_cash,s.freight_charge,o.tip_charge as tips FROM " . C('DB_PREFIX') . "deliver_supply AS s left JOIN " . C('DB_PREFIX') . "merchant_store AS m ON m.store_id=s.store_id left join ". C('DB_PREFIX') ."shop_order as o on s.order_id=o.order_id";
            $sql .= ' WHERE s.type=0 AND s.uid=' . $uid;
            if ($begin_time && $end_time) {
                $sql .= ' AND s.start_time>' . strtotime($begin_time." 00:00:00") . ' AND s.start_time<' . strtotime($end_time." 23:59:59");
            }

            $sql .= ' ORDER BY s.`supply_id` DESC LIMIT ' . $i * 1000 . ', 1000';

            $supply_list = D()->query($sql);

            if (!empty($supply_list)) {
                import('ORG.Net.IpLocation');
                $IpLocation = new IpLocation();
                $index = 2;
                foreach ($supply_list as $value) {

                    $objActSheet->setCellValueExplicit('A' . $index, $value['order_id']);
                    if ($value['item'] == 0) {
                        $objActSheet->setCellValueExplicit('B' . $index, $this->config['meal_alias_name']);
                    } elseif ($value['item'] == 1) {
                        $objActSheet->setCellValueExplicit('B' . $index, $this->config['waimai_alias_name']);
                    } elseif ($value['item'] == 2) {
                        $objActSheet->setCellValueExplicit('B' . $index, 'Delivery');//$this->config['shop_alias_name']
                    }
                    $objActSheet->setCellValueExplicit('C' . $index, $value['storename']);
                    $objActSheet->setCellValueExplicit('D' . $index, $value['username']);
                    $objActSheet->setCellValueExplicit('E' . $index, $value['userphone'] . ' ');
                    $objActSheet->setCellValueExplicit('F' . $index, $value['aim_site']);
                    if ($value['paid'] == 1) {
                        $objActSheet->setCellValueExplicit('G' . $index, 'Paid');
                    } else {
                        $objActSheet->setCellValueExplicit('G' . $index, 'Unpaid');
                    }

                    $objActSheet->setCellValueExplicit('H' . $index, floatval($value['money']));
                    $objActSheet->setCellValueExplicit('I' . $index, floatval($value['freight_charge']));
                    $objActSheet->setCellValueExplicit('J' . $index, floatval($value['tips']));
                    $objActSheet->setCellValueExplicit('K' . $index, floatval($value['deliver_cash']));
                    $objActSheet->setCellValueExplicit('L' . $index, floatval($value['freight_charge']+$value['tips']-$value['deliver_cash']));
                    switch ($value['status']) {
                        case 1:
                            $value['order_status'] = '<font color="red">等待接单</font>';
                            break;
                        case 2:
                            $value['order_status'] = "接单";
                            break;
                        case 3:
                            $value['order_status'] = "取货";
                            break;
                        case 4:
                            $value['order_status'] = "开始配送";
                            break;
                        case 5:
                            $value['order_status'] = "Complete";
                            break;
                        default:
                            $value['order_status'] = "订单失效";
                            break;
                    }
                    $objActSheet->setCellValueExplicit('M' . $index, $value['order_status']);
                    $objActSheet->setCellValueExplicit('N' . $index, $value['start_time'] ? date('Y-m-d H:i:s', $value['start_time']) : '--');
                    $objActSheet->setCellValueExplicit('O' . $index, $value['end_time'] ? date('Y-m-d H:i:s', $value['end_time']) : '--');

                    $index++;
                }
            }
            sleep(2);
        }
        //输出
        $objWriter = new PHPExcel_Writer_Excel5($objExcel);
        ob_end_clean();
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header('Content-Disposition:attachment;filename="' . $title . '.xls"');
        header("Content-Transfer-Encoding:binary");
        $objWriter->save('php://output');
        exit();
    }

    public function getStoreList(){
        if($_GET['begin_time'] && $_GET['end_time']) {
            $b_date = $_GET['begin_time'] . ' 00:00:00';
            $e_date = $_GET['end_time'] . ' 24:00:00';

            $b_time = strtotime($b_date);
            $e_time = strtotime($e_date);

            $where['o.create_time'] = array('between',array($b_time,$e_time));
        }else{
            $this->error(L('J_SPECIFY_TIME'));
        }

        $where['o.status'] = array('egt',2);

        $list = D('Shop_order')->field('o.store_id,m.name as store_name')->join(' as o left join '.C('DB_PREFIX').'merchant_store as m on m.store_id=o.store_id')->where($where)->group('store_id')->select();

        $this->ajaxReturn($list);
    }
}