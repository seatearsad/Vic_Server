<include file="Public:header"/><div id="wrapper">    <include file="Public:left_menu"/>    <!----------------------------------------    以上不要写代码     ------------------------------------------------>    <div class="row wrapper border-bottom white-bg page-heading">        <div class="col-lg-9">            <h2>{pigcms{:L('E_ORDER_REVIEWS')}</h2>            <ol class="breadcrumb">                <li class="breadcrumb-item">                    <a href="{pigcms{:U('Index/index')}">Home</a>                </li>                <!--                <li class="breadcrumb-item">-->                <!--                    <a>UI Elements</a>-->                <!--                </li>-->                <li class="breadcrumb-item active">                    <strong>{pigcms{:L('E_ORDER_REVIEWS')}</strong>                </li>            </ol>        </div>        <div class="col-lg-3 float-right" style="height 90px;margin-top:40px;">        </div>    </div>    <div class="wrapper wrapper-content animated fadeInRight">        <div class="row">            <div class="col-lg-12">                <div class="ibox ">                    <div class="ibox-title">                        <h5>{pigcms{:L('E_ORDER_REVIEWS')}</h5>                        <div class="ibox-tools">                            <if condition="$system_session['level'] neq 3">                                <div style="margin-left:40px;">                                </div>                            </if>                        </div>                    </div>                    <div class="ibox-content">                        <!-------------------------------- 工具条 -------------------------------------->                        <div style="height: 50px;">                            <div id="tool_bar" style="form-group tutti_toolbar" style="height: 80px;">                                <form action="{pigcms{:U('Merchant/reply')}" class="form-inline" method="get">                                    <input type="hidden" name="c" value="Merchant"/>                                    <input type="hidden" name="a" value="reply"/>                                    {pigcms{:L('E_FILTER')}:&nbsp;&nbsp; <input type="text" name="keyword" class="form-control" value="{pigcms{$_GET['keyword']}"/>                                    &nbsp;&nbsp;<select name="searchtype" class="form-control">                                        <option value="m_name" <if condition="$_GET['searchtype'] eq 'm_name'">selected="selected"</if>>{pigcms{:L('E_MERCHANTNAME')}</option>                                        <option value="s_name" <if condition="$_GET['searchtype'] eq 's_name'">selected="selected"</if>>{pigcms{:L('E_STORENAME')}</option>                                        <option value="nickname" <if condition="$_GET['searchtype'] eq 'nickname'">selected="selected"</if>>{pigcms{:L('E_USERNAME')}</option>                                        <option value="phone" <if condition="$_GET['searchtype'] eq 'phone'">selected="selected"</if>>{pigcms{:L('E_USERPHONE')}</option>                                    </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;                                    <input type="submit" class="form-control" value="{pigcms{:L('E_SEARCH')}" class="button"/>                                </form>                            </div>                        </div>                        <!------------------------------------------------------------------------------>                        <!-- <form name="myform" id="myform" action="" method="post">-->                        <table class="footable table table-stripped toggle-arrow-tiny" data-sorting="false">                            <thead>                            <tr>                                <th>{pigcms{:L('E_REVIEWID')}</th>                                <!--th>商户名称</th-->                                <th>{pigcms{:L('E_STORENAME')}</th>                                <th>City</th>                                <th>{pigcms{:L('E_REST_SCORE')}</th>                                <th>{pigcms{:L('E_REST_REVIEW')}</th>                                <!--th>评论类型</th-->                                <th>{pigcms{:L('E_COURIER_SCORE')}</th>                                <th>{pigcms{:L('E_COURIERREVIEW')}</th>                                <th data-hide="all" >{pigcms{:L('E_REST_REVIEW')}</th>                                <th data-hide="all">{pigcms{:L('E_USERPHONE')}</th>                                <th data-hide="all">{pigcms{:L('E_REVIEWTIME')}</th>                                <th data-hide="all">{pigcms{:L('E_RESTAURANT_REVIEW_TRANSLATE')}</th>                                <th data-hide="all">{pigcms{:L('E_COURIER_REVIEW_TRANSLATE')}</th>                                <th class="textcenter">{pigcms{:L('E_ACTION')}</th>                            </tr>                            </thead>                            <tbody>                            <if condition="is_array($reply_list)">                                <volist name="reply_list" id="vo">                                    <tr>                                        <td>{pigcms{$vo.pigcms_id}</td>                                        <td>{pigcms{$vo.s_name}</td>                                        <td>{pigcms{$vo.city_id}</td>                                        <td>{pigcms{$vo.score}</td>                                        <td>{pigcms{$vo.comment}</td>                                        <td><if condition="$vo['score_deliver'] neq -1">{pigcms{$vo.score_deliver}</if></td>                                        <td>{pigcms{$vo.comment_deliver}</td>                                        <td>{pigcms{$vo.nickname}</td>                                        <td>{pigcms{$vo.phone}</td>                                        <td><if condition="$vo['add_time']">{pigcms{$vo.add_time|date='Y-m-d H:i:s',###}<else/>None</if></td>                                        <td><if condition="$vo['comment_en']">{pigcms{$vo.comment_en}<else/>None</if></td>                                        <td><if condition="$vo['comment_deliver_en']">{pigcms{$vo.comment_deliver_en}<else/>None</if></td>                                        <td class="textcenter">                                            <div class="btn-group">                                                <div class="float-right">                                                    <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Merchant/replyinfo',array('reply_id'=>$vo['pigcms_id'],'frame_show'=>true))}','{pigcms{:L(\'E_VIEW\')}',520,470,true,false,false,false,'detail',true);"><button class="btn btn-white text-grey" type="button">{pigcms{:L('E_VIEW')}</button></a>                                                    <a href="javascript:void(0);" class="delete_row" parameter="reply_id={pigcms{$vo.pigcms_id}" url="{pigcms{:U('Merchant/replydel')}"><button class="btn btn-white text-grey" type="button">{pigcms{:L('E_DELETE')}</button></a>                                                    <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Shop/order_detail',array('order_id'=>$vo['order_id'],'frame_show'=>true))}','{pigcms{:L(\'_BACK_ORDER_DETAIL_\')}',920,520,true,false,false,false,'detail',true);"><button class="btn btn-white text-grey" type="button">{pigcms{:L('E_VIEWORDER')}</button></a>                                                </div>                                            </div>                                        </td>                                    </tr>                                </volist>                                <else/>                                <tr>                                    <td                                    <if condition="$system_session['level'] neq 3">colspan="9"                                        <else/>                                        colspan="22"                                    </if>                                    >{pigcms{:L('_BACK_EMPTY_')}</td>                                </tr>                            </if>                            </tbody>                            <tfoot>                            <tr>                            </tr>                            </tfoot>                        </table>                        <div style="height: 30px;">                            {pigcms{$pagebar}                        </div>                    </div>                </div>            </div>        </div>    <script>        var city_id = $('#city_select').val();        $('#city_select').change(function () {            city_id = $(this).val();            window.location.href = "{pigcms{:U('Deliver/user')}" + "&city_id="+city_id;        });        $(document).ready(function () {            $('.footable').footable({                "columns": {                    "sortable": false                }, "sorting": {                    "enabled": false                }            });            // $('.footable').footable({            //     "columns": {            //         "sortable": false            //     },{            //         ...            //     }            // });        });    </script>    <include file="Public:footer"/>=========================================================================================================================<include file="Public:header"/>		<div class="mainbox"><!--            ■ 列表信息：评论ID，店铺名称，城市，店铺评分，店铺评论，送餐员评分，送餐员评论，查看订单按键，删除按键；--><!--            ■ 下拉展开信息：用户名称，用户电话，评论时间，翻译评论内容--><!--            ■ 筛选：(1) 搜索筛选项：店铺名称，用户ID，用户电话，评论ID；(2) 请增加城市筛选；(3) 请增加店铺评分筛选；(4) 请增加送餐员评分筛选-->            <div class="table-list">                <table width="100%" cellspacing="0">                    <tbody>                        <if condition="is_array($reply_list)">                            <volist name="reply_list" id="vo">                                <tr>                                    <td>{pigcms{$vo.pigcms_id}</td>                                    <!--td>{pigcms{$vo.m_name}</td-->                                    <td>{pigcms{$vo.s_name}</td>                                    <td>{pigcms{$vo.nickname}</td>                                    <td>{pigcms{$vo.phone}</td>                                    <!--td><if condition="$vo['order_type'] eq 0">团购<elseif condition="$vo['order_type'] eq 1" />餐饮<elseif condition="$vo['order_type'] eq 2" />预约<elseif condition="$vo['order_type'] eq 3" />快店</if></td-->                                    <td><if condition="$vo['add_time']">{pigcms{$vo.add_time|date='Y-m-d H:i:s',###}<else/>None</if></td>                                    <td class="textcenter">{pigcms{$vo.score}</td>                                    <td style="width:100px">{pigcms{$vo.comment}</td>                                    <td class="textcenter"><if condition="$vo['score_deliver'] neq -1">{pigcms{$vo.score_deliver}</if></td>                                    <td style="width:100px">{pigcms{$vo.comment_deliver}</td>                                    <td class="textcenter">                                        <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Merchant/replyinfo',array('reply_id'=>$vo['pigcms_id'],'frame_show'=>true))}','{pigcms{:L(\'E_VIEW\')}',520,470,true,false,false,false,'detail',true);">{pigcms{:L('E_VIEW')}</a> |                                        <a href="javascript:void(0);" class="delete_row" parameter="reply_id={pigcms{$vo.pigcms_id}" url="{pigcms{:U('Merchant/replydel')}">{pigcms{:L('E_DELETE')}</a> |                                        <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Shop/order_detail',array('order_id'=>$vo['order_id'],'frame_show'=>true))}','{pigcms{:L(\'_BACK_ORDER_DETAIL_\')}',920,520,true,false,false,false,'detail',true);">{pigcms{:L('E_VIEWORDER')}</a>                                    </td>                                </tr>                            </volist>                            <tr><td class="textcenter pagebar" colspan="10">{pigcms{$pagebar}</td></tr>                        <else/>                            <tr><td class="textcenter red" colspan="10">{pigcms{:L('_BACK_EMPTY_')}</td></tr>                        </if>                    </tbody>                </table>            </div>		</div><include file="Public:footer"/>