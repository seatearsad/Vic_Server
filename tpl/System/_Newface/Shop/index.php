<include file="Public:header"/><div id="wrapper">    <include file="Public:left_menu"/>    <!----------------------------------------    以上不要写代码     ------------------------------------------------>    <div class="row wrapper border-bottom white-bg page-heading">        <div class="col-lg-9">            <h2>{pigcms{:L('C_CATEGORYLIST')}</h2>            <ol class="breadcrumb">                <li class="breadcrumb-item">                    <a href="{pigcms{:U('Index/index')}">Home</a>                </li>                <!--                <li class="breadcrumb-item">-->                <!--                    <a>UI Elements</a>-->                <!--                </li>-->                <li class="breadcrumb-item active">                    <strong>{pigcms{:L('C_CATEGORYLIST')}</strong>                </li>            </ol>        </div>        <div class="col-lg-3" style="height 90px;margin-top:40px;">            <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Shop/cat_add', array('parentid' => $parentid))}','<if condition="               $category">{pigcms{:L(\'C_ADDSUBCATE\')}<else/>{pigcms{:L(\'C_ADDCATEGORY\')}</if>',650,360,true,false,false,addbtn,'add',true);"><if condition="$category"><button type="button" class="btn btn-primary btn-sm float-right">{pigcms{:L('C_ADDSUBCATE')}</button><else /><button type="button" class="btn btn-primary btn-sm float-right">{pigcms{:L('C_ADDCATEGORY')}</button></if></a>        </div>    </div>    <div class="wrapper wrapper-content animated fadeInRight">        <div class="row">            <div class="col-lg-12">                <div class="ibox ">                    <div class="ibox-title tutti_hidden_obj">                        <h5>{pigcms{:L('C_CATEGORYLIST')}</h5>                        <div class="ibox-tools">                        </div>                    </div>                    <div class="ibox-content">                        <div class="table-responsive">                            <!-------------------------------- 工具条 -------------------------------------->                            <div style="height: 50px;">                                <div id="tool_bar" class="form-inline tutti_toolbar">                                <if condition="$system_session['level'] neq 3 and $parentid eq 0">                                    City {pigcms{$city_id}: &nbsp;                                    <select name="city_select" id="city_select" class="form-control">                                        <option value="-1" <if condition="$city_id eq -1">selected="selected"</if>>All</option>                                        <option value="0"  <if condition="$city_id eq 0 ">selected="selected"</if>>{pigcms{:L('_BACK_Universial_INFO_')}</option>                                        <volist name="city" id="vo">                                            <option value="{pigcms{$vo.area_id}" <if condition="$city_id eq $vo['area_id']">selected="selected"</if>>{pigcms{$vo.area_name}</option>                                        </volist>                                    </select>                                </if>                                </div>                            </div>                            <table class="table table-striped table-bordered table-hover dataTables-example">                                <thead>                                <tr>                                    <th>{pigcms{:L('C_CATEGORYID')}</th>                                    <th>{pigcms{:L('C_CATEGORYORDER')}</th>                                    <th>{pigcms{:L('C_CATEGORYNAME')}</th>                                    <th>{pigcms{:L('C_CATEGORYURL')}</th>                                    <if condition="empty($parentid)">                                        <th>{pigcms{:L('C_VIEWSUB')}</th>                                        <else/>                                        <!--th>表单填写项</th-->                                    </if>                                    <th>{pigcms{:L('C_CATEGORYSTAT')}</th>                                    <th>{pigcms{:L('BASE_TYPE')}</th>                                    <th>{pigcms{:L('BASE_CITY')}</th>                                    <th>{pigcms{:L('C_CATEGORYNUM')}</th>                                    <th>{pigcms{:L('C_CATEGORYSCS')}</th>                                    <th class="textcenter">{pigcms{:L('B_ACTION')}</th>                                </tr>                                </thead>                                <tbody>                                <if condition="is_array($category_list)">                                    <volist name="category_list" id="vo">                                        <tr class="gradeX">                                            <td>{pigcms{$vo.cat_id}</td>                                            <td>{pigcms{$vo.cat_sort}</td>                                            <td>{pigcms{$vo.cat_name}</td>                                            <td>{pigcms{$vo.cat_url}</td>                                            <if condition="empty($parentid)">                                                <td><a href="{pigcms{:U('Shop/index',array('parentid'=>$vo['cat_id']))}">{pigcms{:L('C_VIEWSUB')}</a></td>                                                <else/>                                                <!--td><a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('cue_field',array('cat_id'=>$vo['cat_id']))}','表单填写项',580,420,true,false,false,false,'detail',true);">表单填写项</a></td-->                                            </if>                                            <td>                                                <if condition="$vo['cat_status'] eq 1">                                                    <span class="label label-primary">{pigcms{:L('C_CATEGORYSEN')}</span>                                                    <elseif condition="$vo['cat_status'] eq 2"/>                                                    <span class="label label-warning">待审核</span>                                                    <else/>                                                    <span class="label label-warning">{pigcms{:L('C_CATEGORYDIS')}</span>                                                </if>                                            </td>                                            <td>                                                <if condition="$vo['cat_type'] eq 0">                                                    <span class="label label-primary">{pigcms{:L('C_CATEGORYNOR')}</span>                                                    <else/>                                                    <span class="label label-warning">{pigcms{:L('C_CATEGORYFT')}</span>                                                </if>                                            </td>                                            <td>                                                <if condition="$vo['city_id'] eq 0">                                                    <font color="gray">{pigcms{$vo['city_name']}</font>                                                    <else/>                                                    <font color="green">{pigcms{$vo['city_name']}</font>                                                </if>                                            </td>                                            <td>                                                <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Shop/cat_store',array('cat_id'=>$vo['cat_id'], 'parentid'=>$vo['cat_fid']))}','{pigcms{:L(\'J_CATEGORIZED_STORES\')}',480,360,true,false,false,editbtn,'edit',true);">                                                    {pigcms{$vo.store_num}                                                </a>                                            </td>                                            <td><if condition="$vo['show_method'] eq 0"><font color="green">{pigcms{:L('C_CATEGORYSCS1')}</font><elseif condition="$vo['show_method'] eq 1"/><font color="red">{pigcms{:L('C_CATEGORYSCS2')}</font><else/><font color="red">{pigcms{:L('C_CATEGORYSCS3')}</font></if></td>                                            <td class="textcenter">                                                <!--a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Shop/cat_edit',array('cat_id'=>$vo['cat_id'],'frame_show'=>true))}','{pigcms{:L(\'BASE_VIEW\')}',650,360,true,false,false,false,'detail',true);"><button class="btn btn-white text-grey" type="button">{pigcms{:L('BASE_VIEW')}</button></a-->                                                <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Shop/cat_edit',array('cat_id'=>$vo['cat_id'], 'parentid'=>$vo['cat_fid']))}','{pigcms{:L(\'BASE_EDIT\')}',650,360,true,false,false,editbtn,'edit',true);"><button class="btn btn-white text-grey" type="button">{pigcms{:L('BASE_EDIT')}</button></a>                                                <a href="javascript:void(0);" class="delete_row" parameter="cat_id={pigcms{$vo.cat_id}" url="{pigcms{:U('Shop/cat_del')}"><button class="btn btn-white text-grey" type="button">{pigcms{:L('BASE_DELETE')}</button></a>                                                <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Shop/cat_service',array('cat_id'=>$vo['cat_id'], 'parentid'=>$vo['cat_fid']))}','{pigcms{:L(\'C_CATESF\')}',650,260,true,false,false,editbtn,'edit',true);"><button class="btn btn-white text-grey" type="button">{pigcms{:L('BASE_SERVICEFEE')}</button></a>                                                <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Shop/cat_pay',array('cat_id'=>$vo['cat_id'], 'parentid'=>$vo['cat_fid']))}','{pigcms{:L(\'C_CATE3DS\')}',650,260,true,false,false,editbtn,'edit',true);"><button class="btn btn-white text-grey" type="button">{pigcms{:L('BASE_ENCRYPTION')}</button></a>                                            </td>                                        </tr>                                    </volist>                                    <else/>                                    <tr>                                        <td class="textcenter red" colspan="11">{pigcms{:L('_BACK_EMPTY_')}</td>                                    </tr>                                </if>                                </tbody>                                <tfoot>                                <tr>                                </tr>                                </tfoot>                            </table>                        </div>                    </div>                </div>            </div>        </div>    </div><script>    var city_id = $('#city_select').val();    $('#city_select').change(function () {        city_id = $(this).val();        window.location.href = "{pigcms{:U('Shop/index')}" + "&city_id="+city_id;    });</script><include file="Public:footer"/>