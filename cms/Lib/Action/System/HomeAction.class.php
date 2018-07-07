<?php

/*
 * 首页回复管理
 *
 * @  Writers    Jaty
 * @  BuildTime  2014/11/06 16:47
 *
 */

class HomeAction extends BaseAction {

    protected function _initialize() {
	parent::_initialize();
	$this->database_home_menu = D('Home_menu');
	$this->database_image = D('Image');
	$this->database_home_menu_category = D('Home_menu_category');
    }

    public function index() {
	$paltform = D("Platform")->where(array('key' => '首页'))->find();
	if (IS_POST) {
	    $data = array();
	    $data['title'] = isset($_POST['title']) ? htmlspecialchars($_POST['title']) : '';
	    $data['info'] = isset($_POST['info']) ? htmlspecialchars($_POST['info']) : '';
	    $data['key'] = '首页';
	    $data['url'] = $this->config['site_url'] . '/wap.php';
// 			$images = $this->upload();
// 			if (empty($images['error'])) {
// 				foreach ($images['msg'] as $image) {
// 					$data[$image['key']] = substr($image['savepath'] . $image['savename'], 1);
// 				}
// 			}

	    $image = D('Image')->handle($this->system_session['id'], 'platform');
	    if (!$image['error']) {
		$data = array_merge($data, $image['url']);
	    }
	    if ($paltform == false) {
		$id = D("Platform")->add($data);
		D('Image')->update_table_id($_POST['pic'], $id, 'platform');
	    } else {
		D("Platform")->where(array('id' => $paltform['id']))->save($data);
		D('Image')->update_table_id($_POST['pic'], $paltform['id'], 'platform');
	    }
	    $this->success("设置成功");
	} else {
	    if (isset($paltform['pic']) && $paltform['pic'])
		$paltform['pic'] = $this->config['site_url'] . $paltform['pic'];
	    $this->assign('info', $paltform);
	    $this->display();
	}
    }

    public function group() {
	$paltform = D("Platform")->where(array('key' => $this->config['group_alias_name']))->find();
	if (IS_POST) {
	    $data = array();
	    $data['title'] = isset($_POST['title']) ? htmlspecialchars($_POST['title']) : '';
	    $data['info'] = isset($_POST['info']) ? htmlspecialchars($_POST['info']) : '';
	    $data['key'] = $this->config['group_alias_name'];
	    $data['url'] = $this->config['site_url'] . '/wap.php?g=Wap&c=Group&a=index';
// 			$images = $this->upload();
// 			if (empty($images['error'])) {
// 				foreach ($images['msg'] as $image) {
// 					$data[$image['key']] = substr($image['savepath'] . $image['savename'], 1);
// 				}
// 			}

	    $image = D('Image')->handle($this->system_session['id'], 'platform');
	    if (!$image['error']) {
		$data = array_merge($data, $image['url']);
	    }
	    if ($paltform == false) {
		D("Platform")->add($data);
		D('Image')->update_table_id($_POST['pic'], $id, 'platform');
	    } else {
		D("Platform")->where(array('id' => $paltform['id']))->save($data);
		D('Image')->update_table_id($_POST['pic'], $paltform['id'], 'platform');
	    }
	    $this->success("设置成功");
	} else {
	    if (isset($paltform['pic']) && $paltform['pic'])
		$paltform['pic'] = $this->config['site_url'] . $paltform['pic'];
	    $this->assign('info', $paltform);
	    $this->display();
	}
    }

