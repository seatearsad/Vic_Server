var confirmbtn = [
	{
		name:'Confirm',
		callback:function () {
			var iframe = this.iframe.contentWindow;
			if (iframe.document.body) {
				var submits=iframe.document.getElementById('dosubmit');
				submits.click();
				return false;
			}else{
				return false;
			}
		},
		focus:true
	},
	{name:'Close'}
];
var addbtn = [
	{
		name:'Add',
		callback:function () {
			var iframe = this.iframe.contentWindow;
			if (iframe.document.body) {
        	    var submits=iframe.document.getElementById('dosubmit');
				submits.click();
				return false;
            }else{
				return false;
			}
		},
		focus:true
	},
	{name:'Close'}
];
var editbtn = [
	{
		name:'Save',
		callback:function () {
			var iframe = this.iframe.contentWindow;
			if (iframe.document.body) {
        	    var submits=iframe.document.getElementById('dosubmit');
				submits.click();
				return false;
            }else{
				return false;
			}
		},
		focus:true
	},
	{name:'Close'}
];
var verifybtn = [
	{
		name:'审核',
		callback:function () {
			var iframe = this.iframe.contentWindow;
			if (iframe.document.body) {
        	    var submits=iframe.document.getElementById('dosubmit');
				submits.click();
				/*this.close();*/
				return false;
            }else{
				return false;
			}
		},
		focus:true
	},
	{name:'关闭'}
];

var submitbtn = [
	{
		name:'Submit',
		callback:function () {
			var iframe = this.iframe.contentWindow;
			if (iframe.document.body) {
				var submits=iframe.document.getElementById('dosubmit');
				submits.click();
				return false;
			}else{
				return false;
			}
		},
		focus:true
	},
	{name:'Close'}
];

var importbtn = [
	{
		name:'导入',
		callback:function () {
			var iframe = this.iframe.contentWindow;
			if (iframe.document.body) {
				var submits=iframe.document.getElementById('dosubmit');
				submits.click();
				return false;
			}else{
				return false;
			}
		},
		focus:true
	},
	{name:'关闭'}
];
var closebtn = [
	{name:'关闭'}
];


//显示省份
function show_province(){
	$.post(choose_province,function(result){
		result = $.parseJSON(result);
		if(result.error == 0){
			var area_dom = '<select id="choose_province" name="province_id">';
			$.each(result.list,function(i,item){
				area_dom+= '<option value="'+item.id+'" '+(item.id==$('#choose_cityarea').attr('province_id') ? 'selected="selected"' : '')+'>'+item.name+'</option>';
			});
			area_dom+= '</select>';
			$('#choose_cityarea').prepend(area_dom);
			show_city($('#choose_province').find('option:selected').attr('value'),$('#choose_province').find('option:selected').html(),1);
			$('#choose_province').change(function(){
				show_city($(this).find('option:selected').attr('value'),$(this).find('option:selected').html(),1);
			});
		}else if(result.error == 2){
			var area_dom = '<select id="choose_province_hide" name="province_id" style="display:none;">';
			area_dom += '<option value="'+result.id+'">'+result.name+'</option>';
			area_dom += '</select>';
			$('#choose_cityarea').prepend(area_dom);
			show_city(result.id,result.name,0);
		}else{
			window.top.msg(0,result.info,true);
			window.top.closeiframe();
		}
	});
}
//显示城市
function show_city(id,name,type){
	$.post(choose_city,{id:id,name:name,type:type},function(result){
		result = $.parseJSON(result);
		if(result.error == 0){
			var area_dom = '<select id="choose_city" name="city_id">';
			$.each(result.list,function(i,item){
				area_dom+= '<option value="'+item.id+'" '+(item.id==$('#choose_cityarea').attr('city_id') ? 'selected="selected"' : '')+'>'+item.name+'</option>';
			});
			area_dom+= '</select>';
			if(document.getElementById('choose_city')){
				$('#choose_city').replaceWith(area_dom);
			}else if(document.getElementById('choose_province')){
				$('#choose_province').after(area_dom);
			}else{
				$('#choose_cityarea').prepend(area_dom);
			}
			if($('#choose_cityarea').attr('area_id') != '-1'){
				show_area($('#choose_city').find('option:selected').attr('value'),$('#choose_city').find('option:selected').html(),1);
			
				$('#choose_city').change(function(){
					show_area($(this).find('option:selected').attr('value'),$(this).find('option:selected').html(),1);
				});
			}
		}else if(result.error == 2){
			var area_dom = '<select id="choose_city_hide" name="city_id" style="display:none;">';
			area_dom += '<option value="'+result.id+'">'+result.name+'</option>';
			area_dom += '</select>';
			$('#choose_cityarea').prepend(area_dom);
			if($('#choose_cityarea').attr('area_id') != '-1'){
				show_area(result.id,result.name,0);
			}
		}else{
			window.top.msg(0,result.info,true,5);
			window.top.closeiframe();
		}
	});
}

