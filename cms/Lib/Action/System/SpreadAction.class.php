<?php

class SpreadAction extends BaseAction
{

    public function spread_change(){
        if (!empty($_GET['keyword'])) {
            if ($_GET['searchtype'] == 'uid') {
                $condition_user['uid'] = $_GET['keyword'];
            } else if ($_GET['searchtype'] == 'nickname') {
                $condition_user['nickname'] = array('like', '%' . $_GET['keyword'] . '%');
            } else if ($_GET['searchtype'] == 'phone') {
                $condition_user['phone'] = array('like', '%' . $_GET['keyword'] . '%');
            }
        }
        $condition_user['spread_change_uid'] = array('gt',0);
        $count = M('User')->where($condition_user)->count();
        import('@.ORG.system_page');
        $p = new Page($count, 20);
        $spread_user_list = M('User')->where(array('spread_change_uid'=>array('gt',0)))->limit($p->firstRow,$p->listRows)->select();

        foreach ($spread_user_list as &$v) {
            $v['change_user'] = D('User')->get_user($v['spread_change_uid']);
            $v['spread_money'] = M('User_spread_list')->where(array('uid'=>$v['uid'],'change_uid'=>$v['spread_change_uid']))->sum('money');
        }
        $this->assign('spread_user_list', $spread_user_list);
        $this->assign('pagebar', $p->show());
        $this->display();
    }

    public function unbind_spread_change(){
        $uid=$_POST['uid'];
        if(M('User')->where(array('uid'=>$uid))->setField('spread_change_uid',0)){
            $this->success('解绑成功');
        }else{
            $this->error('解绑失败');
        }
        exit;
    }


}