<?php
/**
 * Created by PhpStorm.
 * User: garfunkel
 * Date: 2021/10/12
 * Time: 7:03 PM
 */

class StoreMenuV2Model extends Model
{
    protected $menuTable = "Store_menu";
    protected $categoriseTable = "Store_categories";
    protected $categoriseProductTable = "Store_categories_product";
    protected $categoriseTimeTable = "Store_categories_time";
    protected $productTable = "Store_product";
    protected $productRelationTable = "Store_product_relation";

    public function getStoreMenu($storeId){
        $menuList = D($this->menuTable)->where(array('storeId'=>$storeId))->select();

        return $menuList;
    }

    public function getMenuCategories($menuId,$storeId){
        $categories = D($this->categoriseTable)->where(array('menuId'=>$menuId,'storeId'=>$storeId))->order('sort asc')->select();
        foreach ($categories as &$category){
            $category['productNum'] = $this->getCategoryProductNum($category['id'],$storeId);
            $category['time'] = $this->getCategoryTime($category['id'],$storeId);
            $category['week'] = $this->getCategoryTimeWeekStr($category['time']);
        }

        return $categories;
    }

    /**
     * @param $storeId
     * @param bool $isFilter 是否过滤时间设置
     * @return array
     */
    public function getStoreCategories($storeId,$isFilter = false){
        $menuList = $this->getStoreMenu($storeId);
        $allCategories = array();
        foreach ($menuList as $menu){
            $categories = $this->getMenuCategories($menu['id'],$storeId);
            $allCategories = array_merge($allCategories,$categories);
        }

        if($isFilter){
            $today = date('w');
            $curr_time = intval(date('Hi',time()));

            foreach ($allCategories as $key => &$row) {
                $categoryTime = $this->getCategoryTime($row['id'],$storeId);
                $categoryTime = $this->arrangeCategoryTime($categoryTime);

                $row['week'] = $categoryTime['week'];
                $row['week_str'] = $categoryTime['week_str'];

                $is_move = false;
                $week_arr = $categoryTime['week_arr'];

                if (!in_array($today, $week_arr)) {
                    $is_move = true;
                }else{
                    $time_arr = $categoryTime['time_arr'][$today];
                    $has_time = false;
                    foreach ($time_arr as $ct) {
                        $startTime = str_replace(':', '', $ct['startTime']);
                        $endTime = str_replace(':', '', $ct['endTime']);

                        if ($curr_time >= $startTime && $curr_time < $endTime) {
                            $has_time = true;
                            $row['show_time'] = $ct['startTime'].','.$ct['endTime'];
                            $row['show_time_str'] = $ct['startTime'].' - '.$ct['endTime'];
                            $row['startTime'] = $ct['startTime'];
                            $row['endTime'] = $ct['endTime'];
                        }
                    }

                    if(!$has_time) $is_move = true;
                }

                if($is_move){
                    unset($allCategories[$key]);
                }
            }
        }

        return $allCategories;
    }

    /**
     * @param $categories
     * @param $storeId
     * @param int $from_type 1Wap 2App
     * @return array
     */
    public function getStoreProduct($categories,$storeId,$from_type = 1,$keyword = ''){
        $allList = array();
        foreach ($categories as $category){
            $currCate = array();
            $currCate['cat_id'] = $category['id'];
            $currCate['cat_name'] = $category['name'];
            $currCate['sort_discount'] = 0;
            $currCate['limited_offers'] = 0;
            $currCate['is_time'] = 0;
            $products = $this->getMenuCategoriesProduct($category['id'],$storeId,1,$keyword);

            if($from_type == 1){
                $currCate['product_list'] = $this->arrangeProductWap($products,$category['id']);
            }

            $allList[] = $currCate;
        }

        return $allList;
    }

    public function getStoreProductApp($categories,$uid,$storeId,$keyword = ''){
        $allList = array();
        foreach ($categories as $category){
            $products = $this->getMenuCategoriesProduct($category['id'],$storeId,1,$keyword);
            $products = $this->arrangeProductApp($products,$category,$uid);


            $allList = array_merge($allList,$products);
        }

        return $allList;
    }

    public function getStoreProductAll($categories,$storeId){
        $allList = array();
        foreach ($categories as $category){
            $products = $this->getMenuCategoriesProduct($category['id'],$storeId,1);

            $allList = array_merge($allList,$products);
        }

        return $allList;
    }