//显示区域
function show_area(id,name,type){
	$.post(choose_area,{id:id,name:name,type:type},function(result){
		result = $.parseJSON(result);
		if(result.error == 0){
			var area_dom = '<select id="choose_area" name="area_id">';
			$.each(result.list,function(i,item){
				area_dom+= '<option value="'+item.id+'" '+(item.id==$('#choose_cityarea').attr('area_id') ? 'selected="selected"' : '')+'>'+item.name+'</option>';
			});
			area_dom+= '</select>';
			if(document.getElementById('choose_area')){
				$('#choose_area').replaceWith(area_dom);
			}else if(document.getElementById('choose_city')){
				$('#choose_city').after(area_dom);
			}else{
				$('#choose_cityarea').prepend(area_dom);
			}
			if($('#choose_cityarea').attr('circle_id') != '-1'){
				show_circle($('#choose_area').find('option:selected').attr('value'),$('#choose_area').find('option:selected').html(),1);
				$('#choose_area').change(function(){
					show_circle($(this).find('option:selected').attr('value'),$(this).find('option:selected').html(),1);
				});
			}
		}else{
			window.top.msg(0,result.info,true,5);
			window.top.closeiframe();
		}
	});
}
function show_circle(id,name,type){
	$.post(choose_circle,{id:id,name:name,type:type},function(result){
		result = $.parseJSON(result);
		if(result.error == 0){
			var area_dom = '<select id="choose_circle" name="circle_id" class="col-sm-1" style="margin-right:10px;">';
			$.each(result.list,function(i,item){
				area_dom+= '<option value="'+item.id+'" '+(item.id==$('#choose_cityarea').attr('circle_id') ? 'selected="selected"' : '')+'>'+item.name+'</option>';
			});
			area_dom+= '</select>';
			if(document.getElementById('choose_circle')){
				$('#choose_circle').replaceWith(area_dom);
			}else if(document.getElementById('choose_area')){
				$('#choose_area').after(area_dom);
			}else{
				$('#choose_cityarea').prepend(area_dom);
			}
//			if($('#choose_cityarea').attr('circle_id') != '-1'){
				show_market($('#choose_circle').find('option:selected').attr('value'),$('#choose_circle').find('option:selected').html(),1);
				$('#choose_circle').change(function(){
					show_market($(this).find('option:selected').attr('value'),$(this).find('option:selected').html(),1);
				});

//			 }
		}else{
			window.top.msg(0,result.info,true,5);
			window.top.closeiframe();
		}
	});
}

//显示商场
function show_market(id,name,type){
	if(document.getElementById('choose_market')){
		$('#choose_market').html('<option value="0">正在获取中..</option>');
	}
	$.post(choose_market,{id:id,name:name,type:type},function(result){
		result = $.parseJSON(result);
		if(result.error == 0){
			var area_dom = '<select id="choose_market" name="market_id" class="col-sm-1" style="margin-right:10px;">';
			$.each(result.list,function(i,item){
				area_dom+= '<option value="'+item.id+'" '+(item.id==$('#choose_cityarea').attr('market_id') ? 'selected="selected"' : '')+'>'+item.name+'</option>';
			});
			area_dom+= '</select>';
			if(document.getElementById('choose_market')){
				$('#choose_market').replaceWith(area_dom);
			}else if(document.getElementById('choose_circle')){
				$('#choose_circle').after(area_dom);
			}
			$('#choose_cityarea').removeAttr('province_id city_id area_id circle_id market_id');
		}else{
			// $('#choose_circle').val($('#choose_circle').find('option:first').attr('value'));
			$('#choose_market').remove();
		}
	});
}

