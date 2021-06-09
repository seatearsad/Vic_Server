<include file="Public:header"/>
<body style="background-color: #fff;">
<div id="wrapper-singlepage">

    <div id="page-wrapper-singlepage" class="white-bg">

        <!----------------------------------------    以上不要写代码     ------------------------------------------------>
        <div class="row wrapper wrapper-content animated fadeInRight">

            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-content">
                        <form method="post" id="myform" action="{pigcms{:U('Index/savemenu')}" frame="true" refresh="true">
                            <table cellpadding="0" cellspacing="0" class="table_form" width="100%">
                            <input type="hidden" name="admin_id" value="{pigcms{$admin.id}"/>
                                <tr>
                                    <th width="150px">
                                        <label><input type="checkbox" class="menu_0 father_menu" data-id="0" value="0" name="menus[]" <if condition="in_array('0', $admin['menus'])">checked</if>/>　{pigcms{:L('_BACK_OVERVIEW_')}</label>
                                    </th>
                                    <td  class="">

                                    </td>
                                </tr>
                            <volist name="menus" id="rowset">
                                <tr class="menu_list">
                                    <th width="150px">
                                        <label><input type="checkbox" class="menu_{pigcms{$rowset['id']} father_menu" data-id="{pigcms{$rowset['id']}" value="{pigcms{$rowset['id']}" name="menus[]" <if condition="in_array($rowset['id'], $admin['menus'])">checked</if>/>　{pigcms{$rowset['name']}</label>
                                    </th>
                                    <td >
                                        <volist name="rowset['lists']" id="row">
                                        <label><input type="checkbox" class="child_menu_{pigcms{$row['fid']} child_menu" value="{pigcms{$row['id']}"  name="menus[]" data-fid="{pigcms{$row['fid']}"  <if condition="in_array($row['id'], $admin['menus'])">checked</if> />　{pigcms{$row['name']}</label>　
                                        </volist>
                                    </td>
                                </tr>
                            </volist>
                            <tr><td colspan="2"><label><input type="checkbox" id="all"/> {pigcms{:L('J_SELECT_ALL')}</label></td></tr>
                            </table>
                            <div class="btn tutti_hidden_obj">
                                <input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
                                <input type="reset" value="取消" class="button" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

<script type="text/javascript">

$(document).ready(function(){

	$('#all').click(function(){
        if($(this).is(':checked')){
			$('.father_menu, .child_menu').prop('checked',true);
		} else {
			$('.father_menu, .child_menu').prop('checked', false);
		}
	});

    $('table').find('.menu_list').each(function () {
        //console.log("father_menu");
        //alert($(this).find('input').data('for'));
        //var input = $(this).find('input');
        //$(this).find("father_menu").bind('input porpertychange', father_changed(this));
        //$(this).find(".father_menu").on('click',father_changed(this));
        $(this).find(".father_menu").click(function () {
            father_changed(this);
        });

        $(this).find(".child_menu").click(function () {
            child_changed(this);
        });
    });

    $('table').find('.child_menu').each(function () {
        $(this).find("input").bind('input porpertychange', child_changed);
        //alert($(this).find('input').data('for'));
        //var input = $(this).find('input');
        //$(this).find('input').bind('input porpertychange', changeGoodNum);
        //$(this).find('input').on('focusout', checkNum);
    });

    function father_changed(obj){
        var fid = $(obj).data('id');
        //console.log("father_changed="+fid);

        $(obj).parents('.menu_list').find('.child_menu_' + fid).each(function(){
            if($(obj).is(':checked')){
            }else{
                $(this).prop('checked',false);
            }

            // if ($(this).attr('checked')) {
            //     flag = true;
            // }
        });
    }
    function child_changed(obj){
        var fid = $(obj).data('fid');
        //console.log("child_changed="+fid);
        if($(obj).is(':checked')){
            console.log($(".menu_"+fid).attr('class'));
            $(".menu_"+fid).prop('checked',true);
        }

        // $(obj).parents('.menu_list').find('.child_menu_' + fid).each(function(){
        //     if($(obj).is(':checked')){
        //     }else{
        //         $(this).attr('checked',$(obj).is(':checked'));
        //     }
        //
        //     // if ($(this).attr('checked')) {
        //     //     flag = true;
        //     // }
        // });
    }

	// $('.father_menu').click(function(){
	// 	var fid = $(this).val();
	// 	if ($(this).attr('checked')) {
     //        $('.child_menu_' + fid).attr('checked', true);
    //
	// 	} else {
     //        $('.child_menu_' + fid).attr('checked', false);
	// 	}
	// });
	// $('.child_menu').click(function(){
	// 	var fid = $(this).attr('data-fid');
	// 	if ($(this).attr('checked')) {
	// 		$('.menu_' + fid).attr('checked', true);
	// 	} else {
	// 		var flag = false;
	// 		$('.child_menu_' + fid).each(function(){
	// 			if ($(this).attr('checked')) {
	// 				flag = true;
	// 			}
	// 		});
	// 		$('.menu_' + fid).attr('checked', flag);
	// 	}
	// });
});
</script>
<<<<<<< HEAD
<!--include file="Public:footer_inner"/-->
=======

<include file="Public:footer_inner"/>
>>>>>>> 36ca397c9297e257b084bd3dd6ef32ffbc5688fb
