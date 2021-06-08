<?php
class AppintroAction extends BaseAction{
	public function index(){
		$intro  = D('Appintro')->select();
		$this->assign('intro',$intro);
		$this->display();
	}
	public function add(){
		if(IS_POST){
			$data['title'] = $_POST['title'];
			if(empty($_POST['content'])){
				$this->error(L('CCBE'));
			}
			$data['content'] = htmlspecialchars_decode($_POST['content']);
			if(D('Appintro')->add($data)){
				$this->success(L('J_SUCCEED1'));
			}else{
				$this->error(L('J_MODIFICATION_FAILED2'));
			}
		}else {
			$this->display();
		}
	}
	public function edit(){
		if(IS_POST){
			$data['title'] = $_POST['title'];
			$data['content'] = htmlspecialchars_decode($_POST['content']);
			if(D('Appintro')->where('id='.$_POST['id'])->save($data)){
				$this->success(L('J_SUCCEED3'));
			}else{
				$this->error(L('J_FAILED_SAVE'));
			}
		}else {
			$intro = D('Appintro')->where('id='.$_GET['id'])->select();
			$this->assign("intro",$intro[0]);

			$this->display();
		}
	}
	public function del(){
		if(!empty($_POST['id'])){
			if(D('Appintro')->where('id='.$_POST['id'])->delete()){
				$this->success(L('J_DELETION_SUCCESS'));
			}else{
				$this->error(L('J_DELETION_FAILED_RETRY'));
			}
		}
	}
}