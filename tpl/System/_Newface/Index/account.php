<include file="Public:header"/><div id="wrapper">    <include file="Public:left_menu"/>    <!----------------------------------------    以上不要写代码     ------------------------------------------------>    <div class="row wrapper border-bottom white-bg page-heading">        <div class="col-lg-9">            <h2>{pigcms{:L('B_USERLIST')}</h2>            <ol class="breadcrumb">                <li class="breadcrumb-item">                    <a href="{pigcms{:U('Index/index')}">Home</a>                </li>                <!--                <li class="breadcrumb-item">-->                <!--                    <a>UI Elements</a>-->                <!--                </li>-->                <li class="breadcrumb-item active">                    <strong>{pigcms{:L('B_USERLIST')}</strong>                </li>            </ol>        </div>        <div class="col-lg-3" style="height 90px;margin-top:40px;">            <a href="javascript:void(0);"  onclick="window.top.artiframe('{pigcms{:U('Index/admin',array('area_id'=>$_GET['area_id']))}','{pigcms{:L(\'B_ADDUSER\')}',650,320,true,false,false,addbtn,'add',true);"><button type="button" class="btn btn-primary btn-sm float-right">{pigcms{:L('B_ADDUSER')}</button></a>        </div>    </div>    <div class="wrapper wrapper-content animated fadeInRight">        <div class="row">            <div class="col-lg-12">                <div class="ibox ">                    <div class="ibox-title">                        <h5>{pigcms{:L('B_USERLIST')}</h5>                        <div class="ibox-tools">                        </div>                    </div>                    <div class="ibox-content">                        <div class="table-responsive">                            <!-------------------------------- 工具条 -------------------------------------->                            <div style="height: 50px;">                            </div>                            <table class="table table-striped table-bordered table-hover dataTables-example">                                <thead>                                <tr>                                    <th  data-toggle="true">{pigcms{:L('B_ACCOUNTID')}</th>                                    <th>{pigcms{:L('B_ACCOUNTNAME')}</th>                                    <th>{pigcms{:L('B_NAME')}</th>                                    <th>{pigcms{:L('B_USERPHONE')}</th>                                    <th>{pigcms{:L('B_USERPEMAIL')}</th>                                    <th>{pigcms{:L('B_LASTLOGIN')}</th>                                    <th>{pigcms{:L('BASE_CITY')}</th>                                    <th>{pigcms{:L('B_NUMOFLOGIN')}</th>                                    <th>{pigcms{:L('B_USERSTAT')}</th>                                    <th>{pigcms{:L('B_ACTION')}</th>                                </tr>                                </thead>                                <tbody>                                    <if condition="is_array($admins)">                                        <volist name="admins" id="vo">                                            <tr class="gradeX">                                                <td>{pigcms{$vo.id}</td>                                                <td>{pigcms{$vo.account}</td>                                                <td>{pigcms{$vo.realname}</td>                                                <td>{pigcms{$vo.phone}</td>                                                <td>{pigcms{$vo.email}</td>                                                <td><if condition="$vo['last_time']">{pigcms{$vo.last_time|date='Y-m-d H:i:s',###}<else/>{pigcms{:L('J_NA')}</if></td>                                                <td>{pigcms{$vo.city_name}</td>                                                <td class="textcenter">{pigcms{$vo.login_count}</td>                                                <td class="textcenter" style="vertical-align: middle">                                                    <if condition="$vo['status'] eq 1">                                                        <span class="label label-primary">{pigcms{:L('B_STATACTIVE')}</span>                                                    <else />                                                        <span class="label label-warning">{pigcms{:L('B_STATCLD')}</span>                                                    </if>                                                </td>                                                <td class="textcenter">                                                    <if condition="$vo['level'] neq 2">                                                        <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Index/admin',array('id'=>$vo['id']))}','{pigcms{:L(\'BASE_EDIT\')}',650,320,true,false,false,editbtn,'edit',true);"><button class="btn btn-white text-grey" type="button">{pigcms{:L('BASE_EDIT')}</button></a>                                                        <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Index/menu',array('admin_id'=>$vo['id']))}','{pigcms{:L(\'B_PERMISSIONS\')}',800,500,true,false,false,editbtn,'edit',true);"><button class="btn btn-white text-grey" type="button">{pigcms{:L('B_PERMISSIONS')}</button></a>                                                        <if condition="$_SESSION['system']['level'] eq 2">                                                            <a href="javascript:void(0);" class="delete_row" parameter="id={pigcms{$vo.id}" url="{pigcms{:U('account_del')}"><button class="btn btn-white text-grey" type="button">{pigcms{:L('BASE_DELETE')}</button></a>                                                        </if>                                                    </if>                                                </td>                                            </tr>                                        </volist>                                    <else/>                                        <tr>                                            <td class="textcenter red" colspan="11">{pigcms{:L('_BACK_EMPTY_')}</td>                                        </tr>                                    </if>                                </tbody>                                <tfoot>                                <tr>                                </tr>                                </tfoot>                            </table>                        </div>                    </div>                </div>            </div>        </div><script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script><script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script><script type="text/javascript">var test;$(document).ready(function(){	$('.see_qrcode').on('click', function(){		test = art.dialog.open($(this).attr('href'),{			init: function(){				var iframe = this.iframe.contentWindow;				window.top.art.dialog.data('iframe_handle',iframe);			},			id: 'handle',			title:'扫描二维码绑定微信号',			padding: 0,			width: 430,			height: 433,			lock: true,			resize: false,			background:'black',			button: null,			fixed: false,			close: function(){clearInterval(t);},			left: '50%',			top: '38.2%',			opacity:'0.4'		});		var id = $(this).attr('data-id'), obj = $(this);	 	var t = window.setInterval(function(){			$.get("{pigcms{:U('Index/check_account')}", {id:id},  function(result){				if (result.error_code == 0) {					test.close();					clearInterval(t);					obj.parent('td').html(result.nickname).siblings('td').children('.cancel').show();				}			}, 'json');		},3000);		return false;	});	$('.cancel').click(function(){		var id = $(this).attr('data-id'), obj = $(this);		obj.attr('disabled', true);		$.get("{pigcms{:U('Index/cancel_account')}", {id:id}, function(result){			obj.attr('disabled', false);			if (result.error_code == 1) {				alert(result.msg);			} else {				var qrcode_id = 3890000000 + id;				obj.hide().parent('td').siblings('.nickname').html('<a href="{pigcms{$config.site_url}/index.php?g=Index&c=Recognition&a=see_tmp_qrcode&qrcode_id=' + qrcode_id + '&img=1" data-id="' + id + '" class="see_qrcode" style="color:green">绑定微信号</a>');			}		}, 'json');	});});</script><include file="Public:footer"/>