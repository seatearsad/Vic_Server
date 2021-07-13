<include file="Public:header"/>
<div id="wrapper">
    <include file="Public:left_menu"/>
    <!----------------------------------------    以上不要写代码     ------------------------------------------------>

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>{pigcms{:L('B_PINF')}</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    {pigcms{:L('B_INFO')}
                </li>
                <!--                <li class="breadcrumb-item">-->
                <!--                    <a>UI Elements</a>-->
                <!--                </li>-->
                <li class="breadcrumb-item active">
                    <strong>{pigcms{:L('B_PINF')}</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2">

        </div>
    </div>
    <div class="row wrapper wrapper-content animated fadeInRight">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-content">
                    <form method="post" id="myform" action="{pigcms{:U('amend_profile')}" refresh="true" onclick='javascript:return submitcheck();'>
                        <input type="hidden" class="input-text" id="system_menu" name="system_menu" value=""/>
                        <div class="form-group  row">
                            <label class="col-sm-2 col-form-label">{pigcms{:L('B_USERNAME')}</label>
                            <div class="col-sm-10">
                                {pigcms{$admin.account}
                            </div>
                        </div>
                        <div class="form-group  row">
                            <label class="col-sm-2 col-form-label">{pigcms{:L('B_FULLNAME')}</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control"  name="realname" value="{pigcms{$admin.realname}" validate="required:true" />
                            </div>
                        </div>
                        <div class="form-group  row">
                            <label class="col-sm-2 col-form-label">{pigcms{:L('B_EMAIL')}</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control"  name="email" value="{pigcms{$admin.email}" validate="required:true,email:true,minlength:1,maxlength:40" />
                            </div>
                        </div>
                        <input type="hidden" class="input-text"  name="qq" value="{pigcms{$admin.qq|default='123'}" />
                        <div class="form-group  row">
                            <label class="col-sm-2 col-form-label">{pigcms{:L('B_PHONE')}</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control"  name="phone" value="{pigcms{$admin.phone}"  validate="required:true,minlength:10,maxlength:10" />
                            </div>
                        </div>
                        <div class="form-group  row">
                            <label class="col-sm-2 col-form-label">{pigcms{:L('B_MENUORDER')}</label>
                            <div class="col-sm-10">
                                {pigcms{:L('B_MODES')}<br/>
                                <volist name="system_menu" id="vv" key="k">
                                    <div style="margin-top:10px;width:30%;float:left;">{pigcms{$vv['name']} <input type="text" min="0" class="form-control input-text input1" name="{pigcms{$vv.id}" value="{pigcms{$sort_menus_son[$vv['id']]}" /></div>
                                    <if condition="$k%3 eq 0"><br /></if>
                                </volist>
                            </div>
                        </div>
                        <div class="btn">
                            <input TYPE="submit" id="submit" name="dosubmit" value="{pigcms{:L('BASE_SUBMIT')}" class="btn btn-w-m btn-primary" />
                            <input type="reset" value="{pigcms{:L('BASE_CANCEL')}" class="btn btn-w-m btn-primary" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
	</div>
	<script type="text/javascript">
		function submitcheck(){
			var system_menu	=	'';
			$(".input1").each(function(){
				if(this.value){
					system_menu	+=	this.name+','+this.value+';';
				}
		    });
		    system_menu=system_menu.substring(0,system_menu.length-1);
			$("#system_menu").val(system_menu);
		}
	</script>
<include file="Public:footer"/>