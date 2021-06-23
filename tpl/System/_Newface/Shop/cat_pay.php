<include file="Public:header"/>
<body style="background-color: #fff;">
<div id="wrapper-singlepage">
    <div id="page-wrapper-singlepage" class="white-bg">
        <!----------------------------------------    以上不要写代码     ------------------------------------------------>
        <div class="row wrapper wrapper-content animated fadeInRight">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-content">
                        <form id="myform" method="post" action="{pigcms{:U('Shop/cat_pay')}" frame="true" refresh="true" onclick="return check()">
                            <input type="hidden" name="cat_id" value="{pigcms{$now_category.cat_id}"/>
                            <input type="hidden" name="cat_fid" value="{pigcms{$parentid}"/>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('C_CATEGORYNAME')}</label>
                                <div class="col-sm-9 col-form-label">
                                    {pigcms{$now_category.cat_name}
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('BASE_ENCRYPTION')}</label>
                                <div class="col-sm-9 col-form-label">
                                    <span class="cb-enable"><label class="cb-enable "><span>{pigcms{:L('C_ENCRYPTION1')}</span><input type="radio" name="pay_secret" value="1"/></label></span>
                                    <span class="cb-disable"><label class="cb-disable "><span>{pigcms{:L('C_ENCRYPTION2')}</span><input type="radio" name="pay_secret" value="0" /></label></span>
                                </div>
                            </div>
                            <div class="btn tutti_hidden_obj">
                                <input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
                                <input type="reset" value="取消" class="button" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script>
            var let_go=false;
            $(".cb-enable").click(function () {
                let_go=true;
                //$(".city_list").hide();
            });
            $(".cb-disable").click(function () {
                let_go=true;
                //$(".city_list").hide();
            });

            // $("form").on("submit",function(event){
            //     alert("submit");
            //     event.preventDefault();return false;
            //     if(/*验证通过*/1){
            //
            //     }else{
            //         event.preventDefault();
            //         return false;
            //     }
            // })

            function check(){//function check(f)对应下面程序中的this。f代表表单信息
                //var e = f.email.value;
                //var e = document.myform.email.value;//可以直接用document获取表单指定内容
                //document.write(e);
                if(let_go) {
                    return true;
                }else{
                    alert("Please select 3D Payment options");
                    return false;
                }
            }

            $(function () {

                if ($('input[name="pay_secret"]:checked').val() == 1) {
                    //$('.sub_mch').show();
                    console.log("1111");
                } else {
                    //$('.sub_mch').hide();
                    console.log("0000");
                }

            });
        </script>
<include file="Public:footer_inc"/>