var lang = new Array();
lang['_SALE_NUM_ORDER_'] = 'Sold %s';
lang['_MONTH_SALE_NUM_'] = 'Monthly sold %s';
lang['_EVENT_NUM_'] = '%s activities';
lang['_DISCOUNT_NUM_'] = '%s discount';
lang['_EVERY_FULL_'] = 'Once reaches %s';
lang['_REDUCE_NUM_'] = '%s dollars off';
lang['_CLOSEST_ME_'] = 'Nearest';
lang['_BEING_POSITION_'] = 'Locating';
lang['_MIN_DELI_PRICE_'] = 'Minimum order';
lang['_DELI_PRICE_'] = 'Delivery fee';
lang['_PACK_PRICE_'] =  'Packing fee';
lang['_DEIL_NUM_MIN_'] = 'Delivered %s minutes';
lang['_SHOP_AT_REST_'] = 'The store is temporarily closed';
lang['_NUM_DELI_PRICE_'] = 'Minimum order %s';
lang['_SHOP_ERROR_NOTICE_'] = 'The information is not complete, click confirm to return to the last page！';
lang['_ONLY_SELF_'] = 'This restaurant is pick up only';
lang['_NO_STOCK_'] = 'Out of stock';
lang['_PRAISE_TXT_'] = '';//本应是图片
lang['_POOR_DELI_'] = ' ';
lang['_REMINDER_STRING_'] = 'Tips: Picture might be different from the real products, please order in advance during the bad weathers or busy hours';
lang['_SHOP_PHONE_'] = 'Restaurant\'s phone number';
lang['_BUSINESS_TIME_'] = 'Hours';
lang['_SHOP_ADDRESS_'] = 'Address';
lang['_DIST_SERVICE_'] = 'Delivery service';
lang['_PLAT_DIST_'] = 'Delivered by the platform';
lang['_SHOP_DIST_'] = 'Delivered by the restaurant';
lang['_SELF_DIST_'] = 'This store is pick up only';
lang['_SHOP_NOTICE_'] = 'Restaurant\'s announcement';
lang['_SHOP_CERTIFICATION_'] = 'Verify Restaurant';
lang['_CERTIFIED_'] = 'Verified';
lang['_CALL_PHONE_'] = 'Call';
lang['_CANCEL_TXT_'] = 'Cancel';
lang['_PRODUCT_DESC_'] = 'Description of the product';
lang['_GOOD_CHOICE_'] =  'Check out';
//
lang['_IS_CLEAR_CART_'] = 'Are you sure you want to clear your cart';
lang['_CONFIRM_TXT_'] = 'Confirm';
lang['_AT_ONCE_BUY_'] = 'Buy';
lang['_CART_IS_EMPTY_'] = 'Your cart is empty';
lang['_B_LOGIN_ENTERPHONENO_'] =  'Please enter your phone number';
lang['_B_LOGIN_ENTERKEY_'] =  'Please enter your password';
lang['_B_D_LOGIN_6KEYWORD_'] = 'The password must be more than 6 digits';
lang['_B_D_LOGIN_DISPLAY_'] =  'View more';
lang['_B_LOGIN_PHONENOHAVE_']= 'This phone number already existed';
//
lang['_B_D_LOGIN_DISPLAY_PASS_'] = 'Hide password';
lang['_B_D_LANG_RE_NOREP_'] = 'Processing, please do not send twice';
lang['_NO_BROWSE_RECORD_'] = 'No browsing history';
lang['_PLEASE_INPUT_KET_'] = 'Please enter a key word';
lang['_IS_CONFIRM_BIND_'] = 'Are you sure you want to bind exsited account?';

function getLangStr(key,replace=''){
    var str = lang[key];
    str = str.replace('%s',replace);

    return str;
}