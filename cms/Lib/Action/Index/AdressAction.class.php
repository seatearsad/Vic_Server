<?php
/*
 * 选择地址
 *
 */
class AdressAction extends BaseAction{
    public function frame(){
		if(empty($this->user_session)){
			$this->assign('error_msg',L('_B_MY_LOGINFIRST_').' <a href="'.U('Index/Login/index').'" target="_blank">'.L('_B_D_LOGIN_LOGIN1_').'</a>&nbsp;&nbsp;<a href="'.U('Index/Adress/frame').'" style="color:blue;">'.L('_REFRESH_TXT_').'</a>');
		}else{
			$adress_list = D('User_adress')->get_adress_list($this->user_session['uid']);
			if(!empty($adress_list)){
				$this->assign('adress_list',$adress_list);
			}else{
				$this->assign('error_msg',L('_CLICK_ADD_NEW_A_').' <a href="'.U('User/Adress/index').'" target="_blank">'.L('_B_PURE_MY_26_').'</a>&nbsp;&nbsp;<a href="'.U('Index/Adress/frame').'" style="color:blue;">'.L('_REFRESH_TXT_').'</a>');
			}
		}
		$this->display();
    }

	public function pick_address(){

		$flag = $_GET['buy_type'] == 'shop' ? true : false;
		$adress_list = D('Pick_address')->get_pick_addr_by_merid($_GET['mer_id'], $flag);

		if(!empty($adress_list)){
			$this->assign('adress_list',$adress_list);
		}else{
			$this->assign('error_msg','<a href="'.U('Index/Adress/pick_address').'" style="color:blue;">刷新</a>');
		}

		$this->display();
	}
}