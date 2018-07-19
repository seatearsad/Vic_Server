var lang = new Array();
lang['_SALE_NUM_ORDER_'] = 'Sold %s';
lang['_MONTH_SALE_NUM_'] = 'Monthly sold %s';
lang['_EVENT_NUM_'] = '%s activities';
lang['_DISCOUNT_NUM_'] = '%s discount';
lang['_EVERY_FULL_'] = 'Once reaches %s';
lang['_REDUCE_NUM_'] = '%s dollars off';
lang['_CLOSEST_ME_'] = 'nearest';
lang['_BEING_POSITION_'] = 'locating';
lang['_MIN_DELI_PRICE_'] = 'minimum order';
lang['_DELI_PRICE_'] = 'delivery fee';
lang['_PACK_PRICE_'] =  'packing fee';
lang['_DEIL_NUM_MIN_'] = 'delivered %s minutes';
lang['_SHOP_AT_REST_'] = 'the store is temporarily closed';
lang['_NUM_DELI_PRICE_'] = 'starting from %s';
lang['_SHOP_ERROR_NOTICE_'] = 'The information is not complete, click confirm to return to the last page！';
lang['_ONLY_SELF_'] = 'This restaurant is pick up only';
lang['_NO_STOCK_'] = 'Out of stock';
lang['_PRAISE_TXT_'] = 'Excellent';
lang['_POOR_DELI_'] = 'left';
lang['_REMINDER_STRING_'] = 'Tips: Picture might be different from the real products, please order in advance during the bad weathers or busy hours';
lang['_SHOP_PHONE_'] = "Restaurant's number";
lang['_BUSINESS_TIME_'] = 'Hours';
lang['_SHOP_ADDRESS_'] = 'Address';
lang['_DIST_SERVICE_'] = 'Delivery service';
lang['_PLAT_DIST_'] = 'delivered by us';
lang['_SHOP_DIST_'] = 'delivered by the restaurant';
lang['_SELF_DIST_'] = 'This store is pick up only';
lang['_SHOP_NOTICE_'] = "Restaurant's announcement";
lang['_SHOP_CERTIFICATION_'] = 'Verify Restaurant';
lang['_CERTIFIED_'] = 'Verified';
lang['_CALL_PHONE_'] = 'Call';
lang['_CANCEL_TXT_'] = 'Cancel';
//
lang['_IS_CLEAR_CART_'] = 'is clear cart？';
lang['_CONFIRM_TXT_'] = 'confirm';

function getLangStr(key,replace=''){
    var str = lang[key];
    str = str.replace('%s',replace);

    return str;
}