//旅游显示省份
function show_provinces(){
	$.post(choose_provinces,function(result){
		result = $.parseJSON(result);
		if(result.error == 0){
			var area_dom = '<select id="choose_provinces" name="province_ids">';
			$.each(result.list,function(i,item){
				area_dom+= '<option value="'+item.id+'" '+(item.id==$('#choose_cityareas').attr('province_ids') ? 'selected="selected"' : '')+'>'+item.name+'</option>';
			});
			area_dom+= '</select>';
			$('#choose_cityareas').prepend(area_dom);
			show_citys($('#choose_provinces').find('option:selected').attr('value'),$('#choose_provinces').find('option:selected').html(),1);
			$('#choose_provinces').change(function(){
				show_citys($(this).find('option:selected').attr('value'),$(this).find('option:selected').html(),1);
			});
		}else if(result.error == 2){
			var area_dom = '<select id="choose_province_hides" name="province_ids" style="display:none;">';
			area_dom += '<option value="'+result.id+'">'+result.name+'</option>';
			area_dom += '</select>';
			$('#choose_cityareas').prepend(area_dom);
			show_citys(result.id,result.name,0);
		}else{
			window.top.msg(0,result.info,true);
//			window.top.closeiframe();
		}
	});
}
//旅游显示城市
function show_citys(id,name,type){
	$.post(choose_citys,{id:id,name:name,type:type},function(result){
		result = $.parseJSON(result);
		if(result.error == 0){
			var area_dom = '<select id="choose_citys" name="city_ids">';
			$.each(result.list,function(i,item){
				area_dom+= '<option value="'+item.id+'" '+(item.id==$('#choose_cityareas').attr('city_ids') ? 'selected="selected"' : '')+'>'+item.name+'</option>';
			});
			area_dom+= '</select>';
			if(document.getElementById('choose_citys')){
				$('#choose_citys').replaceWith(area_dom);
			}else if(document.getElementById('choose_provinces')){
				$('#choose_provinces').after(area_dom);
			}else{
				$('#choose_cityareas').prepend(area_dom);
			}
			show_areas($('#choose_citys').find('option:selected').attr('value'),$('#choose_citys').find('option:selected').html(),1);
			$('#choose_citys').change(function(){
				show_areas($(this).find('option:selected').attr('value'),$(this).find('option:selected').html(),1);
			});
		}else if(result.error == 2){
			var area_dom = '<select id="choose_city_hides" name="city_ids" style="display:none;">';
			area_dom += '<option value="'+result.id+'">'+result.name+'</option>';
			area_dom += '</select>';
			$('#choose_cityareas').prepend(area_dom);
			show_areas(result.id,result.name,0);
		}else{
			window.top.msg(0,result.info,true,5);
//			window.top.closeiframe();
		}
	});
}
//旅游显示区域
function show_areas(id,name,type){
	$.post(choose_areas,{id:id,name:name,type:type},function(result){
		result = $.parseJSON(result);
		if(result.error == 0){
			var area_dom = '<select id="choose_areas" name="area_ids">';
			$.each(result.list,function(i,item){
				area_dom+= '<option value="'+item.id+'" '+(item.id==$('#choose_cityareas').attr('area_ids') ? 'selected="selected"' : '')+'>'+item.name+'</option>';
			});
			area_dom+= '</select>';
			if(document.getElementById('choose_areas')){
				$('#choose_areas').replaceWith(area_dom);
			}else if(document.getElementById('choose_citys')){
				$('#choose_citys').after(area_dom);
			}else{
				$('#choose_cityareas').prepend(area_dom);
			}
		}else{
			window.top.msg(0,result.info,true,5);
//			window.top.closeiframe();
		}
	});
}
/*
 * 获得字符串的连续首字母
 * id1 为原字符串，id2 为字符串每个字符的首字母，id3 为字符串首字符的首字母
*/
function get_first_word(id1,id2,id3){
	$('#'+id1).bind('blur',function(){
		var id1_val = $('#'+id1).val();
		if(id1_val != '' && ($('#'+id2).val() == '' || $('#'+id3).val() == '')){

			$.getJSON(get_firstword,{str:id1_val},function(result){
				if(result.status == 1){
					if($('#'+id2).val() == ''){
						$('#'+id2).val(result.info);
					}
					if($('#'+id3).val() == ''){
						$('#'+id3).val(result.info.substr(0,1));
					}
				}else{
					window.top.msg(0,result.info,true);
				}
			});

		}
	});
}