    public function meal() {
	$paltform = D("Platform")->where(array('key' => $this->config['meal_alias_name']))->find();
	if (IS_POST) {
	    $data = array();
	    $data['title'] = isset($_POST['title']) ? htmlspecialchars($_POST['title']) : '';
	    $data['info'] = isset($_POST['info']) ? htmlspecialchars($_POST['info']) : '';
	    $data['key'] = $this->config['meal_alias_name'];
	    $data['url'] = $this->config['site_url'] . '/wap.php?g=Wap&c=Meal_list&a=index';
// 			$images = $this->upload();
// 			if (empty($images['error'])) {
// 				foreach ($images['msg'] as $image) {
// 					$data[$image['key']] = substr($image['savepath'] . $image['savename'], 1);;
// 				}
// 			}

	    $image = D('Image')->handle($this->system_session['id'], 'platform');
	    if (!$image['error']) {
		$data = array_merge($data, $image['url']);
	    }
	    if ($paltform == false) {
		D("Platform")->add($data);
		D('Image')->update_table_id($_POST['pic'], $id, 'platform');
	    } else {
		D("Platform")->where(array('id' => $paltform['id']))->save($data);
		D('Image')->update_table_id($_POST['pic'], $paltform['id'], 'platform');
	    }
	    $this->success("设置成功");
	} else {
	    if (isset($paltform['pic']) && $paltform['pic'])
		$paltform['pic'] = $this->config['site_url'] . $paltform['pic'];
	    $this->assign('info', $paltform);
	    $this->display();
	}
    }

    public function first() {
	$first = D("First")->field(true)->where(array('reply_type' => 0))->find();
	if (IS_POST) {
	    $data = array();
	    $data['title'] = isset($_POST['title']) ? htmlspecialchars($_POST['title']) : '';
	    $data['info'] = isset($_POST['info']) ? htmlspecialchars($_POST['info']) : '';
	    $data['content'] = isset($_POST['content']) ? htmlspecialchars_decode($_POST['content']) : '';
	    $data['url'] = isset($_POST['url']) ? htmlspecialchars_decode($_POST['url']) : '';
	    $data['type'] = isset($_POST['type']) ? intval($_POST['type']) : 0;
	    $data['fromid'] = isset($_POST['fromid']) ? intval($_POST['fromid']) : 0;
	    $data['reply_type'] = 0;



	    $image = D('Image')->handle($this->system_session['id'], 'first');
	    if (!$image['error']) {
		$data = array_merge($data, $image['url']);
	    }

// 			$images = $this->upload();
// 			if (empty($images['error'])) {
// 				foreach ($images['msg'] as $image) {
// 					$data[$image['key']] = substr($image['savepath'] . $image['savename'], 1);
// 				}
// 			}
	    if ($first == false) {
		$id = D("First")->add($data);

		D('Image')->update_table_id($_POST['pic'], $id, 'first');
	    } else {
		D("First")->where(array('id' => $first['id']))->save($data);

		D('Image')->update_table_id($_POST['pic'], $first['id'], 'first');
	    }
	    $this->success("设置成功");
	} else {
	    if (isset($first['pic']) && $first['pic'])
		$first['pic'] = $this->config['site_url'] . $first['pic'];
	    $this->assign('first', $first);
	    $this->display();
	}
    }

    public function invalid() {
		$invalid = D("First")->field(true)->where(array('reply_type' => 1))->find();
		if (IS_POST) {
		    $data = array();
		    $data['title'] = isset($_POST['title']) ? htmlspecialchars($_POST['title']) : '';
		    $data['info'] = isset($_POST['info']) ? htmlspecialchars($_POST['info']) : '';
		    $data['content'] = isset($_POST['content']) ? htmlspecialchars($_POST['content']) : '';
		    $data['url'] = isset($_POST['url']) ? htmlspecialchars_decode($_POST['url']) : '';
		    $data['type'] = isset($_POST['type']) ? intval($_POST['type']) : 0;
		    $data['fromid'] = isset($_POST['fromid']) ? intval($_POST['fromid']) : 0;
		    $data['reply_type'] = 1;
	// 			$images = $this->upload();
	// 			if (empty($images['error'])) {
	// 				foreach ($images['msg'] as $image) {
	// 					$data[$image['key']] = substr($image['savepath'] . $image['savename'], 1);
	// 				}
	// 			}
		    $key = '';
		    if ($data['fromid'] == 1) {
		    	$key = '首页';
		    } elseif ($data['fromid'] == 2) {
		    	$key = $this->config['group_alias_name'];
		    } elseif ($data['fromid'] == 3) {
		    	$key = $this->config['meal_alias_name'];
		    }
		    if ($key && !($platform = D("Platform")->field(true)->where(array('key' => $key))->find())) {
		    	$this->error('您还未配置【'. $key .'回复配置】');
		    }
		    $image = D('Image')->handle($this->system_session['id'], 'first');
		    if (!$image['error']) {
				$data = array_merge($data, $image['url']);
		    }
		    if ($invalid == false) {
				$id = D("First")->add($data);
				D('Image')->update_table_id($_POST['pic'], $id, 'first');
		    } else {
				D("First")->where(array('id' => $invalid['id']))->save($data);
				D('Image')->update_table_id($_POST['pic'], $invalid['id'], 'first');
		    }
		    $this->success("设置成功");
		} else {
		    if (isset($invalid['pic']) && $invalid['pic'])
			$invalid['pic'] = $this->config['site_url'] . $invalid['pic'];
		    $this->assign('first', $invalid);
		    $this->display();
		}
    }

