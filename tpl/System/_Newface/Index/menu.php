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
                                <tr>
                                    <th width="150px">
                                        <label><input type="checkbox" class="menu_{pigcms{$rowset['id']} father_menu" data-id="{pigcms{$rowset['id']}" value="{pigcms{$rowset['id']}" name="menus[]" <if condition="in_array($rowset['id'], $admin['menus'])">checked</if>/>　{pigcms{$rowset['name']}</label>
                                    </th>
                                    <td  class="">
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
		if ($(this).attr('checked')) {
			$('.father_menu, .child_menu').attr('checked', true);
		} else {
			$('.father_menu, .child_menu').attr('checked', false);
		}
	});

    $('.father_menu').each(function () {
        console.log("father_menu");
        //alert($(this).find('input').data('for'));
        //var input = $(this).find('input');
        $(this).bind('input porpertychange', father_changed);
        //$(this).find('input').on('click', checkNum);
    });

    $('table').find('.child_menu').each(function () {
        console.log("child_menu");
        $(this).bind('input porpertychange', child_changed);
        //alert($(this).find('input').data('for'));
        //var input = $(this).find('input');
        //$(this).find('input').bind('input porpertychange', changeGoodNum);
        //$(this).find('input').on('focusout', checkNum);
    });

    function father_changed(){
        var fid = $(this).data(id');
        console.log("father_changed data-id=".fid);
        // $('.child_menu_' + fid).each(function(){
        //     if ($(this).attr('checked')) {
        //         flag = true;
        //     }
        // });
    }
    function child_changed(){
        console.log("child_changed");
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
<!--include file="Public:footer_inner"/-->