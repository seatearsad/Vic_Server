<include file="Public:header"/>
<div id="wrapper">
    <include file="Public:left_menu"/>
    <!----------------------------------------    以上不要写代码     ------------------------------------------------>

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-9">
            <h2>{pigcms{:L('I_ARTICLES')}</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    {pigcms{:L('G_SYSTME_SETTINGS')}
                </li>
                <!--                <li class="breadcrumb-item">-->
                <!--                    <a>UI Elements</a>-->
                <!--                </li>-->
                <li class="breadcrumb-item active">
                    <strong>{pigcms{:L('I_ARTICLES')}</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-3" style="height 90px;margin-top:40px;">
            <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Systemnews/add_category')}','{pigcms{:L(\'I_ADD_ARTCAT\')}',800,460,true,false,false,addbtn,'add',true);"><button type="button" class="btn btn-primary btn-sm float-right">{pigcms{:L('I_ADD_CATEGORY')}</button></a>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title tutti_hidden_obj">
                        <h5>{pigcms{:L('I_ARTICLES')}</h5>
                        <div class="ibox-tools">

                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="table-responsive">
                            <!-------------------------------- 工具条 -------------------------------------->
                            <div style="margin-bottom: 15px;min-height: 55px">
                                <div id="tool_bar" class="form-inline">
                                    {pigcms{:L('I_GENERAL_CATEGORY')}：
                                    <select name="all_type" id="select_type" class="form-control">
                                        <option value="-1">All</option>
                                        <volist name="all_type" id="type">
                                            <option value="{pigcms{$key}" <if condition="$key eq $select_type">selected</if>>{pigcms{$type}</option>
                                        </volist>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <table class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                            <tr>
                                <th>{pigcms{:L('G_ID')}</th>
                                <th>{pigcms{:L('I_GENERAL_CATEGORY')}</th>
                                <th>{pigcms{:L('C_CATEGORYNAME')}</th>
                                <th>{pigcms{:L('I_CONTENT_LIST')}</th>
                                <th>{pigcms{:L('I_LISTING_ORDER')}</th>
                                <th>{pigcms{:L('G_STATUS')}</th>
                                <th class="textcenter">{pigcms{:L('E_ACTION')}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <if condition="is_array($category)">
                                <volist name="category" id="vo">
                                    <tr>
                                        <td>{pigcms{$vo.id}</td>
                                        <td>{pigcms{$all_type[$vo['type']]}</td>
                                        <td>{pigcms{$vo.name}</td>
                                        <td><a href="{pigcms{:U('Systemnews/news',array('category_id'=>$vo['id']))}">{pigcms{:L('I_VIEW_CONTENT')}({pigcms{$vo.count})</a></td>
                                        <td>{pigcms{$vo.sort}</td>
                                        <td>
                                            <if condition="$vo['status'] eq 1">
                                                <span class="label label-primary">{pigcms{:L('I_ACTIVE')}</span>
                                                <else/>
                                                <span class="label label-warning">{pigcms{:L('_BACK_FORBID_')}</span>
                                            </if>
                                        </td>
                                        <td class="textcenter">
                                            <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Systemnews/edit_category',array('id'=>$vo['id']))}','{pigcms{:L(\'BASE_EDIT\')}',800,460,true,false,false,editbtn,'edit',true);"><button class="btn btn-white text-grey" type="button">{pigcms{:L('BASE_EDIT')}</button></a>
                                            <a href="javascript:void(0);" class="delete_row" parameter="category_id={pigcms{$vo.id}" url="{pigcms{:U('Systemnews/del',array('category_id'=>$vo['id']))}"><button class="btn btn-white text-grey" type="button">{pigcms{:L('BASE_DELETE')}</button></a>
                                        </td>
                                    </tr>
                                </volist>
                                <tr><td class="textcenter pagebar" colspan="9">{pigcms{$pagebar}</td></tr>
                                <else/>
                                <tr><td class="textcenter red" colspan="9">{pigcms{:L('_BACK_EMPTY_')}</td></tr>
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
<script>
    $('#select_type').change(function () {
        var type = $(this).val();
        if(type != -1)
            location.href = "{pigcms{:U('Systemnews/index')}&type="+type;
        else
            location.href = "{pigcms{:U('Systemnews/index')}";
    });
</script>
<include file="Public:footer"/>