    public function other()
    {
		$list = D("Platform")->field(true)->select();
		$this->assign('list', $list);
		$this->display();
    }

    public function other_add()
    {
		if (IS_POST) {
		    $data = array();
		    $data['key'] = isset($_POST['key']) ? htmlspecialchars($_POST['key']) : '';
		    $data['title'] = isset($_POST['title']) ? htmlspecialchars($_POST['title']) : '';
		    $data['info'] = isset($_POST['info']) ? htmlspecialchars($_POST['info']) : '';
		    $data['content'] = isset($_POST['content']) ? $_POST['content'] : '';
		    $data['url'] = isset($_POST['url']) ? htmlspecialchars_decode($_POST['url']) : '';
		    $data['api_url'] = isset($_POST['api_url']) ? htmlspecialchars_decode($_POST['api_url']) : '';
		    $data['type'] = 0;
		    if (empty($data['key'])) {
				$this->error('关键词不能为空');
		    }
// 			$platform = M('Platform')->where(array('key' => $data['key']))->find();
// 			if ($platform) {
// 				$this->error('关键词已存在，不能重复添加！');
// 			}
// 			$images = $this->upload();
// 			if (empty($images['error'])) {
// 				foreach ($images['msg'] as $image) {
// 					$data[$image['key']] = substr($image['savepath'] . $image['savename'], 1);
// 				}
// 			}
		    $image = D('Image')->handle($this->system_session['id'], 'platform');
		    if (!$image['error']) {
				$data = array_merge($data, $image['url']);
		    }
		    if ($id = D("Platform")->add($data)) {
				D('Image')->update_table_id($_POST['pic'], $id, 'platform');
				$this->success('添加成功');
		    } else {
				$this->error('添加失败');
		    }
		} else {
		    $this->display();
		}
    }

    public function other_edit()
    {
		if (IS_POST) {
		    $data = array();
		    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
		    $data['key'] = isset($_POST['key']) ? htmlspecialchars($_POST['key']) : '';
		    $data['title'] = isset($_POST['title']) ? htmlspecialchars($_POST['title']) : '';
		    $data['info'] = isset($_POST['info']) ? htmlspecialchars($_POST['info']) : '';
		    $data['content'] = isset($_POST['content']) ? $_POST['content'] : '';
		    $data['url'] = isset($_POST['url']) ? htmlspecialchars_decode($_POST['url']) : '';
		    $data['api_url'] = isset($_POST['api_url']) ? htmlspecialchars_decode($_POST['api_url']) : '';
		    $data['type'] = 0;
		    if (empty($data['key'])) {
				$this->error('关键词不能为空');
		    }
// 				$platform = M('Platform')->where("`id`<>'$id' AND `key`='{$data['key']}'")->find();
// 				if ($platform) {
// 					$this->error('关键词已存在，不能重复添加！');
// 				}
// 				$images = $this->upload();
// 				if (empty($images['error'])) {
// 					foreach ($images['msg'] as $image) {
// 						$data[$image['key']] = substr($image['savepath'] . $image['savename'], 1);
// 					}
// 				}

		    $image = D('Image')->handle($this->system_session['id'], 'platform');
		    if (!$image['error']) {
				$data = array_merge($data, $image['url']);
		    }

		    D('Platform')->where(array('id' => $id))->save($data);
		    D('Image')->update_table_id($_POST['pic'], $id, 'platform');
		    $this->success('更新成功',U('Home/other'));
		} else {
		    $data = M('Platform')->where(array('id' => $this->_get('id')))->find();
		    if ($data == false) {
				$this->error('您所操作的数据对象不存在！');
		    }
		    $this->assign('info', $data);
		    $this->display();
		}
    }