$(function(){
	if(typeof(window.parent.isSoftView) != "undefined" && window.parent.isSoftView){
		$('a').live('click',function(){
			if($(this).attr('target') == '_blank'){
				gosofturl($(this).attr('href'));
				return false;
			}
		});
	}
	try{
		$('#sx', window.parent.document).show();
	}catch(e){
		// alert(e.name + ": " + e.message);
	}
	$('.hideSxBtn').on('click',function(){
		$('#sx', window.parent.document).hide();
	});
	$('.showSxBtn').on('click',function(){
		$('#sx', window.parent.document).show();
	});
	if($('#myform').length>0){
		if(document.getElementById('choose_map')){
			$('#choose_map').html('<input type="text" class="input fl" name="long_lat" id="long_lat" size="20" placeholder="Coordinate of Location" value="'+(typeof($('#choose_map').attr('default_long_lat'))!='undefined' ? $('#choose_map').attr('default_long_lat') : '')+'" validate="required:true" readonly="readonly"/><a href="javascript:void(0);" style="margin-left:10px;" id="show_map_frame">Click to pin location</a>');
			$('#show_map_frame').click(function(){
				window.top.change_frame_position_left('store_add');
				window.top.artiframe(choose_map+'&long_lat='+$('#long_lat').val(),"Pin the store location",655,520,true,false,false,false,"choose_map",true,function(){window.top.art.dialog.list["store_add"].position("50%","38.2%");},window.top.get_frame_position_left("store_add",655));
			});
		}

		//检测是否需要显示城市
		if(document.getElementById('choose_cityarea')){
			show_province();
		}
		//检测是否需要显示城市
		if(document.getElementById('choose_cityareas')){
			show_provinces();
		}
		/* 检测密码强度 */
		var check_pwd = $('#myform #check_pwd');
		if(typeof(check_pwd.val()) != 'undefined'){
			var check_width = check_pwd.attr('check_width');
			if(!check_width) check_width=200;
			if(!check_pwd.attr('no_check_tips')){
				var no_check_tips = true;
			}else{
				var no_check_tips = false;
			}
			var check_tr = '<tr id="check_tr"><th>Password Strength</th><td><table width="'+check_width+'" border="0" cellspacing="0" cellpadding="1" style="display:inline-block;_display:inline;"><tbody><tr class="noboder" align="center" style="background:none; border:none;"><td width="33%" id="pwd_lower" style="border-bottom:2px solid #DADADA">Weak</td><td width="33%" id="pwd_middle" style="border-bottom:2px solid #DADADA">Fair</td><td width="33%" id="pwd_high" style="border-bottom:2px solid #DADADA">Good</td></tr></tbody></table>'+( no_check_tips ? '' : '')+'</td></tr>';
			var check_event = check_pwd.attr('check_event');
			if(!check_event){
				check_pwd.closest('tr').after(check_tr);
			}else{
				check_pwd.bind(check_event,function(){
					if(!document.getElementById('check_tr')){
						check_pwd.closest('tr').after(check_tr);
					}
					if(check_event == 'keyup'){
						if(check_pwd.val().length == 0){
							$('#check_tr').remove();
						}
					}
				});
			}
			check_pwd.keyup(function(){
				var pwd = $(this).val();
				var Mcolor = "#FFF",Lcolor = "#FFF",Hcolor = "#FFF";
				var m=0;
				if(pwd.length >= 6){
					if(/[a-zA-Z]+/.test(pwd) && /[0-9]+/.test(pwd) && /\W+\D+/.test(pwd)) {
						m = 3;
					}else if(/[a-zA-Z]+/.test(pwd) || /[0-9]+/.test(pwd) || /\W+\D+/.test(pwd)) {
						if(/[a-zA-Z]+/.test(pwd) && /[0-9]+/.test(pwd)) {
							m = 2;
						}else if(/[a-zA-Z]+/.test(pwd) && /\W+\D+/.test(pwd)){
							m = 2;
						}else if(/[0-9]+/.test(pwd) && /\W+\D+/.test(pwd)) {
							m = 2;
						}else{
							m = 1;
						}
					}
				}else if(pwd.length == 0){
					m = 0;
				}else{
					m = 1;
				}
				switch(m){
					case 1 :
						Lcolor = "2px solid red";
						Mcolor = Hcolor = "2px solid #DADADA";
						break;
					case 2 :
						Mcolor = "2px solid #f90";
						Lcolor = Hcolor = "2px solid #DADADA";
						break;
					case 3 :
						Hcolor = "2px solid #3c0";
						Lcolor = Mcolor = "2px solid #DADADA";
						break;
					case 4 :
						Hcolor = "2px solid #3c0";
						Lcolor = Mcolor = "2px solid #DADADA";
						break;
					default :
						Hcolor = Mcolor = Lcolor = "";
						break;
				}
				if (document.getElementById("pwd_lower")){
					document.getElementById("pwd_lower").style.borderBottom  = Lcolor;
					document.getElementById("pwd_middle").style.borderBottom = Mcolor;
					document.getElementById("pwd_high").style.borderBottom   = Hcolor;
				}
			});
		}
		if(frame_show){
			$.each($('#myform td'),function(i,item){
				$(item).find("input[type='file']").closest('tr').remove();
				if(typeof($(item).find("input[type='text']").val()) != 'undefined'){
					if($(item).find("input[type='text']").val().length == 0){
						$(item).html('<div class="show">None</div>');
					}else{
						$(item).html('<div class="show">'+$(item).find("input[type='text']").val()+'</div>');
					}
				}else if(typeof($(item).find("input[type='password']").val()) != 'undefined'){
					$(item).html('<div class="show">密码项不能查看</div>');
				}else if(typeof($(item).find("input[type='radio']:checked").val()) != 'undefined'){
					$(item).html('<div class="show">'+$(item).find("input[type='radio']:checked").parent().text()+'</div>');
				}else if(typeof($(item).find("select").val()) != 'undefined'){
					$(item).html('<div class="show">'+$(item).find("select option:selected").text()+'</div>');
				}
			});
		}else{
			$.each($('#myform').find("input,select,textarea").not(":submit,:reset,:image,[disabled]"),function(i,item){
				if($(item).attr('tips')){
					$(item).after('<img src="'+static_path+'images/help.gif" class="tips_img" title="'+$(item).attr('tips')+'"/>');
				}
				var validate = $(item).attr('validate');
				if(validate){
					varlidate_arr = validate.split(',');
					for(var i in varlidate_arr){
						if(varlidate_arr[i] == 'required:true'){
							if($(item).attr('id')){
								var em_for = $(item).attr('id');
							}else{
								var em_for = $(item).attr('name');
							}
							if($(item).val() == ''){
								$(item).parent().append('<em for="'+em_for+'" generated="true" class="error tips">Required</em>');
							}else{
								$(item).parent().append('<em for="'+em_for+'" generated="true" class="error success"></em>');
							}
							break;
						}
					}
				}
			});
			$.each($('#myform .notice_tips'),function(i,item){
				$(this).replaceWith('<img src="'+static_path+'images/help.gif" class="tips_img" title="'+$(this).attr('tips')+'" style="margin-top:1px;"/>');
			});
			$("#myform").validate({
				event:"blur",
				errorElement: "em",
				errorPlacement: function(error,element){
					error.appendTo(element.parent("td"));
				},
				success: function(label){
					label.addClass("success");
				},
				submitHandler:function(form){
					if($('.ke-container').length > 0){
						kind_editor.sync();
					}
					if($(form).attr('frame') == 'true' || $(form).attr('refresh') == 'true'){
                        if(!$(form).data('call_fun')) {
                            window.top.msg(2, 'Submitting, please wait a moment.', true, 360);
                        }
						$.post($(form).attr('action'),$(form).serialize(),function(result){
							if(result.status == 1){

								if($(form).data('call_fun')){
									//submitCallBack(result.info);
                                    //window.top.artiframe('/admin.php?c=Index&a=menu&admin_id=14','Permissions',800,500,true,false,false,editbtn,'edit',true);
                                    window.location.href = '/admin.php?c=Index&a=menu&admin_id='+result.info;
								}else{
									window.top.msg(1,result.info,true);
									if($(form).attr('refresh') == 'true'){
										window.top.main_refresh();
									}
									window.top.closeiframe();
								}
							}else{
								window.top.msg(0,result.info,true);
							}
						});
						return false;
					}else{
						window.top.msg(2,'Submitting, please wait a moment.',true,360);
						form.submit();
					}
				}
			});
		}
	}
	//全屏预览键，即隐藏左边
	$('.full_screen_link').click(function(){
		window.top.toggleMenu(1);
	});
	//删除行
	$('.delete_row').click(function(){
		var now_dom = $(this);
		window.top.art.dialog({
			icon: 'question',
			title: 'Reminder',
			id: 'msg' + Math.random(),
			lock: true,
			fixed: true,
			opacity:'0.4',
			resize: false,
			content: 'There is no going back for deleting,are you sure about this?' + (now_dom.attr('tip') ? '<br/><br/><div style="color:red;">'+now_dom.attr('tip')+'</div>' : ''),
			okVal:'Yes',
			ok:function (){
				$.post(now_dom.attr('url'),now_dom.attr('parameter'),function(result){
					if(result.status == 1){
						window.top.msg(1,result.info,true);
						if(now_dom.closest('table').find('tr').length>3){
							now_dom.closest('tr').remove();
							$('#row_count').html(parseInt($('#row_count').html())-1);
						}else{
							window.location.reload();
						}
					}else{
						window.top.msg(0,result.info);
					}
				});
			},
			cancelVal:'Cancel',
			cancel:true
		});
		return false;
	});
	$('td .tips_img').mouseover(function(e){
		var now    = $(this);
		var offset = $(this).offset();
		var parent = $(this).closest('td');
		now.data('tips',now.attr('title')).attr('title','');
		parent.append('<div class="tooltipdi" style="left:'+(offset.left+30)+'px;top:'+(offset.top-6)+'px"><span><b></b><em></em>'+now.data('tips')+'</span></div>');
		var tips_div = parent.find('.tooltipdi');
		parent.one('mouseout',function(){
			//setTimeout(function(){
				//if(parent.data('hover')!=1){
					now.attr('title',now.data('tips'));
					tips_div.remove();
				//}
			//},1150);
		});
		// tips_div.bind({'mouseover':function(){
			// parent.data('hover',1);
		// },'mouseout':function(){
			// parent.data('hover',0);
			// now.attr('title',now.data('tips'));
			// tips_div.remove();
		// }});
	});
	//开关
	$('.cb-enable').click(function(){
		//console.log(".cb-enable");
		$(this).find('label').addClass('selected');
		$(this).find('label').find('input').prop('checked',true);
		$(this).next('.cb-disable').find('label').find('input').prop('checked',false);
		$(this).next('.cb-disable').find('label').removeClass('selected');
	});
	$('.cb-disable').click(function(){
        //console.log(".cb-disable");
		$(this).find('label').addClass('selected');
		$(this).find('label').find('input').prop('checked',true);
		$(this).prev('.cb-enable').find('label').find('input').prop('checked',false);
		$(this).prev('.cb-enable').find('label').removeClass('selected');
	});

	//预览大图
	$('.view_msg').click(function(){
		window.top.art.dialog({
			padding: 0,
			title: '大图',
			content: '<img src="'+$(this).attr('src')+'" style="width:600px;height:400px;" />',
			lock: true
		});
	});

	$('#choose_color_box').click(function(){
		window.top.showTopColorPanel('Openadd',$(this).offset().top,$(this).height(),$(this).offset().left,'choose_color')
	});
});