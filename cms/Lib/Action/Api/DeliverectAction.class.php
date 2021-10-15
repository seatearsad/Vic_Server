<?php
/**
 * Created by PhpStorm.
 * User: garfunkel
 * Date: 2021/9/28
 * Time: 8:09 PM
 */

class DeliverectAction
{
    private $site_url;
    private $link_type = 1;

    private $menu_version = 2;

    private $menumFolder = "./DeliverMenu/";
    //获取传递数据
    private $data;
    public function __construct()
    {
        file_put_contents("./deliverect_log.log",date("Y/m/d")."   ".date("h:i:sa")."   "."Deliverect" ."   ". $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'--'.json_encode(file_get_contents("php://input"))."\r\n",FILE_APPEND);
        $this->data = json_decode(file_get_contents("php://input"),true);
    }

    public function channelStatus(){
        $config = D('Config')->where(array('name'=>'site_url'))->find();
        $this->site_url = $config['value'];
        //echo "channelStatus";
        $link_array = array(
            "statusUpdateURL"=> $this->site_url."/deliverect/statusUpdate",
            "menuUpdateURL"=> $this->site_url."/deliverect/menuUpdate",
            "snoozeUnsnoozeURL"=> $this->site_url."/deliverect/snoozeUnsnooze",
            "busyModeURL"=> $this->site_url."/deliverect/busyMode",
            "updatePrepTimeURL"=> $this->site_url."/deliverect/updatePrepTimeURL"
        );


        $store_id = $this->data['channelLocationId'];
        $link_id = $this->data['channelLinkId'];
        $status = $this->data['status'];

        $link_status = 0;
        switch ($status){
            case 'register':
                $link_status = 1;
                break;
            case 'active':
                $link_status = 2;
                break;
            case 'inactive':
                $link_status = 3;
                break;

            default:
                break;
        }

        $updateData['link_type'] = $this->link_type;
        $updateData['link_id'] = $link_id;
        $updateData['link_status'] =$link_status;
        $updateData['menu_version'] =$this->menu_version;

        D('Merchant_store')->where(array('store_id'=>$store_id))->save($updateData);

        echo json_encode($link_array);
    }

    public function snoozeUnsnooze(){
        echo "snoozeUnsnooze";
    }

    public function menuUpdate(){
        //Menu Type 0-Delivery and pickup 1-Delivery 2-Pickup 3-Eat-in 4-Curbside

        print_r($this->data);
        //菜单数量
        $menuNum = count($this->data);

        $storeThirdId = $this->data[0]['channelLinkId'];

        $store = D('Merchant_store')->where(array('link_id'=>$storeThirdId))->find();

        $storeId = $store['store_id'];

        //存储文件备份
        if(!is_dir($this->menumFolder)) {
            mkdir($this->menumFolder);
        }elseif(!is_writeable($this->menumFolder)) {
            header('Content-Type:text/html; charset=utf-8');
            exit('目录 [ '.$this->menumFolder.' ] 不可写！');
        }

        mkdir($this->menumFolder.$storeId);

        $docName = date('Y-m-d H_i_s').".json";

        file_put_contents($this->menumFolder.$storeId."/".$docName,json_encode($this->data),FILE_APPEND);
        ////
        foreach ($this->data as $k=>$menu){

            $menuId = $menu['menuId'];
            $menuName = $menu['menu'];
            $menuType = $menu['menuType'];
            //时间表
            $menuTime = $menu['availabilities'];

            D('Store_menu')->where(array('storeId'=>$storeId))->delete();

            $menuData['id'] = $menuId;
            $menuData['storeId'] = $storeId;
            $menuData['name'] = $menuName;
            $menuData['type'] = $menuType;
            $menuData['createType'] = $this->link_type;
            $menuData['createTime'] = time();

            D('Store_menu')->add($menuData);

            $categories = $menu['categories'];

            $this->intoCategories($categories,$menuData['id'],$storeId,$menuTime);

            //print_r($menuData);
            $products = array_merge($menu['products'],$menu['modifierGroups'],$menu['modifiers'],$menu['bundles']);
            $this->intoProducts($products,$storeId);

        }
        echo "menuUpdate";
    }

    public function busyMode(){
        echo "busyMode";
    }

    public function statusUpdate(){
        echo "statusUpdate";
    }

    public function updatePrepTimeURL(){
        echo "updatePrepTimeURL";
    }