    public function text_add()
    {
		if (IS_POST) {
		    $data = array();
		    $data['key'] = isset($_POST['key']) ? htmlspecialchars($_POST['key']) : '';
		    $data['content'] = isset($_POST['content']) ? $_POST['content'] : '';
		    $data['type'] = 1;
		    if (empty($data['key'])) {
				$this->error('关键词不能为空');
		    }
		    if ($id = D("Platform")->add($data)) {
				$this->success('添加成功');
		    } else {
				$this->error('添加失败');
		    }
		} else {
		    $this->display();
		}
    }

    public function text_edit()
    {
		if (IS_POST) {
		    $data = array();
		    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
		    $data['key'] = isset($_POST['key']) ? htmlspecialchars($_POST['key']) : '';
		    $data['content'] = isset($_POST['content']) ? $_POST['content'] : '';
		    $data['type'] = 0;
		    if (empty($data['key'])) {
				$this->error('关键词不能为空');
		    }
		    D('Platform')->where(array('id' => $id))->save($data);
		    $this->success('更新成功',U('Home/other'));
		} else {
		    $data = M('Platform')->where(array('id' => $this->_get('id')))->find();
		    if ($data == false) {
				$this->error('您所操作的数据对象不存在！');
		    }
		    $this->assign('info', $data);
		    $this->display();
		}
    }

    public function other_del()
    {
		D('Platform')->where(array('id' => intval($_POST['id'])))->delete();
		$this->success('删除成功');
    }

    public function diytool()
    {
		$this->display();
    }

    public function foot_menu_category()
    {
		$database_home_menu_category = D('Home_menu_category');
		$category_list = $database_home_menu_category->home_menu_category_list(true);
		if(!$category_list){
		    $this->error('数据处理有误！');
		}else{
		    $this->assign('category_list',$category_list['list']);
		}
		$this->display();
    }

    public function foot_menu_add() {
		if (IS_POST) {
		    if (($_FILES['pic_path']['error'] != 4) || ($_FILES['hover_pic_path']['error'] != 4)) {
			$image = $this->database_image->handle($this->system_session['id'], 'slider');
			if (!$image['error']) {
					if($image['url']['pic_path']){
						$_POST['pic_path'] =str_replace('/upload/slider/', '', $image['url']['pic_path']);
					}

					if($image['url']['hover_pic_path']){
						$_POST['hover_pic_path'] = str_replace('/upload/slider/', '', $image['url']['hover_pic_path']);
					}
				} else {
					$this->frame_submit_tips(0, $image['msg']);
				}
		    }
			$database_home_menu = D('Home_menu ');
			$_POST['url'] = htmlspecialchars_decode($_POST['url']);
		    $result = $this->database_home_menu->home_menu_add($_POST);
		    if (!$result) {
				$this->frame_submit_tips(0,'数据有误！');
		    } else {
				if ($result['status']) {
				    $this->database_image->update_table_id('/upload/slider/' . $_POST['pic'], $id, 'slider');
				    $this->frame_submit_tips($result['status'], $result['msg']);
				} else {
				    $this->frame_submit_tips($result['status'], $result['msg']);
				}
		    }
		} else {
		    $cat_id = $this->_get('cat_id');
		    if(!$cat_id){
				$this->error('传递参数有误！');
		    }
		    $this->assign('bg_color','#F3F3F3');
		    $now_category = $this->frame_check_get_category($cat_id);
		    $this->assign('now_category',$now_category['info']);
		    $this->display();
		}
    }

