<include file="Public:header"/>
<div id="wrapper">
    <include file="Public:left_menu"/>
    <!----------------------------------------    以上不要写代码     ------------------------------------------------>

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-6">
            <h2>{pigcms{:L('_BACK_BAG_LIST_TITLE_')}</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    {pigcms{:L('_BACK_DLVMNG_')}
                </li>
                <!--                <li class="breadcrumb-item">-->
                <!--                    <a>UI Elements</a>-->
                <!--                </li>-->
                <li class="breadcrumb-item active">
                    <strong>{pigcms{:L('_BACK_BAG_LIST_TITLE_')}</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-6 " style="height 90px;margin-top:40px;">
            <div class="btn-group float-right">
                <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Deliver/bag_add')}','{pigcms{:L(\'_BACK_BAG_LIST_ADD_\')}',880,760,true,false,false,editbtn,'edit',true);" style="float:right;margin-left: 10px;"><button class="btn btn-primary">{pigcms{:L('_BACK_BAG_LIST_ADD_')}</button></a>
            </div>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title tutti_hidden_obj">
                        <h5>{pigcms{:L('_BACK_DELIVERY_LIST_')}</h5>
                        <div class="ibox-tools">

                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="table-responsive">
                            <!-------------------------------- 工具条 -------------------------------------->
                            <table class="table table-striped table-bordered table-hover dataTables-example">
                                <thead>
                                <tr>
                                    <th>Bag ID</th>
                                    <th>Bag Name</th>
                                    <th>Price</th>
                                    <th>Description</th>
                                    <th class="textcenter">{pigcms{:L('_BACK_CZ_')}</th>
                                    <th class="textcenter"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <if condition="is_array($bag_list)">
                                    <volist name="bag_list" id="vo">
                                        <tr>
                                            <td>{pigcms{$vo.bag_id}</td>
                                            <td>{pigcms{$vo.bag_name}</td>
                                            <td>{pigcms{$vo.bag_price}</td>
                                            <td>{pigcms{$vo.phone}-{pigcms{$vo.bag_switch}</td>
                                            <td>
                                                <div style="width: 120px;" data-id="{pigcms{$vo.bag_id}" >
                                                        <span class="cb-enable cb-enableex"><label id="cat_type_0" class="cb-enable <if condition="$vo['bag_switch'] eq 1">selected</if>"><span>ON</span>
                                                        <input type="radio" class="cat_type"  name="cat_type" value="1" <if condition="$vo['bag_switch'] eq 1">checked="checked"</if> /></label></span>

                                                        <span class="cb-disable cb-disableex"><label id="cat_type_1" class="cb-disable <if condition="$vo['bag_switch'] eq 0">selected</if>"><span>OFF</span>
                                                        <input type="radio" class="cat_type"   name="cat_type" value="0" <if condition="$vo['bag_switch'] eq 0">checked="checked"</if> /></label></span>
                                                </div>
                                            </td>
                                            <td class="textcenter">　
                                                <a href="javascript:void(0);"
                                                   onclick="window.top.artiframe('{pigcms{:U('Deliver/bag_edit',array('bag_id'=>$vo['bag_id']))}','{pigcms{:L(\'_BACK_EDIT_COURIER_\')}',880,760,true,false,false,editbtn,'edit',true);">
                                                    <button class="btn btn-white text-grey" type="button">
                                                        {pigcms{:L('_BACK_EDIT_')}
                                                    </button>
                                                </a>
                                            </td>
                                        </tr>
                                    </volist>
                                    <tr>
                                        <td class="textcenter pagebar" colspan="10">{pigcms{$pagebar}</td>
                                    </tr>
                                    <else/>
                                    <tr>
                                        <td class="textcenter red" colspan="10">{pigcms{:L('_BACK_EMPTY_')}</td>
                                    </tr>
                                </if>
                                </tbody>
                                <tfoot>
                                <tr>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(function() {
                $('.cb-enableex').click(function () {
                    bid = $(this).parent().attr("data-id");
                    console.log("data-id==" + bid);
                    SetBagSwitch(bid, 1);
                });
                $('.cb-disableex').click(function () {
                    bid = $(this).parent().attr("data-id");
                    console.log("data-id=" + bid);
                    SetBagSwitch(bid, 0)
                });
            });
            function SetBagSwitch(bid,status){
                $.ajax({
                    //url:"{pigcms{:U('Shop/change_mall')}",
                    url:"{pigcms{:U('Deliver/change_switch')}",
                    type:"post",
                    data:{"switch":status,"bid":bid},
                    dataType:"text",
                    success:function(d){
                        if(d != '1'){		//失败
                            // if(status=='1'){
                            //     _this.attr("checked",false);
                            // }else{
                            //     _this.attr("checked",true);
                            // }
                            alert("操作失败");
                        }
                        // _this.attr("disabled",false);
                    }
                });
            }

            var city_id = $('#city_select').val();
            $('#city_select').change(function () {
                // city_id = $(this).val();
                // window.location.href = "{pigcms{:U('Deliver/review')}" + "&city_id="+city_id;
            });
        </script>
        <include file="Public:footer"/>