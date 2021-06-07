<include file="Public:header"/>
<div id="wrapper">
    <include file="Public:left_menu"/>
    <!----------------------------------------    以上不要写代码     ------------------------------------------------>

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-9">
            <h2>{pigcms{:L('I_ABOUT_US')}</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{pigcms{:U('Index/index')}">Home</a>
                </li>
                <!--                <li class="breadcrumb-item">-->
                <!--                    <a>UI Elements</a>-->
                <!--                </li>-->
                <li class="breadcrumb-item active">
                    <strong>{pigcms{:L('I_ABOUT_US')}</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-3" style="height 90px;margin-top:40px;">
            <a href="javascript:void(0);" onclick="winCourier Monitoringdow.top.artiframe('{pigcms{:U('Appintro/add')}','{pigcms{:L(\'I_ADD_INFORMATION\')}',800,460,true,false,false,addbtn,'add',true);"><button type="button" class="btn btn-primary btn-sm float-right">{pigcms{:L('I_ADD_INFORMATION')}</button></a>
        </div>
    </div>
    <div class="row wrapper wrapper-content animated fadeInRight">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-content">
                    <table class="table table-striped table-bordered table-hover dataTables-example">
                        <thead>
                        <tr>
                            <th>{pigcms{:L('G_ID')}</th>
                            <th>{pigcms{:L('G_NAME')}</th>
                            <th class="textcenter">{pigcms{:L('E_ACTION')}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <if condition="is_array($intro)">
                            <volist name="intro" id="vo">
                                <tr>
                                    <td>{pigcms{$vo.id}</td>
                                    <td>{pigcms{$vo.title}</td>
                                    <td class="textcenter">
                                        <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Appintro/edit',array('id'=>$vo['id']))}','{pigcms{:L(\'BASE_EDIT\')}',800,460,true,false,false,editbtn,'edit',true);"><button class="btn btn-white text-grey" type="button">{pigcms{:L('BASE_EDIT')}</button></a>
                                        <a href="javascript:void(0);" class="delete_row" parameter="id={pigcms{$vo.id}" url="{pigcms{:U('Appintro/del')}"><button class="btn btn-white text-grey" type="button">{pigcms{:L('BASE_DELETE')}</button></a>
                                    </td>
                                </tr>
                            </volist>
                            <tr><td class="textcenter pagebar" colspan="4">{pigcms{$pagebar}</td></tr>
                            <else/>
                            <tr><td class="textcenter red" colspan="4">{pigcms{:L('_BACK_EMPTY_')}</td></tr>
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
<include file="Public:footer"/>