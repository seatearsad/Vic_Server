<include file="Public:header"/>
<div id="wrapper">
    <include file="Public:left_menu"/>
    <!----------------------------------------    以上不要写代码     ------------------------------------------------>

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-9">
            <h2>{pigcms{:L('_BACK_COUPON_LIST_')}</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{pigcms{:U('Index/index')}">Home</a>
                </li>
                <!--                <li class="breadcrumb-item">-->
                <!--                    <a>UI Elements</a>-->
                <!--                </li>-->
                <li class="breadcrumb-item active">
                    <strong>{pigcms{:L('_BACK_COUPON_LIST_')}</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-3" style="height 90px;margin-top:40px;">
            <div class="btn-group float-right">

                <a href="{pigcms{:U('Coupon/had_pull')}"
                   style="color: inherit"><button class="btn btn-white">{pigcms{:L('_BACK_PICK_COU_LIST_')}</button></a>

                <a href="javascript:void(0);"
                   onclick="window.top.artiframe('{pigcms{:U('Coupon/add')}','{pigcms{:L(\'_BACK_ADD_COUPON_\')}',800,500,true,false,false,addbtn,'edit',true);" style="margin-left:20px">
                    <button type="button" class="btn btn-primary float-right">{pigcms{:L('_BACK_ADD_COUPON_')}</button>
                </a>
            </div>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title">
                        <h5>{pigcms{:L('_BACK_COUPON_LIST_')}</h5>
                        <div class="ibox-tools">

                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="table-responsive">
                            <!-------------------------------- 工具条 -------------------------------------->
                            <div style="height: 50px;">
                                <div id="tool_bar" class="form-inline tutti_toolbar">
                                    <form action="{pigcms{:U('Coupon/index')}" method="get">
                                        <input type="hidden" name="c" value="Coupon"/>
                                        <input type="hidden" name="a" value="index"/>
                                        {pigcms{:L('_BACK_SEARCH_')}:
                                        <input type="text" name="keyword" class="form-control"
                                               value="{pigcms{$_GET['keyword']}"/>
                                        <select name="searchtype" class="form-control">
                                            <option value="name"
                                            <if condition="$_GET['searchtype'] eq 'name'">selected="selected"</if>
                                            >{pigcms{:L('_STORE_PRO_NAME_')}</option>
                                            <option value="coupon_id"
                                            <if condition="$_GET['searchtype'] eq 'coupon_id'">selected="selected"</if>
                                            >ID</option>
                                            <option value="code"
                                            <if condition="$_GET['searchtype'] eq 'code'">selected="selected"</if>
                                            >{pigcms{:L('_BACK_PICK_KEY_')}</option>
                                        </select>
                                        <if condition="$system_session['level'] neq 3">
                                            City:
                                            <select name="city_select" id="city_select" class="form-control">
                                                <option value="0"
                                                <if condition="$city_id eq '' or $city_id eq 0">selected="selected"</if>
                                                >All</option>
                                                <volist name="city" id="vo">
                                                    <option value="{pigcms{$vo.area_id}"
                                                    <if condition="$city_id eq $vo['area_id']">selected="selected"</if>
                                                    >{pigcms{$vo.area_name}</option>
                                                </volist>
                                            </select>
                                        </if>
                                        <input type="submit" value="{pigcms{:L('_BACK_SEARCH_')}" class="form-control"/>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <table class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>{pigcms{:L('_STORE_PRO_NAME_')}</th>
                                <th>{pigcms{:L('_BACK_PICK_KEY_')}</th>
                                <th>{pigcms{:L('_BACK_COUPON_TOTAL_')}</th>
                                <th>{pigcms{:L('_BACK_HAS_RECE_')}</th>
                                <th>{pigcms{:L('_BACK_PERIOD_')}</th>
                                <th>{pigcms{:L('_PURCHASE_TXT_')}</th>
                                <th class="textcenter">{pigcms{:L('_BACK_ONLY_NEW_USER_')}</th>
                                <th class="textcenter">{pigcms{:L('_B_PURE_MY_13_')}</th>
                                <th class="textcenter">{pigcms{:L('_BACK_STATUS_')}</th>
                                <th class="textcenter">{pigcms{:L('_BACK_CZ_')}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <if condition="is_array($coupon_list)">
                                <volist name="coupon_list" id="vo">
                                    <tr>
                                        <td>{pigcms{$vo.coupon_id}</td>
                                        <td>{pigcms{$vo.name}</td>
                                        <td>{pigcms{$vo.notice}</td>
                                        <td>{pigcms{$vo.num}</td>
                                        <td>{pigcms{$vo.had_pull}</td>
                                        <td>{pigcms{$vo.start_time|date='Y-m-d',###} -
                                            {pigcms{$vo.end_time|date='Y-m-d',###}
                                        </td>
                                        <td>
                                            <php>if(C('DEFAULT_LANG') == 'zh-cn'){</php>
                                            {pigcms{:replace_lang_str(L('_MAN_NUM_REDUCE_'),$vo['order_money'])}{pigcms{:replace_lang_str(L('_MAN_REDUCE_NUM_'),$vo['discount'])}
                                            <php>}else{</php>
                                            {pigcms{:replace_lang_str(L('_MAN_NUM_REDUCE_'),$vo['discount'])}{pigcms{:replace_lang_str(L('_MAN_REDUCE_NUM_'),$vo['order_money'])}
                                            <php>}</php>
                                        </td>
                                        <td class="textcenter">
                                            <if condition="$vo['allow_new'] eq 1"><font color="green">Yes</font>
                                                <else/>
                                                <font color="red">No</font></if>
                                        </td>
                                        <td class="textcenter">{pigcms{$vo.city_name}</td>
                                        <td class="textcenter">
                                            <if condition="$vo['status'] eq 1"><span class="label label-primary">{pigcms{:L('_BACK_ACTIVE_')}</span>
                                                <elseif condition="$vo['status'] eq 2"/>
                                                <span class="label label-warning">{pigcms{:L('_EXPIRED_TXT_')}</span>
                                                <elseif condition="$vo['status'] eq 3"/>
                                                <span class="label label-warning">领完了</span>
                                                <else/>
                                                <span class="label label-warning">{pigcms{:L('_BACK_FORBID_')}</span>
                                            </if>
                                        </td>
                                        <td class="textcenter">
                                            <a href="javascript:void(0);"
                                               onclick="window.top.artiframe('{pigcms{:U('Coupon/edit',array('coupon_id'=>$vo['coupon_id']))}','{pigcms{:L(\'_BACK_EDIT_COU_INFO_\')}',800,500,true,false,false,editbtn,'edit',true);">
                                                <button class="btn btn-white text-grey" type="button">
                                                    {pigcms{:L('_BACK_EDIT_')}
                                                </button>
                                            </a>
                                        </td>
                                    </tr>
                                </volist>
                                <tr>
                                    <td class="textcenter pagebar" colspan="11">{pigcms{$pagebar}</td>
                                </tr>
                                <else/>
                                <tr>
                                    <td class="textcenter red" colspan="11">{pigcms{:L('_BACK_EMPTY_')}</td>
                                </tr>
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
        <script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
        <script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
        <script type="text/javascript">
            $(function () {
                var city_id = $('#city_select').val();
                $('#city_select').change(function () {
                    city_id = $(this).val();
                    window.location.href = "{pigcms{:U('Coupon/index')}" + "&city_id=" + city_id;
                });

                $('#indexsort_edit_btn').click(function () {
                    $(this).prop('disabled', true).html('提交中...');
                    $.post("/merchant.php?g=Merchant&c=Config&a=merchant_indexsort", {
                        group_indexsort: $('#group_indexsort').val(),
                        indexsort_groupid: $('#indexsort_groupid').val()
                    }, function (result) {
                        alert('处理完成！正在刷新页面。');
                        window.location.href = window.location.href;
                    });
                });
                $('.see_qrcode').click(function () {
                    art.dialog.open($(this).attr('href'), {
                        init: function () {
                            var iframe = this.iframe.contentWindow;
                            window.top.art.dialog.data('iframe_handle', iframe);
                        },
                        id: 'handle',
                        title: '查看渠道二维码',
                        padding: 0,
                        width: 430,
                        height: 433,
                        lock: true,
                        resize: false,
                        background: 'black',
                        button: null,
                        fixed: false,
                        close: null,
                        left: '50%',
                        top: '38.2%',
                        opacity: '0.4'
                    });
                    return false;
                });
            });

        </script>
        <include file="Public:footer"/>