    public function foot_menu_edit()
    {
		$this->assign('bg_color', '#F3F3F3');
		$id = $this->_get('id');
		$where['id'] = $id;
		if (IS_POST) {
		    if (($_FILES['pic_path']['error'] != 4) || ($_FILES['hover_pic_path']['error'] != 4)) {
				$image = $this->database_image->handle($this->system_session['id'], 'slider');
				if (!$image['error']) {
				    if($image['url']['pic_path']){
						$_POST['pic_path'] =str_replace('/upload/slider/', '', $image['url']['pic_path']);
					}
					if($image['url']['hover_pic_path']){
						$_POST['hover_pic_path'] = str_replace('/upload/slider/', '', $image['url']['hover_pic_path']);
					}
				} else {
					$this->frame_submit_tips(0, $image['msg']);
				}
		    }

			$_POST['url'] = htmlspecialchars_decode($_POST['url']);
		    $result = $this->database_home_menu->home_menu_edit($where, $_POST);
		    if(!$result){
				$this->error('传递数据有误！');
		    }else{
				if($result['status']){
				    $this->frame_submit_tips(1,$result['msg']);
				}else{
				    $this->frame_error_tips($result['msg']);
				}
		    }

		} else {
		    $foot_menu_info = $this->database_home_menu->home_menu_info($where);
		    if (!$foot_menu_info) {
				$this->frame_error_tips('数据有误！');
		    } else {
				if (!$foot_menu_info['status']) {
				    $this->frame_error_tips('该信息不存在！');
				} else {
				    $this->assign('foot_menu_info', $foot_menu_info['info']);
				}
		    }
		    $this->display();
		}
    }

    public function foot_menu_del(){
		$id = $this->_post('id');
		$where['id'] = $id;
		$result = $this->database_home_menu->home_menu_del($where);
		if($result){
		    if($result['status']){
			$this->success($result['msg']);
		    }else{
			$this->error($result['msg']);
		    }
		}else{
		    $this->error($result['msg']);
		}
	}

    public function category_add(){
		if (IS_POST) {
			$result = $this->database_home_menu_category->home_menu_category_add($_POST);
			if(!$result){
			    $this->error('传递参数有误！');
			}else{
			    if($result['status']){
				$this->success($result['msg']);
			    }else{
				$this->error($result['msg']);
			    }
			}
		} else {
			$this->display();
		}
    }

    public function category_del(){
	$cat_id = $this->_post('cat_id');
	if(!$cat_id){
	    $this->error('传递参数有误！');
	}

	$where['cat_id'] = $cat_id;
	$result = $this->database_home_menu_category->home_menu_category_del($where);
	if(!$result){
	    $this->error('数据处理有误！');
	}else{
	    if($result['status']){
		$this->success($result['msg']);
	    }else{
		$this->error($result['msg']);
	    }
	}
    }

    public function category_edit(){
	$this->assign('bg_color','#F3F3F3');
	$cat_id = $this->_get('cat_id');

	if(!$cat_id){
	    $this->error('传递参数有误！');
	}
	$where['cat_id'] = $cat_id;


	if(IS_POST){
	    $result = $this->database_home_menu_category->home_menu_category_edit($where,$_POST);
	    if(!$result){
		$this->error('数据处理有误！');
	    }else{
		if($result['status']){
		    $this->success($result['msg']);
		}else{
		    $this->error($result['msg']);
		}
	    }
	}else{
	    $category_info = $this->database_home_menu_category->home_menu_category_info($where);
	    if(!$category_info){
		$this->error('数组处理有误！');
	    }else{
		$this->assign('category_info',$category_info['info']);
	    }
	    $this->display();
	}
    }

    public function foot_menu_list(){
	$cat_id = $this->_get('cat_id');
	if(!$cat_id){
	    $this->error('传递参数有误！');
	}
	$now_category = $this->check_get_category($cat_id);
	$this->assign('now_category',$now_category['info']);



	$where['cat_id'] = $cat_id;
	$home_menu_list = $this->database_home_menu->home_menu_list($where);
	$this->assign('home_menu_list',$home_menu_list['list']);
	$this->display();
    }

    private function check_get_category($cat_id){
	$now_category = $this->get_category($cat_id);
	if(empty($now_category)){
		$this->error_tips('分类不存在！');
	}else{
		return $now_category;
	}
    }


    private function get_category($cat_id){
	    $where['cat_id'] = $cat_id;
	    $now_category = $this->database_home_menu_category->home_menu_category_info($where);
	    return $now_category;
    }

    private function frame_check_get_category($cat_id){
		$now_category = $this->get_category($cat_id);
		if(empty($now_category)){
			$this->frame_error_tips('分类不存在！');
		}else{
			return $now_category;
		}
	}
}
