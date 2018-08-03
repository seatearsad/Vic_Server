var lang = new Array();
lang['_SALE_NUM_ORDER_'] = '已售%s单';
lang['_MONTH_SALE_NUM_'] = '月售%s单';
lang['_EVENT_NUM_'] = '%s个活动';
lang['_DISCOUNT_NUM_'] = '%s折';
lang['_EVERY_FULL_'] = '每满%s';
lang['_REDUCE_NUM_'] = '减%s元';
lang['_CLOSEST_ME_'] = '离我最近';
lang['_BEING_POSITION_'] = '正在定位';
lang['_MIN_DELI_PRICE_'] = '起送价';
lang['_DELI_PRICE_'] = '配送费';
lang['_PACK_PRICE_'] =  '打包费';
lang['_DEIL_NUM_MIN_'] = '送达%s分钟';
lang['_SHOP_AT_REST_'] = '店铺休息中';
lang['_NUM_DELI_PRICE_'] = '%s元起送';
lang['_SHOP_ERROR_NOTICE_'] = '店铺未完善信息，点击确定将返回到上一页！';
lang['_ONLY_SELF_'] = '本店铺仅支持门店自提';
lang['_NO_STOCK_'] = '没有库存了';
lang['_PRAISE_TXT_'] = '好评';
lang['_POOR_DELI_'] = '还差';
lang['_REMINDER_STRING_'] = '温馨提示：图片仅供参考，请以实物为准；高峰时段及恶劣天气，请提前下单。';
lang['_SHOP_PHONE_'] = '店铺电话';
lang['_BUSINESS_TIME_'] = '营业时间';
lang['_SHOP_ADDRESS_'] = '店铺地址';
lang['_DIST_SERVICE_'] = '配送服务';
lang['_PLAT_DIST_'] = '由平台提供配送';
lang['_SHOP_DIST_'] = '由店铺提供配送';
lang['_SELF_DIST_'] = '本店铺仅支持门店自提';
lang['_SHOP_NOTICE_'] = '店铺公告';
lang['_SHOP_CERTIFICATION_'] = '店铺认证';
lang['_CERTIFIED_'] = '已认证';
lang['_CALL_PHONE_'] = '拨打电话';
lang['_CANCEL_TXT_'] = '取消';
lang['_PRODUCT_DESC_'] = '商品描述';
lang['_GOOD_CHOICE_'] =  '选好了';
//
lang['_IS_CLEAR_CART_'] = '您确定要清空购物车吗？';
lang['_CONFIRM_TXT_'] = '确定';
lang['_AT_ONCE_BUY_'] = '立即购买';
lang['_CART_IS_EMPTY_'] = '您的购物车还是空！';
lang['_B_LOGIN_ENTERPHONENO_'] =  '请输入手机号';
lang['_B_LOGIN_ENTERKEY_'] =  '请输入密码';
lang['_B_D_LOGIN_6KEYWORD_'] = '6位以上的密码';
lang['_B_D_LOGIN_DISPLAY_'] =  '显示明文';
lang['_B_LOGIN_PHONENOHAVE_']= '手机号已存在';
//
lang['_B_D_LOGIN_DISPLAY_PASS_'] = '显示密文';
lang['_B_D_LANG_RE_NOREP_'] = '注册中，请不要重复提交';
lang['_NO_BROWSE_RECORD_'] = '暂无浏览记录';
lang['_PLEASE_INPUT_KET_'] = '请输入关键词';
lang['_IS_CONFIRM_BIND_'] = '你确定要绑定已存在的账号吗？';
lang['_LOADING_TXT_'] = '正在加载中...';

function getLangStr(key,replace=''){
    var str = lang[key];
    str = str.replace('%s',replace);

    return str;
}