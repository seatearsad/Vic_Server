<?php
// 商家推广用户
class Merchant_spread_userAction extends BaseAction{
    public function index(){
        import('@.ORG.merchant_page');
        $where['mer_id'] = $this->merchant_session['mer_id'];
        $count = M('Merchant_spread')->where($where)->count();
        $p = new Page($count, 20);
        $spread_user = M('Merchant_spread')->join('as s left join '.C('DB_PREFIX').'user u ON s.openid = u.openid ')
            ->join('(SELECT openid,SUM(money) AS spread_money FROM '.C('DB_PREFIX').'merchant_spread_list where mer_id ='.$where['mer_id'].' GROUP BY openid) AS m ON m.openid = s.openid ')
            ->where($where)->limit($p->firstRow . ',' . $p->listRows)->select();
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
        $all_spread_money = M('Merchant_spread_list')->where($where)->sum('money');
        $this->assign('all_spread_money',$all_spread_money);
        $this->assign('spread_user',$spread_user);
        $this->display();
    }

    //推广佣金记录
    public function spread_list(){
        $where['mer_id'] = $this->merchant_session['mer_id'];
        import('@.ORG.merchant_page');
        $count = M('Merchant_spread_list')->where($where)->count();
        $p = new Page($count, 20);
        $spread_list = M('Merchant_spread_list')->where($where)->limit($p->firstRow . ',' . $p->listRows)->select();
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
        $this->assign('spread_list',$spread_list);
        $this->display();
    }

    //店铺推广记录
    public function store_spread(){
        import('@.ORG.merchant_page');
        $where['mer_id'] = $this->merchant_session['mer_id'];

        $store_list = D('Merchant_store')->where($where)->select();
        $this->assign('store_list',$store_list);
        if($_GET['store_id']){
            $where['store_id'] = $_GET['store_id'];
        }
        $count = M('Merchant_spread')->where($where)->count();
        $p = new Page($count, 20);
        $spread_user = M('Merchant_spread')->join('as s left join '.C('DB_PREFIX').'user u ON s.openid = u.openid ')
            ->join('(SELECT openid,SUM(money) AS spread_money FROM '.C('DB_PREFIX').'merchant_spread_list where mer_id ='.$where['mer_id'].' GROUP BY openid) AS m ON m.openid = s.openid ')
            ->where($where)->limit($p->firstRow . ',' . $p->listRows)->select();
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
        $all_spread_money = M('Merchant_spread_list')->where($where)->sum('money');
        $this->assign('all_spread_money',$all_spread_money);
        $this->assign('spread_user',$spread_user);
        $this->display();
    }

    public function store_spread_list(){
        $where['l.mer_id'] = $this->merchant_session['mer_id'];
        $store_list = D('Merchant_store l')->where($where)->select();
        $this->assign('store_list',$store_list);
        if($_GET['store_id']){
            $where['s.store_id'] = $_GET['store_id'];
        }else{
            $where['s.store_id'] = array('neq','');
        }
        import('@.ORG.merchant_page');
        $count = M('Merchant_spread_list l')->join('as l left join '.C('DB_PREFIX').'merchant_spread s ON s.openid = l.openid')->where($where)->count();
        $p = new Page($count, 20);
        $spread_list = M('Merchant_spread_list l')->join(C('DB_PREFIX').'merchant_spread s ON s.openid = l.openid')->where($where)->limit($p->firstRow . ',' . $p->listRows)->select();
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
        $this->assign('spread_list',$spread_list);
        $this->display();
    }
}