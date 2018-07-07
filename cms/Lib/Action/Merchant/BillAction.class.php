<?php
/*对账管理*/
class BillAction extends BaseAction{

    public function order(){
        $percent = 0;
        $period = 0;
        $time = '';
        $mer_id = $this->merchant_session['mer_id'];
        if (!$_POST['begin_time']) {

            $type = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : 'meal';
        }else{

            $type = isset($_POST['type']) ? htmlspecialchars($_POST['type']) : 'meal';
        }

        if(isset($_POST['begin_time'])&&isset($_POST['end_time'])){
            if ($_POST['begin_time']>$_POST['end_time']) {
                $this->error("结束时间应大于开始时间");
            }
            $period = $_POST['begin_time']==$_POST['end_time']?array(strtotime($_POST['begin_time']." 00:00:00"),strtotime($_POST['begin_time']." 23:59:59")):array(strtotime($_POST['begin_time']),strtotime($_POST['end_time']));
            $time = array('period'=>$period);
            //$time = serialize($time);
            $this->assign('begin_time',$_POST['begin_time']);
            $this->assign('end_time',$_POST['end_time']);
        }
        //$time = unserialize($time);
        if ($time['period']){
            if (is_array($time['period'])) {
                $time_condition = " AND (pay_time BETWEEN ".$time['period'][0].' AND '.$time['period'][1].")";
            }else{
                $time_condition = " AND pay_time=".$time['period'];
            }
        }

        switch($type){
            case 'meal':
                $where = "mer_id=".$mer_id." AND paid=1 AND is_own=0 AND status in (1,2) AND ( balance_pay<>'0.00'  OR score_deducte <> '0.00' OR coupon_price <> '0.00' OR payment_money <>'0.00')".$time_condition;
                break;
            case 'group':
                $where = "mer_id=".$mer_id." AND paid=1 AND is_own=0 AND status in (1,2,6) AND (balance_pay<>'0.00' OR score_deducte <> '0.00' OR coupon_price <> '0.00' OR payment_money <>'0.00')".$time_condition;
                break;
            case 'weidian':
                $where = "mer_id=".$mer_id." AND paid=1 AND pay_type<>'offline'".$time_condition;
                break;
            case 'wxapp':
                $where = "mer_id=".$mer_id." AND paid=1 AND pay_type<>'offline'".$time_condition;
                break;
            case 'appoint':
                $where = "mer_id=".$mer_id." AND paid=1 AND is_own=0 AND pay_type<>'offline' AND service_status=1".$time_condition;
                break;
            case 'store':
                $where = "mer_id=".$mer_id." AND paid=1 AND is_own=0 AND refund=0 AND (payment_money<>'0.00' OR balance_pay<>'0.00' OR score_deducte <> '0.00' OR coupon_price <> '0.00') ".$time_condition;
                break;
            case 'waimai':
                $where = "mer_id=".$mer_id." AND paid=1 AND is_own=0 AND (payment_money<>'0.00' OR balance_pay<>'0.00' OR score_deducte <> '0.00' OR coupon_price <> '0.00') ".$time_condition;
                break;
            case 'shop':
                $where = "mer_id=".$mer_id." AND paid=1 AND is_own=0 AND status IN (2,3)".$time_condition;
                break;
        }
        if($type=='waimai'){
            $un_bill_count = D(ucfirst($type).'_order')->where($where." AND is_pay_bill=0 ")->count();
        }else if($type=='appoint'){
            $un_bill_count = D(ucfirst($type).'_order o')->join(C('DB_PREFIX') ."appoint a ON o.appoint_id = a.appoint_id")->where("o.mer_id=".$mer_id." AND o.paid=1 AND o.is_own=0 AND o.pay_type<>'offline' AND o.service_status=1 AND o.is_pay_bill=0 AND a.payment_status=1")->count();
        }else if($type=='store'){
            $un_bill_count = D(ucfirst($type).'_order')->where($where." AND is_pay_bill=0 AND (payment_money<>'0.00' OR balance_pay<>'0.00') ")->count();
        }else{
            $un_bill_count = D(ucfirst($type).'_order')->where($where." AND is_pay_bill=0 ")->count();
        }

        $merchant = D('Merchant')->field(true)->where(array('mer_id'=> $mer_id))->find();
        if ($merchant['percent']) {
            $percent = $merchant['percent'];
        } elseif ($this->config['platform_get_merchant_percent']) {
            $percent = $this->config['platform_get_merchant_percent'];
        }
        $res = M('Bill_time')->where(array('mer_id'=>$mer_id))->find();
        if($res){
            foreach($res as $key=>$v){
                if(stristr($key,'_time')){
                    $arr[]=$v;
                }
            }
            rsort($arr);
            $bill_time=$arr[0];
            $this->assign('bill_time',$bill_time);
        }
        $this->assign('percent', $percent);
        $result = D("Order")->bill_order($mer_id, $type, 0,$time);
        $this->assign($result);
        $this->assign('un_bill_count',$un_bill_count);
        $this->assign('now_merchant', $merchant);
        $this->assign('mer_id', $mer_id);
        $this->assign('type', $type);
        $this->display();
    }

