<include file="Public:header"/><div id="wrapper">    <include file="Public:left_menu"/>    <!----------------------------------------    以上不要写代码     ------------------------------------------------>    <div class="row wrapper border-bottom white-bg page-heading">        <div class="col-lg-9">            <h2>{pigcms{:L('E_ORDER_REVIEWS')}</h2>            <ol class="breadcrumb">                <li class="breadcrumb-item">                    <a href="{pigcms{:U('Index/index')}">Home</a>                </li>                <!--                <li class="breadcrumb-item">-->                <!--                    <a>UI Elements</a>-->                <!--                </li>-->                <li class="breadcrumb-item active">                    <strong>{pigcms{:L('E_ORDER_REVIEWS')}</strong>                </li>            </ol>        </div>        <div class="col-lg-3 float-right" style="height 90px;margin-top:40px;">        </div>    </div>    <div class="wrapper wrapper-content animated fadeInRight">        <div class="row">            <div class="col-lg-12">                <div class="ibox ">                    <div class="ibox-title tutti_hidden_obj">                        <h5>{pigcms{:L('E_ORDER_REVIEWS')}</h5>                        <div class="ibox-tools">                            <if condition="$system_session['level'] neq 3">                                <div style="margin-left:40px;">                                </div>                            </if>                        </div>                    </div>                    <div class="ibox-content">                        <!-------------------------------- 工具条 -------------------------------------->                        <div style="height: 50px;">                            <div id="tool_bar" style="form-group tutti_toolbar" style="height: 80px;">                                <form action="{pigcms{:U('Merchant/reply')}" class="form-inline" method="get">                                    <input type="hidden" name="c" value="Merchant"/>                                    <input type="hidden" name="a" value="reply"/>                                    {pigcms{:L('E_FILTER')}:&nbsp;&nbsp; <input type="text" name="keyword" class="form-control" value="{pigcms{$_GET['keyword']}"/>                                    &nbsp;&nbsp;<select name="searchtype" class="form-control"><!--                                        <option value="m_name" <if condition="$_GET['searchtype'] eq 'm_name'">selected="selected"</if>>{pigcms{:L('_ND_RESTAURANT_')}</option>-->                                        <option value="s_name" <if condition="$_GET['searchtype'] eq 's_name'">selected="selected"</if>>{pigcms{:L('E_STORENAME')}</option>                                        <option value="userid" <if condition="$_GET['searchtype'] eq 'userid'">selected="selected"</if>>{pigcms{:L('F_USER_ID')}</option><!--                                        <option value="nickname" <if condition="$_GET['searchtype'] eq 'nickname'">selected="selected"</if>>{pigcms{:L('E_USERNAME')}</option>-->                                        <option value="phone" <if condition="$_GET['searchtype'] eq 'phone'">selected="selected"</if>>{pigcms{:L('E_USERPHONE')}</option>                                        <option value="replyid" <if condition="$_GET['searchtype'] eq 'replyid'">selected="selected"</if>>{pigcms{:L('E_REVIEWID')}</option>                                    </select>&nbsp;&nbsp;                                    <if condition="$system_session['level'] neq 3">                                    &nbsp;City:&nbsp;&nbsp;                                    <select name="city_select" id="city_select" class="form-control" >                                        <option value="0" <if condition="$city_id eq '' or $city_id eq 0">selected="selected"</if>>All</option>                                        <volist name="city" id="vo">                                            <option value="{pigcms{$vo.area_id}" <if condition="$city_id eq $vo['area_id']">selected="selected"</if>>{pigcms{$vo.area_name}</option>                                        </volist>                                    </select>&nbsp;&nbsp;                                    </if>                                    &nbsp;{pigcms{:L('E_REST_SCORE')}:&nbsp;&nbsp;                                    <select name="dianpu_rate" class="form-control" >                                        <option value="0" <if condition="$dianpu_rate eq 0">selected="selected"</if>>ALL</option>                                        <option value="5" <if condition="$dianpu_rate eq 5">selected="selected"</if>>5</option>                                        <option value="4" <if condition="$dianpu_rate eq 4">selected="selected"</if>>4</option>                                        <option value="3" <if condition="$dianpu_rate eq 3">selected="selected"</if>>3</option>                                        <option value="2" <if condition="$dianpu_rate eq 2">selected="selected"</if>>2</option>                                        <option value="1" <if condition="$dianpu_rate eq 1">selected="selected"</if>>1</option>                                    </select>&nbsp;&nbsp;                                    &nbsp;{pigcms{:L('E_COURIER_SCORE')}:&nbsp;&nbsp;                                    <select name="songcanyuan_rate" class="form-control" >                                        <option value="0" <if condition="$songcanyuan_rate eq 0">selected="selected"</if>>ALL</option>                                        <option value="5" <if condition="$songcanyuan_rate eq 5">selected="selected"</if>>5</option>                                        <option value="4" <if condition="$songcanyuan_rate eq 4">selected="selected"</if>>4</option>                                        <option value="3" <if condition="$songcanyuan_rate eq 3">selected="selected"</if>>3</option>                                        <option value="2" <if condition="$songcanyuan_rate eq 2">selected="selected"</if>>2</option>                                        <option value="1" <if condition="$songcanyuan_rate eq 1">selected="selected"</if>>1</option>                                    </select>&nbsp;&nbsp;                                    <input type="submit" class="form-control" value="{pigcms{:L('E_SEARCH')}" class="button"/>                                </form>                            </div>                        </div>                        <!------------------------------------------------------------------------------>                        <!-- <form name="myform" id="myform" action="" method="post">-->                        <table class="footable table table-stripped toggle-arrow-tiny" data-sorting="false">                            <thead>                            <tr>                                <th>{pigcms{:L('E_REVIEWID')}</th>                                <!--th>商户名称</th-->                                <th>{pigcms{:L('E_STORENAME')}</th>                                <th>City</th>                                <th>{pigcms{:L('E_REST_SCORE')}</th>                                <th>{pigcms{:L('E_REST_REVIEW')}</th>                                <!--th>评论类型</th-->                                <th>{pigcms{:L('E_COURIER_SCORE')}</th>                                <th>{pigcms{:L('E_COURIERREVIEW')}</th>                                <th data-hide="all" >{pigcms{:L('F_USER_NAME')}</th>                                <th data-hide="all">{pigcms{:L('E_USERPHONE')}</th>                                <th data-hide="all">{pigcms{:L('E_REVIEWTIME')}</th>                                <th data-hide="all">{pigcms{:L('E_RESTAURANT_REVIEW_TRANSLATE')}</th>                                <th data-hide="all">{pigcms{:L('E_COURIER_REVIEW_TRANSLATE')}</th>                                <th class="textcenter">{pigcms{:L('E_ACTION')}</th>                            </tr>                            </thead>                            <tbody>                            <if condition="is_array($reply_list)">                                <volist name="reply_list" id="vo">                                    <tr>                                        <td>{pigcms{$vo.pigcms_id}</td>                                        <td>{pigcms{$vo.s_name}</td>                                        <td>{pigcms{$vo.area_name}</td>                                        <td>{pigcms{$vo.score}</td>                                        <td>{pigcms{$vo.comment}</td>                                        <td><if condition="$vo['score_deliver'] neq -1">{pigcms{$vo.score_deliver}</if></td>                                        <td>{pigcms{$vo.comment_deliver}</td>                                        <td>{pigcms{$vo.nickname} ({pigcms{$vo.userid})</td>                                        <td>{pigcms{$vo.phone}</td>                                        <td><if condition="$vo['add_time']">{pigcms{$vo.add_time|date='Y-m-d H:i:s',###}<else/>None</if></td>                                        <td><if condition="$vo['comment_en']">{pigcms{$vo.comment_en}<else/>None</if></td>                                        <td><if condition="$vo['comment_deliver_en']">{pigcms{$vo.comment_deliver_en}<else/>None</if></td>                                        <td class="textcenter">                                            <div class="btn-group">                                                <div class="float-right"><!--                                                    <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Merchant/replyinfo',array('reply_id'=>$vo['pigcms_id'],'frame_show'=>true))}','{pigcms{:L(\'E_VIEW\')}',520,470,true,false,false,false,'detail',true);"><button class="btn btn-white text-grey" type="button">{pigcms{:L('E_VIEW')}</button></a>-->                                                    <a href="javascript:void(0);" class="delete_row" parameter="reply_id={pigcms{$vo.pigcms_id}" url="{pigcms{:U('Merchant/replydel')}"><button class="btn btn-white text-grey" type="button">{pigcms{:L('E_DELETE')}</button></a>                                                    <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Shop/order_detail',array('order_id'=>$vo['order_id'],'frame_show'=>true))}','{pigcms{:L(\'_BACK_ORDER_DETAIL_\')}',920,520,true,false,false,false,'detail',true);"><button class="btn btn-white text-grey" type="button">{pigcms{:L('E_VIEWORDER')}</button></a>                                                </div>                                            </div>                                        </td>                                    </tr>                                </volist>                                <else/>                                <tr>                                    <td                                    <if condition="$system_session['level'] neq 3">colspan="9"                                        <else/>                                        colspan="22"                                    </if>                                    >{pigcms{:L('_BACK_EMPTY_')}</td>                                </tr>                            </if>                            </tbody>                            <tfoot>                            <tr>                            </tr>                            </tfoot>                        </table>                        <div id="table_pagebar" style="height: 30px;">                        </div>                    </div>                </div>            </div>        </div>    <script>        var pagestr='{pigcms{$pagebar}';        let pagediv= $('#table_pagebar');        var city_id = $('#city_select').val();        $('#city_select').change(function () {            city_id = $(this).val();            window.location.href = "{pigcms{:U('Merchant/reply')}" + "&city_id="+city_id;        });        $(document).ready(function () {            $('.footable').footable({                "columns": {                    "sortable": false                }, "sorting": {                    "enabled": false                }            });            pagediv.html(pagestr);            // $('.footable').footable({            //     "columns": {            //         "sortable": false            //     },{            //         ...            //     }            // });        });    </script>    <include file="Public:footer"/>