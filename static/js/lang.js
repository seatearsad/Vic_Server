/*
Garfunkel Add
setCookie
2018-07-13
 */

function setCookie(c_name,value,expiredays)
{
    var exdate=new Date();
    exdate.setDate(exdate.getDate()+expiredays);
    document.cookie=c_name+ "=" +escape(value)+
        ((expiredays==null) ? "" : ";expires="+exdate.toGMTString());
}

$('.lang_div').hover(function () {
    $(this).children('.lang_select').show();
},function () {
    $(this).children('.lang_select').hide();
})

$('.lang_curr_wap').click(function () {
    // alert($(this).children('.lang_select').is);

    if($('.lang_select').is(':hidden')){
        $(this).css('border-bottom','1px dashed #ffffff');
        $('.lang_select').show();
    }
    else{
        $('.lang_select').hide();
        $(this).css('border-bottom','0px');
    }
})

$('.lang_cn').click(function(){
    setCookie('lang','zh-cn',30);
    window.location.reload();
})

$('.lang_en').click(function () {
    setCookie('lang','en-us',30);
    window.location.reload();
})
