<include file="Public:header"/>
<div id="wrapper">
    <include file="Public:left_menu"/>
    <!----------------------------------------    以上不要写代码     ------------------------------------------------>
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-6">
            <h2>{pigcms{:L('F_CREDITS_ADDED')}</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    {pigcms{:L('_BACK_USERMNG_')}
                </li>
                <!--                <li class="breadcrumb-item">-->
                <!--                    <a>UI Elements</a>-->
                <!--                </li>-->
                <li class="breadcrumb-item active">
                    <strong>{pigcms{:L('F_CREDITS_ADDED')}</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-6" style="height 90px;margin-top:40px;">
            <div class="btn-group float-right">
                <button class="btn btn-white active">
                    <a href="{pigcms{:U('User/recharge_list')}" style="color: inherit">{pigcms{:L('F_TOP_UP_LIST')}</a>
                </button>
            </div>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title tutti_hidden_obj">
                        <h5>{pigcms{:L('F_CREDITS_ADDED')}</h5>
                        <div class="ibox-tools">

                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="table-responsive">
                            <!-------------------------------- 工具条 -------------------------------------->
                            <div style="margin-bottom: 10px;min-height: 50px">
                                <div id="tool_bar" class="form-inline tutti_toolbar">
                                    <form action="{pigcms{:U('admin_recharge_list')}" method="get">
                                        <input type="hidden" name="c" value="User"/>
                                        <input type="hidden" name="a" value="admin_recharge_list"/>
                                        {pigcms{:L('F_FILTER')}:
                                        <input type="text" name="keyword" class="form-control" value="{pigcms{$_GET['keyword']}"/>
                                        <select name="searchtype" class="form-control">
                                            <option value="uid" <if condition="$_GET['searchtype'] eq 'uid'">selected="selected"</if>>{pigcms{:L('F_USER_ID')}</option>
                                        </select>
                                        {pigcms{:L('F_SEARCH_ADMIN')}:
                                        <select name="admin_id" class="form-control">
                                            <option value="0">All</option>
                                            <volist name="admin_list" id="vo">
                                                <option value="{pigcms{$vo.id}">{pigcms{$vo.realname}</option>
                                            </volist>
                                        </select>
                                        <font color="#000">{pigcms{:L('F_SEARCH_DATE')}：</font>
                                        <input type="text" class="form-control" name="begin_time" style="width:120px;" id="d4311"  value="{pigcms{$_GET.begin_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>
                                        <input type="text" class="form-control" name="end_time" style="width:120px;" id="d4311" value="{pigcms{$_GET.end_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>
                                        <input type="submit" value="{pigcms{:L('F_SEARCH')}" class="form-control"/>　　
                                    </form>
                                </div>
                            </div>
                        </div>
                        <table class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                            <tr>
                                <th>{pigcms{:L('F_ORDER_ID')}</th>
                                <th>{pigcms{:L('F_AMOUNT')}</th>
                                <th>{pigcms{:L('F_USER')}</th>
                                <th>Admin</th>
                                <th>{pigcms{:L('F_USER_INFO')}</th>
                                <th>{pigcms{:L('F_TIME')}</th>
                                <th class="textcenter">{pigcms{:L('F_ACTION')}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <if condition="is_array($recharge_list)">
                                <volist name="recharge_list" id="vo">
                                    <tr>
                                        <td>{pigcms{$vo.pigcms_id}</td>
                                        <td><if condition="$vo.type eq 1">{pigcms{:L('F_ADD')}: <else />{pigcms{:L('F_LESS')}: </if>${pigcms{$vo.money}</td>

                                        <td><if condition="$vo.nickname">{pigcms{$vo.nickname}<else />{pigcms{$vo.phone}</if></td>
                                        <td>{pigcms{$vo.realname}</td>
                                        <td>
                                            <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('User/edit',array('uid'=>$vo['uid']))}','{pigcms{:L(\'F_EDIT_INFO\')}',680,560,true,false,false,editbtn,'edit',true);">{pigcms{:L('F_USER_INFO')}</a>
                                        </td>
                                        <td>
                                            {pigcms{$vo['time']|date='Y-m-d H:i:s',###}<br/>
                                        </td>
                                        <td class="textcenter">{pigcms{:str_replace("管理员后台操作","Backend Operated by ",$vo['desc'])}</td>
                                    </tr>
                                </volist>
                                <tr><td class="textcenter pagebar" colspan="7">{pigcms{$pagebar}</td></tr>
                                <else/>
                                <tr><td class="textcenter red" colspan="7">{pigcms{:L('_BACK_EMPTY_')}</td></tr>
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
<script>
$(function(){
	$('#status').change(function(){
		//location.href = "{pigcms{:U('User/recharge_list')}&status=" + $(this).val();
	});
});

</script>
<include file="Public:footer"/>