    public function get_un_bill_count(){
        $mer_id= $_POST['mer_id'];
        $where['meal'] = "mer_id=".$mer_id." AND paid=1 AND is_own=0 AND status in (1,2) AND ( balance_pay<>'0.00 ' OR score_deducte <> '0.00' OR coupon_price <> '0.00' OR payment_money<>'0.00')";
        $un_bill_count['meal'] = D('Meal_order')->where($where['meal']." AND is_pay_bill=0 ")->count();
        $all_bill_money = D('Meal_order')->where($where['meal']." AND is_pay_bill=0 ")->sum('balance_pay+payment_money+score_deducte+coupon_price');

        $where['group'] = "mer_id=".$mer_id." AND paid=1 AND is_own=0 AND status in (1,2,6) AND (balance_pay<>'0.00' OR score_deducte <> '0.00' OR coupon_price <> '0.00' OR payment_money<>'0.00')";
        $un_bill_count['group'] = D('Group_order')->where($where['group']." AND is_pay_bill=0 ")->count();
        $all_bill_money += D('Group_order')->where($where['group']." AND is_pay_bill=0 ")->sum('balance_pay+payment_money+score_deducte+coupon_price-refund_money+refund_fee');

        $where['weidian'] = "mer_id=".$mer_id." AND paid=1 AND pay_type<>'offline'";
        $un_bill_count['weidian'] = D('Weidian_order')->where($where['weidian']." AND is_pay_bill=0 ")->count();
        $all_bill_money += D('Weidian_order')->where($where['weidian']." AND is_pay_bill=0 ")->sum('balance_pay+payment_money');

        $where['wxapp'] = "mer_id=".$mer_id." AND paid=1 AND pay_type<>'offline'";
        $un_bill_count['wxapp'] = D('Wxapp_order')->where($where['wxapp']." AND is_pay_bill=0 ")->count();
        $all_bill_money += D('Wxapp_order')->where($where['wxapp']." AND is_pay_bill=0 ")->sum('balance_pay+payment_money');

        $where['appoint'] = "mer_id=".$mer_id." AND paid=1 AND is_own=0 AND pay_type<>'offline' AND service_status=1";
        $un_bill_count['appoint'] = D('Appoint_order o')->join(C('DB_PREFIX') ."appoint a ON o.appoint_id = a.appoint_id")->where("o.mer_id=".$mer_id." AND o.paid=1 AND o.is_own=0 AND o.pay_type<>'offline' AND o.service_status=1 AND o.is_pay_bill=0 AND a.payment_status=1")->count();
        $all_bill_money += D('Appoint_order o')->join(C('DB_PREFIX') ."appoint a ON o.appoint_id = a.appoint_id")->where("o.mer_id=".$mer_id." AND o.paid=1 AND o.is_own=0 AND o.pay_type<>'offline' AND o.service_status=1 AND o.is_pay_bill=0 AND a.payment_status=1")->sum('o.balance_pay+o.payment_money+o.score_deducte+o.coupon_price');

        $where['store']  = "mer_id=".$mer_id." AND paid=1 AND is_own=0 AND refund=0";
        $un_bill_count['store']  = D('Store_order')->where($where['store']." AND is_pay_bill=0 AND (payment_money<>'0.00' OR balance_pay<>'0.00') ")->count();
        $all_bill_money += D('Store_order')->where($where['store']." AND is_pay_bill=0 AND (payment_money<>'0.00' OR balance_pay<>'0.00') ")->sum('balance_pay+payment_money');

        $where['waimai'] = "mer_id=".$mer_id." AND paid=1 AND is_own=0 ";
        $un_bill_count['waimai'] = D('Waimai_order')->where($where['waimai']." AND is_pay_bill=0  AND (online_pay<>'0.00' OR balance_pay<>'0.00')")->count();
        $all_bill_money += D('Waimai_order')->where($where['waimai']." AND is_pay_bill=0  AND (online_pay<>'0.00' OR balance_pay<>'0.00')")->sum('online_pay+balance_pay');

        $where['shop'] = "mer_id=".$mer_id." AND paid=1 AND is_own=0 AND status IN (2,3) AND ( balance_pay<>'0.00' OR coupon_price<>'0.00' OR payment_money<>'0.00' OR score_deducte<>'0.00')";
        $un_bill_count['shop'] = D('Shop_order')->where($where['shop']." AND is_pay_bill=0 ")->count();
        $all_bill_money += D('Shop_order')->where($where['shop']." AND is_pay_bill=0 ")->sum('balance_pay+payment_money+balance_reduce+coupon_price+score-deducte-no_bill_money');

        $return['all_bill_money'] = $all_bill_money;
        $return['un_bill_count'] = $un_bill_count;
        $this->AjaxReturn($return);
        exit;
    }

