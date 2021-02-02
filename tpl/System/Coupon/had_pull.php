<include file="Public:header"/>
<style>
	img{height:30px;width:60px;}
</style>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Coupon/index')}">{pigcms{:L('_BACK_COUPON_LIST_')}</a>
					<a href="{pigcms{:U('Coupon/had_pull')}" class="on">{pigcms{:L('_BACK_PICK_COU_LIST_')}</a>
				</ul>
			</div>
			<table class="search_table" width="100%">
				<tr>
					<td>
						<form action="{pigcms{:U('Coupon/had_pull')}" method="get">
							<input type="hidden" name="c" value="Coupon"/>
							<input type="hidden" name="a" value="had_pull"/>
                            {pigcms{:L('_BACK_SEARCH_')}: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
							<select name="searchtype">
								<option value="name" <if condition="$_GET['searchtype'] eq 'name'">selected="selected"</if>>{pigcms{:L('_BACK_COUPON_NAME_')}</option>
								<option value="nickname" <if condition="$_GET['searchtype'] eq 'nickname'">selected="selected"</if>>{pigcms{:L('_BACK_LOGIN_NAME_')}</option>
							</select>
							<input type="submit" value="{pigcms{:L('_BACK_SEARCH_')}" class="button"/>
						</form>
					</td>
				</tr>
			</table>
			<form name="myform" id="myform" action="" method="post">
				<div class="table-list">
					<table width="100%" cellspacing="0">
						<colgroup>
							<col/>
							<col/>
						
							<col/>
							<col/>
							<col width="180" align="center"/>
						</colgroup>
						<thead>
							<tr>
								<th>ID</th>
								<th>{pigcms{:L('_BACK_COUPON_NAME_')}</th>
								<th>{pigcms{:L('_BACK_LOGIN_NAME_')}</th>
							
								<th>{pigcms{:L('_BACK_QUANTITY_')}</th>
								<th>{pigcms{:L('_BACK_LING_TIME_')}</th>
                                <th>Admin</th>
								<th class="textcenter">{pigcms{:L('_BACK_STATUS_')}</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($coupon_list)">
								<volist name="coupon_list" id="vo">
									<tr>
										<td>{pigcms{$vo.id}</td>
										<td>{pigcms{$vo.name}</td>
										<td>{pigcms{$vo.nickname}</td>
										
										<td>{pigcms{$vo.num}</td>
										<td>{pigcms{$vo.receive_time|date='Y-m-d',###}</td>
                                        <td>{pigcms{$vo.admin_name}</td>
										<td class="textcenter"><if condition="$vo['is_use'] eq 1"><font color="green">Used</font><elseif condition="$vo['is_use'] eq 0" /><font color="red">Not Yet</font><else /><font color="red">{pigcms{:L('_BACK_PENDING_')}</font></if></td>
									</tr>
								</volist>
								<tr><td class="textcenter pagebar" colspan="7">{pigcms{$pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" colspan="7">{pigcms{:L('_BACK_EMPTY_')}</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script type="text/javascript">
	$(function(){
		$('#indexsort_edit_btn').click(function(){
			$(this).prop('disabled',true).html('提交中...');
			$.post("/merchant.php?g=Merchant&c=Config&a=merchant_indexsort",{group_indexsort:$('#group_indexsort').val(),indexsort_groupid:$('#indexsort_groupid').val()},function(result){
				alert('处理完成！正在刷新页面。');
				window.location.href = window.location.href;
			});
		});
		$('.see_qrcode').click(function(){
			art.dialog.open($(this).attr('href'),{
				init: function(){
					var iframe = this.iframe.contentWindow;
					window.top.art.dialog.data('iframe_handle',iframe);
				},
				id: 'handle',
				title:'查看渠道二维码',
				padding: 0,
				width: 430,
				height: 433,
				lock: true,
				resize: false,
				background:'black',
				button: null,
				fixed: false,
				close: null,
				left: '50%',
				top: '38.2%',
				opacity:'0.4'
			});
			return false;
		});
	});
	
</script>
<include file="Public:footer"/>