<include file="Public:header"/><div id="wrapper">    <include file="Public:left_menu"/>    <!----------------------------------------    以上不要写代码     ------------------------------------------------>    <div class="row wrapper border-bottom white-bg page-heading">        <div class="col-lg-9">            <h2>{pigcms{:L('G_EVENT_LIST')}</h2>            <ol class="breadcrumb">                <li class="breadcrumb-item">                    <a href="{pigcms{:U('Index/index')}">Home</a>                </li>                <!--                <li class="breadcrumb-item">-->                <!--                    <a>UI Elements</a>-->                <!--                </li>-->                <li class="breadcrumb-item active">                    <strong>{pigcms{:L('G_EVENT_LIST')}</strong>                </li>            </ol>        </div>        <div class="col-lg-3" style="height 90px;margin-top:40px;">            <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Event/add')}','{pigcms{:L(\'G_ADD_EVENT\')}',680,300,true,false,false,addbtn,'add',true);"><button type="button" class="btn btn-primary btn-sm float-right">{pigcms{:L('G_ADD_EVENT')}</button></a>        </div>    </div>    <div class="wrapper wrapper-content animated fadeInRight">        <div class="row">            <div class="col-lg-12">                <div class="ibox ">                    <div class="ibox-title">                        <h5>{pigcms{:L('G_EVENT_LIST')}</h5>                        <div class="ibox-tools">                        </div>                    </div>                    <div class="ibox-content">                        <div class="table-responsive">                            <!-------------------------------- 工具条 -------------------------------------->                            <div style="height: 50px;">                                <div id="tool_bar" class="form-inline tutti_toolbar">                                </div>                            </div>                        </div>                        <table class="table table-striped table-bordered table-hover dataTables-example">                            <thead>                            <tr>                                <th>{pigcms{:L('G_ID')}</th>                                <th>{pigcms{:L('G_NAME')}</th>                                <th>{pigcms{:L('G_TYPE')}</th>                                <th>{pigcms{:L('G_START_TIME')}</th>                                <th>{pigcms{:L('G_END_TIME')}</th>                                <th>{pigcms{:L('G_STATUS')}</th>                                <th>{pigcms{:L('G_CITY')}</th>                                <th>{pigcms{:L('G_COUPON_LIST')}</th>                                <if condition="$system_session['level'] eq 2 || $system_session['level'] eq 0">                                    <th class="textcenter">{pigcms{:L('G_ACTION')}</th>                                </if>                            </tr>                            </thead>                            <tbody>                            <if condition="is_array($event_list)">                                <volist name="event_list" id="vo">                                    <tr>                                        <td>{pigcms{$vo.id}</td>                                        <td>{pigcms{$vo.name}</td>                                        <td>{pigcms{$vo.type_name}</td>                                        <td>                                            <if condition="$vo['begin_time'] eq 0">                                                {pigcms{:L('G_UNLIMITED')}                                                <else />                                                {pigcms{$vo.begin_time|date='Y-m-d',###}                                            </if>                                        </td>                                        <td>                                            <if condition="$vo['end_time'] eq 0">                                                {pigcms{:L('G_UNLIMITED')}                                                <else />                                                {pigcms{$vo.end_time|date='Y-m-d',###}                                            </if>                                        </td>                                        <td>                                            <if condition="$vo['status'] eq 1">                                                <span class="label label-primary">{pigcms{$vo.status_name}</span>                                                <else />                                                <span class="label label-warning">{pigcms{$vo.status_name}</span>                                            </if>                                        </td>                                        <td>                                            <if condition="$vo['type'] neq 3">                                                -                                                <else />                                                {pigcms{$vo.city_name}                                            </if>                                        </td>                                        <td>                                            <a href="{pigcms{:U('Event/coupon_list',array('id'=>$vo['id']))}"><button class="btn btn-white text-grey" type="button">{pigcms{:L('G_COUPON_LIST')}</button></a>                                        </td>                                        <if condition="$system_session['level'] eq 2 || $system_session['level'] eq 0">                                            <td class="textcenter">                                                <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Event/add',array('id'=>$vo['id']))}','{pigcms{:L(\'G_EDIT\')}',680,370,true,false,false,editbtn,'add',true);"><button class="btn btn-white text-grey" type="button">{pigcms{:L('G_EDIT')}</button></a>                                                <!--| <a href="javascript:void(0);" class="delete_row" parameter="id={pigcms{$vo.activity_id}" url="{pigcms{:U('Activity/del')}">删除</a-->                                            </td>                                        </if>                                    </tr>                                </volist>                                <else/>                                <tr><td class="textcenter red" colspan="9">{pigcms{:L('_BACK_EMPTY_')}</td></tr>                            </if>                            </tbody>                            <tfoot>                            <tr>                            </tr>                            </tfoot>                        </table>                    </div>                </div>            </div>        </div><include file="Public:footer"/>