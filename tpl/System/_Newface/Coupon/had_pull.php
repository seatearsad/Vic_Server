<include file="Public:header"/>
<div id="wrapper">
    <include file="Public:left_menu"/>
    <!----------------------------------------    以上不要写代码     ------------------------------------------------>

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-9">
            <h2>{pigcms{:L('_BACK_PICK_COU_LIST_')}</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    {pigcms{:L('_BACK_MARKETING_')}
                </li>
                <!--                <li class="breadcrumb-item">-->
                <!--                    <a>UI Elements</a>-->
                <!--                </li>-->
                <li class="breadcrumb-item active">
                    <strong>{pigcms{:L('_BACK_PICK_COU_LIST_')}</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-3" style="height 90px;margin-top:40px;">
            <div class="btn-group float-right">
                <button class="btn btn-white">
                    <a href="{pigcms{:U('Coupon/index')}" style="color: inherit">{pigcms{:L('_BACK_COUPON_LIST_')}</a>
                </button>
            </div>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox ">

                    <div class="ibox-content">
                        <div class="table-responsive">
                            <!-------------------------------- 工具条 -------------------------------------->
                            <div style="margin-bottom: 10px;min-height: 50px">
                                <div id="tool_bar" class="form-inline ">
                                    <form action="{pigcms{:U('Coupon/had_pull')}" method="get">
                                        <input type="hidden" name="c" value="Coupon"/>
                                        <input type="hidden" name="a" value="had_pull"/>
                                        {pigcms{:L('_BACK_SEARCH_')}:
                                        <input type="text" name="keyword" class="form-control" value="{pigcms{$_GET['keyword']}"/>
                                        <select name="searchtype" class="form-control">
                                            <option value="nickname" <if condition="$_GET['searchtype'] eq 'nickname'">selected="selected"</if>>{pigcms{:L('_BACK_LOGIN_NAME_')}</option>
                                            <option value="uid" <if condition="$_GET['searchtype'] eq 'uid'">selected="selected"</if>>{pigcms{:L('F_USER_ID')}</option>
                                            <option value="cid" <if condition="$_GET['searchtype'] eq 'cid'">selected="selected"</if>>{pigcms{:L('_BACK_COUPON_ID_')}</option>
                                        </select>
                                        <input type="submit" value="{pigcms{:L('_BACK_SEARCH_')}" class="form-control"/>
                                    </form>
                                </div>
                            </div>

                        <table class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>{pigcms{:L('_BACK_COUPON_NAME_')}</th>
                                <th>Coupon ID</th>
                                <th>{pigcms{:L('_BACK_LOGIN_NAME_')}</th>
                                <th>{pigcms{:L('F_USER_ID')}</th>
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
                                        <td>{pigcms{$vo.coupon_id}</td>
                                        <td>{pigcms{$vo.nickname}</td>
                                        <td>{pigcms{$vo.uid}</td>
                                        <td>{pigcms{$vo.num}</td>
                                        <td>{pigcms{$vo.receive_time|date='Y-m-d',###}</td>
                                        <td>{pigcms{$vo.admin_name}</td>
                                        <td class="textcenter">
                                            <if condition="$vo['status'] eq 2">
                                                <font color="red">{pigcms{:L('_BACK_NOTAVA_')}</font>
                                                <else/>
                                                    <if condition="$vo['is_use'] eq 1">
                                                    <font color="green">Used</font>
                                                    <elseif condition="$vo['is_use'] eq 0"/>
                                                    <font color="red">Not Yet</font>
                                                    </if>
                                            </if>
                                        </td>
                                    </tr>
                                </volist>
                                <tr><td class="textcenter pagebar" colspan="9">{pigcms{$pagebar}</td></tr>
                                <else/>
                                <tr><td class="textcenter red" colspan="9">{pigcms{:L('_BACK_EMPTY_')}</td></tr>
                            </if>
                            </tbody>
                            <tfoot>
                            <tr>
                            </tr>
                            </tfoot>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
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