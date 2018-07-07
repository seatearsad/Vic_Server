<include file="Public:header"/>
<script src="./tpl/System/Update/images/jquery-1.10.2.min.js" type="text/javascript"></script>

<style type="text/css">
table.imagetable {
	font-family: verdana,arial,sans-serif;
	font-size:11px;
	color:#22303C;
	border-width: 1px;
	border-color: #999999;
	border-collapse: collapse;
}
table.imagetable th {
	background:#22303C url('cell-blue.jpg');
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #999999;
}
table.imagetable td {
	background:#FFFFFFurl('cell-grey.jpg');
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #999999;
}
</style>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Update/compare')}" class="on">数据库同步</a>|
				</ul>
			</div>
    <div class="con dcon">
	<ul class="myinfo">
   <li>
   <marquee width="550" scrollamount="10" direction="right" behavior="slide" ><font size="4">本次数据库升级详情</font></marquee>
   </li>
   <!-- Table goes in the document BODY -->
<table class="imagetable" width="100%">
<tr>
	<th><font color="#ffffff">表名称</font></th><th><font color="#ffffff">字段名称</font></th>
</tr>
<foreach name="tablename" item="vo">
	  <tr>
			<td align="center">{pigcms{:$vo}</td>
			<td align="center">缺整张表</td>
	  </tr>
</foreach>
<foreach name="fieldname" item="vo2">
	  <tr>
			<td align="center">{pigcms{:$key}</td>
			<td align="center">
			<foreach name="vo2" item="vo3">
				{pigcms{:$vo3},
			</foreach>
			</td>
	  </tr>
</foreach>
</table>
   <hr/>
   <table border="0" width="100%" bgcolor="#22303C" style="font-family: 'Microsoft YaHei'">
      <tr>
	  <td width="30%"> </td>
	  <td align="left"><font color="#ffffff"  size="3">我尊重官方的辛苦劳动成果:</font>
	  <input type="checkbox" id="checkresult" style="vertical-align: middle;"></h1></td>
	  <td width="300"> </td>
	  </tr>
	  
	   <tr>
	   <td> </td>
	  <td align="left"><font color="#ffffff" size="3">我愿意在活动结束后再进行更新操作:</font>
	  <input type="checkbox" id="checkrule" style="vertical-align: middle;"></td>
	  <td> </td>
	  </tr>
	  
      <tr>
	  <td></td>
	     <td align="left"><font color="#ffffff" size="3">我已经做好备份并自愿承担一切因不备份造成的后果:</font>
		 <input type="checkbox" id="checksql" style="vertical-align: middle;"></td>
	  <td></td>
	  </tr>
	  </table>
	  <hr/>
	  
   <table width="100%" border="0">
   <tr><td align="center"><input type="button" id="updatesql" style="cursor: pointer;background-color: #22303C;padding: 5px 15px;border-radius: 10px;border: 2px;color: white;margin-top: 12px;" value="开始更新">
   </td></tr>
   </table>
   </li>
	</ul>
    </div>
<div id="load" style="display:none;margin:50px auto;  padding: 8px; border: 1px solid gray; background: #EAEAEA; width: 400px;">
    <div style="padding: 0; background-color: white; border: 1px solid navy; width:400px">
        <div id="progress"
             style="padding: 0; background-color: #FFCC66; border: 0; width: 0px; text-align: center; height: 16px"></div>
    </div>
    <div id="status" style="color:red;">&nbsp;</div>
    <div id="percent"
         style="position: relative; top: -30px; text-align: center; font-weight: bold; font-size: 8pt;">0%</div>
</div>
			
			
			
		</div>
<script>
$(function(){
    $('#updatesql').bind('click',function(){
		if($('#checksql').is(':checked')&&$('#checkrule').is(':checked')&&$('#checkresult').is(':checked')){
		 //加载更新模块
			$('#load').css('display','block');
			 //ajax更新模块,获取更新模块
			var num = {pigcms{:$tablenum};
			/*var tablename = new Array();
			var fieldname = new Array();
			<?php foreach($tablename as $key=>$val){?>
			   tablename[<?php echo $key;?>] = "<?php echo $val;?>";
			<?php }?>
			//alert(tablename[0]);
			<?php foreach($fieldnames as $key=>$val){?>
			   fieldname[<?php echo $key;?>] = "<?php echo $val;?>";
			<?php }?>*/
			getajax(0,num);
		 }else{
			 alert("请勾选协议!");
		 }	
	});
})

	function updateProgress(sMsg, iWidth)
     {
            document.getElementById("status").innerHTML = sMsg;
            document.getElementById("progress").style.width = iWidth + "px";
            document.getElementById("percent").innerHTML = parseInt(iWidth / 400 * 100) + "%";
     }
	 //初始化identity为0
	 function getajax(identity,num){
		$.post("./admin.php?g=System&c=Update&a=compare",{isassign:1},function(msg){
				if(msg.status){
					if(identity<num){
						updateProgress(msg.message,400/num+identity*400/num);
						getajax(identity+1,num);
					}else{
						updateProgress("更新完毕!",400);
						location.href = "./admin.php?g=System&c=Update&a=compare";
					}	
				}else{
						updateProgress("更新完毕!",400);
						location.href = "./admin.php?g=System&c=Update&a=compare";
				}			
			  },'json')
		}
</script>
<include file="Public:footer"/>