    public function arrangeWap($categories){
        $newCategories = array();
        foreach ($categories as $category){
            $newCate = array();
            $newCate['sort_id'] = $category['id'];
            $newCate['store_id'] = $category['storeId'];
            $newCate['sort_name'] = $category['name'];
            $newCate['sort'] = 0;//$category['sort'];
            $newCate['is_weekshow'] = 1;
            $newCate['week'] = $category['week'];
            $newCate['week_str'] = $category['week_str'];
            $newCate['status'] = 0;
            $newCate['sort_discount'] = 0;
            $newCate['image'] = "";
            $newCate['fid'] = 0;
            $newCate['level'] = 1;
            $newCate['print_id'] = 0;
            $newCate['is_time'] = 1;
            $newCate['show_time'] = $category['show_time'];
            $newCate['cat_id'] = $category['id'];
            $newCate['cat_name'] = $category['name'];
            $newCate['show_time_str'] = $category['show_time_str'];

            $newCategories[] = $newCate;
        }

        return $newCategories;
    }

    public function arrangeApp($categories){
        $newCategories = array();

        foreach ($categories as $category){
            $newCate = array();
            $newCate['id'] = $category['id'];
            $newCate['sort_id'] = $category['id'];
            $newCate['sid'] = $category['storeId'];
            $newCate['title'] = $category['name'];
            $newCate['is_time'] = "1";
            $newCate['begin_time'] = $category['startTime'];
            $newCate['end_time'] = $category['endTime'];

            $newCategories[] = $newCate;
        }

        return $newCategories;
    }

    public function arrangeProductWap($products,$categoryId){
        $newProducts = array();
        foreach ($products as $product){
            $new_product = array();
            $new_product['product_id'] = $product['id'];
            $new_product['categoryId'] = $categoryId;
            $new_product['product_name'] = $product['name'];
            $new_product['product_desc'] = $product['desc'];
            $new_product['product_price'] = $product['price']/100;
            $new_product['is_seckill_price'] = false;
            $new_product['o_price'] = $product['price']/100;
            $new_product['number'] = "";
            $new_product['packing_charge'] = 0;
            $new_product['unit'] = "";
            $new_product['product_image'] = $product['image'] == "" ? null : $product['image'];
            $new_product['product_sale'] = 0;
            $new_product['product_reply'] = 0;
            $new_product['has_format'] = false;
            $new_product['has_dish'] = $product['subNum'] > 0 ? true : false;
            $new_product['stock'] = -1;
            $new_product['version'] = 2;

            $newProducts[] = $new_product;
        }

        return $newProducts;
    }

    public function arrangeProductApp($products,$category,$uid){
        $newProducts = array();

        foreach ($products as $product){
            $new_product = $this->arrangeProductAppOne($product,$category);

            $num = 0;
            if($uid && $uid != 0) {
                $cart_list = D('Cart')->where(array('uid' => $uid, 'fid' => $product['id']))->select();
                foreach ($cart_list as $c) {
                    $num += $c['num'];
                }
            }
            $new_product['quantity'] = strval($num);


            $newProducts[] = $new_product;
        }

        return $newProducts;
    }

    public function arrangeProductAppOne($product,$category){
        $new_product['fid'] = $product['id'];
        $new_product['group_id'] = $category ? $category['id'] : 0;
        $new_product['sid'] = $product['storeId'];
        $new_product['name'] = $product['name'];
        $new_product['desc'] = $product['desc'];
        $new_product['market_price'] = $product['price']/100;
        $new_product['price'] = $product['price']/100;
        $new_product['status'] = $product['status'];
        $new_product['number'] = "";
        $new_product['packing_charge'] = 0;
        $new_product['default_image'] = $product['image'];
        $new_product['has_format'] = $product['subNum'] > 0 ? true : false;
        $new_product['stock'] = 10000;
        $new_product['tax_num'] = $product['tax']/1000;
        $new_product['sales'] = 0;
        $new_product['deposit'] = 0;
        $new_product['is_time'] = "1";
        $new_product['begin_time'] = $category ? $category['startTime'] : "";
        $new_product['end_time'] = $category ? $category['endTime'] : "";
        $new_product['is_weekshow'] = "1";
        $new_product['dish_desc'] = "";
        $new_product['dish_id'] = "";
        $new_product['spec'] = "";
        $new_product['spec_desc'] = "";
        $new_product['proper_desc'] = "";
        $new_product['attr_num'] = "0";
        $new_product['attr'] = "";
        $new_product['proper'] = "";
        $new_product['menu_version'] = 2;

        return $new_product;
    }

    public function arrangeProductWapShow($product){
        $newProduct = array();
        $newProduct['goods_id'] = $product['id'];
        $newProduct['store_id'] = $product['storeId'];
        $newProduct['name'] = $product['name'];
        $newProduct['des'] = $product['desc'];
        $newProduct['price'] = $product['price']/100;
        $newProduct['image'] = $product['image'];
        $newProduct['pic_arr'] = array(array('title'=>'','url'=>$product['image']));
        $newProduct['unit'] = "";
        $newProduct['stock_num'] = -1;

        return $newProduct;
    }

