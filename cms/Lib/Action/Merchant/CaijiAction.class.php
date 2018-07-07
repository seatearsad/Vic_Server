<?php

class CaijiAction extends BaseAction {
    var $meun = "upload/group/";
    var $imeun = "caiji/";
    var $group_id = "";
    public function index() {
        $config = $this->config();
        $group = $this->is_group();
        $this->assign("caiji", $config);
        $this->assign("group", $group);
        $this->display();
    }
    public function post() {
        $type = $_POST['type'];
        $url = $_POST['url'];
        $config = $this->config($type, 1);
        $group = $this->is_group();
        if (strpos($url, $config['url']) == false) {
            $this->error("您提交的采集网址与采集方式不同!");
        }
        if ($this->$type($url)) {
            $this->success('采集成功，点击查看！', U('Group/frame_edit', array(
                "group_id" => $this->group_id
            )));
        } else {
            $this->success('采集失败！');
        }
    }
    private function config($type = "", $is = 0) {
        $config['meituan']['name'] = "美团";
        $config['meituan']['url'] = "meituan.com";
        $config['nuomi']['name'] = "糯米";
        $config['nuomi']['url'] = "nuomi.com";
        $config['dazhong']['name'] = "大众点评";
        $config['dazhong']['url'] = "dianping.com";
        if ($is) {
            if ($type) {
                return $config[$type];
            } else {
                $this->error("请选择采集方式");
            }
        }
        return $config;
    }
    private function meituan($url) {
        $id = $this->text($url, "/deal/", ".html");
        if (empty($url)) {
            $this->error("采集的网址不正确！");
        }
        $url = "http://i.meituan.com/deal/" . $id . ".html";
        $content = "http://i.meituan.com/deal/details/" . $id;
        $meituan = file_get_contents($url);
        $content = file_get_contents($content);
        $pic = $this->text($meituan, 'data-pics="', '">');
        $name = $this->text($meituan, '<h1 class="title">', '</h1>');
        $intro = $this->text($meituan, 'name="description" content="', '">');
        $price = $this->text($meituan, '<strong class="J_pricetag strong-color">', '</strong>');
        $content = $this->text($content, '本单详情</h2>', '<div id="anchor-bizinfo">');
        $content = $this->body_img($content);
        $pic = explode(",", $pic);
        $pic = array_slice($pic, 0, 5);
        $pic = $this->images($pic, 1, 1);
        return $this->baocun($name, $intro, $price, $content, $pic);
    }
    private function dazhong($url) {
        $url.= "|";
        $id = $this->text($url, "deal/", "|");
        if (empty($url)) {
            $this->error("采集的网址不正确！");
        }
        $id = str_replace("hotel/", "", $id);
        $url = "http://m.dianping.com/tuan/deal/$id";
        $body = $this->gethtml($url);
        $content_url = "http://m.dianping.com/tuan/deal/moreinfo/$id";
        $content_body = $this->gethtml($content_url);
        $pic_body = $this->text($body, '<div class="swipe-wrap">', '</div>');
        $pic = $this->alltext($pic_body, 'src="', '"');
        $name = $this->text($body, '<h3>', '</h3>');
        $intro = $this->text($body, '<p>', '</p>');
        $price = $this->text($body, '<div class="price sum">', '</div>');
        $content = $this->text($content_body, "介绍</h3>", '<div class="detail-info group-detail">');
        $content = $this->body_img($content);
        $content = str_replace('<div class="img">', "", $content);
        $content = str_replace('</div>', "", $content);
        $pic = array_slice($pic, 0, 5);
        $pic = $this->images($pic, 1, 1);
        return $this->baocun($name, $intro, $price, $content, $pic);
    }
    private function nuomi($url) {
        $id = $this->text($url, "deal/", ".html");
        if (empty($url)) {
            $this->error("采集的网址不正确！");
        }
        $url = "http://m.nuomi.com/th/deal/$id.html";
        $body = file_get_contents($url);
        $content_url = "http://m.nuomi.com/webapp/tuan/moredetail?dealTinyUrl=$id";
        $content_body = file_get_contents($content_url);
        $pic[] = $this->text($body, '<img src="', '"');
        $name = $this->text($body, '<h3 class="title">', '</h3>');
        $intro = $this->text($body, '<p class="desc">', '</p>');
        $price = $this->text($body, '<span class="current">$', '</span>');
        $content = $this->text($content_body, '<div class="detail-area bulk_order_details">', '</div>');
        $content = $this->body_img($content);
        $pic = $this->images($pic, 1, 1);
        return $this->baocun($name, $intro, $price, $content, $pic);
    }
    private function kaixin($url) {
        $url.= "|";
        $id = $this->text($url, "goods.php?id=", "|");
        if (empty($url)) {
            $this->error("采集的网址不正确！");
        }
        $url = "http://www.kaixinguangjie.com/caiji.php?id=$id";
        $body = json_decode(file_get_contents($url) , true);
        $pic = $body['pic'];
        $name = $body['name'];
        $intro = $body['intro'];
        $price = $body['price'];
        $content = $this->body_img($body['content']);
        $pic = array_slice($pic, 0, 5);
        $pic = $this->images($pic, 1, 1);
        return $this->baocun($name, $intro, $price, $content, $pic);
    }
    private function baocun($name, $intro, $price, $content, $pic) {
        $data["name"] = $name;
        $data["s_name"] = $name;
        $data["intro"] = $intro;
        $data["price"] = $price;
        $data["content"] = $content;
        $data["pic"] = $pic;
        if (D('Group')->where("group_id=" . $this->group_id)->data($data)->save()) {
            return $data;
        } else {
            return 0;
        }
    }
    function is_group() {
        $group_id = $_GET['group_id'];
        if (empty($group_id)) {
            $this->error('请传递商品参数');
        }
        $group = D('Group')->where("group_id=" . $group_id)->find();
        if (empty($group)) {
            $this->error('此商品不存在');
        }
        $this->group_id = $group_id;
        return $group;
    }
    private function text($txt, $left, $right, $type = 0) {
        $temp = explode($left, $txt);
        if (count($temp) < 2) {
            return;
        }
        $temp = explode($right, $temp[1]);
        if ($type) {
            return $left . $temp[0] . $right;
        } else {
            return $temp[0];
        }
    }
    function alltext($txt, $left, $right, $type = 0) {
        $temp = explode($left, $txt);
        if (count($temp) < 2) {
            return;
        }
        for ($i = 1; $i < count($temp); $i++) {
            $body_temp = explode($right, $temp[$i]);
            $body_temp = $body_temp[0];
            if ($type) {
                $body[] = $left . $body_temp . $right;
            } else {
                $body[] = $body_temp;
            }
        }
        return $body;
    }
    private function wenjianjia($path) {
        if (!file_exists($path)) {
            $this->wenjianjia(dirname($path));
            mkdir($path, 0777);
        }
    }
    private function body_img($body) {
        $img = $this->alltext($body, "http://", ".jpg", 1);
        $bimg = $this->images($img);
        foreach ($img as $key => $temp) {
            $body = str_replace($temp, $bimg[$key], $body);
        }
        $img = $this->alltext($body, "http://", ".png", 1);
        $bimg = $this->images($img);
        foreach ($img as $key => $temp) {
            $body = str_replace($temp, $bimg[$key], $body);
        }
        return $body;
    }
    private function images($img, $type = 0, $x = 0) {
        $meun = $this->imeun . date("Y") . "/" . date("m") . "/" . date("d");
        $this->wenjianjia($this->meun . $meun);
        $img_temp = "";
        foreach ($img as $temp) {
            $name = md5($temp) . ".jpg";
            $temp = $this->gethtml($temp);
            file_put_contents($this->meun . $meun . "/" . $name, $temp);
            if ($type) {
                file_put_contents($this->meun . $meun . "/s_" . $name, $temp);
                file_put_contents($this->meun . $meun . "/m_" . $name, $temp);
            }
            if ($x) {
                $img_temp.= ";$meun,$name";
            } else {
                $img_temp[] = $this->meun . $meun . "/" . $name;
            }
        }
        if ($x) {
            $img_temp = substr($img_temp, 1);
        }
        return $img_temp;
    }
    function gethtml($url) {
        $UserAgent = 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; SLCC1; .NET CLR 2.0.50727; .NET CLR 3.0.04506; .NET CLR 3.5.21022; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_ENCODING, '');
        curl_setopt($curl, CURLOPT_USERAGENT, $UserAgent);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        $data = curl_exec($curl);
        curl_close($curl);
        return $data;
    }
}
?>              
                    
