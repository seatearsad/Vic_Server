<include file="Public:header"/>
<div id="wrapper">

    <include file="Public:left_menu"/>
    <!----------------------------------------    以上不要写代码     ------------------------------------------------>
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2>{pigcms{:L('_BACK_HISTORY_RECORD_')}</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    {pigcms{:L('_BACK_DLVMNG_')}
                </li>
                <li class="breadcrumb-item">
                    <a href="{pigcms{:U('Deliver/user')}" style="text-decoration:underline;">{pigcms{:L('_BACK_COURIER_MANA_')}</a>
                </li>
                <li class="breadcrumb-item active">
                    <strong>【{pigcms{$user['name']}】{pigcms{:L('_BACK_HISTORY_RECORD_')} </strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-4 float-right" style="height 90px;margin-top:40px;">
            <div class="btn-group">
            </div>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox ">
<!--                    <div class="ibox-title">-->
<!--                        <h5>{pigcms{:L('_BACK_DAILY_TOTALS_')}</h5>-->
<!--                        <div class="ibox-tools">-->
<!--                            <if condition="$system_session['level'] neq 3">-->
<!--                                <div style="margin-left:40px;">-->
<!--                                </div>-->
<!--                            </if>-->
<!--                        </div>-->
<!--                    </div>-->
                    <div class="ibox-content">
                        <!-------------------------------- 工具条 -------------------------------------->

                        <!------------------------------------------------------------------------------>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover dataTables-example">
                                <thead>
                                <tr>
                                    <th>{pigcms{:L('_BACK_DATE_')}</th>
                                    <th>{pigcms{:L('_BACK_ORDERS_DELIVER_')}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <if condition="is_array($count_list)">
                                    <volist name="count_list" id="vo">
                                        <tr>
                                            <td>{pigcms{$vo.today}</td>
                                            <td>{pigcms{$vo.num}</td>
                                        </tr>
                                    </volist>

                                    <else/>
                                    <tr>
                                        <td class="textcenter red" colspan="2">No Data</td>
                                    </tr>
                                </if>
                                </tbody>
                            </table>
                            <div style="height: 30px;">
                                {pigcms{$pagebar}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <include file="Public:footer"/>