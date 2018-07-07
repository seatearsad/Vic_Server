<?php
set_time_limit(0);
class UpdateAction extends BaseAction{
    public function index(){
		$version = './conf/version.php';
        $ver = include($version);
		$checkver = $ver;//记录原始版本
        $ver = $ver['ver'];	
        $updatehost = 'http://yun.idz.pw/o2o/update.php';
		$hosturl = urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);	
		$param['u']=$hosturl;
		$param['a']='check';
		$param['v']=$ver;
		$lastver = $this->http($updatehost,$param,'GET', array("Content-type: text/html; charset=utf-8"));
        if($lastver !== $ver){
           $updateinfo = ('<p class="red">亲爱的主人，又有新更新了，最新版本为：嘿小信v ' . $lastver) . '</p>';
				$chanageinfo = array();
				$temver = $lastver-$ver;
				for($i=0;$i<10*$temver;$i++){
					$param['a']='chanage';
					$param['v']=$ver;
					$hosturl = urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
					$param['u']=$hosturl;
					$chanageinfo[$i] = $this->http($updatehost,$param,'GET', array("Content-type: text/html; charset=utf-8"));
					$ver+=1;	
				}
        }else{
			$this->error("已经是最新的版本,不需要升级!",U('System/Index/index'));
        }
		$temp = array();
		foreach($chanageinfo as $key =>$val){
			$chanageinfo[$key] = json_decode($val,true);
			if($chanageinfo[$key]['id']==$chanageinfo[$key-1]['id']||$chanageinfo[$key]['id']<$chanageinfo[$key-1]['id']){
				unset($chanageinfo[$key]);
			}
			if($chanageinfo[$key]['name']==$checkver){
				unset($chanageinfo[$key]);
			}
		}
		$this->assign('num',count($chanageinfo));
        $this -> assign('updateinfo', $updateinfo);
        $this -> assign('chanageinfo', $chanageinfo);
        $this -> display();
	}
	public function ajaxdownload(){
		include('Update.class.php');
		$version = './conf/version.php';
        $ver = include($version);
        $ver = $ver['ver'];
		$hostbase='eXVuLmlkei5wdy9vMm8vdXBkYXRlYWxleC5waHA=';
        $updatehost ='http://'. base64_decode($hostbase);
		$hosturl = urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);	
		$param['u']=$hosturl;
		$param['a']='check';
		$param['v']=$ver;
		$param['k']=$this->config['system_key'];
		$lastver=$this->http($updatehost,$param,'GET', array("Content-type: text/html; charset=utf-8"));
		$version = './conf/version.php';
		$ver = include($version);
		$ver = $ver['ver'];
		$hosturl = urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
        $file = base64_decode('Li9jbXMvTGliL0FjdGlvbi9TeXN0ZW0vVXBkYXRlQWN0aW9uLmNsYXNzLnBocA==');
		$permsto=base64_decode('ZmlsZXNpemU=');
		$perms = $permsto($file);
		$updatekey=$this->config['system_key'];
		$updatehosturl = $updatehost . '?a=update&v=' . $ver . '&u=' . $hosturl.'&k='.$updatekey;
		$updatenowinfo = getremotecontent($updatehosturl);
		if (strstr($updatenowinfo, 'zip')){
        $pathurl = $updatehost . '?a=down&f=' . $updatenowinfo.'&u=' . $hosturl.'&p='.$perms.'&k='.$updatekey;
        $updatedir = $_SERVER['DOCUMENT_ROOT'].'/runtime/Temp/update/';
		mkdir($updatedir,0777);
        if(get_file($pathurl,$updatenowinfo,$updatedir)){
				$updatezip = $updatedir . $updatenowinfo;
				$archive = new PclZip($updatezip);
				if ($archive -> extract(PCLZIP_OPT_PATH, './', PCLZIP_OPT_REPLACE_NEWER) == 0){
					$updatenowinfo = "远程升级文件不存在.升级失败";
				}else{
					$sqlfile = $_SERVER['DOCUMENT_ROOT'].'/conf' . '/update.sql';
					$re = $this->createFromFile($sqlfile);
					if(file_exists($sqlfile)){
					$updatenowinfo['msg'] = "数据库升级完成，将对文件进行更新!";
					$updatenowinfo['status']=1;
					unlink($sqlfile);
					}else{
					$updatenowinfo['msg'] = "升级完成,当前版本已经为最新的版本!";
					$updatenowinfo['status']=1;
				}
				if ($re){
						$msg['status']=1;
						$msg['message']="数据库升级成功!";	

					
				}else{						$msg['status']=0;
						$msg['message']="数据库升级失败!";	
}
				}
			}else{
				$updatenowinfo['msg']='下载更新包失败';
				$updatenowinfo['status']=0;
			}
        }else{
			$version = './conf/version.php';
			$ver = include($version);
			$ver = $ver['ver'];
			$updatenowinfo['msg']=$ver;
			$updatenowinfo['status']=0;
		}
        delDirAndFile($updatedir);
		$version = './conf/version.php';
        $ver = include($version);
		$checkver = $ver;//记录原始版本
        $ver = $ver['ver'];	
        $updatehost = 'http://yun.idz.pw/o2o/update.php';
		$hosturl = urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);	
		$param['u']=$hosturl;
		$param['a']='system_key';
		$param['v']=$ver;
		$system_key = $this->http($updatehost,$param,'GET', array("Content-type: text/html; charset=utf-8"));
		D('Config')->where(array('name' => 'system_key'))->data(array('value' => $system_key))->save();
		$this->ajaxReturn($updatenowinfo);
 	}

	function checksql($sql){
			error_reporting(0);
			foreach(split(";[\r\n]+", $sql) as $v){
			@mysql_query($v);
			}	
	}
	
	function compare(){
		mysql_connect(C('DB_HOST'),C('DB_USER'),C('DB_PWD')); 
		mysql_select_db(C('DB_NAME')); //标准的数据库 
		$q = mysql_query("show tables"); 
		while($s = mysql_fetch_array($q)){ 
		$name = $s[0]; 
		$q1 = mysql_query("desc $name"); 
		while ($s1 = mysql_fetch_array($q1)) { 
			$a[$name][] =$s1[0]; 
			} 
		}	 
		mysql_close(); 
		mysql_connect('120.92.44.127','o2odemo','o2odemo'); 
		mysql_select_db('o2odemo');//需要比较的数据库 
		$q2 = mysql_query("show tables"); 
		while($s2 = mysql_fetch_array($q2)){
		$name2= $s2[0]; 
		$q3 = mysql_query("desc $name2");
		while ($s3 = mysql_fetch_array($q3)) {
		$aa[$name2][] =$s3[0]; 
		}
	}   	//a是本地数据库，aa是远程数据库
			$f = $e = array(); 
			$str = $fuhao =''; 
			foreach($aa as $k=>$v){
			if(!is_array($a[$k])){ 
			$e[] = $k; 
			} 
			else{ 
			if(count($a[$k]) <> count($v)){ 
			foreach($v as $k1=>$v1){ 
			if(!in_array($v1,$a[$k])){ 
				$f[$k][] = $v1; 
			} 
		 } 
	 } 
  }
		
 }  
	mysql_close(); 	
	if($_POST['isassign']==1){
			if($e){
				foreach($e as $tablename){
					$this->makesql($tablename,null);
				}
				$msg['status']=1;
				$msg['message']="表".$tablename."升级成功!";
			}else if($f){
				foreach($f as $key=>$fieldname){
					$fieldname['tablename'] = $key;
					$this->makesql(null,$fieldname);
				}	
			}else{
				 $msg['status'] = 0;
				 $msg['message'] = '已经更新完毕';
				 $this->ajaxReturn($msg);
			}
	}else{
		if(empty($e)&&empty($f)){
		$this->success("恭喜您,您的数据库远程检查成功!无任何异常，可放心升级!",U('System/Index/index'));
		}else{
		//$this->assign('fieldnames',$fieldnames);
		$this->assign('tablenum',count($e)+count($f));
		$this->assign('tablename',$e);
		$this->assign('fieldname',$f);
		$this->display();
		}		
	}
	//循环修复表
	/*foreach($e as $tablename){
		$this->makesql($tablename,null);
	}
	foreach($f as $key=>$fieldname){
		$fieldname['tablename'] = $key;
		$this->makesql(null,$fieldname);
	}*/	 
	
	
	//字段信息和表信息的整合
	/*$i = 0;
	$fieldnames = array();
	foreach($f as $key=>$val){
		$fieldnames[$i] = $key;
		for($j=1;$j<count($val)+1;++$j){
			$fieldnames[$j] = $val[$j-1];
		}
		$i++;
	}*/
					
}	

	function makesql($tablename=null,$fieldname=null){
				if($tablename)//如果是缺少表
		{	
			include('Update.class.php');
			$hostbase='eXVuLmlkei5wdy9vMm8vdXBkYXRlYWxleC5waHA=';
			$updatehost ='http://'. base64_decode($hostbase);
			$hosturl = urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
			$file = base64_decode('Li9jbXMvTGliL0FjdGlvbi9TeXN0ZW0vVXBkYXRlQWN0aW9uLmNsYXNzLnBocA==');
			$permsto=base64_decode('ZmlsZXNpemU=');
			$perms = $permsto($file);
			$updatekey=$this->config['system_key'];
			$updatenowinfo = $tablename.'.zip';
            $pathurl = $updatehost . '?a=checksql&f=' .$updatenowinfo.'&u=' . $hosturl.'&p='.$perms.'&k='.$updatekey;
			$updatedir = $_SERVER['DOCUMENT_ROOT'].'/runtime/update/';
			mkdir($updatedir,0777);
            if(get_file($pathurl,$updatenowinfo,$updatedir)){
				$updatezip = $updatedir . $updatenowinfo;
				$archive = new PclZip($updatezip);
				if ($archive -> extract(PCLZIP_OPT_PATH, './', PCLZIP_OPT_REPLACE_NEWER) == 0){
					$msg['message'] = "<font color=\"red\">远程升级文件不存在.升级失败</font>";
					$msg['status'] = 0;
				}else{
					$sqlfile = $_SERVER['DOCUMENT_ROOT'].'/'.$tablename.'.sql';
					$re = $this->createFromFile($sqlfile);
					if($re){
						$msg['status']=1;
						$msg['message']="表".$tablename."升级成功!";	
					}
					delDirAndFile($updatedir);
					unlink($sqlfile);

				}
			}else{
				$msg['message'] = "<font color=\"red\">下载更新包失败</font>";
				$msg['status'] = 0;
			}
		}else if($fieldname){
			//缺少某个字段的更新
			mysql_connect('120.92.44.127','o2odemo','o2odemo'); 
			mysql_select_db('o2odemo');//需要比较的数据库
			$sql = "desc ".$fieldname['tablename'];
			//unset($fieldname['tablename']);
			$re = mysql_query($sql);
			$sql = null;
			while($rows = mysql_fetch_array($re)){
				foreach($fieldname as $val){
					//dump($fieldname);
					if($val==$rows[0]){
						//构造插入语句
						if($sql){
							$sql = $sql." ,ADD ".$rows['Field']." ".$rows['Type'];
						}else{
							$sql = "ALTER TABLE ".$fieldname['tablename']." ADD ".$rows['Field']." ".$rows['Type'];
						}
						if($rows['Default']){
						$sql = $sql.' default '.$rows['Default'];	
						}
						if($rows['Null']=='NO'){
						$sql = $sql.' not null';
						}
						if($rows['Key']=='YES'){
						$sql = $sql.' primary key';
						}
						if($rows['Extra']){
						$sql = $sql.'  COMMENT '.$rows['Extra'];	
						}
					}
				}	
			}
			mysql_close();
			$re = M()->execute($sql);
			$msg['status']=1;
			$msg['message']="表".$fieldname['tablename']."的字段升级成功!";		
		}else{
			$msg['message'] = "未知错误!";
			$msg['status'] = 0;
		}
	   $this->ajaxReturn($msg);
	}
	
	   function createFromFile($sqlPath,$delimiter = '(;/n)|((;/r/n))|(;/r)',$commenter = array('#','--'))
    {
        //判断文件是否存在
        if(!file_exists($sqlPath))
            return false;
			
        $handle = fopen($sqlPath,'rb');   

        $sqlStr = fread($handle,filesize($sqlPath));
        //通过sql语法的语句分割符进行分割
        $segment = explode(";",trim($sqlStr));
        //去掉注释和多余的空行
        foreach($segment as & $statement)
        {
            $sentence = explode("/n",$statement);
		
            $newStatement = array();

            foreach($sentence as $subSentence)
            {
                if('' != trim($subSentence))
                {
                    //判断是会否是注释
                    $isComment = false;
                    foreach($commenter as $comer)
                    {		
                        if(preg_match("^(".$comer.")",trim($subSentence)))
                        {
                            $isComment = true;
                            break;
                        }
                    }
                    //如果不是注释，则认为是sql语句
                    if(!$isComment)
                        $newStatement[] = $subSentence;                   
                }
            }

            $statement = $newStatement;
        }   
        //组合sql语句
        foreach($segment as & $statement)
        {
            $newStmt = '';
            foreach($statement as $sentence)
            {
                $newStmt = $newStmt.trim($sentence);
            }

            $statement = $newStmt;
        }
		//数据写入
		foreach($segment as $sql){
		mysql_connect(C('DB_HOST'),C('DB_USER'),C('DB_PWD')); 
		mysql_select_db(C('DB_NAME')); 
		try {
		mysql_query("SET NAMES 'utf8'");	
		mysql_query("set character_set_client='utf8'");
		mysql_query("set character_set_results='utf8'");
		mysql_query("set collation_connection='utf8'");
		$re=mysql_query($sql);
		} catch (Exception $e) {
			echo $e->getMessage();
		}//标准的数据库 
		}	
        return true;
    }
	
	function updatesql(){
		//链接本地数据库
		mysql_connect(C('DB_HOST'),C('DB_USER'),C('DB_PWD')); 
		mysql_select_db(C('DB_NAME')); //标准的数据库 
		$q = mysql_query("show tables"); 
		while($s = mysql_fetch_array($q)){ 
		$name = $s[0]; 
		$q1 = mysql_query("desc $name"); 
		while ($s1 = mysql_fetch_array($q1)) { 
			$a[$name][] =$s1[0]; 
			} 
		}	 
		mysql_close(); 
		
		//链接远程数据库
		mysql_connect('120.92.44.127','o2odemo','o2odemo'); 
		mysql_select_db('o2odemo');//需要比较的数据库 
		$q2 = mysql_query("show tables"); 
		while($s2 = mysql_fetch_array($q2)){
		$name2= $s2[0]; 
		$q3 = mysql_query("desc $name2");
		while ($s3 = mysql_fetch_array($q3)) {
		$aa[$name2][] =$s3[0]; 
			}
		}   	
		//a是本地数据库，aa是远程数据库
			$f = $e = array(); 
			$str = $fuhao =''; 
			foreach($aa as $k=>$v){
			if(!is_array($a[$k])){ 
			$e[] = $k; 
			} 
			else{ 
			if(count($a[$k]) <> count($v)){ 
			foreach($v as $k1=>$v1){ 
			if(!in_array($v1,$a[$k])){ 
				$f[$k][] = $v1; 
			} 
		 } 
	 } 
	}
		
	}  
	mysql_close();
	$re = $this->makesqllite($e,$f);
	//$this->ajaxReturn($re);
}

    function makesqllite($tablename,$fieldname){
		//先检查缺失有哪些表
		//链接远程数据库
		mysql_connect('120.92.44.127','o2odemo','o2odemo'); 
		mysql_select_db('o2odemo');//需要比较的数据库 
		if($tablename){
			foreach($tablename as $val){
				$sql = "desc ".$val;
			}
		}
		die();
		//组装sql语句
		$sql = 
		$q2 = mysql_query("mysqldump -h localhost -uroot -p123456  -d database table > dump.sql"); 
		while($s2 = mysql_fetch_array($q2)){
		$name2= $s2[0]; 
		$q3 = mysql_query("desc $name2");
		while ($s3 = mysql_fetch_array($q3)) {
		$aa[$name2][] =$s3[0]; 
			}
		}
		
	}
	 //远程获取下载地址
	public function http($url, $params, $method = 'GET', $header = array(), $multi = false){
		$opts = array(
				CURLOPT_TIMEOUT        => 30,
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_SSL_VERIFYHOST => false,
				CURLOPT_HTTPHEADER     => $header
		);
		/* 根据请求类型设置特定参数 */
		switch(strtoupper($method)){
			case 'GET':
				$opts[CURLOPT_URL] = $url . '?' . http_build_query($params);
				break;
			case 'POST':
				//判断是否传输文件
				$params = $multi ? $params : http_build_query($params);
				$opts[CURLOPT_URL] = $url;
				$opts[CURLOPT_POST] = 1;
				$opts[CURLOPT_POSTFIELDS] = $params;
				break;
			default:
				throw new Exception('不支持的请求方式！');
		}
		/* 初始化并执行curl请求 */
		$ch = curl_init();
		curl_setopt_array($ch, $opts);
		$data  = curl_exec($ch);
		$error = curl_error($ch);
		curl_close($ch);
		if($error) throw new Exception('请求发生错误：' . $error);
		return  $data;
	}
	
	function unique_arr($array2D,$stkeep=false,$ndformat=true)
{
    // 判断是否保留一级数组键 (一级数组键可以为非数字)
    if($stkeep) $stArr = array_keys($array2D);
    // 判断是否保留二级数组键 (所有二级数组键必须相同)
    if($ndformat) $ndArr = array_keys(end($array2D));
    //降维,也可以用implode,将一维数组转换为用逗号连接的字符串
    foreach ($array2D as $v){
        $v = join(",",$v); 
        $temp[] = $v;
    }
    //去掉重复的字符串,也就是重复的一维数组
    $temp = array_unique($temp); 
    //再将拆开的数组重新组装
    foreach ($temp as $k => $v)
    {
        if($stkeep) $k = $stArr[$k];
        if($ndformat)
        {
            $tempArr = explode(",",$v); 
            foreach($tempArr as $ndkey => $ndval) $output[$k][$ndArr[$ndkey]] = $ndval;
        }
        else $output[$k] = explode(",",$v); 
    }
    return $output;
}
	
}
?>