<?php
/*
 *系统新闻系统前台显示
 */
 
 class NewsAction extends BaseAction{
     protected $type = 0;
	 public function index(){
            $cate = D('System_news_category');
            $news_cat = $cate->where(array('status'=>1,'type'=>$this->type))->order('sort DESC')->select();
            $this->assign('news_cat',$news_cat);
            $cat_id = $_GET['category_id'];

            if(isset($cat_id)){
                if($cat_id != 0){
                    $where =array('category_id'=>$cat_id,'status'=>1,'type'=>$this->type);
                    $now_cat = $cate->where(array('id'=>$cat_id))->find();
                }else{
                    $where =array('status'=>1,'type'=>$this->type);
                    $now_cat['id'] = 0;
                    $now_cat['name'] = 'ALL POSTS';
                }

                $this->assign('now_cat',$now_cat);

                $count_news =  D('System_news')->where($where)->count();
                import('@.ORG.news_page');
                $p = new Page($count_news, 15,'page');
                $news_title = D('System_news')->field('id,title,add_time,last_time,cover')->where($where)->order('sort DESC,id DESC')->limit($p->firstRow . ',' . $p->listRows)->select();
                $this->assign('news_title',$news_title);

                $pagebar = $p->show();
                $this->assign('pagebar', $pagebar);

                $this->display('category');
            }else if(!empty($_GET['id'])){
                $news = D('System_news')->where(array('id'=>$_GET['id']))->find();
                $now_cat = $cate->where(array('id'=>$news['category_id']))->find();
                $this->assign('now_cat',$now_cat);
                $this->assign('news',$news);

                $cate_news = $this->getCateList(5, $now_cat['id'], false);
                $this->assign('cate_list',$cate_news['list']);

                $this->display('news');
            }else{
                $news = $this->getCateList(3,0,false);
                $this->assign('news_all',$news['list']);

                $i = 0;
                $cate_show = array();
                foreach ($news_cat as $cate){
                    //首页显示三个栏目
                    if($i<3) {
                        $cate_show['cate'][] = $cate;

                        $news_cate = $this->getCateList(3, $cate['id'], false);
                        $cate_show['news'][$cate['id']] = $news_cate['list'];
                    }else{
                        break;
                    }
                    $i++;
                }

                $this->assign('cate_list',$cate_show);
                $this->display();
            }

	 }

	 public function getCateList($num=3,$cate_id=0,$is_page=false){
         $news = M('System_news');
         $where['n.status'] = 1;
         $where['c.type'] = $this->type;

         if($cate_id != 0){
             $where['n.category_id'] = $cate_id;
         }
         $count_news = $news->where($where)->count();
         import('@.ORG.news_page');
         $p = new Page($count_news, $num,'page');
         $news_list = $news->field('n.id,n.title,n.cover,n.top_img,n.add_time,n.last_time,c.name')->join('as n left join '.C('DB_PREFIX').'system_news_category c ON c.id = n.category_id ')->where($where)->order('n.sort DESC,n.id DESC')->limit($p->firstRow . ',' . $p->listRows)->select();
         $pagebar = $p->show();

         $return['list'] = $news_list;
         if($is_page)
             $return['page'] = $pagebar;
         else
             $return['page'] = "";

         return $return;
     }
 }
