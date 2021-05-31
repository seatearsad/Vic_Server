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

    public function order(){
        set_time_limit(0);
        require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
        $title = 'Order Summary';
        $objExcel = new PHPExcel();
        $objProps = $objExcel->getProperties();
        // 设置文档基本属性
        $objProps->setCreator($title);
        $objProps->setTitle($title);
        $objProps->setSubject($title);
        $objProps->setDescription($title);

        // 设置当前的sheet

        $where_store = null;
        if(!empty($_GET['keyword']) && $_GET['searchtype'] == 's_name'){
            $where_store['name'] = array('like', '%'.$_GET['keyword'].'%');
        }

        if ($this->system_session['area_id']) {
            $area_index = $this->system_session['level'] == 1 ? 'area_id' : 'city_id';
            $where_store[$area_index] = $this->system_session['area_id'];
        }

        $store_ids = array();
        $where = array();
        $condition_where = 'WHERE 1=1';
        if ($where_store) {
            $stores = D('Merchant_store')->field('store_id')->where($where_store)->select();
            foreach ($stores as $row) {
                $store_ids[] = $row['store_id'];
            }
            if ($store_ids) {
                $where['store_id'] = array('in', $store_ids);
                //                 $condition_where .= ' AND o.store_id IN ('.explode(',',$store_ids).')';
                $condition_where .= ' AND oo.store_id IN ('.implode(',',$store_ids).')';//implode,explode
            }
        }

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
            }

        }
        $status = isset($_GET['status']) ? intval($_GET['status']) : -1;
        $type = isset($_GET['type']) && $_GET['type'] ? $_GET['type'] : '';
        $sort = isset($_GET['sort']) && $_GET['sort'] ? $_GET['sort'] : '';
        $pay_type = isset($_GET['pay_type']) && $_GET['pay_type'] ? $_GET['pay_type'] : '';
        if ($sort != 'DESC' && $sort != 'ASC') $sort = '';
        if ($type != 'price' && $type != 'pay_time') $type = '';

        if($status == 100){
            $where['paid'] = 0;
            $condition_where .= ' AND oo.paid=0';
            //$condition_where .= ' AND paid=0';
        }else if ($status != -1) {
            $where['status'] = $status;
            $condition_where .= ' AND oo.status='.$status;
            //$condition_where .= ' AND status='.$status;
        }

        if($pay_type&&$pay_type!='balance'){
            $where['pay_type'] = $pay_type;
            $condition_where .= ' AND oo.pay_type="'.$pay_type.'"';
            //$condition_where .= ' AND pay_type="'.$pay_type.'"';
        }else if($pay_type=='balance'){
            $where['_string'] = "(`balance_pay`<>0 OR `merchant_balance` <> 0 )";
            $condition_where .= ' AND (`oo`.`balance_pay`<>0 OR `oo`.`merchant_balance` <> 0 )';
            //$condition_where .= ' AND (`balance_pay`<>0 OR `merchant_balance` <> 0 )';
        }

        if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }
            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $where['_string'] =( $where['_string']?' AND ':''). " (create_time BETWEEN ".$period[0].' AND '.$period[1].")";
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
                foreach ($result_list as $v){
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
                        $objActSheet->setCellValueExplicit('C' . $index, D('Shop_order')->status_list[$value['status']]);//订单状态
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
        $title = 'Order Total';

        if(!$_GET['begin_time'] || !$_GET['end_time']){
            $this->error('请选择时间！');
        }else{
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
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

        $objActSheet->setCellValue('A1', '订单数量');
        $objActSheet->setCellValue('B1', '税前商品总金额');
        $objActSheet->setCellValue('C1', '商品税费总金额');
        $objActSheet->setCellValue('D1', '配送费总金额');
        $objActSheet->setCellValue('E1', '配送费税费总金额');
        $objActSheet->setCellValue('F1', '税前商品抽成总金额');
        $objActSheet->setCellValue('G1', '商品税费抽成总金额');//无
        $objActSheet->setCellValue('H1', '订单总金额');
        $objActSheet->setCellValue('I1', '小费总数');
        $objActSheet->setCellValue('J1', '优惠券金额总数');
        $objActSheet->setCellValue('K1', '免配送费金额总数');
        $index = 2;
        $objActSheet->setCellValueExplicit('A' . $index, $order_count,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objActSheet->setCellValueExplicit('B' . $index, floatval(sprintf("%.2f", ($total_goods_price - $total_reduce))),PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objActSheet->setCellValueExplicit('C' . $index, floatval(sprintf("%.2f", $total_goods_tax)),PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objActSheet->setCellValueExplicit('D' . $index, floatval(sprintf("%.2f", $total_freight_price)),PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objActSheet->setCellValueExplicit('E' . $index, floatval(sprintf("%.2f", $total_freight_tax)),PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objActSheet->setCellValueExplicit('F' . $index, floatval(sprintf("%.2f", $total_goods_price_pro)),PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objActSheet->setCellValueExplicit('G' . $index, floatval(sprintf("%.2f", $total_goods_tax_pro)),PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objActSheet->setCellValueExplicit('H' . $index, floatval(sprintf("%.2f", ($total_all_price - $total_reduce))),PHPExcel_Cell_DataType::TYPE_NUMERIC);
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
            $this->error('请选择时间！');
        }else{
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
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

        $sql = "SELECT COUNT(o.order_id) as count,SUM(o.price) as sum,s.`name` as store_name,s.store_id FROM " . C('DB_PREFIX') . "merchant_store AS s LEFT JOIN " . C('DB_PREFIX') . "shop_order AS o ON s.store_id=o.store_id ".$condition_where." GROUP BY s.store_id ORDER BY SUM(o.price) DESC";
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

        $objActSheet->setCellValue('A1', '订单数量');
        $objActSheet->setCellValue('B1', '销售金额');
        $objActSheet->setCellValue('C1', '店铺名称');
        $index = 2;

        $store_id_list = array();
        foreach ($list as $store) {
            $objActSheet->setCellValueExplicit('A' . $index, $store['count'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
            $objActSheet->setCellValueExplicit('B' . $index, floatval(sprintf("%.2f", $store['sum'])), PHPExcel_Cell_DataType::TYPE_NUMERIC);
            $objActSheet->setCellValueExplicit('C' . $index, $store['store_name']);
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
            $this->error('请选择时间！');
        }else{
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
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

        $sql = "SELECT COUNT(o.order_id) as count,SUM(o.price) as sum,o.username,u.add_time FROM " . C('DB_PREFIX') . "shop_order AS o LEFT JOIN " . C('DB_PREFIX') . "merchant_store AS s ON s.store_id=o.store_id LEFT JOIN " . C('DB_PREFIX') . "user as u ON u.uid=o.uid ".$condition_where." GROUP BY o.uid ORDER BY SUM(o.price) DESC";
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

        $objActSheet->setCellValue('A1', '订单数量');
        $objActSheet->setCellValue('B1', '销售金额');
        $objActSheet->setCellValue('C1', '用户名');
        $objActSheet->setCellValue('D1', '注册时间');
        $index = 2;

        foreach ($list as $store) {
            $objActSheet->setCellValueExplicit('A' . $index, $store['count'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
            $objActSheet->setCellValueExplicit('B' . $index, floatval(sprintf("%.2f", $store['sum'])), PHPExcel_Cell_DataType::TYPE_NUMERIC);
            $objActSheet->setCellValueExplicit('C' . $index, $store['username']);
            $objActSheet->setCellValueExplicit('D' . $index, date('Y-m-d',$store['add_time']));
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
            $title = 'Customer Summary';
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

            $database_user = D('User');
            $count_user = $database_user->where($where)->count();

            $length = ceil($count_user / 1000);
            for ($i = 0; $i < $length; $i++) {
                $i && $objExcel->createSheet();
                $objExcel->setActiveSheetIndex($i);

                //$objExcel->getActiveSheet()->setTitle('第' . ($i + 1) . '个一千个用户');
                $objExcel->getActiveSheet()->setTitle( strval($i*1000+1).' - '.strval($i*1000+1000));
                $objActSheet = $objExcel->getActiveSheet();

                $objActSheet->setCellValue('A1', '用户ID');
                $objActSheet->setCellValue('B1', '昵称');
                $objActSheet->setCellValue('C1', ' ');
                $objActSheet->setCellValue('D1', '手机号');
                $objActSheet->setCellValue('E1', 'Email');
//                $objActSheet->setCellValue('F1', '省份');
//                $objActSheet->setCellValue('G1', '城市');
//                $objActSheet->setCellValue('H1', 'QQ');
                $objActSheet->setCellValue('F1', '注册时间');
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
                        $objActSheet->setCellValueExplicit('C' . $index, $value['truename']);
                        $objActSheet->setCellValueExplicit('D' . $index, $value['phone'] . ' ');
                        $objActSheet->setCellValueExplicit('E' . $index, $value['email']);
                        //$sex = $value['sex'] == 0 ? '未知' : ($value['sex'] == 1 ? '男' : '女');
                        //$objActSheet->setCellValueExplicit('E' . $index, $sex);

//                        $objActSheet->setCellValueExplicit('F' . $index, $value['province']);
//                        $objActSheet->setCellValueExplicit('G' . $index, $value['city']);
//                        $objActSheet->setCellValueExplicit('H' . $index, $value['qq'] . ' ');
                        $objActSheet->setCellValueExplicit('F' . $index, date('Y-m-d H:i:s', $value['add_time']));

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
}