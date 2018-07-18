var lang = new Array();
lang['_SALE_NUM_ORDER_'] = 'sale %s order';
lang['_MONTH_SALE_NUM_'] = 'month sale %s order';
lang['_CLOSEST_ME_'] = 'closest me';
lang['_BEING_POSITION_'] = 'being position';
lang['_MIN_DELI_PRICE_'] = 'min deli price';
lang['_DELI_PRICE_'] = 'deli price';
lang['_PACK_PRICE_'] =  'pack price';
lang['_DEIL_NUM_MIN_'] = 'deil %s min';
lang['_SHOP_AT_REST_'] = 'shop at rest';
lang['_NUM_DELI_PRICE_'] = '%s deli price';
lang['_PRAISE_TXT_'] = 'Praise';
lang['_POOR_DELI_'] = 'poor';
lang['_REMINDER_STRING_'] = 'REMINDER STRING';
lang['_SHOP_PHONE_'] = 'SHOP PHONE';
lang['_BUSINESS_TIME_'] = 'business time';
lang['_SHOP_ADDRESS_'] = 'SHOP ADDRESS';
lang['_DIST_SERVICE_'] = 'dist service';
lang['_PLAT_DIST_'] = 'plat dist';
lang['_SHOP_DIST_'] = 'shop dist';
lang['_SELF_DIST_'] = 'self dist';
lang['_SHOP_NOTICE_'] = 'SHOP NOTICE';
lang['_SHOP_CERTIFICATION_'] = 'shop certification';
lang['_CERTIFIED_'] = 'certified';
lang['_CALL_PHONE_'] = 'CALL PHONE';
lang['_CANCEL_TXT_'] = 'CANCEL';

function getLangStr(key,replace=''){
    var str = lang[key];
    str = str.replace('%s',replace);

    return str;
}