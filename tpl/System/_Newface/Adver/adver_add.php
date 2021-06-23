<include file="Public:header"/>
<body style="background-color: #fff;">
<div id="wrapper-singlepage">

    <div id="page-wrapper-singlepage" class="white-bg">

        <!----------------------------------------    以上不要写代码     ------------------------------------------------>
        <div class="row wrapper wrapper-content animated fadeInRight">

            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-content">
                        <form id="myform" method="post" action="{pigcms{:U('Adver/adver_modify')}" enctype="multipart/form-data">
                            <input type="hidden" name="cat_id" value="{pigcms{$now_category.cat_id}"/>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('I_AD_NAME')}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="name" size="20" placeholder="" validate="maxlength:20,required:true"/>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('I_AD_SUBTITLE')}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="sub_name" size="20" placeholder="" validate="required:true"/>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('I_UNIVERSAL_AD')}</label>
                                <div class="col-sm-9">
                                    <span class="cb-enable"><label class="cb-enable selected"><span>{pigcms{:L('G_UNIVERSAL')}</span><input id="yes" type="radio" name="currency" value="1" checked="checked" /></label></span>
                                    <span class="cb-disable"><label class="cb-disable"><span>{pigcms{:L('G_CITY_SPECIFIC')}</span><input id="no" type="radio" name="currency" value="2" /></label></span>
                                </div>
                            </div>
                            <div class="form-group  row" id="adver_region" style="display:none;">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('E_CITY_OF_LOCATION')}</label>
                                <div class="col-sm-9" id="choose_cityareass" province_idss="" city_idss="">
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('I_UPLOAD_IMAGE')}</label>
                                <div class="col-sm-9">
                                    <input type="file" class="form-control" name="pic" style="width:200px;" placeholder="" validate="required:true"/>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('I_BACKGROUND_COLOR')}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="bg_color" id="choose_color" style="width:120px;" placeholder="" tips=""/><a href="javascript:void(0);" id="choose_color_box" style="line-height:28px;">{pigcms{:L('I_CHOOSE_COLOR')}</a>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('I_URL')}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="url" id="url" style="width:200px;" placeholder="" validate="maxlength:200,required:true,url:true"/>
                                    <if condition="!C('butt_open')">
                                        <if condition="$now_category['cat_type'] neq 1">
                                            <a href="#modal-table" class="btn btn-sm btn-success" onclick="addLink('url', 0, 0)" data-toggle="modal"> </a>
                                            <else />
                                            <a href="#modal-table" class="btn btn-sm btn-success" onclick="addLink('url', 0, 1)" data-toggle="modal"> </a>
                                        </if>
                                    </if>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('I_LISTING_ORDER2')}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="sort" style="width:80px;" value="0" validate="maxlength:10,required:true,number:true" />
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('I_STATUS')}</label>
                                <div class="col-sm-9">
                                    <span class="cb-enable"><label class="cb-enable selected"><span>{pigcms{:L('C_CATEGORYSEN')}</span><input type="radio" name="status" value="1" checked="checked" /></label></span>
                                    <span class="cb-disable"><label class="cb-disable"><span>{pigcms{:L('C_CATEGORYDIS')}</span><input type="radio" name="status" value="0" /></label></span>
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
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script>
function addLink(domid, iskeyword, type){
	art.dialog.data('domid', domid);
	if (type == 1) {
		art.dialog.open('?g=Admin&c=LinkPC&a=insert&iskeyword='+iskeyword,{lock:true,title:'插入链接或关键词',width:800,height:500,yesText:'关闭',background: '#000',opacity: 0.45});
	} else {
		art.dialog.open('?g=Admin&c=Link&a=insert&iskeyword='+iskeyword,{lock:true,title:'插入链接或关键词',width:800,height:500,yesText:'关闭',background: '#000',opacity: 0.45});
	}
}
$("#yes").click(function(){
	$("#adver_region").hide();
})
$("#no").click(function(){
	$("#adver_region").show();
})
</script>
<include file="Public:footer_inc"/>