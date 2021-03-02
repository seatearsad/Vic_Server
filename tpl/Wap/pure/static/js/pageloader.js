var a_window_width = $(window).width();
var a_window_height = $(window).height();
function pageLoadTips(options){
    this.options = {
        showBg:true,
        top:'center',
        left:'center'
    }
    for (var i in options){
        this.options[i] = options[i];
    }
    options = this.options;
    //显示背景
    if(options.showBg){
        $('#pageLoadTipShade').css('background','rgba(216,216,216,0.5)').removeClass('nobg');
    }else{
        $('#pageLoadTipShade').addClass('nobg');
    }
    //显示顶边
    if(options.top == 'center'){
        options.top = (a_window_height-120)/2;
    }
    //显示顶边
    if(options.left == 'center'){
        options.left = (a_window_width-120)/2;
    }
    $('#pageLoadTipBox').css({'top':options.top+'px','left':options.left+'px'});
    $('#pageLoadTipShade').css({'height':$(window).height(),'width':$(window).width()}).show();
}

function pageLoadHides(){
    $('#pageLoadTipShade').hide();
}