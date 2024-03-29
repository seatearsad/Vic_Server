<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-cubes"></i>
				<a href="{pigcms{:U('Shop/index')}">{pigcms{:L('DELIVERY_MANAGEMENT_BKADMIN')}</a>
			</li>
			<li class="active"><a href="{pigcms{:U('Shop/goods_sort',array('store_id'=>$now_store['store_id']))}">{pigcms{:L('C_CATEGORYLIST')}</a></li>
			<li class="active"><a href="{pigcms{:U('Shop/goods_list',array('sort_id'=>$now_sort['sort_id']))}">{pigcms{$now_sort.sort_name}</a></li>
			<li class="active">{pigcms{:L('ADD_ITEM_BKADMIN')}</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<style>
				.ace-file-input a {display:none;}
				#levelcoupon select {width:150px;margin-right: 20px;}
			</style>
			<div class="row">
				<div class="col-xs-12">
					<div class="tabbable">
						<ul class="nav nav-tabs" id="myTab">
							<li class="active">
								<a data-toggle="tab" href="#basicinfo">{pigcms{:L('BASEINFO_BKADMIN')}</a>
							</li>
							<li>
								<a data-toggle="tab" href="#txtintro">{pigcms{:L('ITEM_DESCRIPTION_BKADMIN')}</a>
							</li>
							<li>
								<a data-toggle="tab" href="#txtattr">{pigcms{:L('GOODS_SPEC_BKADMIN')}</a>
							</li>
                            <li>
                                <a data-toggle="tab" href="#allergens">{pigcms{:L('ALLERGENS')}</a>
                            </li>
<!--							<li>-->
<!--								<a data-toggle="tab" href="#seckill">{pigcms{:L('LIMIT_TIME_DISCOUNT_BKADMIN')}</a>-->
<!--							</li>-->
							<if condition="$now_store['store_theme'] AND $category_list AND 0">
							<li>
								<a data-toggle="tab" href="#category">商城属性设置</a>
							</li>
							</if>
						</ul>
					</div>
					<form enctype="multipart/form-data" class="form-horizontal" method="post">
						<input type="hidden" value="0" id="goods_id" />
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane active">
								<if condition="$error_tips">
									<div class="alert alert-danger">
										<p>{pigcms{:L('CORRECT_BKADMIN')}</p>
										<p>{pigcms{$error_tips}</p>
									</div>
								</if>
								<if condition="$ok_tips">
									<div class="alert alert-info">
										<p>{pigcms{$ok_tips}</p>
									</div>
								</if>
								<div class="form-group">
									<label class="col-sm-1"><label for="name">{pigcms{:L('ITEM_NAME_BKADMIN')}</label></label>
									<input class="col-sm-1" size="20" name="name" id="name" type="text" value="{pigcms{$now_goods.name}"/>
									<span class="form_tips">{pigcms{:L('REQUIRED_BKADMIN')}</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="name">{pigcms{:L('SERIAL_NUMBER_BKADMIN')}</label></label>
									<input class="col-sm-1" size="20" name="number" id="number" type="text" value="{pigcms{$now_goods.number}"/>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="unit">{pigcms{:L('GOOD_UNIT_BKADMIN')}</label></label>
									<input class="col-sm-1" size="20" name="unit" id="unit" type="text" value="{pigcms{$now_goods.unit}"/>
									<span class="form_tips">{pigcms{:L('REQUIRED_UNIT_BKADMIN')}</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1" for="Food_status">{pigcms{:L('VIEW_CATEGORIES_BKADMIN')}</label>
									<fieldset id="choose_sort"></fieldset>
								</div>
								<!--div class="form-group">
									<label class="col-sm-1"><label for="price">商品原价</label></label>
									<input class="col-sm-1" size="20" name="old_price" id="old_price" type="text" value="{pigcms{$now_goods.old_price|floatval}"/>
									<span class="form_tips">原价可不填，不填和现价一样</span>
								</div-->
								<div class="form-group hidden_obj">
									<label class="col-sm-1"><label for="price">商品进价</label></label>
									<input class="col-sm-1" size="20" name="cost_price" id="cost_price" type="text" value="{pigcms{$now_goods.cost_price|floatval}"/>
									<span class="form_tips">进货价用户是看不到{pigcms{:L('REQUIRED_BKADMIN')}</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="price">{pigcms{:L('LISTING_PRICE_NOW_BKADMIN')}</label></label>
									<input class="col-sm-1" size="20" name="price" id="price" type="text" value="{pigcms{$now_goods.price|floatval}"/>
									<if condition="$config.open_extra_price eq 1">
										元 + <input class="col-sm-1" maxlength="30" name="extra_pay_price" type="text" value="" style="float:none"/>{pigcms{$config.extra_price_alias_name}
										<span class="form_tips">如果填写{pigcms{$config.extra_price_alias_name}字段，商品价格将变为：金额+{pigcms{$config.extra_price_alias_name}数</span>
									</if>
									<span class="form_tips">{pigcms{:L('REQUIRED_BKADMIN')}</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="price">{pigcms{:L('QW_PACKAGEFEE')}</label></label>
									<input class="col-sm-1" size="20" name="packing_charge" id="packing_charge" type="text" value="{pigcms{$now_goods.packing_charge|floatval}"/>
								</div>

								<div class="form-group  hidden_obj">
									<label class="col-sm-1"><label for="price">{pigcms{:L('PRODUCT_INVENTORY_BKADMIN')}</label></label>
									<input class="col-sm-1" size="20" name="stock_num" id="stock_num" type="text" value="{pigcms{$now_goods.stock_num|default='-1'}"/>
									<span class="form_tips">{pigcms{:L('NEG1_MEANS_BKADMIN')}</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="sort">{pigcms{:L('ITEM_LISTING_ODER_BKADMIN')}</label></label>
									<input class="col-sm-1" size="10" name="sort" id="sort" type="text" value="{pigcms{$now_goods.sort|default='0'}"/>
									<span class="form_tips">{pigcms{:L('C_LISTORDERDES')}</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1" for="Food_status">{pigcms{:L('ITEM_STATUS_BKADMIN')}</label>
									<select name="status" id="Food_status">
										<option value="1" selected="selected">{pigcms{:L('ACTIVE_BKADMIN')}</option>
										<option value="0" >{pigcms{:L('SOLD_OUT_BKADMIN')}</option>
									</select>
								</div>
                                <div class="form-group">
                                    <label class="col-sm-1"><label for="sort">{pigcms{:L('GOOD_TAX_RATE_BKADMIN')}</label></label>
                                    <input class="col-sm-1" size="10" name="tax_num" id="tax_num" type="text" value="{pigcms{$now_goods.tax_num|default=$now_store['default_tax']}"/> %
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-1"><label for="sort">{pigcms{:L('GOOD_BOTTLE_DEPOSIT_BKADMIN')}</label></label>
                                    <input class="col-sm-1" size="10" name="deposit_price" id="deposit_price" type="text" value="{pigcms{$now_goods.deposit_price|default='0.00'}"/>
                                </div>
								<if condition="$print_list">
								<div class="form-group hidden_obj">
									<label class="col-sm-1" for="Food_status">{pigcms{:L('PRINTER_BKADMIN')}</label>
									<select name="print_id" id="print_id">
										<option value="0" selected>选择打印机</option>
										<volist name="print_list" id="print">
										<option value="{pigcms{$print['pigcms_id']}" <if condition="$print['pigcms_id'] eq $now_goods['print_id']">selected</if>>{pigcms{$print['name']}</option>
										</volist>
									</select>
									<span class="form_tips" style="color:red;">如果选择了一台非主打印机的话，那么客户在下单的时候选择的打印机和主打印机同时打印，如果不选打印机或是选择了主打印机的话，那么就主打印机打印</span>
								</div>
								</if>
                                <!--               图片上传                 /////-->
                                <div id="upload_image_box" class="row" style="margin-bottom: 10px;display: none;">
                                    <div class="col-lg-12">
                                        <div class="ibox ">
                                            <div class="ibox-title  back-change">
                                                <label style="margin-bottom: 10px">{pigcms{:L('ITEM_PHOTO_BKADMIN')}</label>
                                            </div>
                                            <div class="ibox-content">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="image-crop">
                                                            <img id="ori_image" src="{pigcms{$static_path}images/p3.jpg">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h4>{pigcms{:L('PREVIEW_BKADMIN')}</h4>
                                                        <div class="img-preview img-preview-sm"></div>
                                                        <p>
                                                            &nbsp;<div  id="upld" class="btn btn-primary">Upload</div>
                                                        </p>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--                                //---------------------------------->
                                <div class="form-group" >
                                    <label class="col-sm-1">{pigcms{:L('UPLOAD_BKADMIN')}</label>
                                    <div style="display:inline-block;" id="J_selectImage">
                                        <div class="btn btn-sm btn-success" style="position:relative;width:78px;height:34px;">
                                            <label title="Upload image file" for="inputImage" >
                                                <input type="file" accept="image/*" name="pic" id="inputImage" style="display:none">
                                                {pigcms{:L('UPLOAD_BKADMIN')}
                                            </label>
                                        </div>
                                    </div>
                                    <span class="form_tips"></span>
                                </div>

                                <div class="form-group hidden_obj">
                                    <label class="col-sm-1">{pigcms{:L('IMAGE_SELECT_BKADMIN')}</label>
                                    <a href="#modal-table" class="btn btn-sm btn-success" onclick="selectImg('upload_pic_ul','goods')">{pigcms{:L('IMAGE_SELECT_BKADMIN')}</a>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-1">{pigcms{:L('PREVIEW_BKADMIN')}</label>
                                    <div id="upload_pic_box">
                                        <ul id="upload_pic_ul">
                                            <volist name="now_goods['pic_arr']" id="vo">
                                                <li class="upload_pic_li"><img src="{pigcms{$vo.url}"/><input type="hidden" name="pic[]" value="{pigcms{$vo.title}"/><br/><a href="#" onclick="deleteImage('{pigcms{$vo.title}',this);return false;">[ {pigcms{:L('DELETE_BKADMIN')} ]</a></li>
                                            </volist>
                                        </ul>
                                    </div>
                                </div>
							</div>
							<div id="txtintro" class="tab-pane">
								<div class="form-group" >
									<label class="col-sm-1">{pigcms{:L('ITEM_DESCRIPTION_2_BKADMIN')}</label>
									<!--textarea name="des" id="content" style="width:702px;">{pigcms{$now_goods.des}</textarea-->
                                    <textarea name="des" style="width:702px;height: 200px">{pigcms{$now_goods.des}</textarea>
								</div>
							</div>

							<div id="txtattr" class="tab-pane">
								<div class="alert alert-info" style="margin:10px 0;">
									<button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
                                    {pigcms{:L('ATTRI_SPECIFICA_BKADMIN')}
								</div>
								<div class="topic_box">
									<volist name="now_goods['spec_list']" id="row" key="ii">
									<div class="question_box spec">
										<p class="question_info"><span>{pigcms{:L('SPECIFICATION_BKADMIN')}：</span>
											<input type="text" class="txt spec_name" value="{pigcms{$row['name']}" name="specs[]"/>
											<input type="hidden" name="spec_id[]" value="{pigcms{$row['id']}"/>
											<a href="javascript:;" class="box_del">{pigcms{:L('DELETE_BKADMIN')}</a>
										</p>
										<ul id="1" class="optionul">
											<volist name="row['list']" id="r">
											<li>
												<u>{pigcms{:L('SPECIFICATION_VALUE_BKADMIN')}：</u>
												<input type="hidden" class="hide_txt spec_val_id" name="spec_val_id[{pigcms{$ii-1}][]" value="{pigcms{$r['id']}">
												<input type="text" class="txt spec_val" name="spec_val[{pigcms{$ii-1}][]" value="{pigcms{$r['name']}"/>
												<a class="list_del" href="javascript:;" title="{pigcms{:L('DELETE_BKADMIN')}">×</a>
											</li>
											</volist>
										</ul>
										<p class="bot_add"><a href="javascript:;" class="btn btn-sm btn-success">  {pigcms{:L('ADD_VALVE_BKADMIN')}</a></p>
									</div>
									</volist>
									<p class="add_spec"><a href="javascript:;" title="添加" class="btn btn-sm btn-success" <if condition="count($now_goods['spec_list']) egt 3">style="display:none"</if>>{pigcms{:L('ADD_SPECIFICATION_BKADMIN')}</a></p>
								</div>

								<div class="topic_box">
									<volist name="now_goods['properties_list']" id="ro" key="ik">
									<div class="question_box properties">
										<p class="question_info">
											<span>{pigcms{:L('ATTRIBUTE_NAME_BKADMIN')}：</span>
											<input type="text" class="txt properties_name" value="{pigcms{$ro['name']}" name="properties[]"/>
											<span>{pigcms{:L('QUANTITY_ALLOWED_BKADMIN')}：</span><input type="text" class="txt properties_num" value="{pigcms{$ro['num']}" name="properties_num[]" style="width:50px"/>
											<input type="hidden" name="properties_id[]" value="{pigcms{$ro['id']}">
											<a href="javascript:;" class="box_del">{pigcms{:L('DELETE_BKADMIN')}</a>
										</p>
										<ul id="1" class="optionul">
											<volist name="ro['val']" id="ra">
											<li>
												<u>{pigcms{:L('ATTRIBUTE_VALUE_BKADMIN')}：</u>
												<input type="text" class="txt properties_val" name="properties_val[{pigcms{$ik-1}][]" value="{pigcms{$ra}"/>
												<a class="list_del" href="javascript:;" title="{pigcms{:L('DELETE_BKADMIN')}">×</a>
											</li>
											</volist>
										</ul>
										<p class="bot_add"><a href="javascript:;" class="btn btn-sm btn-success">  {pigcms{:L('ADD_VALUE_BKADMIN')}</a></p>
									</div>
									</volist>
									<p class="add_properties"><a href="javascript:;" title="" class="btn btn-sm btn-success">{pigcms{:L('ADD_ATTRIBUTE_BKADMIN')}</a></p>
								</div>

								<div class="topic_box">
									<p class="add_table" <if condition="!$now_goods['spec_list']">style="display:none"</if>><a href="javascript:;" title="{pigcms{:L('BASE_ADD')}" class="btn btn-sm btn-success" >{pigcms{:L('GENERATE_CHART_BKADMIN')}</a></p>
									<table class="table table-striped table-bordered table-hover" id="table_list">
									<if condition="$now_goods['spec_list']">
									<tbody>
										<tr>
											<volist name="now_goods['spec_list']" id="gs">
											<th>{pigcms{$gs['name']}</th>
											</volist>
<!--											<th style="display:none">{pigcms{:L('ORIGINAL_PRICE_BKADMIN')}</th>-->
<!--                                            <th>进价</th>-->
                                            <th>{pigcms{:L('CURR_PRICE_BKADMIN')}</th>
<!--                                            <th>{pigcms{:L('LIMIT_TIME_DISCOUNT_BKADMIN')}</th>-->
                                            <th>{pigcms{:L('STOCK_BKADMIN')}</th>
											<volist name="now_goods['properties_list']" id="gp">
											<th>{pigcms{$gp['name']}({pigcms{:L('QUANTITY_ALLOWED_BKADMIN')})</th>
											</volist>
										</tr>

										<volist name="now_goods['list']" id="gl" key="num">
											<tr id="{pigcms{$gl['index']}">
												<volist name="gl['spec']" id="g">
												<td>{pigcms{$g['spec_val_name']}</td>
												</volist>

<!--												<td  style="display:none"><input type="text" class="txt" name="old_prices[]" value="{pigcms{$gl['old_price']}" style="width:80px;"></td>-->
<!--												<td><input type="text" class="txt" name="cost_prices[]" value="{pigcms{$gl['cost_prices']}" style="width:80px;"></td>-->
												<td><input type="text" class="txt" name="prices[]" value="{pigcms{$gl['price']}" style="width:80px;"></td>
<!--												<td><input type="text" class="txt" name="seckill_prices[]" value="{pigcms{$gl['seckill_price']}" style="width:80px;"></td>-->
												<td><input type="text" class="txt" name="stock_nums[]" value="{pigcms{$gl['stock_num']}" style="width:80px;"></td>

												<volist name="gl['properties']" id="gpp" key="num">
												<td><input type="text" class="txt" name="num{pigcms{$num-1}[]" value="{pigcms{$gpp['num']}" style="width:80px;"></td>
												</volist>
											</tr>
										</volist>
									</tbody>
									</if>
									</table>
								</div>
							</div>
                            <div id="allergens" class="tab-pane">
                                <div class="allergens_div">
                                    <volist name="allergens_list" id="v">
                                        <label data-id="{pigcms{$v['id']}" class="label label-large" style="margin-top: 5px;line-height:25px;height:auto;font-size: 16px;">{pigcms{$v['name']}</label>
                                    </volist>
                                    <input type="hidden" name="allergens" value="" />
                                </div>
                            </div>
							<div id="seckill" class="tab-pane hidden_obj">
								<div class="form-group">
									<label class="col-sm-1"><label for="price">商品限时价</label></label>
									<input class="col-sm-1" size="20" name="seckill_price" id="seckill_price" type="text" value="{pigcms{$now_goods.seckill_price|default=0}"/>
									<span class="form_tips">0表示无限时价。</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="price">限时价库存</label></label>
									<input class="col-sm-1" size="20" name="seckill_stock" id="seckill_stock" type="text" value="{pigcms{$now_goods.seckill_stock|default=-1}"/>
									<span class="form_tips">-1表示无限量。</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="price">限时价类型</label></label>
									<span><label><input id='seckill_type0' name="seckill_type" <if condition="$now_goods['seckill_type'] eq 0 ">checked="checked"</if> value="0" type="radio">&nbsp;<span>固定时间段</span>&nbsp;</label></span>
									<span><label><input id='seckill_type1' name="seckill_type" <if condition="$now_goods['seckill_type'] eq 1 ">checked="checked"</if> value="1" type="radio" >&nbsp;<span>每天的时间段</span></label></span>
								</div>

								<div class="form-group">
									<label class="col-sm-1"><label for="price">限时段</label></label>
									<div>
										<input id="goods_seckill_open_time" type="text" value="{pigcms{$now_goods['seckill_open_time']|date='Y-m-d H:i',###}" name="seckill_open_time" readonly/>	至
										<input id="goods_seckill_close_time" type="text" value="{pigcms{$now_goods['seckill_close_time']|date='Y-m-d H:i',###}" name="seckill_close_time" readonly/>
										<div class="errorMessage" id="Config_shop_start_time_em_" style="display:none"></div>
										<div class="errorMessage" id="Config_shop_stop_time_em_" style="display:none"></div>
									</div>
								</div>
							</div>
							<div id="category" class="tab-pane">
								<div class="alert alert-info" style="margin:10px 0;">
									<button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
									运费模版：用户在选购该商品的时候，不同的区域有不同的运费。
									<br/><br/>
									其他区域运费：指的是用户选择的配送区域不在运费模板的区域内的其他城市的运费！（如果不选择运费模板的话，那么该商品的运费就是这个地方设置的值）
									<br/><br/>
									运费计算方式：1、按最大值算：就是用户同时买了该店铺的多个商品，则本次购物的运费只收取商品运费最高的那个。
									<br/><br/>
									　　　　　　　2、单独计算：指的是用户在购买该商品的时候运费单独另外收取，不与其他商品合并计算运费。
								</div>
								<div class="form-group">
									<label class="col-sm-1">运费模版</label>
									<select name="freight_template" id="freight_template">
										<option value="0" <if condition="0 eq $now_goods['freight_template']">selected</if>>请选择运费模板...</option>
										<volist name="express_template" id="express">
										<if condition="$express['id'] eq intval($now_goods['freight_template'])">
										<option value="{pigcms{$express['id']}" selected>{pigcms{$express['name']}</option>
										<else />
										<option value="{pigcms{$express['id']}">{pigcms{$express['name']}</option>
										</if>
										</volist>
									</select>
									<a href="{pigcms{:U('Express/add')}">+新建</a>
								</div>
								<div class="form-group">
									<label class="col-sm-1">其他区域运费</label>
									<div><input name="freight_value" type="text" value="{pigcms{$now_goods.freight_value|floatval}" /></div>
								</div>
								<div class="form-group">
									<label class="col-sm-1">运费计算方式</label>
									<span><label><input name="freight_type" <if condition="$now_goods['freight_type'] eq 0 ">checked="checked"</if> value="0" type="radio">&nbsp;<span>按最大值算</span>&nbsp;</label></span>
									<span><label><input name="freight_type" <if condition="$now_goods['freight_type'] eq 1 ">checked="checked"</if> value="1" type="radio" >&nbsp;<span>单独计算</span></label></span>
								</div>
								<div class="form-group">
									<label class="col-sm-1" for="Food_status">商城商品分类</label>
									<fieldset id="choose_category" cat_fid="0" cat_id="0"></fieldset>
								</div>
<!-- 								<volist name="category_list" id="vo"> -->
<!-- 									<div class="form-group"> -->
<!-- 										<div class="radio"> -->
<!-- 											<label> -->
												<span class="lbl"><label style="color: red">{pigcms{$vo.cat_name}：</label></span>
<!-- 											</label> -->
<!-- 											<volist name="vo['son_list']" id="child"> -->
<!-- 												<label> -->
<!-- 													<input class="cat_class" type="checkbox" name="store_category[]" value="{pigcms{$vo.cat_id}-{pigcms{$child.cat_id}" id="Config_store_category_{pigcms{$child.cat_id}" <if condition="in_array($child['cat_id'],$relation_array)">checked="checked"</if>/> -->
<!-- 													<span class="lbl"><label for="Config_store_category_{pigcms{$child.cat_id}">{pigcms{$child.cat_name}</label></span> -->
<!-- 												</label> -->
<!-- 											</volist> -->
<!-- 										</div> -->
<!-- 									</div> -->
<!-- 								</volist> -->
							</div>
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<button class="btn btn-info" type="submit">
										<i class="ace-icon fa fa-check bigger-110"></i>
										{pigcms{:L('SAVE_BKADMIN')}
									</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<style>
input.ke-input-text {
	background-color: #FFFFFF;
	background-color: #FFFFFF!important;
	font-family: "sans serif",tahoma,verdana,helvetica;
	font-size: 12px;
	line-height: 24px;
	height: 24px;
	padding: 2px 4px;
	border-color: #848484 #E0E0E0 #E0E0E0 #848484;
	border-style: solid;
	border-width: 1px;
	display: -moz-inline-stack;
	display: inline-block;
	vertical-align: middle;
	zoom: 1;
}
.form-group>label{font-size:12px;line-height:24px;}
#upload_pic_box{margin-top:20px;height:150px;}
#upload_pic_box .upload_pic_li{width:130px;float:left;list-style:none;}
#upload_pic_box img{width:100px;height:70px;}
.webuploader-element-invisible {
    position: absolute !important;
    clip: rect(1px 1px 1px 1px);
    clip: rect(1px,1px,1px,1px);
}
.webuploader-pick-hover .btn{
	background-color: #629b58!important;
    border-color: #87b87f;
}
</style>
<link rel="stylesheet" href="{pigcms{$static_path}css/activity.css">
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script type="text/javascript" src="./static/js/upyun.js"></script>
<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/webuploader.min.js"></script>
<script>

    //----------------------------------- 以下为Crooper段 --------------------------------------
    var loaded = false;
    var $upload_image_box;
    var $inputImage;var $cropped;
    function load_cooper() {
        if (loaded == false) {
            loaded = true;

            $upload_image_box = $("#upload_image_box");

            $inputImage = $("#inputImage");

            if (window.FileReader) {//检测浏览器是否支持FileReader
                $inputImage.change(function () {
                    var fileReader = new FileReader(),
                        files = this.files,
                        file;
                    if (!files.length) {
                        return;
                    }
                    var $image = $(".image-crop > img");
                    $cropped = $($image).cropper({
                        aspectRatio: 1,
                        preview: ".img-preview",
                        done: function (data) {
                            // Output the result data for cropping image.
                        }
                    });
                    $upload_image_box.show();
                    file = files[0];
                    if (/^image\/\w+$/.test(file.type)) {
                        $upload_image_box.show();
                        fileReader.readAsDataURL(file);
                        fileReader.onload = function () {
                            $inputImage.val("");
                            $image.cropper("reset", true).cropper("replace", this.result);
                        };
                    } else {
                        showMessage("Please choose an image file.");
                    }
                });
            } else {
                $inputImage.addClass("hide");
            }

            $("#setDrag").click(function () {
                $image.cropper("setDragMode", "crop");
            });

            $("#upld").on("click", function () {
                //console.log("download");
                if ($("#ori_image").attr("src") == null) {
                    return false;
                } else {

                    var base64 = $cropped.cropper('getCroppedCanvas', {
                        width: 620,
                        height: 520
                    }).toDataURL("image/png");

                    //$("#finalImg").prop("src", base64);// 显示图片
                    uploadFile(base64)//编码后上传服务器
                    //closeTailor();// 关闭裁剪框
                }
            });
        }
    }
    function dataURLtoFile(dataURL, fileName, fileType) {
        var arr = dataURL.split(','), mime = arr[0].match(/:(.*?);/)[1],
            bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
        while(n--){
            u8arr[n] = bstr.charCodeAt(n);
        }
        return new File([u8arr], fileName, {type:fileType || 'image/png'});
    }
    //ajax请求上传
    function uploadFile(file) {
        var oData = new FormData();
        var nameImg=new Date().getTime()+".png";
        var ff=dataURLtoFile(file,nameImg);
        oData.append("file", ff);
        $.ajax({
            url : "{pigcms{:U('Shop/ajax_upload_pic', array('store_id' => $now_store['store_id']))}",
            type: "post",
            dataType:"json",
            data : oData,
            processData: false,
            contentType: false,
            async : true,
            success : function(data) {
                if(data.error == 0){$upload_image_box.hide();
                    $('#upload_pic_ul').append('<li class="upload_pic_li"><img src="'+data.url+'"/><input type="hidden" name="pic[]" value="'+data.title+'"/><br/><a href="#" onclick="deleteImage(\''+data.title+'\',this);return false;">[ {pigcms{:L('DELETE_BKADMIN')} ]</a></li>');
                }else{
                    alert(data.info);
                }
            },
            error:function(data){
                $('.loading'+file.id).remove();
                alert('Upload failed! Please try again.');
            }
        });
    }

    $(document).ready(function(){
        $("#inputImage").on("click", function () {
            load_cooper();
        });
        // $("#download").click(function (link) {
        //     link.target.href = $cropped.cropper('getCroppedCanvas', {
        //         width: 620,
        //         height: 520
        //     }).toDataURL("image/png").replace("image/png", "application/octet-stream");
        //     link.target.download = 'cropped.png';
        // });
        //----- 之前的 ------
    });

    //--------------------------------------  以上为Crooper段  --------------------------------------
</script>
<script>
var uploaderHas = false;
var diyVideo = "{pigcms{:U('Article/diyVideo')}";
$('#myTab li a').click(function(){
	if(uploaderHas == false && $(this).attr('href') == '#txtimage'){
		setTimeout(function(){
			var  uploader = WebUploader.create({
					auto: true,
					swf: '{pigcms{$static_public}js/Uploader.swf',
					server: "{pigcms{:U('Shop/ajax_upload_pic', array('store_id' => $now_store['store_id']))}",
					pick: {
						id:'#J_selectImage',
						multiple:false
					},
					accept: {
						title: 'Images',
						extensions: 'gif,jpg,jpeg,png',
						mimeTypes: 'image/gif,image/jpeg,image/jpg,image/png'
					}
				});
			uploader.on('fileQueued',function(file){
				if($('.upload_pic_li').size() >= 5){
					uploader.cancelFile(file);
					alert('最多上传5个图片！');
					return false;
				}
			});
			uploader.on('uploadSuccess',function(file,response){
				if(response.error == 0){
					$('#upload_pic_ul').append('<li class="upload_pic_li"><img src="'+response.url+'"/><input type="hidden" name="pic[]" value="'+response.title+'"/><br/><a href="#" onclick="deleteImage(\''+response.title+'\',this);return false;">[ {pigcms{:L('DELETE_BKADMIN')} ]</a></li>');
				}else{
					alert(response.info);
				}
			});

			uploader.on('uploadError', function(file,reason){
				$('.loading'+file.id).remove();
				alert('上传失败！请重试。');
			});

		},20);
		uploaderHas = true;
	}
});



var formathtml = new Array();
var format_value = new Array();
var json = '{pigcms{$now_goods['json']}', sortList = '{pigcms{$sort_list}', selectIds = '{pigcms{$select_ids}';
var category_list = '{pigcms{$category_list}', ajax_goods_properties = "{pigcms{:U('Shop/ajax_goods_properties')}";
var session_index = 'goods_{pigcms{$now_sort["sort_id"]}';

var uploadJson = "{pigcms{$config.site_url}/index.php?g=Index&c=Upload&a=editor_ajax_upload&upload_dir=group/content";
var cssPath = "{pigcms{$static_path}css/group_editor.css";
var upload_image = "{pigcms{:U('Shop/ajax_upload_pic', array('store_id' => $now_store['store_id']))}";
function deleteImage(path,obj){
	$.post("{pigcms{:U('Shop/ajax_del_pic')}",{path:path});
	$(obj).closest('.upload_pic_li').remove();
}

window.sessionStorage.setItem(session_index, json);
$(document).ready(function(){
	$('#freight_value1').change(function(){
		if ($(this).val() != 0) {
			$('input[name=freight_type][value=1]').attr("checked",'checked');
		} else {
			$('input[name=freight_type][value=0]').attr("checked",'checked');
		}
	});
});

var allergens_str = [];

$('.allergens_div').find('label').each(function () {
    $(this).click(function () {
        var c_name = $(this).attr('class');
        if(c_name.indexOf("label-primary") != -1){
            $(this).removeClass('label-primary');
            $(this).addClass('label-large');
            for (var i = 0; i < allergens_str.length; i++) {
                if (allergens_str[i] == $(this).data('id')) {
                    allergens_str.splice(i, 1);
                    break;
                }
            }
        }else {
            $(this).addClass('label-primary');
            $(this).removeClass('label-large');
            allergens_str.push($(this).data('id'));
        }

        $("input[name='allergens']").val(allergens_str.toString());
    });
});
</script>
<script type="text/javascript" src="{pigcms{$static_path}js/goods.js"></script>
<include file="Public:footer"/>