    public function billed_list(){
        $mer_id = $this->merchant_session['mer_id'];
        if (!$_POST['begin_time']) {
            $type = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : 'meal';
        }else{
            $type = isset($_POST['type']) ? htmlspecialchars($_POST['type']) : 'meal';
        }
        $bill_info = M('Bill_info');
        $condition_bill['mer_id']=$mer_id;
        $bill_time = M('Bill_time')->where(array('merid'=>$mer_id))->find();
        $this->assign('bill_time',$bill_time);
        $name = 'meal';
        foreach($bill_time as $key=>$val){
            if(stristr($key,'_time')){
                if(!empty($val)){
                    $tmp=explode('_',$key);
                    $name = $tmp[0];
                    break;
                }
            }
        }
        $merchant = D('Merchant')->field(true)->where($condition_bill)->find();
        $condition_bill['name'] = empty($_GET['type'])?$name:$_GET['type'];
        $count_merchant = $bill_info->where($condition_bill)->count();
        import('@.ORG.merchant_page');
        $p = new Page($count_merchant,15);

        if(isset($_POST['begin_time'])&&isset($_POST['end_time'])){
            if ($_POST['begin_time']>$_POST['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }
            $period = $_POST['begin_time']==$_POST['end_time']?array(strtotime($_POST['begin_time']." 00:00:00"),strtotime($_POST['begin_time']." 23:59:59")):array(strtotime($_POST['begin_time']),strtotime($_POST['end_time']));
            $time_condition = " (bill_time BETWEEN ".$period[0].' AND '.$period[1].")";
            $condition_bill['_string']=$time_condition;
            $condition_bill['name']=$type;
            $this->assign('begin_time',$_POST['begin_time']);
            $this->assign('end_time',$_POST['end_time']);
        }

        $bill_list = $bill_info->where($condition_bill)->order('bill_time DESC')->limit($p->firstRow,$p->listRows)->select();
        foreach ($bill_list as $k=>&$v) {
            $v['id_list'] = explode(',',$v['id_list']);
        }
        $pagebar = $p->show();
        $this->assign('now_merchant', $merchant);
        $this->assign('mer_id', $merchant['mer_id']);
        $this->assign('type',$condition_bill['name']);
        $this->assign('pagebar',$pagebar);
        $this->assign('bill_list',$bill_list);
        $this->display();
    }

    public function billed_info(){
        $condition ['id'] = $_GET['id'];
        $res = M('Bill_info')->where($condition)->find();
        $res['count']=count(explode(',',$res['id_list']));

        $this->assign('bill_info',$res);
        $merchant = D('Merchant')->field(true)->where(array('mer_id'=>$res['mer_id']))->find();
        if ($merchant['percent']) {
            $percent = $merchant['percent'];
        } elseif ($this->config['platform_get_merchant_percent']) {
            $percent = $this->config['platform_get_merchant_percent'];
        }
        $this->assign('percent', $percent);
        $this->assign('now_merchant',$merchant );
        $this->assign('mer_id', $merchant['mer_id']);
        $order_list =D('Order')->bill_order($res['mer_id'], $res['name'], 1,'',$res['id_list']);
        $this->assign('order_list',$order_list);
        $this->assign('type',$res['name']);
        $this->assign('mer_id',$res['mer_id']);
        $this->display();
    }

    public function export()
    {
        $mer_id = isset($_GET['mer_id']) ? intval($_GET['mer_id']) : 0;
        $type = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : 'meal';
        $title = '';

        switch ($type) {
            case 'meal':
                $title = '餐饮账单';
                break;
            case 'group':
                $title = '团购账单';
                break;
            case 'weidian':
                $title = '微店账单';
                break;
            case 'wxapp':
                $title = '预约账单';
                break;
            case 'appoint':
                $title = '营销账单';
                break;
            case 'store':
                $title = '收银账单';
                break;
            case 'waimai':
                $title = '外卖账单';
                break;
            case 'shop':
                $title = '快店账单';
                break;
            case 'income':
                $title = '收入明细';
                break;
        }
        require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
        $objExcel = new PHPExcel();
        $objProps = $objExcel->getProperties();
        // 设置文档基本属性
        $objProps->setCreator($title);
        $objProps->setTitle($title);
        $objProps->setSubject($title);
        $objProps->setDescription($title);
        // 设置当前的sheet
        $objExcel->setActiveSheetIndex(0);


        $objExcel->getActiveSheet()->setTitle($type);
        $objActSheet = $objExcel->getActiveSheet();
        $cell_meal    = array('store_name'=>'门店名称','order_id'=>'订单编号','orderid'=>'流水号', 'num'=>'数量', 'order_price'=>'金额','balance_pay'=> '余额支付金额','payment_money'=> '在线支付金额', 'coupon_price'=> '优惠券','score_deducte'=> ''.$this->config['score_name'].'抵扣','pay_time'=>'支付时间','pay_type'=> '支付类型','bill_money'=>'应对账金额');
        $cell_group   = array('store_name'=>'门店名称','real_orderid'=>'订单编号','orderid'=>'流水号', 'num'=>'数量', 'order_price'=>'金额','balance_pay'=> '余额支付金额','payment_money'=> '在线支付金额', 'coupon_price'=> '优惠券','score_deducte'=> ''.$this->config['score_name'].'抵扣','refund_money'=>'退款金额','refund_fee'=>'退款手续费','pay_time'=>'支付时间','pay_type'=> '支付类型','bill_money'=>'应对账金额');
        $cell_appoint = array('store_name'=>'门店名称','order_id'=>'订单编号','orderid'=>'流水号', 'num'=>'数量', 'order_price'=>'金额','balance_pay'=> '余额支付金额','payment_money'=> '在线支付金额', 'coupon_price'=> '优惠券','score_deducte'=> ''.$this->config['score_name'].'抵扣','pay_time'=>'支付时间','pay_type'=> '支付类型','bill_money'=>'应对账金额');
        $cell_shop    = array('store_name'=>'门店名称','real_orderid'=>'订单编号','orderid'=>'流水号', 'num'=>'数量', 'order_price'=>'金额','balance_pay'=> '余额支付金额','payment_money'=> '在线支付金额', 'coupon_price'=> '优惠券','score_deducte'=> ''.$this->config['score_name'].'抵扣','pay_time'=>'支付时间','pay_type'=> '支付类型','bill_money'=>'应对账金额');
        $cell_waimai  = array('store_name'=>'门店名称','order_id'=>'订单编号','orderid'=>'流水号', 'num'=>'数量', 'order_price'=>'金额','balance_pay'=> '余额支付金额','payment_money'=> '在线支付金额', 'pay_time'=>'支付时间','pay_type'=> '支付类型','bill_money'=>'应对账金额');
        $cell_store   = array('store_name'=>'门店名称','order_id'=>'订单编号','orderid'=>'流水号', 'num'=>'数量', 'order_price'=>'金额','balance_pay'=> '余额支付金额','payment_money'=> '在线支付金额', 'pay_time'=>'支付时间','pay_type'=> '支付类型','bill_money'=>'应对账金额');
        $cell_weidian = array('store_name'=>'门店名称','order_id'=>'订单编号','orderid'=>'流水号', 'num'=>'数量', 'order_price'=>'金额','balance_pay'=> '余额支付金额','payment_money'=> '在线支付金额', 'pay_time'=>'支付时间','pay_type'=> '支付类型','bill_money'=>'应对账金额');
        $cell_wxapp   = array('store_name'=>'门店名称','order_id'=>'订单编号','orderid'=>'流水号', 'num'=>'数量', 'order_price'=>'金额','balance_pay'=> '余额支付金额','payment_money'=> '在线支付金额', 'pay_time'=>'支付时间','pay_type'=> '支付类型','bill_money'=>'应对账金额');
        $cell_income   = array('type'=>'类型','order_id'=>'订单编号', 'num'=>'数量', 'money'=>'金额','score'=>'送出'.$this->config['score_name'],'score_count'=>$this->config['score_name'].'使用数量','use_time'=>'记账时间','desc'=>'描述');
        // 开始填充头部
        $cell_name = 'cell_'.$type;
        $cell_count = count($$cell_name);
        $cell_start = 1;
        for($f='A';$f<='Z';$f++,$cell_start++){
            if($cell_start>$cell_count){
                break;
            }
            $col_char[]=$f;
        }
        $col_k=0;
        foreach($$cell_name as $key=>$v){

            $objActSheet->setCellValue($col_char[$col_k].'1', $v);
            $col_k++;
        }
        $i = 2;
        if ($_GET['bill_id']) {
            $res = M('Bill_info')->where(array('id' => $_GET['bill_id']))->find();
            $result = D('Order')->export_order_by_mid($mer_id, $type,1,$res['id_list']);
        }else if($type=='income'){
            $where['mer_id']=$mer_id;
            if($_GET['order_type']&&$_GET['order_type']!='all'){
                $where['type']=$_GET['order_type'];
            }
            if($_GET['order_id']){
                $where['order_id']=$_GET['order_id'];
            }
            if($_GET['store_id']){
                $where['store_id']=$_GET['store_id'];
            }
            if(isset($_GET['begin_time'])&&isset($_GET['end_time'])&&!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
                if ($_GET['begin_time']>$_GET['end_time']) {
                    $this->error_tips("结束时间应大于开始时间");
                }
                $period = $_GET['begin_time']==$_GET['end_time']?array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['begin_time']." 23:59:59")):array(strtotime($_GET['begin_time']),strtotime($_GET['end_time']));
                $time_condition = " (use_time BETWEEN ".$period[0].' AND '.$period[1].")";
                $where['_string']=$time_condition;

            }
            $result = M('Merchant_money_list')->field('type,order_id,num,pow(-1,income+1)*money as money,use_time,desc,score,score_count')->where($where)->order('use_time DESC')->select();
        }else {
            $result = D("Order")->export_order_by_mid($mer_id, $type);
        }
        // dump($result);
        foreach ($result as $row) {
            $col_k=0;
            foreach($$cell_name as $k=>$vv){
                switch($k){
                    case 'order_id':
                        $objActSheet->setCellValue($col_char[$col_k] . $i, $row[$k].' ');
                        break;
                    case 'real_orderid':
                        $objActSheet->setCellValue($col_char[$col_k] . $i, $row[$k].' ');
                        break;
                    case 'orderid':
                        $objActSheet->setCellValue($col_char[$col_k] . $i, $row[$k].' ');
                        break;
                    case 'pay_time':
                        $objActSheet->setCellValue($col_char[$col_k] . $i, $row[$k]?date('Y-m-d H:i:s', $row[$k]) : '');
                        break;
                    case 'use_time':
                        $objActSheet->setCellValue($col_char[$col_k] . $i, $row[$k]?date('Y-m-d H:i:s', $row[$k]) : '');
                        break;
                    case 'desc':
                        $objActSheet->setCellValue($col_char[$col_k] . $i, $row[$k].' ');
                        break;
                    default:
                        $objActSheet->setCellValue($col_char[$col_k] . $i, $row[$k]);
                        break;
                }
                $col_k++;
            }
            if($type!='income'){
                $objActSheet->setCellValue($col_char[$cell_count-1] . $i, $row['balance_pay']+$row['coupon_price']+$row['score_deducte']+$row['payment_money']);
            }
            $i++;
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
}
?>