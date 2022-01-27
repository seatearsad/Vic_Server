<?php
/**
 * Created by PhpStorm.
 * User: garfunkel
 * Date: 2022/1/24
 * Time: 6:47 PM
 */

class RegionalCalu
{
    protected $region;

    protected $rectangles;

    protected $lines;

    protected $point_x;

    protected $point_y;

    public $city_id;

    public function __construct()
    {
        //lng,lat
//        $this->region[] = array(116.4053,39.90632);
//        $this->region[] = array(116.495418,39.911412);
//        $this->region[] = array(116.495418,39.87709);
//        $this->region[] = array(116.491322,39.854717);
//        $this->region[] = array(116.462432,39.851892);
//        $this->region[] = array(116.421362,39.852141);
//        $this->region[] = array(116.420895,39.863412);
//        $this->region[] = array(116.406809,39.863412);
//        $this->region[] = array(116.406881,39.863357);
    }

    public function index(){
        $this->city_id = 3472;

        $city = D('Area')->where(array('area_id'=>$this->city_id))->find();

        if($city['range_type'] == 2){
            $para = explode("|",$city['range_para']);
            foreach ($para as $v){
                $this->region[] = explode(",",$v);
            }
        }

        //var_dump($this->region);die();

        $this->initRectangles();
        $this->initLinse();

        if($this->checkPoint(-123.18216,49.27347)) echo "true";
        else echo "false";
    }

    private function initRectangles(){
        foreach ($this->region as $k=>$v){
            $this->rectangles['minX'] = $this->getMinxInRegion();
            $this->rectangles['minY'] = $this->getMinyInRegion();
            $this->rectangles['maxX'] = $this->getMaxxInRegion();
            $this->rectangles['maxY'] = $this->getMaxyInRegion();
        }
    }

    private function initLinse(){
        $pointNum = count($this->region);
        $lineNum = $pointNum - 1;

        //y=kx+b;
        for ($i = 0; $i < $lineNum; $i++){
            //计算比例
            if($this->region[$i][0] - $this->region[$i+1][0] == 0){
                $this->lines[$i]['k'] = 0;
            }else{
                $this->lines[$i]['k'] = ($this->region[$i][1] - $this->region[$i+1][1])/($this->region[$i][0] - $this->region[$i+1][0]);
            }

            //x的最大值及最小值
            $this->lines[$i]['b'] = $this->region[$i+1][1] - $this->lines[$i]['k'] * $this->region[$i+1][0];
            $this->lines[$i]['lx'] = min($this->region[$i][0],$this->region[$i+1][0]);
            $this->lines[$i]['rx'] = max($this->region[$i][0],$this->region[$i+1][0]);
            $this->lines[$i]['minY'] = min($this->region[$i][1],$this->region[$i+1][1]);
            $this->lines[$i]['maxY'] = max($this->region[$i][1],$this->region[$i+1][1]);
        }

        if($this->region[$lineNum][0] - $this->region[0][0] == 0){
            $this->lines[$lineNum]['k'] = 0;
        }else{
            $this->lines[$lineNum]['k'] = ($this->region[$lineNum][1] - $this->region[0][1])/($this->region[$lineNum][0] - $this->region[0][0]);
        }

        //x的最大值及最小值
        $this->lines[$i]['b'] = $this->region[0][1] - $this->lines[$lineNum]['k'] * $this->region[0][0];
        $this->lines[$i]['lx'] = min($this->region[$lineNum][0],$this->region[0][0]);
        $this->lines[$i]['rx'] = max($this->region[$lineNum][0],$this->region[0][0]);
        $this->lines[$i]['minY'] = min($this->region[$lineNum][1],$this->region[0][1]);
        $this->lines[$i]['maxY'] = max($this->region[$lineNum][1],$this->region[0][1]);
    }

    private function getMinxInRegion(){
        $minX = 0;
        foreach ($this->region as $k=>$v){
            if($minX == 0){
                $minX = $v[0];
            }else{
                if($v[0] < $minX){
                    $minX = $v[0];
                }
            }
        }

        return $minX;
    }

    private function getMinyInRegion(){
        $minY = 0;
        foreach ($this->region as $k=>$v){
            if($minY == 0){
                $minY = $v[1];
            }else{
                if($v[1] < $minY){
                    $minY = $v[1];
                }
            }
        }

        return $minY;
    }

    private function getMaxxInRegion(){
        $maxX = 0;
        foreach ($this->region as $k=>$v){
            if($maxX == 0){
                $maxX = $v[0];
            }else{
                if($v[0] > $maxX){
                    $maxX = $v[0];
                }
            }
        }

        return $maxX;
    }

    private function getMaxyInRegion(){
        $maxY = 0;
        foreach ($this->region as $k=>$v){
            if($maxY == 0){
                $maxY = $v[1];
            }else{
                if($v[1] > $maxY){
                    $maxY = $v[1];
                }
            }
        }

        return $maxY;
    }

    /**
     * 获取 y=y0 与区域的所有边的交点，并去除和顶点重复的，再叫交点风味左右两个部分
     */
    private function getCrossPointInCertain(){
        $crossPoint = null;

        //是否正好在顶点上
        foreach ($this->region as $p){
            if($p[0] == $this->point_x && $p[1] == $this->point_y){
                return true;
            }
        }

        foreach ($this->lines as $k=>$v){
            //如果有一条垂直边
            if($v['k'] == 0){
                if($this->point_y >= $v['minY'] && $this->point_y <= $v['maxY']){
                    if($v['lx'] < $this->point_x && !in_array($v['lx'],$crossPoint['left'])) $crossPoint['left'][] = $v['lx'];
                    if($v['rx'] > $this->point_x && !in_array($v['rx'],$crossPoint['right'])) $crossPoint['right'][] = $v['rx'];
                    echo "0";
                }
            }else {//其他的斜边
                //交点的x坐标
                $x0 = ($this->point_y - $v['b']) / $v['k'];
                //点在边上
                if ($x0 == $this->point_x) return true;

                if($x0 > $v['lx'] && $x0 < $v['rx']){
                    if($x0 < $this->point_x) $crossPoint['left'][] = $x0;
                    if($x0 > $this->point_x) $crossPoint['right'][] = $x0;
                    echo "1";
                }
            }
        }
        var_dump($crossPoint);
        return $crossPoint;
    }

    public function checkPoint($x,$y){
        $this->point_x = $x;
        $this->point_y = $y;

        if($x > $this->rectangles['maxX'] || $x < $this->rectangles['minX'] || $y > $this->rectangles['maxY'] || $y < $this->rectangles['minY']){
            return false;
        }else{
            $crossPoint = $this->getCrossPointInCertain();
            if($crossPoint === true) return true;
            if(count($crossPoint['left'])%2 == 1 && count($crossPoint['right'])%2 == 1){
                return true;
            }else{
                return false;
            }
        }
    }
}