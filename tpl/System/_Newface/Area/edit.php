<include file="Public:header"/>
<body style="background-color: #fff;">
<div id="wrapper-singlepage">

    <div id="page-wrapper-singlepage" class="white-bg">

        <!----------------------------------------    以上不要写代码     ------------------------------------------------>
        <div class="row wrapper wrapper-content animated fadeInRight">

            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-content">
                        <form id="myform" method="post" action="{pigcms{:U('Area/amend')}" frame="true" refresh="true">
                            <input type="hidden" name="area_id" value="{pigcms{$now_area['area_id']}"/>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('G_NAME')}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="area_name" value="{pigcms{$now_area.area_name}" size="20" placeholder="" validate="maxlength:30,required:true"/>
                                </div>
                            </div>
                            <if condition="$_GET['type'] eq 2 || $_GET['type'] eq 4">
                                <div class="form-group  row">
                                    <label class="col-sm-3 col-form-label">{pigcms{:L('I_INITIAL_LETTER')}</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="first_pinyin" value="{pigcms{$now_area.first_pinyin}" size="20" placeholder="" validate="maxlength:20,required:true" tips="{pigcms{:L('FLLC')}"/>
                                    </div>
                                </div>
                            </if>
                            <if condition="$_GET['type'] gt 1">
                                <div class="form-group  row">
                                    <label class="col-sm-3 col-form-label">{pigcms{:L('I_NETWOEK_SYMBOL')}</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="area_url" value="{pigcms{$now_area.area_url}" size="20" placeholder="" validate="maxlength:20,required:true" tips="{pigcms{:L('GITLIOT')}"/>
                                    </div>
                                </div>
                            </if>
                            <if condition="$_GET['type'] gt 1 && $_GET['type'] lt 4">
                                <div class="form-group  row">
                                    <label class="col-sm-3 col-form-label">{pigcms{:L('I_IP_SYMPOL')}</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="area_ip_desc" value="{pigcms{$now_area.area_ip_desc}" size="20" placeholder="" validate="maxlength:30,required:true" tips="{pigcms{:L('TGFIXC')}"/>
                                    </div>
                                </div>
                            </if>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('I_LISTING_ORDER')}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="area_sort" value="{pigcms{$now_area.area_sort}" size="10" validate="required:true,number:true,maxlength:6" tips="{pigcms{:L('HIGHVAL')}"/>
                                </div>
                            </div>
                            <if condition="$_GET['type'] gt 1">
                                <div class="form-group  row">
                                    <label class="col-sm-3 col-form-label">{pigcms{:L('I_POPULARITY')}</label>
                                    <div class="col-sm-9">
                                        <span class="cb-enable">
                                            <if condition="$now_area['is_hot'] eq 1">
                                                <label class="cb-enable selected">
                                                <else/>
                                                <label class="cb-enable">
                                            </if>
                                            <span>Yes</span><input type="radio" name="is_hot" value="1" <if condition="$now_area['is_hot'] eq 1">checked="checked"</if> /></div></span>
                                            <span class="cb-disable">
                                                <if condition="$now_area['is_hot'] eq 0">
                                                <label class="cb-disable selected">
                                                <else/>
                                                <label class="cb-disable">
                                                </if>
                                                <span>No</span><input type="radio" name="is_hot" value="0" <if condition="$now_area['is_hot'] eq 0">checked="checked"</if>/></label></span>
                                </div>
                    </div>
                    </if>
                    <div class="form-group  row">
                        <label class="col-sm-3 col-form-label">{pigcms{:L('G_STATUS')}</label>
                        <div class="col-sm-9">
                            <span class="cb-enable">
                                <if condition="$now_area['is_open'] eq 1">
                                    <label class="cb-enable selected">
                                    <else/>
                                    <label class="cb-enable">
                                </if>
                                <span>{pigcms{:L('I_ACTIVE')}</span><input type="radio" name="is_open" value="1" <if condition="$now_area['is_open'] eq 1">checked="checked"</if> /></label></span>
                            <span class="cb-disable">
                                <if condition="$now_area['is_open'] eq 0">
                                    <label class="cb-disable selected">
                                    <else/>
                                    <label class="cb-disable">
                                </if>
                                <span>{pigcms{:L('_BACK_FORBID_')}</span><input type="radio" name="is_open" value="0" <if condition="$now_area['is_open'] eq 0">checked="checked"</if>/></label></span>
                        </div>
                    </div>
                    <div class="btn tutti_hidden_obj">
                        <input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
                        <input type="reset" value="取消" class="button" />
                    </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<script type="text/javascript">
		get_first_word('area_name','area_url','first_pinyin');
	</script>
<include file="Public:footer_inc"/>