    /**
     * @param $dish_list
     * @param $productId
     * @param $storeId
     * @param $from 1wap 0app
     * @return array
     */
    public function arrangeDishWap($dish_list,$productId,$storeId,$from){
        $dish_list_new = array();
        foreach ($dish_list as $dish){
            $newDish = array();
            $newDish['id'] = $dish['id'];
            $newDish['goods_id'] = $productId;
            $newDish['name'] = $dish['name'];
            $newDish['min'] = $dish['min'];
            $newDish['max'] = $dish['max'];
            if($dish['multiMax'] > 1)
                $newDish['type'] = "1";
            else
                $newDish['type'] = "0";
            $newDish['status'] = $dish['status'];


            $list = $this->getProductRelation($dish['id'],$storeId,1);
            $newList = array();
            foreach ($list as $l){
                $newSide = array();
                $newSide['id'] = $l['id'];
                $newSide['dish_id'] = $dish['id'];
                $newSide['name'] = $l['name'];
                $newSide['price'] = $l['price']/100;
                $newSide['status'] = $l['status'];

                if($l['subNum'] > 0){
                    //如果有第二级 将此选项变成单选
                    $newDish['type'] = "0";

                    if($from == 1) $newSide['name'] = $l['name']." >>";
                    else $newSide['name'] = $l['name'];

                    $sub_products = $this->getProductRelation($l['id'],$storeId,1);
                    $sub_products = $this->arrangeDishWap($sub_products,$l['id'],$storeId);

                    $newSide['list'] = $sub_products;
                }else{
                    $newSide['list'] = array();
                }

                $newList[] = $newSide;
            }

            $newDish['list'] = $newList;

            $dish_list_new[] = $newDish;
        }

        return $dish_list_new;
    }

    public function getMenuCategory($categoryId,$storeId){
        $category = D($this->categoriseTable)->where(array('id'=>$categoryId,'storeId'=>$storeId))->find();

        return $category;
    }

    //获取实际展现的产品
    public function getCategoryProductNum($categoryId,$storeId){
        $productNum = D($this->categoriseProductTable)->where(array('categoryId'=>$categoryId,'storeId'=>$storeId))->count();

        return $productNum;
    }

    public function getMenuCategoriesProduct($categoryId,$storeId,$status = -1,$keyword = ''){
        $productLinks = D($this->categoriseProductTable)->where(array('categoryId'=>$categoryId,'storeId'=>$storeId))->order('sort asc')->select();

        $productIds = array();
        foreach ($productLinks as &$link){
            $productIds[] = $link['productId'];
        }

        $where = array('p.id'=>array('in',$productIds),'p.storeId'=>$storeId,'c.categoryId'=>$categoryId);
        if($status != -1) $where['p.status'] = $status;
        if($keyword != '') $where['p.name'] = array('like', '%' . $keyword . '%');
        $products = D($this->productTable)->field("p.*")->join('as p left join '.C('DB_PREFIX').'store_categories_product as c ON c.productId=p.id and c.storeId=p.storeId')->where($where)->order('c.sort asc')->select();
        //foreach ($products as &$product){
            //$product['relation'] = D($this->productRelationTable)->field("r.*,p.*")->join('as r left join '.C('DB_PREFIX').'store_product as p ON r.subProductId=p.id')->where(array('r.productId'=>$product['id']))->select();
        //}

        return $products;
    }

    public function getProduct($productId,$storeId){
        $product = D($this->productTable)->where(array('id'=>$productId,'storeId'=>$storeId))->find();

        return $product;
    }

    public function getProductRelation($productId,$storeId,$status = -1){
        $where = array('r.productId'=>$productId,'r.storeId'=>$storeId);
        if($status != -1) $where['p.status'] = $status;

        $products = D($this->productRelationTable)->field("r.*,p.*")->join('as r left join '.C('DB_PREFIX').'store_product as p ON r.subProductId=p.id and r.storeId=p.storeId')->where($where)->order('r.sort asc')->select();

        return $products;
    }

    public function getCategoryTime($categoryId,$storeId){
        $categoryTime = D($this->categoriseTimeTable)->where(array('categoryId'=>$categoryId,'storeId'=>$storeId))->order('weekNum asc')->select();

        return $categoryTime;
    }

