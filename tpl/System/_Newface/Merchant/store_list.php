<include file="Public:header"/><div id="wrapper">    <include file="Public:left_menu"/>    <!----------------------------------------    以上不要写代码     ------------------------------------------------>    <div class="row wrapper border-bottom white-bg page-heading">        <div class="col-lg-9">            <h2>{pigcms{:L('E_STORE_STAT_LIST')}</h2>            <ol class="breadcrumb">                <li class="breadcrumb-item">                    <a href="{pigcms{:U('Index/index')}">Home</a>                </li>                <!--                <li class="breadcrumb-item">-->                <!--                    <a>UI Elements</a>-->                <!--                </li>-->                <li class="breadcrumb-item active">                    <strong>{pigcms{:L('E_STORE_STAT_LIST')}</strong>                </li>            </ol>        </div>        <div class="col-lg-3" style="height 90px;margin-top:40px;">        </div>    </div>    <div class="wrapper wrapper-content animated fadeInRight">        <div class="row">            <div class="col-lg-12">                <div class="ibox ">                    <div class="ibox-title tutti_hidden_obj">                        <h5>{pigcms{:L('_BACK_DELIVERY_LIST_')}</h5>                        <div class="ibox-tools">                        </div>                    </div>                    <div class="ibox-content">                        <div class="table-responsive">                            <!-------------------------------- 工具条 -------------------------------------->                            <div style="height: 55px;">                                <div id="tool_bar" class="form-inline tutti_toolbar">                                    <form action="{pigcms{:U('Merchant/store_list')}" method="get">                                        <input type="hidden" name="c" value="Merchant"/>                                        <input type="hidden" name="a" value="store_list"/>                                        {pigcms{:L('_BACK_SEARCH_')}:                                        <input type="text" name="keyword" class="form-control" value="{pigcms{$_GET['keyword']}"/>                                        <select name="searchtype" class="form-control">                                            <option value="name" <if condition="$_GET['searchtype'] eq 'name'">selected="selected"</if>>{pigcms{:L('E_STORE_NAME')}</option>                                            <option value="phone" <if condition="$_GET['searchtype'] eq 'phone'">selected="selected"</if>>{pigcms{:L('E_STORE_NUMB')}</option>                                            <option value="mer_id" <if condition="$_GET['searchtype'] eq 'mer_id'">selected="selected"</if>>{pigcms{:L('G_STORE_ID')}</option>                                        </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;                                        {pigcms{:L('E_STORE_STATUS')}:                                        <select name="searchstatus" class="form-control">                                            <option value="0" <if condition="$_GET['searchstatus'] eq 0">selected="selected"</if>>{pigcms{:L('_BACK_ALL_')}</option>                                            <option value="1" <if condition="$_GET['searchstatus'] eq '1'">selected="selected"</if>>{pigcms{:L('_BACK_NORMAL_')}</option>                                            <option value="2" <if condition="$_GET['searchstatus'] eq '2'">selected="selected"</if>>{pigcms{:L('_BACK_CLOSED_')}</option>                                        </select>                                        <if condition="$system_session['level'] neq 3">                                            City:                                            <select name="city_id" id="city_select" class="form-control">                                                <option value="0" <if condition="$city_id eq '' or $city_id eq 0">selected="selected"</if>>All</option>                                                <volist name="city" id="vo">                                                    <option value="{pigcms{$vo.area_id}" <if condition="$city_id eq $vo['area_id']">selected="selected"</if>>{pigcms{$vo.area_name}</option>                                                </volist>                                            </select>                                        </if>                                        <input type="submit" value="{pigcms{:L('_BACK_SEARCH_')}" class="form-control"/>                                    </form>                                </div>                            </div>                        </div>                        <table class="table table-striped table-bordered table-hover dataTables-example">                            <thead>                            <tr>                                <th>{pigcms{:L('_BACK_CODE_')}</th>                                <th>{pigcms{:L('E_STORE_NAME')}</th>                                <th>{pigcms{:L('E_STORE_NUMB')}</th>                                <th>{pigcms{:L('E_LASTUP')}</th>                                <th class="textcenter">{pigcms{:L('_BACK_VISIT_')}</th>                                <th>{pigcms{:L('_BACK_DELIVERY_STATUS_')}</th>                                <th class="textcenter">{pigcms{:L('_BACK_CZ_')}</th>                            </tr>                            </thead>                            <tbody>                            <if condition="is_array($store_list)">                                <volist name="store_list" id="vo">                                    <tr>                                        <td>{pigcms{$vo.store_id}</td>                                        <td>{pigcms{$vo.name}</td>                                        <td>{pigcms{$vo.phone}</td>                                        <td><if condition="$vo['last_time']">{pigcms{$vo.last_time|date='Y-m-d H:i:s',###}<else/>N/A</if></td>                                        <td class="textcenter"><if condition="$vo['status'] eq 1 OR $vo['status'] eq 3"><a href="{pigcms{:U('Merchant/merchant_login',array('mer_id'=>$vo['mer_id']))}" class="__full_screen_link" target="_blank">{pigcms{:L('_BACK_VISIT_')}</a><else/><a href="javascript:alert('商户状态不正常，无法访问！请先修改商户状态。');" class="__full_screen_link">{pigcms{:L('J_ACESS')}</a></if></td>                                        <td>                                            <if condition="$vo['status'] eq 1">                                                <span class="label label-primary">{pigcms{:L('_BACK_ACTIVE_')}</span><elseif condition="$vo['status'] eq 2"/><span class="label label-warning">{pigcms{:L('_BACK_PENDING_')}</span><elseif condition="$vo['status'] eq 3"/><span class="label label-warning">欠款</span><else/><span class="label label-warning">{pigcms{:L('_BACK_CLOSED_')}</span>                                            </if>                                            <if condition="$vo['store_is_close'] neq 0">                                                <span class="label label-warning">({pigcms{:L('_STORE_ON_HOLIDAY_')})</span>                                            </if>                                            <if condition="$vo['all_zero']">                                                <span class="label label-warning">{pigcms{:L('E_STORETIME0')}</span>                                            </if>                                        </td>                                        <td class="textcenter">                                            <!--a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Merchant/store_edit',array('store_id'=>$vo['store_id'],'frame_show'=>true))}','{pigcms{:L(\'_BACK_VIEW_\')}',620,480,true,false,false,false,'detail',true);"><button class="btn btn-white text-grey" type="button">{pigcms{:L('_BACK_VIEW_')}</button></a-->                                            <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Merchant/store_edit',array('store_id'=>$vo['store_id']))}','{pigcms{:L(\'_BACK_EDIT_STORE_INFO_\')}',680,480,true,false,false,editbtn,'store_add',true);"><button class="btn btn-white text-grey" type="button">{pigcms{:L('_BACK_EDIT_')}</button></a>                                            <a href="javascript:void(0);" class="delete_row" parameter="store_id={pigcms{$vo.store_id}" url="{pigcms{:U('Merchant/store_del')}"><button class="btn btn-white text-grey" type="button">{pigcms{:L('_BACK_DEL_')}</button></a>                                        </td>                                    </tr>                                </volist>                                <tr><td class="textcenter pagebar" <if condition="$system_session['level'] neq 3">colspan="7"<else />colspan="7"</if>>{pigcms{$pagebar}</td></tr>                                <else/>                                <tr><td class="textcenter red" <if condition="$system_session['level'] neq 3">colspan="7"<else />colspan="7"</if>>{pigcms{:L('_BACK_EMPTY_')}</td></tr>                            </if>                            </tbody>                            <tfoot>                            <tr>                            </tr>                            </tfoot>                        </table>                    </div>                </div>            </div>        </div><script>    var city_id = $('#city_select').val();    $('#city_select').change(function () {        // city_id = $(this).val();        // window.location.href = "{pigcms{:U('Merchant/store_list')}" + "&city_id="+city_id;    });</script><include file="Public:footer"/>