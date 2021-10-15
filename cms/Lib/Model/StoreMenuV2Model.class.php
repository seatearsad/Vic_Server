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

    public function getMenuCategories($menuId){
        $categories = D($this->categoriseTable)->where(array('menuId'=>$menuId))->select();
        foreach ($categories as &$category){
            $category['productNum'] = $this->getCategoryProductNum($category['id']);
            $category['time'] = $this->getCategoryTime($category['id']);
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
            $categories = $this->getMenuCategories($menu['id']);
            $allCategories = array_merge($allCategories,$categories);
        }

        if($isFilter){
            $today = date('w');
            $curr_time = intval(date('Hi',time()));

            foreach ($allCategories as $key => &$row) {
                $categoryTime = $this->getCategoryTime($row['id']);
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
     * @param int $from_type 1Wap 2App
     * @return array
     */
    public function getStoreProduct($categories,$from_type = 1){
        $allList = array();
        foreach ($categories as $category){
            $currCate = array();
            $currCate['cat_id'] = $category['id'];
            $currCate['cat_name'] = $category['name'];
            $currCate['sort_discount'] = 0;
            $currCate['limited_offers'] = 0;
            $currCate['is_time'] = 0;
            $products = $this->getMenuCategoriesProduct($category['id']);

            if($from_type == 1){
                $currCate['product_list'] = $this->arrangeProductWap($products,$category['id']);
            }

            $allList[] = $currCate;
        }

        return $allList;
    }

    public function getStoreProductAll($categories){
        $allList = array();
        foreach ($categories as $category){
            $products = $this->getMenuCategoriesProduct($category['id']);

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

    public function arrangeProductWapShow($product){
        $newProduct = array();
        $newProduct['goods_id'] = $product['id'];
        $newProduct['store_id'] = $product['storeId'];
        $newProduct['name'] = $product['name'];
        $newProduct['des'] = $product['desc'];
        $newProduct['price'] = $product['price']/100;
        $newProduct['image'] = $product['image'];
        $newProduct['unit'] = "";
        $newProduct['stock_num'] = -1;

        return $newProduct;
    }

    public function getMenuCategory($categoryId){
        $category = D($this->categoriseTable)->where(array('id'=>$categoryId))->find();

        return $category;
    }

    //获取实际展现的产品
    public function getCategoryProductNum($categoryId){
        $productNum = D($this->categoriseProductTable)->where(array('categoryId'=>$categoryId))->count();

        return $productNum;
    }

    public function getMenuCategoriesProduct($categoryId){
        $productLinks = D($this->categoriseProductTable)->where(array('categoryId'=>$categoryId))->select();

        $productIds = array();
        foreach ($productLinks as &$link){
            $productIds[] = $link['productId'];
        }

        $products = D($this->productTable)->where(array('id'=>array('in',$productIds)))->select();
        //foreach ($products as &$product){
            //$product['relation'] = D($this->productRelationTable)->field("r.*,p.*")->join('as r left join '.C('DB_PREFIX').'store_product as p ON r.subProductId=p.id')->where(array('r.productId'=>$product['id']))->select();
        //}

        return $products;
    }

    public function getProduct($productId){
        $product = D($this->productTable)->where(array('id'=>$productId))->find();

        return $product;
    }

    public function getProductRelation($productId){
        $products = D($this->productRelationTable)->field("r.*,p.*")->join('as r left join '.C('DB_PREFIX').'store_product as p ON r.subProductId=p.id')->where(array('r.productId'=>$productId))->select();

        return $products;
    }

    public function getCategoryTime($categoryId){
        $categoryTime = D($this->categoriseTimeTable)->where(array('categoryId'=>$categoryId))->order('weekNum asc')->select();

        return $categoryTime;
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