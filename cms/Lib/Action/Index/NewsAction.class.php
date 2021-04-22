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

            //echo GROUP_NAME."---------".MODULE_NAME."---------".ACTION_NAME;

            if(isset($cat_id)){

                if($cat_id != 0){
                    $now_cat = $cate->where(array('id'=>$cat_id))->find();
                }else{
                    $now_cat['id'] = 0;
                    $now_cat['name'] = 'ALL POSTS';
                }

                $this->assign('now_cat',$now_cat);

                $news = $this->getCateList(5,$now_cat['id'],true);
                $this->assign('news_title',$news['list']);
                $this->assign('pagebar', $news['page']);

                $this->display('category');

                 $this->display('news');

            }else if(!empty($_GET['id'])){

                $news = D('System_news')->where(array('id'=>$_GET['id']))->find();

                $now_cat = $cate->where(array('id'=>$news['category_id']))->find();
                $this->assign('now_cat',$now_cat);
                $this->assign('news',$news);

                $cate_news = $this->getCateList(5, $now_cat['id'], false);
                $this->assign('cate_list',$cate_news['list']);

                D('System_news')->where(array('id'=>$_GET['id']))->save(array('view_num'=>$news['view_num']+1));


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

                $commend = $this->getCateList(5,0,false,true);
                $this->assign('commend',$commend['list']);
                $this->assign('commend_num',count($commend['list']));

                $this->display("");

            }
	 }

	 public function getCateList($num=3,$cate_id=0,$is_page=false,$is_commend=false){
         $news = M('System_news');
         $where['n.status'] = 1;
         $where['c.type'] = $this->type;

         if($cate_id != 0){
             $where['n.category_id'] = $cate_id;
         }

         if($is_commend)
             $where['n.is_commend'] = 1;

         $count_news = $news->join('as n left join '.C('DB_PREFIX').'system_news_category c ON c.id = n.category_id ')->where($where)->count();
         import('@.ORG.news_page');
         $p = new Page($count_news, $num,'page');
         $news_list = $news->field('n.*,c.name')->join('as n left join '.C('DB_PREFIX').'system_news_category c ON c.id = n.category_id ')->where($where)->order('n.sort DESC,n.id DESC')->limit($p->firstRow . ',' . $p->listRows)->select();
         $pagebar = $p->show();

         $return['list'] = $news_list;
         if($is_page)
             $return['page'] = $pagebar;
         else
             $return['page'] = "";

         return $return;
     }
 }
