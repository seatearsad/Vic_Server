<?php
/*
 * 得到商品的图片
 *
 */
class scenic_image
{
	/*根据商品数据表的图片字段来得到图片*/
	public function get_image_by_path($path, $site_url='',$file,$image_type='-1')
	{
		if (!empty($path)) {
			$image_tmp = explode(',', $path);
			if ($image_type == '-1') {
				$return['image'] = $site_url . '/upload/scenic/'.$file.'/' . $image_tmp[0] . '/' . $image_tmp['1'];
				$return['m_image'] = $site_url . '/upload/scenic/'.$file.'/' . $image_tmp[0].'/m_' . $image_tmp['1'];
				$return['s_image'] = $site_url . '/upload/scenic/'.$file.'/' . $image_tmp[0].'/s_' . $image_tmp['1'];
			} else {
				$return = $site_url . '/upload/scenic/'.$file.'/' . $image_tmp[0] . '/' . $image_tmp['1'];
			}
			return $return;
		} else {
			return false;
		}
	}
	/*根据商品数据表的图片字段来得到图片*/
	public function get_car_by_path($path, $site_url='',$file,$image_type='-1')
	{
		if (!empty($path)) {
			$image_tmp = explode(',', $path);
			if ($image_type == '-1') {
				$return['image'] = $site_url . '/upload/'.$file.'/' . $image_tmp[0] . '/' . $image_tmp['1'];
				$return['m_image'] = $site_url . '/upload/'.$file.'/' . $image_tmp[0].'/m_' . $image_tmp['1'];
				$return['s_image'] = $site_url . '/upload/'.$file.'/' . $image_tmp[0].'/s_' . $image_tmp['1'];
			} else {
				$return = $site_url . '/upload/'.$file.'/' . $image_tmp[0] . '/' . $image_tmp['1'];
			}
			return $return;
		} else {
			return false;
		}
	}
	/*根据商品数据表的自增ID字段来得到图片*/
	public function get_image_by_id($id, $site_url, $image_type){
		$database_goods_sort = D('Shop_goods_sort');
		$condition_goods_sort['sort_id'] = $id;
		$now_sort = $database_goods_sort->field('`image`')->where($condition_goods_sort)->find();
		return $this->get_image_by_path($now_sort['image'], $site_url, $image_type);
	}

	/*根据商品数据表的图片字段来删除图片*/
	public function del_image_by_path($path,$file){
		if (!empty($path)) {
			$image_tmp = explode(',',$path);
			unlink('./upload/scenic/'.$file.'/'.$image_tmp[0].'/'.$image_tmp['1']);
			unlink('./upload/scenic/'.$file.'/'.$image_tmp[0].'/m_'.$image_tmp['1']);
			unlink('./upload/scenic/'.$file.'/'.$image_tmp[0].'/s_'.$image_tmp['1']);
			return true;
		} else {
			return false;
		}
	}
	/*根据商品数据表的自增ID字段来得到图片*/
	public function del_image_by_id($id){
		$database_sort = D('Scenic_guide');
		$condition_sort['guide_id'] = $id;
		$now_sort = $database_sort->field('`guide_pig`')->where($condition_sort)->find();
		return $this->del_image_by_path($now_sort['guide_pig']);
	}
}
?>