<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-gear gear-icon"></i>
				<a href="{pigcms{:U('Merchant_money/index')}">商家余额</a>
			</li>
			<li class="active">申请提现</li>
			
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<div class="tab-content">
						<div class="grid-view">
							<form enctype="multipart/form-data" class="form-horizontal" method="post" action="">
								
								<label style="color:red">您的余额为：${pigcms{$now_merchant['money']}</label><br>
								<label style="color:red"><if condition="$config['company_pay_mer_percent'] gt 0">提现手续费比例：{pigcms{$config['company_pay_mer_percent']}%<else />无手续费</if></label>
							
								<div class="form-group">
									<label class="col-sm-1"><label for="name">真实姓名</label></label>
									<input type="text" class="col-sm-2" name="name" id="name" value="{pigcms{$name}" />
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="money">金额</label></label>
									<input type="text" class="col-sm-2" name="money" id="money" value="{pigcms{$money}" />元&nbsp;<label id="percent"> </label>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="remark">备注</label></label>
									<textarea rows="4" cols="36" name="remark" onmousedown="s(event,this)" >
									
									</textarea>
								</div>
							
								<div class="clearfix form-actions">
									<div class="col-md-9">
										<button class="btn btn-info" type="submit">
											<i class="ace-icon fa fa-check bigger-110"></i>
											申请提现
										</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	var percent = Number('{pigcms{$config['company_pay_mer_percent']}')
	var money = Number('{pigcms{$now_merchant['money']}')
	$(document).ready(function() {
		$('#money').change(function(){
			if(!isNaN($(this).val()) || $(this).val()<0){
				alter('金额有误');
			}
			if($(this).val()>money){
				alter('提现金额超过余额');
				$(this).val(0);
				$('#percent').html('');
			}
			if(percent>0){
				$('#percent').html('(实际提现：'+($(this).val()*(100-percent)/100).toFixed(2)+' , 手续费：'+($(this).val()*(percent)/100).toFixed(2)+')')
			}else{
				$('#percent').html('');
			}
		});
		
	});

	function s(e,a)
	{
	if ( e && e.preventDefault )
	e.preventDefault();
	else 
	window.event.returnValue=false;
	a.focus();

	}
</script>
<include file="Public:footer"/>