<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-gear gear-icon"></i>
                <a href="{pigcms{:U('Config/store')}">{pigcms{:L('STORE_MANAGEMENT_BKADMIN')}</a>
            </li>
            <li class="active">{pigcms{:L('STORE_MANAGEMENT_BKADMIN')}</li>
        </ul>
    </div>
    <!-- 内容头部 -->
    <div class="page-content">
        <div class="page-content-area">
            <style>
                .ace-file-input a {display:none;}
            </style>
            <div class="row">
                <div class="col-xs-12">
                    <button class="btn btn-success" onclick="CreateShop()">{pigcms{:L('CREATE_STORE_BKADMIN')}</button>
                    <div id="shopList" class="grid-view">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>{pigcms{:L('ID_BKADMIN')}</th>
                                <th>{pigcms{:L('LISTING_ORDER_BKADMIN')}</th>
                                <th>{pigcms{:L('STORE_NAME_BKADMIN')}</th>
                                <th>{pigcms{:L('STORE_BASIC_INFO_BKADMIN')}</th>
                                <th>{pigcms{:L('STORE_STATUS_BKADMIN')}</th>
                                <th>{pigcms{:L('USER_MANAGEMENT_BKADMIN')}</th>
                                <th class="button-column" style="width:100px;">{pigcms{:L('ACTION_BKADMIN')}</th>
                            </tr>
                            </thead>

                            <tbody>
                            <if condition="$store_list">
                                <volist name="store_list" id="vo">
                                    <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                        <td>{pigcms{$vo.store_id}</td>
                                        <td>{pigcms{$vo.sort}</td>
                                        <td>{pigcms{$vo.name}</td>
                                        <td><a href="javascript:;" class="store_info_more" data-store_name="{pigcms{$vo.name}" data-phone="{pigcms{$vo.phone}" data-address="{pigcms{$vo.area_name} - {pigcms{$vo.adress}" data-hasmeal="<if condition="$vo['have_meal']">有<else/>无</if>" data-hasgroup="<if condition="$vo['have_group']">有<else/>无</if>" data-hasshop="<if condition="$vo['have_shop']">有<else/>无</if>" data-store_qrcode_url="{pigcms{$config.site_url}/index.php?g=Index&c=Recognition&a=see_qrcode&type=merchantstore&id={pigcms{$vo['store_id']}&img=1" data-store_status="{pigcms{$vo['status']}">{pigcms{:L('VIEW_BKADMIN')}</a></td>

                                        <td>
                                            <switch name="vo['status']">
                                                <case value="0">{pigcms{:L('_BACK_OFF_')}</case>
                                                <case value="1">{pigcms{:L('ACTIVE_BKADMIN')}</case>
                                                <case value="2">{pigcms{:L('_BACK_PENDING_')}</case>
                                            </switch>
                                        </td>

                            <if condition="$vo['status'] neq 2">

                                <td>
                                    <a class="label label-sm label-info" title="{pigcms{:L('USER_MANAGEMENT_BKADMIN')}" href="{pigcms{:U('Config/staff',array('store_id'=>$vo['store_id']))}">{pigcms{:L('USER_MANAGEMENT_BKADMIN')}</a>
                                </td>

                                <else/>
                                												<td>--</td>

                            </if>


                            <td class="button-column" nowrap="nowrap">
                                <a title="{pigcms{:L('EDIT_BKADMIN')}" class="green" style="padding-right:8px;" href="{pigcms{:U('Config/store_edit',array('id'=>$vo['store_id']))}">
                                    <i class="ace-icon fa fa-pencil bigger-130"></i>
                                </a>
                                <a title="{pigcms{:L('DELETE_BKADMIN')}" class="red" style="padding-right:8px;" href="{pigcms{:U('Config/store_del',array('id'=>$vo['store_id']))}">
                                    <i class="ace-icon fa fa-trash-o bigger-130"></i>
                                </a>
                            </td>
                            </tr>
                            </volist>
                            <else/>
                            <tr class="odd"><td class="button-column" colspan="11" >{pigcms{:L('NO_CONTENT_BKADMIN')}</td></tr>
                            </if>
                            </tbody>
                        </table>
                        {pigcms{$pagebar}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        /*店铺状态*/
        updateStatus(".statusSwitch .ace-switch", ".statusSwitch", "OPEN", "CLOSED", "shopstatus");

        jQuery(document).on('click','#shopList a.red',function(){
            if(!confirm('确定要删除这条数据吗?不可恢复。')) return false;
        });
    });
    function CreateShop(){
        window.location.href = "{pigcms{:U('Config/store_add')}";
    }
    function updateStatus(dom1, dom2, status1, status2, attribute){
        $(dom1).each(function(){
            if($(this).attr("data-status")==status1){
                $(this).attr("checked",true);
            }else{
                $(this).attr("checked",false);
            }
            $(dom2).show();
        }).click(function(){
            var _this = $(this),
                type = 'open',
                id = $(this).attr("data-id");
            _this.attr("disabled",true);
            if(_this.attr("checked")){	//开启
                type = 'open';
            }else{		//关闭
                type = 'close';
            }
            $.ajax({
                url:"{pigcms{:U('Config/store_status')}",
                type:"post",
                data:{"type":type,"id":id,"status1":status1,"status2":status2,"attribute":attribute},
                dataType:"text",
                success:function(d){
                    if(!d){		//失败
                        if(type=='open'){
                            _this.attr("checked",false);
                        }else{
                            _this.attr("checked",true);
                        }
                        bootbox.alert("操作失败");
                    }
                    _this.attr("disabled",false);
                }
            });
        });
    }
</script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script type="text/javascript">
    $(function(){
        $('.see_qrcode').live('click',function(){
            art.dialog.open($(this).attr('href'),{
                init: function(){
                    var iframe = this.iframe.contentWindow;
                    window.top.art.dialog.data('iframe_handle',iframe);
                },
                id: 'handle',
                title:'查看渠道二维码',
                padding: 0,
                width: 430,
                height: 433,
                lock: true,
                resize: false,
                background:'black',
                button: null,
                fixed: false,
                close: null,
                left: '50%',
                top: '38.2%',
                opacity:'0.4'
            });
            return false;
        });
        $('.see_qrcode_wxapp').live('click',function(){
            art.dialog.open($(this).attr('href'),{
                init: function(){
                    var iframe = this.iframe.contentWindow;
                    window.top.art.dialog.data('iframe_handle',iframe);
                },
                id: 'handle',
                title:'查看小程序二维码',
                padding: 0,
                width: 380,
                height: 430,
                lock: true,
                resize: false,
                background:'black',
                button: null,
                fixed: false,
                close: null,
                left: '50%',
                top: '38.2%',
                opacity:'0.4'
            });
            return false;
        });

        $('.store_info_more').click(function(){
            var content = '<p>{pigcms{:L(\'STORE_NAME_BKADMIN\')}：'+$(this).data('store_name')+'</p>';
            content+= '<p>{pigcms{:L(\'STORE_PHONE_NUMBER_BKADMIN\')}：'+$(this).data('phone')+'</p>';
            content+= '<p>{pigcms{:L(\'STORE_ADDRESS_BKADMIN\')}：'+$(this).data('address')+'</p>';
            // content+= '<p>{pigcms{:L(\'DELETE_BKADMIN\')}餐饮：'+$(this).data('hasmeal')+'</p>';
            // content+= '<p>{pigcms{:L(\'DELETE_BKADMIN\')}团购：'+$(this).data('hasgroup')+'</p>';
            // content+= '<p>{pigcms{:L(\'DELETE_BKADMIN\')}快店：'+$(this).data('hasshop')+'</p>';
            // if($(this).data('store_status') != '2'){
            //     content+= '<p>店铺二维码：<a href="'+$(this).data('store_qrcode_url')+'" class="see_qrcode">查看二维码</a></p>';
            // }
            art.dialog({
                title: '{pigcms{:L(\'STORE_BASIC_INFO_BKADMIN\')}',
                content: content,
                lock: true
            });
        });


    });
</script>
<include file="Public:footer"/>
