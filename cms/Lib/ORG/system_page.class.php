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

    //nba的分页样式
    public function show2(){

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
        $str="<div class='dataTables_paginate paging_simple_numbers ' id='DataTables_Table_0_paginate'>";
        $str = '<span class="total float-left"><span id="row_count">'.$this->totalRows.'</span> '.L('_BACK_PAGE_ORDERS_').' '.$now.' / '.$total.' '.L('_BACK_PAGE_NUM_').'   </span>';
        $str .="<div class='dataTables_paginate paging_simple_numbers' id='DataTables_Table_0_paginate'><ul class='pagination float-right'>
                ";

        if($now > 1){
            $str.="<li class='page-item previous' id='DataTables_Table_0_previous'>
                        <a href='".$url.($now-1)."' aria-controls='DataTables_Table_0' data-dt-idx='0' tabindex='0' class='page-link'>".L('_BACK_PREVIOUS_')."</a></li>";
        }
        //后面的页可以方便的回首页
        if($now!=1 && $now>4 && $total>6){
            $str.='<li class=" page-item active">
                        <a href="'.$url.'1" aria-controls="DataTables_Table_0" data-dt-idx="1" tabindex="0" class="page-link">1</a></li>';
            $str.='<li class=" page-item disabled" id="DataTables_Table_0_ellipsis">
                        <a href="#" aria-controls="DataTables_Table_0" data-dt-idx="6" tabindex="0" class="page-link">…</a></li>';
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
                    $str .='<li class=" page-item ">
                                <a href="'.$url.$page.'" aria-controls="DataTables_Table_0" data-dt-idx="'.$page.'" tabindex="0" class="page-link">'.$page.'</a></li>';
                }else{
                    break;
                }
            }else{
                if($page == $now)
                    $str .='<li class="page-item active">
                                <a href="'.$url.$page.'" aria-controls="DataTables_Table_0" data-dt-idx="'.$page.'" tabindex="0" class="page-link">'.$page.'</a></li>';
            }
        }
        if($total != $now && $now<$total-5 && $total>10){
            $str.='<li class=" page-item disabled" id="DataTables_Table_0_ellipsis">
                        <a href="#" aria-controls="DataTables_Table_0" data-dt-idx="6" tabindex="0" class="page-link">…</a></li>';
            $str .='<li class=" page-item ">
                                <a href="'.$url.$total.'" aria-controls="DataTables_Table_0" data-dt-idx="'.$total.'" tabindex="0" class="page-link">'.$total.'</a></li>';
        }
        if ($now != $total){
            $str .='<li class=" page-item next" id="DataTables_Table_0_next"><a
                                                    href="'.$url.($now+1).'" aria-controls="DataTables_Table_0" data-dt-idx="8"
                                                    tabindex="0" class="page-link">'.L('_BACK_NEXT_').'</a></li>
                                                    ';
//            $str .= '<a href="'.$url.($now+1).'" class="next">'.L('_BACK_NEXT_').'</a>';
        }

        return $str;
    }
}
?>