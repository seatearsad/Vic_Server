<if condition="!empty($scroll_msg)">
<div style="background:#f2dede;" class="scroll_msg">
	<div style="background:#f2dede;">
		<div class="" style="font-size:14px;" id="scrollText">
			<marquee  scrollamount="5" onmouseover = this.stop()  onmouseout=this.start() style="padding:10px 0 0 0">
			<volist name="scroll_msg" id="vo">
				<div style="display:inline-block">
					<span style="padding-right:30px;color:#a94442;">
						<i class="ice-icon fa fa-volume-up bigger-130"></i>
						<a href="#">{pigcms{$vo.content}</a>
					</span>
				</div>
			</volist>
			</marquee>
		</div>
	</div>
</div>


<style>
#scrollText div a{ color: #a94442;}
</style>
<link rel="stylesheet" href="{pigcms{$static_public}font-awesome/css/font-awesome.min.css">
</if>