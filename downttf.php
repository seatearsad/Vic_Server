<?php
set_time_limit(0);
ignore_user_abort(true);
if(!file_exists('./static/font/yahei.ttf')){
	$content = file_get_contents('http://hf.pigcms.com/static/font/yahei.ttf');
	if(file_put_contents('./static/font/yahei.ttf',$content)){
		unlink('downttf.php');
	}else{
		exit('文件没放成功');
	}
}else{
	exit('文件已经存在');
}