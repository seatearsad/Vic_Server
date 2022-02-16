<include file="Public:header"/>
<body style="background-color: #fff;">
<div id="wrapper-singlepage">

    <div id="page-wrapper-singlepage" class="white-bg">

        <!----------------------------------------    以上不要写代码     ------------------------------------------------>
        <div class="row wrapper wrapper-content animated fadeInRight">

            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-content">
                        <form id="myform" method="post" action="{pigcms{:U('Area/modify')}" frame="true" refresh="true">
                            <input type="hidden" name="area_type" value="{pigcms{$_GET['type']}"/>
                            <input type="hidden" name="area_pid" value="{pigcms{$_GET['pid']}"/>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('G_NAME')}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="area_name" id="area_name" size="20" placeholder="" validate="maxlength:30,required:true"/>
                                </div>
                            </div>
                            <if condition="$_GET['type'] eq 2 || $_GET['type'] eq 4">
                                <div class="form-group  row">
                                    <label class="col-sm-3 col-form-label">{pigcms{:L('I_INITIAL_LETTER')}</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="input fl" name="first_pinyin" id="first_pinyin" size="20" placeholder="" validate="maxlength:20,required:true" tips="{pigcms{:L('FLLC')}"/>
                                    </div>
                                </div>
                            </if>
                            <if condition="$_GET['type'] gt 1">
                                <div class="form-group  row">
                                    <label class="col-sm-3 col-form-label">{pigcms{:L('I_NETWOEK_SYMBOL')}</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="input fl" name="area_url" id="area_url" size="20" placeholder="" validate="maxlength:20,required:true" tips="{pigcms{:L('GITLIOT')}"/>
                                    </div>
                                </div>
                            </if>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('I_IP_SYMPOL')}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="input fl" name="area_ip_desc" size="20" placeholder="" validate="maxlength:30,required:true" tips="{pigcms{:L('TGFIXC')}"/>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('I_LISTING_ORDER')}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="input fl" name="area_sort" size="10" value="0" validate="required:true,number:true,maxlength:6" tips="{pigcms{:L('HIGHVAL')}"/>
                                </div>
                            </div>
                            <if condition="$_GET['type'] gt 1">
                                <div class="form-group  row">
                                    <label class="col-sm-3 col-form-label">{pigcms{:L('I_POPULARITY')}</label>
                                    <div class="col-sm-9">
                                        <span class="cb-enable"><label class="cb-enable"><span>Yes</span><input type="radio" name="is_hot" value="1" /></label></span>
                                        <span class="cb-disable"><label class="cb-disable selected"><span>No</span><input type="radio" name="is_hot" value="0" checked="checked"/></label></span>
                                    </div>
                                </div>
                            </if>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('G_STATUS')}</label>
                                <div class="col-sm-9">
                                    <span class="cb-enable"><label class="cb-enable selected"><span>{pigcms{:L('I_ACTIVE')}</span><input type="radio" name="is_open" value="1" checked="checked" /></label></span>
                                    <span class="cb-disable"><label class="cb-disable"><span>{pigcms{:L('_BACK_FORBID_')}</span><input type="radio" name="is_open" value="0" /></label></span>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_CITY_DELI_BAG_')}</label>
                                <div class="col-sm-9">
                                    <div>
                                        <input type="checkbox"  name="is_pick_up" value="1" <if condition="$now_area['bag_type'] eq 1">checked="checked"</if> />
                                        &nbsp;{pigcms{:L('_BACK_CITY_DELI_PICKUP_')}
                                    </div>
                                    <div style="margin-left: 20px;margin-top: 5px;">Address Name:<input name="bag_address_name"  class="form-control" type="text"/> </div>
                                    <div style="margin-left: 20px;margin-top: 5px;">Address :<input name="bag_address"  class="form-control" type="text"/> </div>
                                    <div style="margin-left: 20px;margin-top: 5px;"><input  type="checkbox" name="is_show_url" value="1"> URL: <input  name="bag_address_url"  type="text" class="form-control"/></div>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label"></label>
                                <div class="col-sm-9">
                                    <div><input type="checkbox"  name="is_shipping" value="1" <if condition="$now_area['bag_type'] eq 2">checked="checked"</if> />&nbsp;{pigcms{:L('_BACK_CITY_DELI_SHIPPING_')}</div>
                                    <div style="margin-left: 20px;margin-top: 5px;">{pigcms{:L('_BACK_CITY_DELI_SHIPPING_FEE_')}:$<input  name="bag_shipping_fee"  class="form-control" type="text"/> </div>
                                </div>
                            </div>
                            <if condition="$_GET['type'] eq 2">
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">范围类型</label>
                                <div class="col-sm-9">
                                    <select name="range_type" id="select_type" class="form-control">
                                        <option value="0" selected>正常城市</option>
                                        <option value="1">纬度限制</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">范围参数</label>
                                <div class="col-sm-9">
                                    <input type="text" class="input fl" name="range_para" size="10" validate="number:true,maxlength:10" />
                                </div>
                            </div>
                                <div class="form-group  row">
                                    <label class="col-sm-3 col-form-label">营业时间</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="input fl area_time" placeholder="00:00:00" value="{pigcms{$now_area['begin_time']}" name="begin_time" validate="" />
                                        - <input type="text" class="input fl area_time" placeholder="00:00:00" value="{pigcms{$now_area['end_time']}" name="end_time" validate="" />
                                    </div>
                                </div>
                            </if>
                            <div class="btn tutti_hidden_obj">
                                <input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
                                <input type="reset" value="取消" class="button" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript" src="{pigcms{$static_path}js/jquery-ui.custom.min.js"></script>
        <script type="text/javascript" src="{pigcms{$static_path}js/jquery-ui.min.js"></script>
        <script type="text/javascript" src="{pigcms{$static_path}js/jquery.ui.touch-punch.min.js"></script>

        <script type="text/javascript" src="{pigcms{$static_public}js/date/WdatePicker.js"></script>
        <script type="text/javascript" src="{pigcms{$static_path}js/jquery-ui-i18n.min.js"></script>
        <script type="text/javascript" src="{pigcms{$static_path}js/jquery-ui-timepicker-addon.min.js"></script>
        <link rel="stylesheet" href="{pigcms{$static_path}css/jquery-ui.css">
        <link rel="stylesheet" href="{pigcms{$static_path}css/jquery-ui-timepicker-addon.css">
	<script type="text/javascript">
        $('.area_time').timepicker($.extend($.datepicker.regional['zh-cn'], {timeFormat: 'hh:mm:ss',showSecond: true}));
		get_first_word('area_name','area_url','first_pinyin');
	</script>
<include file="Public:footer_inc"/>