    /**
     * @param $categories
     * @param $menuId
     * @param $storeId
     * @param $menuTime 菜单的时间列表 如果分类中没有时间 便使用这个时间列表
     */
    public function intoCategories($categories,$menuId,$storeId,$menuTime){
        $category_list = array();
        $allCategoryId = array();
        $all_time = array();
        $all_subproduct = array();

        D('Store_categories')->where(array('storeId'=>$storeId))->delete();
        foreach ($categories as $c => $category){
            $cateData = array();
            $cateData['id'] = $category['_id'];
            $cateData['name'] = $category['name'];
            $cateData['desc'] = $category['description'];
            $cateData['storeId'] = $storeId;
            $cateData['menuId'] = $menuId;
            $cateData['createType'] = 1;
            $cateData['createTime'] = time();

            $category_list[] = $cateData;

            $allCategoryId[] = $category['_id'];

            //如果分类中没有时间 便使用这个时间列表
            $cateTime = count($category['availabilities']) > 0 ? $category['availabilities'] : $menuTime;


            foreach ($cateTime as $ct => $cTime){
                $timeData = array();
                $timeData['categoryId'] = $cateData['id'];
                $timeData['weekNum'] = $cTime['dayOfWeek'];
                $timeData['startTime'] = $cTime['startTime'];
                $timeData['endTime'] = $cTime['endTime'];
                $timeData['storeId'] = $storeId;

                $all_time[] = $timeData;
            }

            $cateProduct = $category['subProducts'];

            foreach ($cateProduct as $p=>$productId){
                $subProduct = array();
                $subProduct['categoryId'] = $cateData['id'];
                $subProduct['productId'] = $productId;
                $subProduct['storeId'] = $storeId;

                $all_subproduct[] = $subProduct;
            }
        }

        D('Store_categories')->addAll($category_list);

        //先删除之前所有的记录
        //D('Store_categories_time')->where(array('categoryId'=>array('in',$allCategoryId)))->delete();
        D('Store_categories_time')->where(array('storeId'=>$storeId))->delete();
        D('Store_categories_time')->addAll($all_time);

        //D('Store_categories_product')->where(array('categoryId'=>array('in',$allCategoryId)))->delete();
        D('Store_categories_product')->where(array('storeId'=>$storeId))->delete();
        D('Store_categories_product')->addAll($all_subproduct);
    }

    public function intoProducts($products,$storeId){
        $addAllList = array();

        $allProductIds = array();

        $allRelation = array();

        D('Store_product')->where(array('storeId'=>$storeId))->delete();

        foreach ($products as $thirdId=>$product){
            $productData['id'] = $thirdId;
            $productData['name'] = $product['name'];
            $productData['desc'] = $product['description'];
            if(count($product['productTags']) > 0)
                $productData['productTags'] = implode(',',$product['productTags']);
            else
                $productData['productTags'] = '';
            $productData['plu'] = $product['plu'];
            $productData['storeId'] = $storeId;
            if($product['imageUrl'])
                $productData['image'] = $product['imageUrl'];
            else
                $productData['image'] = '';
            $productData['sort'] = $product['sortOrder'];
            $productData['price'] = $product['price'] ? $product['price'] : 0;
            $productData['tax'] = $product['deliveryTax'] ? $product['deliveryTax'] : 0;
            $productData['productType'] = $product['productType'];

            if($product['isCombo'])
                $productData['isCombo'] = 1;
            else
                $productData['isCombo'] = 0;

            $productData['max'] = $product['max'];
            $productData['min'] = $product['min'];
            if($product['multiMax'])
                $productData['multiMax'] = $product['multiMax'];
            else
                $productData['multiMax'] = 0;

            $productData['createType'] = 1;
            $productData['createTime'] = time();

            if($productData['productType'] == 4)
                $productData['isBundles'] = 1;
            else
                $productData['isBundles'] = 0;

            if($productData['productType'] == 3)
                $productData['isModifiers'] = 1;
            else
                $productData['isModifiers'] = 0;

            $productData['subNum'] = count($product['subProducts']);

            $allProductIds[] = $thirdId;

            $addAllList[] = $productData;

            $subProducts = $product['subProducts'];

            foreach ($subProducts as $s=>$subId){
                $relationData = array();
                $relationData['productId'] = $thirdId;
                $relationData['relationType'] = 0;
                if($productData['isBundles'] == 1) $relationData['relationType'] = 1;
                if($productData['isModifiers'] == 1) $relationData['relationType'] = 2;
                $relationData['subProductId'] = $subId;
                $relationData['storeId'] = $storeId;

                $allRelation[] = $relationData;
            }
        }

        D('Store_product')->addAll($addAllList);
        //D('Store_product_relation')->where(array('productId'=>array('in',$allProductIds)))->delete();
        D('Store_product_relation')->where(array('storeId'=>$storeId))->delete();
        D('Store_product_relation')->addAll($allRelation);
    }
}