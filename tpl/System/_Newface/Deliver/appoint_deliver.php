<include file="Public:header"/>
<body style="background-color: #fff;">
<div id="wrapper-singlepage">
    <div id="page-wrapper-singlepage" class="white-bg">
        <!----------------------------------------    以上不要写代码     ------------------------------------------------>
        <div class="row wrapper wrapper-content animated fadeInRight">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-content">
                        <form id="myform" method="post" action="" frame="true" refresh="true">
                            <table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
                                <tr>
                                    <th width="90">Name</th>
                                    <th width="90">Phone</th>
                                    <!-- 				<th width="90">常驻地址距离</th> -->
                                    <th width="90">Distance</th>
                                    <th width="90">Click</th>
                                </tr>
                                <volist name="users" id="row">
                                    <tr>
                                        <if condition="$row['work_status'] eq 0">
                                            <th width="90">{pigcms{$row['name']}</th>
                                            <th width="90">{pigcms{$row['phone']}</th>
                                            <!-- 				<th width="90">{pigcms{$row['range']}</th> -->
                                            <th width="90">{pigcms{$row['now_range']}</th>
                                            <else/>
                                            <td width="90">{pigcms{$row['name']}</td>
                                            <td width="90">{pigcms{$row['phone']}</td>
                                            <!-- 				<th width="90">{pigcms{$row['range']}</th> -->
                                            <td width="90">{pigcms{$row['now_range']}</td>
                                        </if>
                                        <td><input type="radio" name="uid" value="{pigcms{$row['uid']}"/></td>
                                    </tr>
                                </volist>
                            </table>
                            <div class="btn tutti_hidden_obj">
                                <input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button"/>
                                <input type="reset" value="取消" class="button"/>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <include file="Public:footer_inc"/>