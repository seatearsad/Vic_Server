<!DOCTYPE html> 
<html> 
<head> 
<title>嘿小信升级说明</title> 
<script type="text/javascript"> 
function showNotice() {            
            document.getElementById("bg").style.display ="block";
            document.getElementById("show").style.display ="block";
            document.getElementsByTagName('body')[0].style.overflow='hidden';
            document.getElementsByTagName('body')[0].scroll="no";
            $('.zRight').css({'display':'none'});
        }
function hideNotice() {
	       // window.location.href = '';
            document.getElementById("bg").style.display ='none';
            document.getElementById("show").style.display ='none';
            document.getElementsByTagName('body')[0].style.overflow='auto';
            document.getElementsByTagName('body')[0].scroll="yes";
            $('.zRight').css({'display':'block'});
			
        }
        
</script>
<style type="text/css">
        #bg{ display: none;  position: absolute;  top: 0%;  left: 0%;  width: 100%;  height: 100%;  background-color: #FFFFFF;  z-index:1001;  -moz-opacity: 0.7;  opacity:.70;  filter: alpha(opacity=70);}
        #show{display: none; z-index:1002;  position: absolute;left:50px;top:0px;right:50px; }
</style>
 
</head> 
<body> 
<div id="bg"></div>
<div id="show">
{pigcms:$gonggao}
</div>
 <script type="text/javascript">
         window.onload=function(){
			var a = document.getElementById("agin").getElementsByTagName("a");
			var len = a.length;
			for(var i=0;i<len;i++){
				$("#ll").append("<li></li>");
			}
	    }
		{pigcms:$showNotice}
            showNotice();		
  </script>

</body> 
</html> 