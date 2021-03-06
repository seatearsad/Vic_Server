var lang = new Array();
lang['_SALE_NUM_ORDER_'] = 'Total sold %s';
lang['_MONTH_SALE_NUM_'] = 'Monthly sold %s';
lang['_EVENT_NUM_'] = '%s activities';
lang['_DISCOUNT_NUM_'] = '%s discount';
lang['_EVERY_FULL_'] = 'Once reaches %s';
lang['_REDUCE_NUM_'] = '%s dollars off';
lang['_CLOSEST_ME_'] = 'Nearest';
lang['_BEING_POSITION_'] = 'Locating';
lang['_MIN_DELI_PRICE_'] = 'Still need';
lang['_DELI_PRICE_'] = 'Delivery fee';
lang['_PACK_PRICE_'] =  'Packing fee';
lang['_DEIL_NUM_MIN_'] = 'Delivered %s minutes';
lang['_SHOP_AT_REST_'] = 'This store is temporarily unavailable.';
lang['_NUM_DELI_PRICE_'] = 'Still need %s';
lang['_SHOP_ERROR_NOTICE_'] = 'The information is not complete, click confirm to return to the last page！';
lang['_ONLY_SELF_'] = 'This restaurant is pick up only';
lang['_NO_STOCK_'] = 'Out of stock';
lang['_NO_STORE_SEARCH_RESULT'] = 'Sorry, no results found.';
lang['_PRAISE_TXT_'] = '';//本应是图片
lang['_POOR_DELI_'] = ' ';
lang['_REMINDER_STRING_'] = 'Note: Picture might be different from the real products, please order in advance during the bad weathers or busy hours';
lang['_SHOP_PHONE_'] = 'Phone';
lang['_BUSINESS_TIME_'] = 'Hours';
lang['_SHOP_ADDRESS_'] = 'Address';
lang['_DIST_SERVICE_'] = 'Delivery service';
lang['_PLAT_DIST_'] = 'Delivered by the platform';
lang['_SHOP_DIST_'] = 'Delivered by the restaurant';
lang['_SELF_DIST_'] = 'This store is pick up only';
lang['_SHOP_NOTICE_'] = 'Announcement';
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
lang['_LOADING_TXT_'] = 'Loading...';
lang['_SMS_CODE_ERROR_'] = 'Invalid verification code.';
lang['_B_D_LOGIN_FILLMESSAGE_'] = 'Enter your SMS code';
lang['_STORE_STATUS_CLOSED'] = 'Closed';

lang['SPECIFICATION_NAME_BKADMIN'] = "Specification Name";
lang['SPECIFICATION_VALUE_BKADMIN'] = "Specification Value";
lang['ADD_VALVE_BKADMIN'] = "Add Value";
lang['ADD_SPECIFICATION_BKADMIN'] = "Add Specification";
lang['ATTRI_SPECIFICA_BKADMIN'] = "Attributes and specifications are for the old system. Please use the new Option function when uploading new items from now on. Specifications are price related (e.g. Large/small), attributes are not price related (e.g. Spiciness/Ice)";
lang['ATTRIBUTE_NAME_BKADMIN'] = "Attribute Name";
lang['QUANTITY_ALLOWED_BKADMIN'] = "Quantity Allowed";
lang['ATTRIBUTE_VALUE_BKADMIN'] = "Attribute Value";
lang['ADD_VALUE_BKADMIN'] = "Add Value";
lang['ADD_ATTRIBUTE_BKADMIN'] = "Add Attribute";
lang['GENERATE_CHART_BKADMIN'] = "Generate Price Chart";
lang['ORIGINAL_PRICE_BKADMIN'] = "Original Price";
lang['CURR_PRICE_BKADMIN'] = "Price";
lang['STOCK_BKADMIN'] = "Stock";
lang['DELETE_BKADMIN'] = "Delete";

function getLangStr(key,replace=''){
    var str = lang[key];
    str = str.replace('%s',replace);

    return str;
}