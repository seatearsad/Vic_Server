<include file="Public:header"/>
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
					<a href="{pigcms{:U('Update/compare')}" class="on">在线更新</a>
				</ul>
			</div>
    <div class="con dcon">
	<ul class="myinfo">
   <li>
   <marquee width="550" scrollamount="10" direction="right" behavior="slide" ><font size="4">{pigcms{:$updateinfo}</font></marquee>
   </li>
   <!-- Table goes in the document BODY -->
<table class="imagetable" width="100%">
<tr>
	<th><font color="#ffffff">版本号</font></th><th><font color="#ffffff">更新内容</font></th><th><font color="#ffffff">更新的内容请注意备份</font></th>
</tr>
<foreach name="chanageinfo" item="vo">
	  <tr>
			<td align="center">{pigcms{:$vo['name']}</td>
			<td align="center">{pigcms{:$vo['content']}</td>
			<td align="center">{pigcms{:$vo['filename']}</td>
	  </tr>
	  </foreach>
</table>
   <hr/>
   <table border="0" width="100%" bgcolor="#22303C" style="font-family: 'Microsoft YaHei'">
      <tr>
	  <td width="30%"> </td>
	  <td align="left"><font color="#ffffff"  size="3">我尊重官方的辛苦劳动成果:</font>
	  <input type="checkbox" id="checkrule" style="vertical-align: middle;"></h1></td>
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
   <tr><td align="center"><input type="button" id="sub" style="cursor: pointer;background-color: #22303C;padding: 5px 15px;border-radius: 10px;border: 2px;color: white;margin-top: 12px;" value="开始更新">
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
	$("#sub").bind('click',function(){
		 if($('#checksql').is(':checked')&&$('#checkrule').is(':checked')){
		 //加载更新模块
			$('#load').css('display','block');
			 //ajax更新模块,获取更新模块
			var num = {pigcms{:$num};
			getajax(0,num);
			//updateProgress("更新完毕!",400);
			location.href = "./admin.php?g=System&m=Update&a=index";
		 }else{
			 alert("请勾选协议!");
		 }
	})	
	 function updateProgress(sMsg, iWidth)
     {
            document.getElementById("status").innerHTML = sMsg;
            document.getElementById("progress").style.width = iWidth + "px";
            document.getElementById("percent").innerHTML = parseInt(iWidth / 400 * 100) + "%";
     }
	 //初始化identity为0
	 function getajax(identity,num){
		$.post("./admin.php?g=System&c=Update&a=ajaxdownload",{},function(msg){
					if(identity<num){
						updateProgress("更新"+msg+"版本号",400/num+identity*400/num);
						getajax(identity+1,num);
					}else{
					updateProgress("更新完毕!",400);
					location.href = "./admin.php?g=System&c=Update&a=index";
					}		
			  },'json')
	 }
})
</script>
<include file="Public:footer"/>