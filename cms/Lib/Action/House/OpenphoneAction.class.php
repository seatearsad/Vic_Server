<?php
/*
 * 常用电话
 *
 */
class OpenphoneAction extends BaseAction{
	protected $village_id;
	protected $village;
	
	public function _initialize(){
		parent::_initialize();
	
		$this->village_id = $this->house_session['village_id'];
		$this->village = M('House_village')->where(array('village_id'=>$this->village_id))->find();
		if(empty($this->village)){
			$this->error('该小区不存在！');
		}
	}
	
    public function index(){
		$cat_list = M('House_village_phone_category')->where(array('village_id'=>$this->village_id,'cat_status'=>array('neq','4')))->order('`cat_sort` DESC,`cat_id` ASC')->select();
		$this->assign('cat_list',$cat_list);
		$this->display();
    }
	public function cat_add(){
		if(IS_POST){
			if(empty($_POST['cat_name'])){
				$this->error('请填写分类名称！');
			}
			$_POST['village_id'] = $this->village_id;
			if(M('House_village_phone_category')->data($_POST)->add()){
				$this->success('添加成功！');
			}else{
				$this->error('添加失败，请重试。');
			}
		}else{
			$this->display();
		}
	}
	public function cat_edit(){
		$now_cat = $this->get_category($_GET['cat_id']);
		if(IS_POST){
			if(empty($_POST['cat_name'])){
				$this->error('请填写分类名称！');
			}
			$_POST['village_id'] = $this->village_id;
			if(M('House_village_phone_category')->where(array('cat_id'=>$_GET['cat_id']))->data($_POST)->save()){
				$this->success('编辑成功！');
			}else{
				$this->error('编辑失败，请检查是否有做过修改后重试。');
			}
		}else{	
			$this->display();
		}
	}
	public function cat_del(){
		$now_cat = $this->get_category($_GET['cat_id']);
		$condition['cat_id'] = $now_cat['cat_id'];
		$condition['village_id'] = $this->village_id;
		if(M('House_village_phone_category')->where($condition)->data(array('cat_status'=>'4'))->save()){
			M('House_village_phone')->where(array('cat_id'=>$now_cat['cat_id']))->data(array('status'=>'4'))->save();
			$this->success('删除成功！');
		}else{
			$this->error('删除失败！');
		}
	}
    public function phone(){
		$now_cat = $this->get_category($_GET['cat_id']);
		$phone_list = M('House_village_phone')->where(array('cat_id'=>$_GET['cat_id'],'village_id'=>$this->village_id,'status'=>array('neq','4')))->order('`sort` DESC,`pigcms_id` ASC')->select();
		$this->assign('phone_list',$phone_list);
		$this->display();
	}
	public function phone_add(){
		$now_cat = $this->get_category($_GET['cat_id']);
		if(IS_POST){
			if(empty($_POST['name'])){
				$this->error('请填写名称！');
			}
			if(empty($_POST['phone'])){
				$this->error('请填写电话号码！');
			}
			$_POST['cat_id'] = $now_cat['cat_id'];
			$_POST['village_id'] = $this->village_id;
			if(M('House_village_phone')->data($_POST)->add()){
				$this->success('添加成功！');
			}else{
				$this->error('添加失败，请重试。');
			}
		}else{
			$this->display();
		}
	}
	public function phone_edit(){
		$now_phone = M('House_village_phone')->where(array('pigcms_id'=>$_GET['id'],'village_id'=>$this->village_id))->find();
		if(empty($now_phone)){
			$this->error('该号码不存在！');
		}
		$this->assign('now_phone',$now_phone);
		
		$now_cat = $this->get_category($now_phone['cat_id']);
		if(IS_POST){
			if(empty($_POST['name'])){
				$this->error('请填写名称！');
			}
			if(empty($_POST['phone'])){
				$this->error('请填写电话号码！');
			}
			$_POST['cat_id'] = $now_cat['cat_id'];
			$_POST['village_id'] = $this->village_id;
			if(M('House_village_phone')->where(array('pigcms_id'=>$_GET['id']))->data($_POST)->save()){
				$this->success('编辑成功！');
			}else{
				$this->error('编辑失败，请重试。');
			}
		}else{
			$this->display();
		}
	}
	public function phone_del(){
		$now_phone = M('House_village_phone')->where(array('pigcms_id'=>$_GET['id'],'village_id'=>$this->village_id))->find();
		if(empty($now_phone)){
			$this->error('该号码不存在！');
		}
		if(M('House_village_phone')->where(array('pigcms_id'=>$_GET['id']))->data(array('status'=>'4'))->save()){
			$this->success('删除成功！');
		}else{
			$this->error('删除失败！');
		}
	}
	protected function get_category($cat_id){
		$now_cat = M('House_village_phone_category')->where(array('cat_id'=>$cat_id))->find();
		if(empty($now_cat)){
			$this->error('该分类不存在！');
		}
		$this->assign('now_cat',$now_cat);
		return $now_cat;
	}
}