<include file="Public:header"/><div id="wrapper">    <include file="Public:left_menu"/>    <!----------------------------------------    以上不要写代码     ------------------------------------------------>    <div class="row wrapper border-bottom white-bg page-heading">        <div class="col-lg-9">            <h2>{pigcms{:L('SYSTEM_MESSAGE')}</h2>            <ol class="breadcrumb">                <li class="breadcrumb-item">                    {pigcms{:L('G_SYSTME_SETTINGS')}                </li>                <!--                <li class="breadcrumb-item">-->                <!--                    <a>UI Elements</a>-->                <!--                </li>-->                <li class="breadcrumb-item active">                    <strong>{pigcms{:L('SYSTEM_MESSAGE')}</strong>                </li>            </ol>        </div>        <div class="col-lg-3" style="height 90px;margin-top:40px;">            <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Config/add_message')}','{pigcms{:L(\'_B_PURE_MY_26_\')}',680,400,true,false,false,addbtn,'add',true);"><button type="button" class="btn btn-primary btn-sm float-right">{pigcms{:L('_B_PURE_MY_26_')}</button></a>        </div>    </div>    <div class="wrapper wrapper-content animated fadeInRight">        <div class="row">            <div class="col-lg-12">                <div class="ibox ">                    <div class="ibox-title tutti_hidden_obj">                        <h5>{pigcms{:L('SYSTEM_MESSAGE')}</h5>                        <div class="ibox-tools">                        </div>                    </div>                    <div class="ibox-content">                        <div class="table-responsive">                            <!-------------------------------- 工具条 -------------------------------------->                            <div style="margin-bottom: 10px;min-height: 50px">                                <form action="{pigcms{:U('Config/message')}" method="get">                                    <input type="hidden" name="c" value="Config"/>                                    <input type="hidden" name="a" value="message"/>                                    <div id="tool_bar" class="form-inline">                                        {pigcms{:L('G_TYPE')}:                                        <select name="type_select" id="type_select" class="form-control">                                            <option value="-1" <if condition="$type eq -1">selected="selected"</if>>All</option>                                            <option value="0" <if condition="$type eq 0">selected="selected"</if>>Text</option>                                            <option value="1" <if condition="$type eq 1">selected="selected"</if>>Image</option>                                        </select>                                        &nbsp;                                        <if condition="$system_session['level'] neq 3">                                            City:                                            <select name="city_select" id="city_select" class="form-control">                                                <option value="0"                                                <if condition="$city_id eq '' or $city_id eq 0">selected="selected"</if>>All</option>                                                <volist name="city" id="vo">                                                    <option value="{pigcms{$vo.area_id}"                                                    <if condition="$city_id eq $vo['area_id']">selected="selected"</if>                                                    >{pigcms{$vo.area_name}</option>                                                </volist>                                            </select>                                        </if>                                        &nbsp;                                        <input type="submit" value="{pigcms{:L('_BACK_SEARCH_')}" class="form-control"/>                                    </div>                                </form>                            </div>                        <table class="table table-striped table-bordered table-hover dataTables-example">                            <thead>                            <tr>                                <th>{pigcms{:L('G_ID')}</th>                                <th>{pigcms{:L('G_NAME')}</th>                                <th>{pigcms{:L('G_TYPE')}</th>                                <th>{pigcms{:L('I_CONTENT')}</th>                                <th>{pigcms{:L('G_START_TIME')}</th>                                <th>{pigcms{:L('G_END_TIME')}</th>                                <th>{pigcms{:L('G_STATUS')}</th>                                <th>{pigcms{:L('G_CITY')}</th>                                <if condition="$system_session['level'] eq 2 || $system_session['level'] eq 0">                                    <th class="textcenter">{pigcms{:L('G_ACTION')}</th>                                </if>                            </tr>                            </thead>                            <tbody>                            <if condition="is_array($message_list)">                                <volist name="message_list" id="vo">                                    <tr>                                        <td>{pigcms{$vo.id}</td>                                        <td>{pigcms{$vo.name}</td>                                        <td>                                            <if condition="$vo['type'] eq 0">                                                Text                                                <else />                                                Image                                            </if>                                        </td>                                        <td>                                            <if condition="$vo['type'] eq 0">                                                {pigcms{$vo.content}                                            <else />                                                <img src="{pigcms{$vo.content}" height="100" />                                            </if>                                        </td>                                        <td>                                            <if condition="$vo['begin_time'] eq 0">                                                {pigcms{:L('G_UNLIMITED')}                                                <else />                                                {pigcms{$vo.begin_time|date='Y-m-d',###}                                            </if>                                        </td>                                        <td>                                            <if condition="$vo['end_time'] eq 0">                                                {pigcms{:L('G_UNLIMITED')}                                                <else />                                                {pigcms{$vo.end_time|date='Y-m-d',###}                                            </if>                                        </td>                                        <td>                                            <if condition="$vo['status'] eq 1">                                                <span class="label label-primary">{pigcms{:L('_BACK_NORMAL_')}</span>                                                <elseif condition="$vo['status'] eq 2" />                                                <span class="label label-warning">{pigcms{:L('G_EXPIRED')}</span>                                                <else />                                                <span class="label label-warning">{pigcms{:L('_BACK_FORBID_')}</span>                                            </if>                                        </td>                                        <td>                                            {pigcms{$vo.city_name}                                        </td>                                        <if condition="$system_session['level'] eq 2 || $system_session['level'] eq 0">                                            <td class="textcenter">                                                <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Config/add_message',array('id'=>$vo['id']))}','{pigcms{:L(\'G_EDIT\')}',680,370,true,false,false,editbtn,'add',true);"><button class="btn btn-white text-grey" type="button">{pigcms{:L('G_EDIT')}</button></a>                                                <!--| <a href="javascript:void(0);" class="delete_row" parameter="id={pigcms{$vo.activity_id}" url="{pigcms{:U('Activity/del')}">删除</a-->                                            </td>                                        </if>                                    </tr>                                </volist>                                <td class="textcenter pagebar" colspan="11">{pigcms{$pagebar}</td>                                <else/>                                <tr><td class="textcenter red" colspan="9">{pigcms{:L('_BACK_EMPTY_')}</td></tr>                            </if>                            </tbody>                            <tfoot>                            <tr>                            </tr>                            </tfoot>                        </table>                        </div>                    </div>                </div>            </div>        </div><include file="Public:footer"/>