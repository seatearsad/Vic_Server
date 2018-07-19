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
//
lang['_IS_CLEAR_CART_'] = '您确定要清空购物车吗？';
lang['_CONFIRM_TXT_'] = '确定';

function getLangStr(key,replace=''){
    var str = lang[key];
    str = str.replace('%s',replace);

    return str;
}