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
        $categoryTime = D($this->categoriseTimeTable)->where(array('categoryId'=>$categoryId))->select();

        return $categoryTime;
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
                return L('SUN_BKADMIN');
            default:
                return '';
        }
    }
}