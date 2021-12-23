<include file="Public:header"/>
<div id="wrapper">
    <include file="Public:left_menu"/>
    <!----------------------------------------    以上不要写代码     ------------------------------------------------>

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-9">
            <h2>{pigcms{:L('_BACK_NEWCOURIERVERFICATION_')}</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    {pigcms{:L('_BACK_DLVMNG_')}
                </li>
                <!--                <li class="breadcrumb-item">-->
                <!--                    <a>UI Elements</a>-->
                <!--                </li>-->
                <li class="breadcrumb-item active">
                    <strong>{pigcms{:L('_BACK_NEWCOURIERVERFICATION_')}</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-3" style="height 90px;margin-top:40px;">

        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title tutti_hidden_obj">
                        <h5>{pigcms{:L('_BACK_DELIVERY_LIST_')}</h5>
                        <div class="ibox-tools">

                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="table-responsive">
                            <!-------------------------------- 工具条 -------------------------------------->
                            <div style="margin-bottom: 10px;min-height: 50px">
                                <form action="{pigcms{:U('Deliver/review')}" method="get">
                                    <input type="hidden" name="c" value="Deliver"/>
                                    <input type="hidden" name="a" value="review"/>
                                <div id="tool_bar" class="form-inline">
                                    {pigcms{:L('_BACK_SEARCH_')} ：
                                    <input type="text" class="form-control" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
                                    &nbsp;
                                    <select name="searchtype" class="form-control">
                                        <option value="uid" <if condition="$_GET['searchtype'] eq 'uid'">selected="selected"</if>>{pigcms{:L('_BACK_USER_ID_')}</option>
                                        <option value="firstname" <if condition="$_GET['searchtype'] eq 'firstname'">selected="selected"</if>>First Name</option>
                                        <option value="lastname" <if condition="$_GET['searchtype'] eq 'lastname'">selected="selected"</if>>Last Name</option>
                                        <option value="phone" <if condition="$_GET['searchtype'] eq 'phone'">selected="selected"</if>>{pigcms{:L('_BACK_PHONE_NUM_')}</option>
                                        <option value="email" <if condition="$_GET['searchtype'] eq 'email'">selected="selected"</if>>{pigcms{:L('_BACK_EMAIL_')}</option>
                                    </select>
                                    <if condition="$system_session['level'] neq 3">
                                        &nbsp;City : &nbsp;
                                        <select name="city_id" id="city_select" class="form-control">
                                            <option value="0" <if condition="$city_id eq '' or $city_id eq 0">selected="selected"</if>>All</option>
                                            <volist name="city" id="vo">
                                                <option value="{pigcms{$vo.area_id}" <if condition="$city_id eq $vo['area_id']">selected="selected"</if>>{pigcms{$vo.area_name}</option>
                                            </volist>
                                        </select>
                                    </if>
                                    &nbsp;&nbsp;
                                    <input type="submit" class="form-control" value="{pigcms{:L('_BACK_SEARCH_')}" class="button"/>
                                </div>
                                </form>
                            </div>

                        <table class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>{pigcms{:L('_BACK_PHONE_NUM_')}</th>
                                <th>{pigcms{:L('_BACK_EMAIL_')}</th>
                                <th>{pigcms{:L('_BACK_REG_TIME_')}</th>
                                <th class="textcenter">{pigcms{:L('_BACK_STATUS_')}</th>
                                <th class="textcenter">{pigcms{:L('_BACK_CZ_')}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <if condition="is_array($user_list)">
                                <volist name="user_list" id="vo">
                                    <tr>
                                        <td>{pigcms{$vo.uid}</td>
                                        <td>{pigcms{$vo.name}</td>
                                        <td>{pigcms{$vo.family_name}</td>
                                        <td>{pigcms{$vo.phone}</td>
                                        <td>{pigcms{$vo.email}</td>
                                        <td>{pigcms{$vo.create_time|date='Y-m-d H:i:s',###}</td>
                                        <td class="textcenter">
                                            <if condition="$vo['bag_is_recruit'] eq 0 and $vo['reg_status'] eq 1">
                                                <font color="red">On Waitlist</font>
                                                <else />
                                                <if condition="$vo['reg_status'] neq 4 and $vo['reg_status'] neq 5 ">
                                                    <font color="red">{pigcms{:L('_BACK_REGISTERED_')}</font>
                                                </if>
                                            </if>


                                            <if condition="$vo['reg_status'] eq 4">
                                                <font color="green">
                                                    <if condition="$vo['bag_get_type'] eq -1 and $vo['is_online_pay'] eq 0">
                                                        <font color="red">Incomplete (Own Bag)</font>
                                                    </if>
                                                    <if condition="$vo['bag_get_type'] eq -1 and $vo['is_online_pay'] eq 1">
                                                        <font color="orange">Waiting for Bag Review</font>
                                                    </if>
                                                    <if condition="$vo['bag_get_type'] neq -1 and $vo['is_online_pay'] eq 0">
                                                        <font color="red">Incomplete (Purchase)</font>
                                                    </if>
                                                    <if condition="$vo['bag_get_type'] eq 1 and $vo['is_online_pay'] eq 1">
                                                        <font color="orange">Paid, Waiting for Shipping</font>
                                                    </if>
                                                    <if condition="$vo['bag_get_type'] eq 2 and $vo['is_online_pay'] eq 1">
                                                        <font color="green">Paid, Waiting for Pickup</font>
                                                    </if>
                                                </font>
                                                |
                                            </if>

                                            <if condition="$vo['reg_status'] eq 5">
                                                <font color="green">
                                                    <if condition="$vo['bag_get_type'] eq -1">
                                                        <font color="green">Bag Approved</font>
                                                    </if>
                                                    <if condition="$vo['bag_get_type'] eq 1">
                                                        <font color="green">Paid & Shipped</font>
                                                    </if>
                                                    <if condition="$vo['bag_get_type'] eq 2">
                                                        <font color="green">Paid, Waiting for Pickup</font>
                                                    </if>
                                                </font>
                                                |
                                            </if>

                                            <if condition="$vo['reg_status'] eq 4 or $vo['reg_status'] eq 5">
                                                <if condition="$vo['is_upload'] eq 0">
                                                    <font color="red">{pigcms{:L('D_INCOMPLETE_REGIST')}</font>
                                                    <else />
                                                    <if condition="$vo['group'] eq 0">
                                                        <font color="orange">{pigcms{:L('D_AWAITING_APPROVAL')}</font>
                                                    </if>
                                                    <if condition="$vo['group'] eq -1">
                                                        <font color="red">{pigcms{:L('D_INCOMPLETE_REGIST')}</font>
                                                    </if>
                                                    <if condition="$vo['group'] eq 1">
                                                        <font color="green">{pigcms{:L('D_APPROVED')}</font>
                                                    </if>
                                                </if>
                                            </if>

                                        </td>
                                        <td class="textcenter">　
                                            <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Deliver/user_view',array('uid'=>$vo['uid']))}','{pigcms{:L(\'_BACK_EDIT_COURIER_\')}',680,560,true,false,false,editbtn,'edit',true);"><button class="btn btn-white text-grey" type="button">{pigcms{:L('_BACK_EDIT_')}</button></a>
                                        </td>
                                    </tr>
                                </volist>
                                <tr><td class="textcenter pagebar" colspan="10">{pigcms{$pagebar}</td></tr>
                                <else/>
                                <tr><td class="textcenter red" colspan="10">{pigcms{:L('_BACK_EMPTY_')}</td></tr>
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
<script>
    var city_id = $('#city_select').val();
    $('#city_select').change(function () {
        // city_id = $(this).val();
        // window.location.href = "{pigcms{:U('Deliver/review')}" + "&city_id="+city_id;
    });
</script>
<include file="Public:footer"/>