<include file="Public:header"/><div id="wrapper">    <include file="Public:left_menu"/>    <!----------------------------------------    以上不要写代码     ------------------------------------------------>    <div class="row wrapper border-bottom white-bg page-heading">        <div class="col-lg-9">            <h2>{pigcms{:L('J_STORE_QUALIFICATION_CHECK')}</h2>            <ol class="breadcrumb">                <li class="breadcrumb-item">                    {pigcms{:L('_BACK_MERCHANTMNG_')}                </li>                <!--                <li class="breadcrumb-item">-->                <!--                    <a>UI Elements</a>-->                <!--                </li>-->                <li class="breadcrumb-item active">                    <strong>{pigcms{:L('J_STORE_QUALIFICATION_CHECK')}</strong>                </li>            </ol>        </div>        <div class="col-lg-3" style="height 90px;margin-top:40px;">        </div>    </div>    <div class="wrapper wrapper-content animated fadeInRight">        <div class="row">            <div class="col-lg-12">                <div class="ibox ">                    <div class="ibox-title tutti_hidden_obj">                        <h5>{pigcms{:L('_BACK_DELIVERY_LIST_')}</h5>                        <div class="ibox-tools">                        </div>                    </div>                    <div class="ibox-content">                        <div class="table-responsive">                            <!-------------------------------- 工具条 -------------------------------------->                            <div style="height: 0px;">                            </div>                            <!-------------------------------- 工具条 -------------------------------------->                        </div>                        <table class="table table-striped table-bordered table-hover dataTables-example">                            <thead>                            <tr>                                <th>{pigcms{:L('G_ID')}</th>                                <th>{pigcms{:L('E_STORE_NAME')}</th>                                <th>{pigcms{:L('E_CONTACT_NUMBER1')}</th>                                <th>{pigcms{:L('E_STORE_ADDRESS')}</th>                                <th>{pigcms{:L('J_CONTACTS_NAME')}</th>                                <th>{pigcms{:L('E_CONTACT_EMAIL')}</th>                                <th>{pigcms{:L('J_APPLICATION_STARTED')}</th>                            </tr>                            </thead>                            <tbody>                            <if condition="is_array($store_list)">                                <volist name="store_list" id="vo">                                    <tr>                                        <td>{pigcms{$vo.id}</td>                                        <td>{pigcms{$vo.store_name}</td>                                        <td>{pigcms{$vo.phone}</td>                                        <td>{pigcms{$vo.store_address}</td>                                        <td>{pigcms{$vo.first_name} {pigcms{$vo.last_name}</td>                                        <td>{pigcms{$vo.email}</td>                                        <td>{pigcms{$vo.create_time}</td>                                    </tr>                                </volist>                                <tr><td class="textcenter pagebar" colspan="7">{pigcms{$pagebar}</td></tr>                                <else/>                                <tr><td class="textcenter red" colspan="7">{pigcms{:L('_BACK_EMPTY_')}</td></tr>                            </if>                            </tbody>                            <tfoot>                            <tr>                            </tr>                            </tfoot>                        </table>                    </div>                </div>            </div>        </div><include file="Public:footer"/>