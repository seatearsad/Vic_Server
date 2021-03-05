<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>{pigcms{:L('_B_PURE_MY_05_')}</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no,viewport-fit=cover">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name='apple-touch-fullscreen' content='yes'>
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <meta name="format-detection" content="address=no">
    <link href="{pigcms{$static_path}css/eve.7c92a906.peter.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?215"/>
    <script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
    <style>
        .btn-wrapper {
            margin: .2rem .2rem;
            padding: 0;
        }

        dd>label.react {
            padding: .28rem .2rem;
        }

        /*input:-webkit-autofill, textarea:-webkit-autofill, select:-webkit-autofill{*/
        /*-webkit-box-shadow: 0 0 0px 1000px #ff2c4c inset*/
        /*}*/
        .kv-line h6 {
            width: 8em;
            text-align: right;
            padding-left: 10px;
        }

        .btn {
            background: #ffa52d;
            margin: auto;
        }
        dl.list-in dd {
            border-bottom: 1px dashed #e5e5e5;
        }
        dl.list dd dl > .dd-padding, dl.list dd dl dd > .react, dl.list dd dl > dt {
            padding-left: 1px;
        }
        .btn-block {
            width: 95%;
        }
        .main{
            width: 100%;
            padding-top: 60px;
            max-width: 640px;
            min-width: 320px;
            margin: 0 auto;
        }
        .gray_line{
            width: 100%;
            height: 2px;
            margin-top: 15px;
            margin-bottom: 15px;
            background-color: #cccccc;
        }
        .this_nav{
            width: 100%;
            text-align: center;
            font-size: 1.8em;
            height: 30px;
            line-height: 30px;
            margin-top: 15px;
            position: relative;
        }
        .this_nav span{
            width: 50px;
            height: 30px;
            display:-moz-inline-box;
            display:inline-block;
            -moz-transform:scaleX(-1);
            -webkit-transform:scaleX(-1);
            -o-transform:scaleX(-1);
            transform:scaleX(-1);
            background-image: url("./tpl/Static/blue/images/new/black_arrow.png");
            background-size: auto 20px;
            background-repeat: no-repeat;
            background-position: right center;
            position: absolute;
            left: 8%;
            cursor: pointer;
        }
        input.mt[type="checkbox"]:checked{
            background-color: #ffa52d;
        }
        .address_text{
            display: block;
            font-size: 16px;
            color: #555555;
            width: 100%;
            background: white;
            padding: 10px 5px 10px 8px;
            border-radius: 5px;
        }
    </style>
    <include file="Public:facebook"/>
</head>
<body id="index" data-com="pagecommon">
<include file="Public:header"/>
<div class="main">

    <div id="tips" class="tips"></div>
    <form id="form" method="post" action="{pigcms{:U('My/edit_adress')}" class="detail_block">
        <dl class="list">
            <dd>
                <dl>
                    <dd class="dd-padding kv-line">
                        <input name="name" type="text" class="kv-v input-weak" placeholder="{pigcms{:L('_B_PURE_MY_07_')}" pattern=".{2,}" oninvalid="setCustomValidity('{pigcms{:L(\'_B_PURE_MY_08_\')}')" data-err="{pigcms{:L('_B_PURE_MY_08_')}" value="{pigcms{$now_adress.name}">
                    </dd>
                    <dd class="dd-padding kv-line">
                        <input name="phone" type="tel" class="kv-v input-weak" placeholder="{pigcms{:L('_B_PURE_MY_10_')}" pattern="\d{3}[\d\*]{4,}" oninvalid="setCustomValidity('{pigcms{:L(\'_B_PURE_MY_11_\')}')" data-err="{pigcms{:L('_B_PURE_MY_11_')}" value="{pigcms{$now_adress.phone}">
                    </dd>
                    <!--dd class="dd-padding kv-line">
                        <h6>{pigcms{:L('_B_PURE_MY_12_')}:</h6>
                        <label class="select kv-v">
                            <select name="province">
                                <if condition="$now_adress">
                                    <volist name="province_list" id="vo">
                                        <option value="{pigcms{$vo.area_id}" <if condition="$vo['area_id'] eq $now_adress['province']">selected="selected"</if>>{pigcms{$vo.area_name}</option>
                                    </volist>
                                <else/>
                                    <volist name="province_list" id="vo">
                                        <option value="{pigcms{$vo.area_id}" <if condition="$vo['area_id'] eq $now_city_area['area_pid']">selected="selected"</if>>{pigcms{$vo.area_name}</option>
                                    </volist>
                                </if>
                            </select>
                        </label>
                    </dd>
                    <dd class="dd-padding kv-line">
                        <h6>{pigcms{:L('_B_PURE_MY_13_')}:</h6>
                        <label class="select kv-v" style="line-height: 30px" id="city_name">
                            {pigcms{$now_adress['city_name']}
                        </label>
                        <input type="hidden" name="city" id="city_id" value="{pigcms{$now_adress['city']}">
                        <input type="hidden" name="province" id="province_id" value="{pigcms{$now_adress['province']}">
                    </dd-->
                    <!--dd class="dd-padding kv-line">
                        <h6>{pigcms{:L('_B_PURE_MY_14_')}:</h6>
                        <label class="select kv-v">
                            <select name="area">
                                <volist name="area_list" id="vo">
                                    <option value="{pigcms{$vo.area_id}"  <if condition="$vo['area_id'] eq $now_adress['area']">selected="selected"</if>>{pigcms{$vo.area_name}</option>
                                </volist>
                            </select>
                        </label>
                    </dd-->
                    <dd class="dd-padding kv-line" id="color-gray">
                        <i class="icon-location" data-node="icon"></i>
                        <span  class="color-gray address_text" data-node="addAddress">
                            <?php if(!empty($now_adress['adress'])): ?>
                                <?php echo $now_adress['adress']; ?>
                            <?php else : ?>
                                <span style="color:#999;"> {pigcms{:L('_B_PURE_MY_101_')}</span>
<!--                                <img src="{pigcms{$static_path}images/location.png" style=" width:25px; height:25px"/>-->
                            <?php endif; ?>
                        </span> <i class="right_arrow"></i>
                        <!--div class="weaksuggestion"> {pigcms{:L('_B_PURE_MY_16_')}<i class="toptriangle"></i> </div-->
                        <!--textarea name="adress" class="input-weak kv-v" placeholder="{pigcms{:L('_B_PURE_MY_17_')}" pattern="^.{5,60}$" data-err="{pigcms{:L('_B_PURE_MY_18_')}">{pigcms{$now_adress.adress}</textarea-->
                    </dd>
                    <dd class="dd-padding kv-line">
                        <textarea name="detail" type="text" class="multi-line kv-v input-weak" placeholder="{pigcms{:L('_B_PURE_MY_20_')}" data-err="{pigcms{:L('_B_PURE_MY_21_')}"  rows="3" >{pigcms{$now_adress.detail}</textarea>
                    </dd>
                    <!--		        		<dd class="dd-padding kv-line">-->
                    <!--		        			<input type="text" name="zipcode" class="input-weak kv-v" placeholder="{pigcms{:L('_B_PURE_MY_23_')}"  maxlength="6" value="<if condition="$now_adress['zipcode']">{pigcms{$now_adress.zipcode}</if>"/>-->
                    <!--		        		</dd>-->
                    <dd>
                        <label class="react">
                            <if condition="$from neq 'map'">
                                <input type="checkbox" name="default" value="1" class="mt"  <if condition="$now_adress['default']">checked="checked"</if>/>
                            <else />
                                <input type="checkbox" name="default" value="1" class="mt"  checked="checked"/>
                            </if>
                            {pigcms{:L('_B_PURE_MY_24_')}
                        </label>
                    </dd>
                </dl>
            </dd>
        </dl>
        <div class="btn-wrapper">
            <input type="hidden" name="adress_id" value="{pigcms{$now_adress.adress_id}"/>
            <input type="hidden" name="longitude" value="{pigcms{$now_adress.longitude}"/>
            <input type="hidden" name="latitude" value="{pigcms{$now_adress.latitude}"/>
            <input type="hidden" name="adress" value="{pigcms{$now_adress.adress}"/>
            <input type="hidden" name="city" value="{pigcms{$now_adress.city}"/>
            <input type="hidden" name="province" value="{pigcms{$now_adress.province}" />
            <button type="submit" class="btn btn-block btn-larger"><if condition="$now_adress">{pigcms{:L('_B_PURE_MY_25_')}<else/>{pigcms{:L('_B_PURE_MY_26_')}</if></button>

            <!--                <if condition="$now_adress"><button type="button" class="btn btn-block btn-larger" style=" background:#fff; color:#000; margin-top:.1rem" id="address_del">{pigcms{:L('_B_PURE_MY_27_')}</button></if>-->

        </div>
    </form>
    <script src="{pigcms{:C('JQUERY_FILE')}"></script>
    <script src="{pigcms{$static_path}js/jquery.cookie.js"></script>
    <script src="{pigcms{$static_path}js/common_wap.js"></script>
    <script src="{pigcms{$static_path}layer/layer.m.js"></script>
    <script>
        $(function(){
            // $("select[name='province']").change(function(){
            // 	show_city($(this).find('option:selected').attr('value'));
            // });
            // $("select[name='city']").change(function(){
            // 	show_area($(this).find('option:selected').attr('value'));
            // });
            $("#color-gray").click(function(){
                <?php if($_GET['from']!="map") { ?>

                var detail = new Object();
                detail.name = $('input[name="name"]').val();
                detail.province = $('input[name="province"]').val();
                //detail.area = $('input[name="area"]').val();
                detail.area = 0;
                detail.city = $('input[name="city"]').val();
                detail.defaul = $('input[name="default"]').val();
                detail.detail = $('input[name="detail"]').val();
                detail.zipcode = $('input[name="zipcode"]').val();
                detail.phone = $('input[name="phone"]').val();
                detail.id = $('input[name="adress_id"]').val();
                detail.city_name = $('#city_name').html();

                $.cookie("user_address", JSON.stringify(detail));
                location.href = "{pigcms{:U('My/adres_map', $params)}";
                <?php } ?>
            });


            $('#form').submit(function(){
                $('#tips').removeClass('tips-err').empty();
                var form_input = $(this).find("input[type='text'],input[type='tel'],textarea");
                $.each(form_input,function(i,item){
                    if($(item).attr('pattern')){
                        var re = new RegExp($(item).attr('pattern'));
                        if($(item).val().length == 0 || !re.test($(item).val())){
                            $('#tips').addClass('tips-err').html($(item).attr('data-err'));
                            return false;
                        }
                    }

                    if(i+1 == form_input.size()){
                        layer.open({type:2,content:"{pigcms{:L('_B_PURE_MY_28_')}"});
                        $.post($('#form').attr('action'),$('#form').serialize(),function(result){
                            layer.closeAll();
                            if(result.status == 1){
                                //return;
                            <if condition="$_GET['referer']">
                                window.location.href="{pigcms{$_GET.referer|htmlspecialchars_decode=###}";
                            <else/>
                                <?php if($_GET['from']!="map"){ ?>
                                window.location.href="{pigcms{:U('My/adress',$params)}";
                                <?php }else{ ?>
                                window.location.href="wap.php";
                                <?php } ?>
                            </if>
                            }else{
                                $('#tips').addClass('tips-err').html(result.info);
                            }
                        });
                    }
                });

                return false;
            });
        });
        function show_city(id){
            $.post("{pigcms{:U('My/select_area')}",{pid:id},function(result){
                result = $.parseJSON(result);
                if(result.error == 0){
                    var area_dom = '';
                    $.each(result.list,function(i,item){
                        area_dom+= '<option value="'+item.area_id+'">'+item.area_name+'</option>';
                    });
                    $("select[name='city']").html(area_dom);
                    show_area(result.list[0].area_id);
                }
            });
        }
        function show_area(id){
            $.post("{pigcms{:U('My/select_area')}",{pid:id},function(result){
                result = $.parseJSON(result);
                if(result.error == 0){
                    var area_dom = '';
                    $.each(result.list,function(i,item){
                        area_dom+= '<option value="'+item.area_id+'">'+item.area_name+'</option>';
                    });
                    $("select[name='area']").html(area_dom);
                }else{
                    $("select[name='area']").html('<option value="0">{pigcms{:L('_B_PURE_MY_29_')}</option>');
                }
            });
        }

        $('#address_del').click(function(){
            layer.open({
                content:"{pigcms{:L('_B_PURE_MY_30_')}",
                btn: ["{pigcms{:L('_B_PURE_MY_31_')}","{pigcms{:L('_B_PURE_MY_32_')}"],
                yes:function(){
                    var del_url = "{pigcms{:U('My/ajax_del_adress')}";
                    $.get(del_url,{'adress_id':"{pigcms{$now_adress['adress_id']}"},function(data){
                        if(data.status){
                            var address_url = "{pigcms{:U('My/adress')}";
                            location.href = address_url;
                        }
                    },'json')
                }
            });
        });

        $('#back_span').click(function () {
            window.history.go(-1);
        });
    </script>
</div>
<include file="Public:footer"/>
</body>
</html>