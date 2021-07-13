<include file="Public:header"/>
<body style="background-color: #fff;">
<div id="wrapper-singlepage">

    <div id="page-wrapper-singlepage" class="white-bg">

        <!----------------------------------------    以上不要写代码     ------------------------------------------------>
        <div class="row wrapper wrapper-content animated fadeInRight">

            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-content">
                        <form id="myform" method="post" action="{pigcms{:U('Index/saveAdmin')}" frame="true" refresh="true" <if condition="$_GET['id'] eq ''">data-call_fun="true"</if>>
                            <input type="hidden" name="id" value="{pigcms{$_GET['id']}"/>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('B_USERNAME')}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="account" id="account" size="20" placeholder="" validate="maxlength:30,required:true" value="{pigcms{$admin['account']}"/>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('B_INPUTPASS')}</label>
                                <div class="col-sm-9">
                                    <input type="password" class="form-control" name="pwd" id="pwd" size="20" placeholder=""  tips="{pigcms{:L('B_PASSDES')}"/>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('B_FULLNAME')}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="realname" id="realname" size="20" placeholder="" tips="{pigcms{:L('B_NAMEDESC')}" validate="maxlength:30,required:true" value="{pigcms{$admin['realname']}"/>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('B_USERPHONE')}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="phone" size="20" placeholder=""  value="{pigcms{$admin['phone']}"/>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('B_USERPEMAIL')}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="email" size="20" value="{pigcms{$admin['email']}"/>
                                </div>
                            </div>
                            <if condition="$admin['level'] eq 3">
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('BASE_CITY')}</label>
                                <div class="col-sm-9">
                                    <select name="area_id" class="form-control m-b">
                                        <option value="0" <if condition="$admin['area_id'] eq 0">selected="selected"</if>>None</option>
                                        <volist name="city" id="vo">
                                            <option value="{pigcms{$vo.area_id}" <if condition="$admin['area_id'] eq $vo['area_id']">selected="selected"</if>>{pigcms{$vo.area_name}</option>
                                        </volist>
                                    </select>
                                    <select name="level" class="form-control m-b">
                                        <option value="0">{pigcms{:L('B_NADMIN')}</option>
                                        <option value="3" selected="selected">{pigcms{:L('B_CADMIN')}</option>
                                    </select>
                                </div>
                            </div>
                            <else />
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('BASE_TYPE')}</label>
                                <div class="col-sm-9">
                                    <if condition="$admin['level'] eq 2">
                                        Super Admin
                                        <else />
                                        <select name="level" class="form-control m-b">
                                            <option value="0" selected="selected">{pigcms{:L('B_NADMIN')}</option>
                                            <option value="3">{pigcms{:L('B_CADMIN')}</option>
                                        </select>
                                    </if>
                                </div>
                            </div>
                            </if>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('B_USERSTAT')}</label>
                                <div class="col-sm-9">
                                    <div class="switch">
                                        <div class="onoffswitch">
                                            <input name="status" type="checkbox" class="onoffswitch-checkbox" id="status_input" <if condition="$admin['status'] eq 1">checked="checked"</if>>
                                            <label class="onoffswitch-label" for="status_input">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="btn tutti_hidden_obj">
                                <input type="submit" name="dosubmit" id="dosubmit" value="{pigcms{:L('BASE_SUBMIT')}" class="button" />
                                <input type="reset" value="{pigcms{:L('BASE_CANCEL')}" class="button" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
	<script type="text/javascript">
		get_first_word('area_name','area_url','first_pinyin');
	</script>
<include file="Public:footer_inc"/>