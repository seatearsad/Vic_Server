<include file="Public:header"/>
<style>.frame_form td {
        vertical-align: middle;
    }

    select {
        width: 80px;
    }

    textarea {
        width: 300px;
        height: 80px;
    }

    .textIamge {
        background-image: none !important;
    }

    .wx_coupon {
        < if condition = "$coupon['sync_wx'] eq 0 OR  $coupon['wx_cardid'] eq ''" >
        display: none;
        < / if >
    }

    .mini_img {
        width: 60px;
        height: 30px;
    }
</style>
<body style="background-color: #fff;">
<div id="wrapper-singlepage">
    <div id="page-wrapper-singlepage" class="white-bg">
        <!----------------------------------------    以上不要写代码     ------------------------------------------------>
        <div class="row wrapper wrapper-content animated fadeInRight">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-content">
                        <div>
                            <form id="myform" method="post" action="{pigcms{:U('Coupon/edit')}" frame="true"
                                  refresh="true">

                                    <input type="hidden" name="coupon_id" value="{pigcms{$coupon.coupon_id}">

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">{pigcms{:L('_STORE_PRO_NAME_')}</label>
                                        <div class="col-sm-9 col-form-label">{pigcms{$coupon.name}</div>
                                    </div>

                                    <div class="form-group  row wx_coupon tutti_hidden_obj">
                                        <label class="col-sm-3 col-form-label">创建朋友的券</label>
                                        <div class="col-sm-9 col-form-label">
                                            <if condition="$coupon['share_friends'] eq 1"><span>是</span>
                                                <else/>
                                                <span>否</span></if> &nbsp;&nbsp;选择创建朋友的券后该优惠券不能分享和赠送
                                        </div>
                                    </div>

                                    <div class="form-group  row wx_coupon tutti_hidden_obj">
                                        <label class="col-sm-3 col-form-label">卡券颜色</label>
                                        <div class="col-sm-9 col-form-label">
                                            <div id="wx_color" style="width:30px;height:30px;background-color:{pigcms{$coupon.color}; float:left;margin-left:10px"></div>
                                        </div>
                                    </div>

                                    <div class="form-group  row wx_coupon tutti_hidden_obj">
                                        <label class="col-sm-3 col-form-label">商家名称</label>
                                        <div class="col-sm-9 col-form-label">
                                            {pigcms{$coupon.brand_name}
                                        </div>
                                    </div>

                                    <div class="form-group  row wx_coupon tutti_hidden_obj">
                                        <label class="col-sm-3 col-form-label">卡券提示</label>
                                        <div class="col-sm-9 col-form-label">
                                            {pigcms{$coupon.notice}
                                        </div>
                                    </div>

                                    <div class="form-group  row wx_coupon tutti_hidden_obj">
                                        <label class="col-sm-3 col-form-label">卡券副标题</label>
                                        <div class="col-sm-9 col-form-label">
                                            {pigcms{$coupon.center_sub_title}
                                        </div>
                                    </div>

                                    <div class="form-group  row wx_coupon tutti_hidden_obj">
                                        <label class="col-sm-3 col-form-label">立即使用链接</label>
                                        <div class="col-sm-9 col-form-label">
                                            {pigcms{$coupon.center_url}
                                        </div>
                                    </div>


                                    <div class="form-group  row wx_coupon tutti_hidden_obj">
                                        <label class="col-sm-3 col-form-label">更多优惠链接</label>
                                        <div class="col-sm-9 col-form-label">
                                            {pigcms{$coupon.promotion_url}
                                        </div>
                                    </div>

                                    <div class="form-grouprow wx_coupon tutti_hidden_obj">
                                        <label class="col-sm-3 col-form-label">自定义链接</label>
                                        <div class="col-sm-9 col-form-label">
                                            标题：{pigcms{$coupon.custom_url_name}<br><br>
                                            链接：{pigcms{$coupon.custom_url}<br><br>
                                            副标题：{pigcms{$coupon.custom_url_sub_title}
                                        </div>
                                    </div>

                                    <div class="form-group  row wx_coupon tutti_hidden_obj">
                                        <label class="col-sm-3 col-form-label">封面图片</label>
                                        <div class="col-sm-9 col-form-label">
                                            <img class="mini_img" src="{pigcms{$coupon.icon_url_list}">&nbsp;&nbsp; 描述 :{pigcms{$coupon.abstract}
                                        </div>
                                    </div>

                                    <div class="form-group  row wx_coupon tutti_hidden_obj" >
                                        <label class="col-sm-3 col-form-label">商家服务类型</label>
                                        <div class="col-sm-9 col-form-label">
                                            <volist name="coupon.business_service" id="vo">
                                                <if condition="$vo eq 'BIZ_SERVICE_DELIVER'">
                                                    外卖服务&nbsp;&nbsp;
                                                    <elseif condition="$vo eq 'BIZ_SERVICE_FREE_PARK'" />
                                                    停车位&nbsp;&nbsp;
                                                    <elseif condition="$vo eq 'BIZ_SERVICE_WITH_PET'" />
                                                    可带宠物&nbsp;&nbsp;
                                                    <elseif condition="$vo eq 'BIZ_SERVICE_FREE_WIFI'" />
                                                    免费wifi&nbsp;&nbsp;
                                                </if>
                                            </volist>
                                        </div>
                                    </div>

                                    <div class="form-group  row wx_coupon tutti_hidden_obj" >
                                        <div class="col-sm-12">
                                            <table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
                                                <volist name="coupon.text_image_list" id="vo">
                                                    <tr class="plus textIamge" >
                                                        <td width="60" style="color:red">卡券图文<label>{pigcms{$i}</label></td>
                                                        <td>
                                                            <table style="width:100%;border:#d5dfe8 1px solid;padding:2px;">
                                                                <tr class="textIamge">
                                                                    <td width="36" style="color:red">图片：</td>
                                                                    <td><img class="mini_img" src="{pigcms{$vo.image_url}"></td>
                                                                    <td width="36" style="color:red">描述：</td>
                                                                    <td>
                                                                        {pigcms{$vo.text}
                                                                    </td>
                                                                    <td rowspan="2" class="delete">

                                                                    </td>
                                                                <tr/>

                                                            </table>
                                                        </td>
                                                    </tr>
                                                </volist>
                                                <tr class="textIamge">

                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="form-group  row ">
                                        <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_ONLY_NEW_USER_')}</label>
                                        <div class="col-sm-9 col-form-label">
                                            <if condition="$coupon['allow_new'] eq 1">Yes<elseif condition="$coupon['allow_new'] eq 0"/>No</if>
                                        </div>
                                    </div>

                                    <div class="form-group  row ">
                                        <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_USE_PLAT_')}</label>
                                        <div class="col-sm-9 col-form-label">
                                            {pigcms{$coupon.platform}
                                        </div>
                                    </div>

                                    <div class="form-group  row">
                                        <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_QUANTITY_')}</label>
                                        <div class="col-sm-9 col-form-label">
                                            {pigcms{$coupon.now_num}
                                        </div>
                                    </div>

                                    <div class="form-group  row">
                                        <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_DIS_PRICE_')}</label>
                                        <div class="col-sm-9 col-form-label">
                                             {pigcms{$coupon.discount}
                                        </div>
                                    </div>

                                    <div class="form-group  row ">
                                        <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_MIN_PRICE_')}</label>
                                        <div class="col-sm-9 col-form-label">
                                            {pigcms{$coupon.order_money}
                                        </div>
                                    </div>
                                    <div class="form-group  row ">
                                        <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_PERIOD_')}</label>
                                        <div class="col-sm-9 col-form-label">
                                            {pigcms{$coupon.start_time|date='Y-m-d',###}——{pigcms{$coupon.end_time|date='Y-m-d',###}
                                        </div>
                                    </div>

                                <if condition="$system_session['level'] neq 3">
                                    <div class="form-group  row ">
                                        <label class="col-sm-3 col-form-label">{pigcms{:L('G_UNIVERSAL')}</label>
                                        <div class="col-sm-9 col-form-label">
                                            <if condition="$coupon['city_id'] eq 0">{pigcms{:L('G_UNIVERSAL')}<else />{pigcms{:L('G_CITY_SPECIFIC')}</if>
                                        </div>
                                    </div>

                                    <div id="adver_region" class="form-group  row " <if condition="$coupon['city_id'] eq 0">style="display:none;"</if>>
                                        <label class="col-sm-3 col-form-label">{pigcms{:L('_B_PURE_MY_13_')}</label>
                                        <div class="col-sm-9 col-form-label" id="choose_cityareass" province_idss="" city_idss="{pigcms{$coupon.city_id}">
                                        </div>
                                    </div>
                                <else />
                                    <div class="form-group  row ">
                                        <label class="col-sm-3 col-form-label">{pigcms{:L('_B_PURE_MY_13_')}</label>
                                        <div class="col-sm-9 col-form-label">
                                            {pigcms{$city['area_name']}
                                            <input type="hidden" name="city_id" value="{pigcms{$city['area_id']}">
                                        </div>
                                    </div>
                                </if>

                                <if condition="($coupon.status eq 0) OR ($coupon.status eq 1)">
                                    <div class="form-group  row ">
                                        <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_STATUS_')}</label>
                                        <div class="col-sm-9 col-form-label">
                                            <if condition="$coupon['status'] eq 1">{pigcms{:L('_BACK_ACTIVE_')}<else/>{pigcms{:L('_BACK_FORBID_')}</if>
                                        </div>
                                    </div>
                                </if>

                                    <div class="form-group  row ">
                                        <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_PICK_KEY_')}</label>
                                        <div class="col-sm-9 col-form-label">
                                            {pigcms{$coupon['notice']}
                                        </div>
                                    </div>
                                    <div class="btn tutti_hidden_obj">
                                        <input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button"/>
                                        <input type="reset" value="取消" class="button"/>
                                    </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
            <script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>

            <link rel="stylesheet" href="{pigcms{$static_public}kindeditor/themes/default/default.css">
            <script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
            <script src="{pigcms{$static_public}kindeditor/lang/zh_CN.js"></script>
            <script type="text/javascript">
                KindEditor.ready(function (K) {
                    var site_url = "{pigcms{$config.site_url}";
                    var editor = K.editor({
                        allowFileManager: true
                    });
                    $('.J_selectImage').click(function () {
                        var upload_file_btn = $(this);
                        editor.uploadJson = "{pigcms{:U('Config/ajax_upload_pic')}";
                        editor.loadPlugin('image', function () {
                            editor.plugin.imageDialog({
                                showRemote: false,
                                clickFn: function (url, title, width, height, border, align) {
                                    upload_file_btn.siblings('.input-image').val(site_url + url);
                                    editor.hideDialog();
                                }
                            });
                        });
                    });

                });

                $(document).ready(function () {

                    $('select[name="color"]').css('background-color', '#63b359');
                    $('select[name="color"]').change(function (event) {
                        $('#wx_color').css('background-color', $('select[name="color"]').find('option:selected').html());
                        $(this).css('background-color', $('select[name="color"]').find('option:selected').html());
                    });

                    $('input:radio[name="sync_wx"]').click(function (i, val) {
                        if ($(this).val() == 1) {
                            $('.wx_coupon').show();
                        } else {
                            $('.wx_coupon').hide();
                        }
                    });

                });

                function plus() {
                    var item = $('.plus:last');
                    var newitem = $(item).clone(true);
                    var No = parseInt(item.find("label").html()) + 1;
                    $('.delete').children().show();
                    if (No > 4) {
                        alert('不能超过4条信息');
                    } else {
                        $(item).after(newitem);
                        newitem.find('input').attr('value', '');
                        newitem.find('textarea').attr('value', '');
                        newitem.find("#addLink").attr('onclick', "addLink('url" + No + "',0)");
                        newitem.find("label").html(No);
                        newitem.find('input[name="url[]"]').attr('id', 'url' + No);
                        newitem.find('.delete').children().show();
                    }
                }

                function del(obj) {
                    if ($('.plus').length <= 1) {
                        $('.delete').children().hide();
                    } else {
                        if ($('.plus').length == 2) {
                            $('.delete').children().hide();
                        }
                        $(obj).parents('.plus').remove();
                        $.each($('.plus'), function (index, val) {
                            var No = index + 1;
                            $(val).find('label').html(No);
                            $(val).find('input[name="url[]"]').attr('id', 'url' + No);
                            $(val).find("#addLink").attr('onclick', "addLink('url" + No + "',0)");
                        });
                    }
                }

                $("#yes").click(function () {
                    $("#adver_region").hide();
                })
                $("#no").click(function () {
                    $("#adver_region").show();
                })
            </script>
            <include file="Public:footer_inc"/>
