<include file="Public:header"/>
<body style="background-color: #fff;">
<div id="wrapper-singlepage">

    <div id="page-wrapper-singlepage" class="white-bg">

        <!----------------------------------------    以上不要写代码     ------------------------------------------------>
        <div class="row wrapper wrapper-content animated fadeInRight">

            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-content">
                        <form id="myform" method="post" action="{pigcms{:U('Event/modify')}" enctype="multipart/form-data">
                            <input name="event_id" value="{pigcms{$event.id|default='0'}" type="hidden">
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('C_CATEGORYNAME')}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="name" size="20" validate="maxlength:200,required:true" value="{pigcms{$event.name|default=''}"/>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('G_DESCRIPTION')}</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="desc" validate="required:true">{pigcms{$event.desc|default=''}</textarea>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('G_TYPE')}</label>
                                <div class="col-sm-9">
                                    <select name="type" id="select_type" class="form-control">
                                        <volist name="type" id="vo">
                                            <if condition="$i gt 1">
                                                <option value="{pigcms{$i-1}" <if condition="$event.type eq ($i-1)">selected</if>>{pigcms{$vo}</option>
                                            </if>
                                        </volist>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('G_START_TIME')}</label>
                                <div class="col-sm-8 input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                    <input type="text" class="form-control" name="begin_time" style="width:120px;" id="d4311" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd',maxDate:'#F{$dp.$D(\'d4312\')}'})" <if condition="$event['begin_time'] neq 0">value="{pigcms{$event.begin_time|date='Y-m-d',###}"</if>/>
                                    <span id="clear_begin" class="input-group-addon bootstrap-touchspin-postfix input-group-append"><span class="input-group-text">{pigcms{:L('G_CLEAR')}</span></span>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('G_END_TIME')}</label>
                                <div class="col-sm-8 input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                    <input type="text" class="form-control" name="end_time" style="width:120px;" id="d4312" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd',minDate:'#F{$dp.$D(\'d4311\')}'})" <if condition="$event['end_time'] neq 0">value="{pigcms{$event.end_time|date='Y-m-d',###}"</if>/>
                                    <span id="clear_end" class="input-group-addon bootstrap-touchspin-postfix input-group-append"><span class="input-group-text">{pigcms{:L('G_CLEAR')}</span></span>
                                </div>
                            </div>
                            <div class="form-group  row" id="city_tr">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('G_CITY')}</label>
                                <div class="col-sm-9">
                                    <select name="city_id" class="form-control">
                                        <option value="0" <if condition="$event and $event['city_id'] eq 0">selected="selected"</if>>{pigcms{:L('G_UNIVERSAL')}</option>
                                        <volist name="city" id="vo">
                                            <option value="{pigcms{$vo.area_id}" <if condition="$event and $event['city_id'] eq $vo['area_id']">selected="selected"</if>>{pigcms{$vo.area_name}</option>
                                        </volist>
                                    </select>
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
<style>
    #clear_begin,#clear_end{
        line-height: 30px;
        cursor: pointer;
        color: #0e62cd;
        padding-left: 10px;
    }
</style>

<script>
    $(function(){
        $('#clear_begin').click(function () {
            $('input[name=begin_time]').val('');
        });
        $('#clear_end').click(function () {
            $('input[name=end_time]').val('');
        });

        $('#select_type').change(function () {

            var type = $(this).val();

            if(type == 3){
                $('#city_tr').show();
            }else{
                $('#city_tr').hide();
            }
        });
    });

</script>
<include file="Public:footer_inc"/>