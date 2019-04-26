<?php
class Page{
	// 起始行数
    public $firstRow;
	//现在页数
	public $nowPage;
	//总页数
	public $totalPage;
	//总行数
	public $totalRows;
	//分页的条数
	public $page_rows;
	//架构函数
	public function __construct($totalRows,$listRows){
		$this->totalRows = $totalRows;
		$this->nowPage  = !empty($_GET['page']) ? intval($_GET['page']) : 1;
		$this->listRows = $listRows;
		$this->totalPage = ceil($totalRows/$listRows);
		if($this->nowPage > $this->totalPage && $this->totalPage>0){
			$this->nowPage = $this->totalPage;
		}
		$this->firstRow = $listRows*($this->nowPage-1);
	}
    public function show(){
		if($this->totalRows == 0) return false;
		$now = $this->nowPage;
		$total = $this->totalPage;
		
		$url  =  $_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'],'?')?'':"?");
        $parse = parse_url($url);
        if(isset($parse['query'])) {
            parse_str($parse['query'],$params);
            unset($params['page']);
            $url   =  $parse['path'].'?'.http_build_query($params);
        }
		if(strpos(strrev($url),'?') === 0){
		   $url .= 'page=';
		}else{
		   $url .= '&page=';
		}
		$str = '<span class="total"><span id="row_count">'.$this->totalRows.'</span> '.L('_BACK_PAGE_ORDERS_').' '.$now.' / '.$total.' '.L('_BACK_PAGE_NUM_').'   </span>';
		if($now > 1){
			$str .= '<a href="'.$url.($now-1).'" class="prev" title="Previous">'.L('_BACK_PREVIOUS_').'</a>';
		}
		if($now!=1 && $now>4 && $total>6){
			$str .= '<a href="'.$url.'1" title="1">1</a><div class="page-numbers dots">…</div>';
		}
		for($i=1;$i<=5;$i++){
			if($now <= 1){
				$page = $i;
			}elseif($now > $total-1){
				$page = $total-5+$i;
			}else{
				$page = $now-3+$i;
			}
			if($page != $now  && $page>0){
				if($page<=$total){
					$str .= '<a href="'.$url.$page.'" title="'.$page.'" class="pga">'.$page.'</a>';
				}else{
					break;
				}
			}else{
				if($page == $now) $str.='<span class="current">'.$page.'</span>';
			}
		}
		if($total != $now && $now<$total-5 && $total>10){
			$str .= '<span class="dots">…</span><a href="'.$url.$total.'" title="'.$total.'">'.$total.'</a>';
		}
		if ($now != $total){
			$str .= '<a href="'.$url.($now+1).'" class="next">'.L('_BACK_NEXT_').'</a>';
		}
		
		return $str;
    }
}
?>