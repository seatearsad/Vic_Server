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
                    <a href="{pigcms{:U('Systemnews/index')}" style="text-decoration: underline">{pigcms{:L('I_ARTICLES')}</a>
                </li>
                <li class="breadcrumb-item active">
                    <strong>【{pigcms{$category_name}】 - Article List </strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-3" style="height 90px;margin-top:40px;">
            <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Systemnews/add_news',array('category_id'=>$_GET['category_id']))}','Add {pigcms{$category_name}',900,500,true,false,false,addbtn,'add',true);"><button type="button" class="btn btn-primary btn-sm float-right">Add {pigcms{$category_name}</button></a>
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
                            <div style="margin-bottom: 5px;min-height: 50px">
                                <div id="tool_bar" class="form-inline">
                                    <form action="{pigcms{:U('Systemnews/news')}" method="get">
                                        <input type="hidden" name="c" value="Systemnews"/>
                                        <input type="hidden" name="a" value="news"/>
                                        <input type="hidden" name="category_id" value="{pigcms{$category_id}"/>
                                        {pigcms{:L('F_FILTER')}:
                                        <input type="text" name="keyword" class="form-control" value="{pigcms{$_GET['keyword']}"/>
                                        <select name="searchtype" class="form-control">
                                            <option value="title" <if condition="$_GET['searchtype'] eq 'title'">selected="selected"</if>>{pigcms{:L('I_TITLE')}</option>
                                            <option value="id" <if condition="$_GET['searchtype'] eq 'id'">selected="selected"</if>>ID</option>
                                        </select>
                                        <input type="submit" value="{pigcms{:L('F_SEARCH')}" class="form-control"/>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <table class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                            <tr>
                                <th>{pigcms{:L('G_ID')}</th>
                                <th>{pigcms{:L('I_TITLE')}</th>
                                <th>{pigcms{:L('TIME_ADDED_BKADMIN')}</th>
                                <th>{pigcms{:L('_BACK_LAST_EDIT_TIME_')}</th>
                                <th>{pigcms{:L('I_LISTING_ORDER')}</th>
                                <th>{pigcms{:L('G_STATUS')}</th>
                                <th>{pigcms{:L('I_RECOMMENDED')}</th>
                                <th class="textcenter">{pigcms{:L('E_ACTION')}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <if condition="is_array($news_list)">
                                <volist name="news_list" id="vo">
                                    <tr>
                                        <td>{pigcms{$vo.id}</td>
                                        <td>{pigcms{$vo.title}</td>
                                        <td>{pigcms{$vo.add_time|date='Y-m-d H:i:s',###}</td>
                                        <td>{pigcms{$vo.last_time|date='Y-m-d H:i:s',###}</td>
                                        <td>{pigcms{$vo.sort}</td>
                                        <td>
                                            <if condition="$vo['status'] eq 1">
                                                <span class="label label-primary">{pigcms{:L('I_ENABLE1')}</span>
                                                <else/>
                                                <span class="label label-warning">{pigcms{:L('I_DISABLE3')}</span>
                                            </if>
                                        </td>
                                        <td>
                                            <if condition="$vo['is_commend'] eq 1">
                                                <span class="label label-primary">{pigcms{:L('I_RECOMMENDED_TOP')}</span>
                                                <else/>
                                                <span class="label label-warning">{pigcms{:L('I_RECOMMENDED_NORMAL')}</span>
                                            </if>
                                        </td>
                                        <td class="textcenter">
                                            <!--a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Systemnews/edit_news',array('id'=>$vo['id'],'frame_show'=>true))}','{pigcms{:L(\'BASE_VIEW\')}',1000,640,true,false,false,false,'add',true);">{pigcms{:L('BASE_VIEW')}</a-->
                                            <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Systemnews/edit_news',array('id'=>$vo['id']))}','{pigcms{:L(\'BASE_EDIT\')}',900,500,true,false,false,editbtn,'edit',true);"><button class="btn btn-white text-grey" type="button">{pigcms{:L('BASE_EDIT')}</button></a>
                                            <a href="javascript:void(0);" class="delete_row" parameter="id={pigcms{$vo.id}" url="{pigcms{:U('Systemnews/del',array('id'=>$vo['id']))}"><button class="btn btn-white text-grey" type="button">{pigcms{:L('BASE_DELETE')}</button></a>
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
<include file="Public:footer"/>