    public function getCategoryByProductId($productId,$storeId){
        $category = D($this->categoriseProductTable)->where(array('productId'=>$productId,'storeId'=>$storeId))->find();

        return $category;
    }
    public function getCategoryTimeByProductId($productId,$storeId){
        $c = D($this->categoriseProductTable)->where(array('productId'=>$productId,'storeId'=>$storeId))->find();

        $categoryTime = $this->getCategoryTime($c['categoryId'],$storeId);

        $categoryTime = $this->arrangeCategoryTime($categoryTime);

        $row['week'] = $categoryTime['week'];
        $row['week_str'] = $categoryTime['week_str'];

        $is_move = false;
        $week_arr = $categoryTime['week_arr'];

        $today = date('w');
        $curr_time = intval(date('Hi',time()));
        if (!in_array($today, $week_arr)) {
            $is_move = true;
        }else{
            $time_arr = $categoryTime['time_arr'][$today];
            $has_time = false;
            foreach ($time_arr as $ct) {
                $startTime = str_replace(':', '', $ct['startTime']);
                $endTime = str_replace(':', '', $ct['endTime']);

                if ($curr_time >= $startTime && $curr_time < $endTime) {
                    $has_time = true;
                    $row['show_time'] = $ct['startTime'].','.$ct['endTime'];
                    $row['show_time_str'] = $ct['startTime'].' - '.$ct['endTime'];
                    $row['startTime'] = $ct['startTime'];
                    $row['endTime'] = $ct['endTime'];
                }
            }

            if(!$has_time) $is_move = true;
        }

        return $row;
    }

    public function getCategoryTimeByCategoryId($categoryId,$storeId){
        $categoryTime = $this->getCategoryTime($categoryId,$storeId);

        $categoryTime = $this->arrangeCategoryTime($categoryTime);

        $row['week'] = $categoryTime['week'];
        $row['week_str'] = $categoryTime['week_str'];

        $is_move = false;
        $week_arr = $categoryTime['week_arr'];

        $today = date('w');
        $curr_time = intval(date('Hi',time()));
        if (!in_array($today, $week_arr)) {
            $is_move = true;
        }else{
            $time_arr = $categoryTime['time_arr'][$today];
            $has_time = false;
            foreach ($time_arr as $ct) {
                $startTime = str_replace(':', '', $ct['startTime']);
                $endTime = str_replace(':', '', $ct['endTime']);

                if ($curr_time >= $startTime && $curr_time < $endTime) {
                    $has_time = true;
                    $row['show_time'] = $ct['startTime'].','.$ct['endTime'];
                    $row['show_time_str'] = $ct['startTime'].' - '.$ct['endTime'];
                    $row['startTime'] = $ct['startTime'];
                    $row['endTime'] = $ct['endTime'];
                }
            }

            if(!$has_time) $is_move = true;
        }

        return $row;
    }

    public function arrangeCategoryTime($categoryTime){
        $weekArr = array();
        $weekStr = "";
        $timeArr = array();
        foreach ($categoryTime as $time){
            $time['weekNum'] = $time['weekNum'] == 7 ? 0 : $time['weekNum'];
            if(!in_array($time['weekNum'],$weekArr)){
                $weekArr[] = $time['weekNum'];
                $weekStr[] = $this->get_week($time['weekNum']);
            }
            $timeArr[$time['weekNum']][] = array('startTime'=>$time['startTime'],'endTime'=>$time['endTime']);
        }

        $arr['week'] = implode(',',$weekArr);
        $arr['week_str'] = implode(' ',$weekStr);
        $arr['week_arr'] = $weekArr;
        $arr['time_arr'] = $timeArr;

        return $arr;
    }

    public function calculationTaxFromOrder($orderDetail){
        $tax = 0;
        $product = $this->getProduct($orderDetail['goods_id'],$orderDetail['store_id']);
        $productTax = floatval(($product['price']/100) * ($product['tax']/100000)) * $orderDetail['num'];

        $tax += $productTax;
        $dishList = explode("|",$orderDetail['dish_id']);
        foreach ($dishList as $dishStr){
            $dish = explode(',',$dishStr);

            $dishProduct = $this->getProduct($dish[1],$orderDetail['store_id']);
            $dishProductTax = floatval(($dishProduct['price']/100) * ($dishProduct['tax']/100000))*$dish[2];
            $tax += $dishProductTax * $orderDetail['num'];
        }

        return $tax;
    }

    public function getCategoryTimeWeekStr($categoryTime){
        $weekArr = array();
        foreach ($categoryTime as $time){
            $weekArr[] = $this->get_week($time['weekNum'])." (".$time['startTime']." - ".$time['endTime'].")<br/>";
        }

        return implode('',$weekArr);
    }


    protected function get_week($num)
    {
        switch($num){
            case 1:
                return L('MON_BKADMIN');
            case 2:
                return L('TUE_BKADMIN');
            case 3:
                return L('WED_BKADMIN');
            case 4:
                return L('THUR_BKADMIN');
            case 5:
                return L('FRI_BKADMIN');
            case 6:
                return L('SAT_BKADMIN');
            case 7:
            case 0:
                return L('SUN_BKADMIN');
            default:
                return '';
        }
    }
}