<?php
	class SystemnewsAction extends BaseAction{
	    protected $all_type;
        public function __construct()
        {
            parent::__construct();

            $this->all_type = array(
                '0'=>L('I_ANNOUNCEMENT'),
                '1'=>L('I_FAQ'),
                '2'=>L('I_COURIER_REGULATIONS'),
                '3'=>L('I_COURIER_ACTIVITIES')
            );

            $this->assign('all_type',$this->all_type);
        }
		public function index(){
			$news = D('System_news')->select();
			$this->assign("news",$news[0]);
			if(isset($_GET['type'])) {
                $category = D('System_news_category')->where(array('type' => $_GET['type']))->order('sort DESC')->select();
                $this->assign('select_type',$_GET['type']);
            }else {
                $category = D('System_news_category')->order('sort DESC')->select();
                $this->assign('select_type',-1);
            }

			foreach ($category as &$v){
			    $count = D('System_news')->where(array('category_id'=>$v['id']))->count();
			    $v['count'] = $count;
            }
			$this->assign("category",$category);
			$this->display();
		}
		public function news(){
			if (!empty($_GET['keyword'])) {
				if ($_GET['searchtype'] == 'id') {
					$condition_news['id'] = $_GET['keyword'];
				} else if ($_GET['searchtype'] == 'title') {
					$condition_news['title'] = array('like', '%' . $_GET['keyword'] . '%');
				}
			}
			$condition_news['category_id'] = $_GET['category_id'];
			//排序 /*/
			$order_string = '`sort` DESC';
			if($_GET['sort']){
				switch($_GET['sort']){
					case 'uid':
						$order_string = '`uid` DESC';
						break;
					case 'lastTime':
						$order_string = '`last_time` DESC';
						break;
					case 'money':
						$order_string = '`now_money` DESC';
						break;
					case 'score':
						$order_string = '`score_count` DESC';
						break;
				}
			}
			$news = M('System_news');
			$count_news = $news->where($condition_news)->count();
			import('@.ORG.system_page');
			$p = new Page($count_news, 15);
			$news_list = $news->field('id,title,add_time,last_time,sort,status')->where($condition_news)->order($order_string)->limit($p->firstRow . ',' . $p->listRows)->select();
			$this->assign('news_list',$news_list);
			$pagebar = $p->show();
			$category_name = D('System_news_category')->where('id='.$_GET['category_id'])->getField('name');
			$this->assign('category_name',$category_name);
			$this->assign('pagebar', $pagebar);
			$this->display();
		}

		public function add_news(){
			if(IS_POST){

				$data['title'] = $_POST['title'];
				if(empty($_POST['content'])){
					$this->error('内容不能为空！');
				}
				$data['content'] = htmlspecialchars_decode($_POST['content']);
				$data['sort'] = $_POST['sort'];
				$data['status'] = $_POST['status'];
				$data['category_id'] = $_POST['category_id'];
				$data['add_time'] = $data['last_time']=time();

				$data['cover'] = $_POST['cover'] ? $_POST['cover'] : '';
                $data['top_img'] = $_POST['top_img'] ? $_POST['top_img'] : '';
                $data['city_id'] = $_POST['city_id'] ? $_POST['city_id'] : 0;

                $data['sub_title'] = $_POST['sub_title'];
                $data['keyword'] = $_POST['keyword'];
                $data['desc'] = $_POST['desc'];
                $data['is_commend'] = $_POST['is_commend'];

				if(D('System_news')->add($data)){
					$this->success('添加公告成功！');
				}else{
					$this->error('添加失败！');
				}
			}else {
				$category = D('System_news_category')->select();
				$this->assign("category",$category);

				if($_GET['category_id']){
				    $curr_cate = D('System_news_category')->where(array('id'=>$_GET['category_id']))->find();
                    $this->assign("curr_cate",$curr_cate);
                }

				$city = D('Area')->where(array('is_open'=>1,'area_type'=>2))->select();
				$this->assign('city',$city);
				$this->display();
			}
		}

		public function edit_news(){
			if(IS_POST){
				$data['title'] = $_POST['title'];
				$data['content'] = htmlspecialchars_decode($_POST['content']);
				$data['sort'] = $_POST['sort'];
				$data['category_id'] = $_POST['category_id'];
				$data['status'] = $_POST['status'];
				$data['last_time'] = time();

                $data['cover'] = $_POST['cover'] ? $_POST['cover'] : '';
                $data['top_img'] = $_POST['top_img'] ? $_POST['top_img'] : '';
                $data['city_id'] = $_POST['city_id'] ? $_POST['city_id'] : 0;

                $data['sub_title'] = $_POST['sub_title'];
                $data['keyword'] = $_POST['keyword'];
                $data['desc'] = $_POST['desc'];
                $data['is_commend'] = $_POST['is_commend'];

				if(D('System_news')->where('id='.$_POST['id'])->save($data)){
                    $this->success(L('J_SUCCEED3'));
				}else{
					$this->error(L('J_FAILED_SAVE'));
				}
			}else {
				$news = D('System_news')->where(array('id'=>$_GET['id']))->find();
				$this->assign("news",$news);
				$category = D('System_news_category')->select();
				$this->assign("category",$category);

                $curr_cate = D('System_news_category')->where(array('id'=>$news['category_id']))->find();
                $this->assign("curr_cate",$curr_cate);

                $city = D('Area')->where(array('is_open'=>1,'area_type'=>2))->select();
                $this->assign('city',$city);

				$this->display();
			}
		}
		
		public function add_category(){
			if(IS_POST){
				$data['name'] = $_POST['name'];
				$data['sort'] = $_POST['sort'];
				$data['status'] = $_POST['status'];
                $data['type']=$_POST['type'];
                $data['link_img'] = $_POST['link_img'];
                $data['link_url'] = $_POST['link_url'];
				if(D('System_news_category')->add($data)){
					$this->success('添加分类成功！');
				}else{
					$this->error('添加分类失败！');
				}
			}else {
				$this->display();
			}
		}
		
		public function edit_category(){
			if(IS_POST){
				$data['name']=$_POST['name'];
				$data['sort']=$_POST['sort'];
				$data['status']=$_POST['status'];
				$data['type']=$_POST['type'];
                $data['link_img'] = $_POST['link_img'];
                $data['link_url'] = $_POST['link_url'];
				if(D('System_news_category')->where(array('id'=>$_POST['id']))->save($data)){
					$this->success('更新成功！');
				}else{
					$this->error(L('J_FAILED_UPDATE'));
				}
			}else {
				$category = D('System_news_category')->where(array('id'=>$_GET['id']))->find();
				$this->assign("category",$category);
				$this->display();
			}
		}

		public function del(){
			if(IS_POST){

				if(!empty($_POST['id'])){
					if(D('System_news')->where(array('id'=>$_POST['id']))->delete()){
						$this->success(L('J_DELETION_SUCCESS'));
					}else{
						$this->error('删除失败！');
					}
				}
				if(!empty($_POST['category_id'])){
					if(!D('System_news_category')->where(array('id'=>$_POST['category_id']))->delete()){
						$this->error('删除失败！');
					}else{
						D('System_news')->where(array('category_id'=>$_POST['category_id']))->delete();
                        $this->success(L('J_DELETION_SUCCESS'));
					}
				}
			}
		}

        public function ajax_upload()
        {
            if ($_FILES['file']['error'] != 4) {
                //$store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
                //$shop = D('Merchant_store_shop')->field('store_theme')->where(array('store_id' => $store_id))->find();
                //$store_theme = isset($shop['store_theme']) ? intval($shop['store_theme']) : 0;
                //if ($store_theme) {
                //$width = '900,450';
                //$height = '900,450';
                //} else {
                $width = '900,450';
                $height = '500,250';
                //}
                $param = array('size' => $this->config['group_pic_size']);
                $param['thumb'] = true;
                $param['imageClassPath'] = 'ORG.Util.Image';
                $param['thumbPrefix'] = 'm_,s_';
                $param['thumbMaxWidth'] = $width;
                $param['thumbMaxHeight'] = $height;
                $param['thumbRemoveOrigin'] = false;
                $image = D('Image')->handle($_GET['cate_id'], 'system_news', 1, $param,false);
                if ($image['error']) {
                    exit(json_encode(array('error' => 1,'message' =>$image['msg'])));
                } else {
                    $title = $image['title']['file'];
                    $image_tmp = explode(',', $title);
                    $url = C('config.site_url') . '/upload/system_news/' . $image_tmp[0] . '/s_' . $image_tmp['1'];
                    $file = $image['url']['file'];
                    exit(json_encode(array('error' => 0, 'url' => $url, 'title' => $title,'file'=>$file)));
                }
            } else {
                exit(json_encode(array('error' => 1,'message' =>'没有选择图片')));
            }
        }
	}