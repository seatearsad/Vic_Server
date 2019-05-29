<include file="Public:header"/>
<style>
    img{height:30px;width:60px;}
</style>
<div class="mainbox">
    <div id="nav" class="mainnav_title">
        <ul>
            <a href="{pigcms{:U('Coupon/index')}" class="on">{pigcms{:L('_BACK_COUPON_LIST_')}</a>
            <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Coupon/add')}','{pigcms{:L(\'_BACK_ADD_COUPON_\')}',800,500,true,false,false,addbtn,'edit',true);">{pigcms{:L('_BACK_ADD_COUPON_')}</a>
            <a href="{pigcms{:U('Coupon/had_pull')}" >{pigcms{:L('_BACK_PICK_COU_LIST_')}</a>
        </ul>
    </div>
    <table class="search_table" width="100%">
        <tr>
            <td>
                <form action="{pigcms{:U('Coupon/index')}" method="get">
                    <input type="hidden" name="c" value="Coupon"/>
                    <input type="hidden" name="a" value="index"/>
                    {pigcms{:L('_BACK_SEARCH_')}: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
                    <select name="searchtype">
                        <option value="name" <if condition="$_GET['searchtype'] eq 'name'">selected="selected"</if>>{pigcms{:L('_STORE_PRO_NAME_')}</option>
                        <option value="coupon_id" <if condition="$_GET['searchtype'] eq 'coupon_id'">selected="selected"</if>>ID</option>
                    </select>
                    <input type="submit" value="{pigcms{:L('_BACK_SEARCH_')}" class="button"/>
                </form>
            </td>
        </tr>
    </table>
    <form name="myform" id="myform" action="" method="post">
        <div class="table-list">
            <table width="100%" cellspacing="0">
                <colgroup>
                    <col/>
                    <col/>
                    <col/>
                    <col/>
                    <col/>
                    <col/>
                    <col/>
                    <col/>
                    <col/>
                    <col/>
                    <!--col/>
                    <col/>
                    <col/-->
                    <col width="180" align="center"/>
                </colgroup>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>{pigcms{:L('_STORE_PRO_NAME_')}</th>
                    <!--th>{pigcms{:L('_BACK_IMAGE_')}</th-->
                    <th>{pigcms{:L('_BACK_USE_PLAT_')}</th>
                    <!--th>{pigcms{:L('_BACK_USE_TYPE_')}</th>
                    <th>{pigcms{:L('_BACK_USE_CATE_')}</th-->
                    <th>{pigcms{:L('_BACK_COUPON_TOTAL_')}</th>
                    <th>{pigcms{:L('_BACK_HAS_RECE_')}</th>
                    <th>{pigcms{:L('_BACK_PERIOD_')}</th>
                    <th>{pigcms{:L('_PURCHASE_TXT_')}</th>
                    <th class="textcenter">{pigcms{:L('_BACK_ONLY_NEW_USER_')}</th>
                    <!--th>查看二维码</th-->
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
                            <!--td><img src="{pigcms{$vo.img}"></td-->
                            <td><volist name="vo.platform" id="vv">{pigcms{$platform[$vv]}&nbsp;&nbsp;</volist></td>
                            <!--td><if condition="$vo.cate_name eq 'all'">{pigcms{:L('_BACK_ALL_TYPE_')}<else />{pigcms{$category[$vo['cate_name']]}</if></td>
                            <td><if condition="$vo.cate_id eq '0'">{pigcms{:L('_ALL_CLASSIF_')}<else />{pigcms{$vo['cate_id']}</if></td-->
                            <td>{pigcms{$vo.num}</td>
                            <td>{pigcms{$vo.had_pull}</td>
                            <td>{pigcms{$vo.start_time|date='Y-m-d',###} - {pigcms{$vo.end_time|date='Y-m-d',###}</td>
                            <td>
                                <php>if(C('DEFAULT_LANG') == 'zh-cn'){</php>
                                {pigcms{:replace_lang_str(L('_MAN_NUM_REDUCE_'),$vo['order_money'])}{pigcms{:replace_lang_str(L('_MAN_REDUCE_NUM_'),$vo['discount'])}
                                <php>}else{</php>
                                {pigcms{:replace_lang_str(L('_MAN_NUM_REDUCE_'),$vo['discount'])}{pigcms{:replace_lang_str(L('_MAN_REDUCE_NUM_'),$vo['order_money'])}
                                <php>}</php>
                            </td>
                            <td class="textcenter"><if condition="$vo['allow_new'] eq 1"><font color="green">Yes</font><else /><font color="red">No</font></if></td>
                            <!--td><a href="{pigcms{$config.site_url}/index.php?g=Index&c=Recognition&a=see_qrcode&type=coupon&id={pigcms{$vo.coupon_id}" class="see_qrcode">渠道消息二维码</a>&nbsp;&nbsp; <if condition="$vo.wx_qrcode neq ''"><a href="{pigcms{:U('Coupon/see_qrcode',array('id'=>$vo['coupon_id']))}" class="see_qrcode">微信卡券二维码</a></if></td-->
                            <td class="textcenter">{pigcms{$vo.city_name}</td>
                            <td class="textcenter"><if condition="$vo['status'] eq 1"><font color="green">{pigcms{:L('_BACK_ACTIVE_')}</font><elseif condition="$vo['status'] eq 2"/><font color="blue">{pigcms{:L('_EXPIRED_TXT_')}</font><elseif condition="$vo['status'] eq 3" /><font color="black">领完了</font><else /><font color="red">{pigcms{:L('_BACK_FORBID_')}</font></if></td>
                            <td class="textcenter"><a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Coupon/edit',array('coupon_id'=>$vo['coupon_id']))}','{pigcms{:L(\'_BACK_EDIT_COU_INFO_\')}',800,500,true,false,false,editbtn,'edit',true);">{pigcms{:L('_BACK_EDIT_')}</a></td>
                        </tr>
                    </volist>
                    <tr><td class="textcenter pagebar" colspan="11">{pigcms{$pagebar}</td></tr>
                    <else/>
                    <tr><td class="textcenter red" colspan="11">{pigcms{:L('_BACK_EMPTY_')}</td></tr>
                </if>
                </tbody>
            </table>
        </div>
    </form>
</div>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script type="text/javascript">
    $(function(){
        $('#indexsort_edit_btn').click(function(){
            $(this).prop('disabled',true).html('提交中...');
            $.post("/merchant.php?g=Merchant&c=Config&a=merchant_indexsort",{group_indexsort:$('#group_indexsort').val(),indexsort_groupid:$('#indexsort_groupid').val()},function(result){
                alert('处理完成！正在刷新页面。');
                window.location.href = window.location.href;
            });
        });
        $('.see_qrcode').click(function(){
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
    });

</script>
<include file="Public:footer"/>