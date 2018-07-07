<?php
class Shop_discountModel extends Model
{

	public function get_discount_byids($store_ids = array(), $is_all = true)
	{
		if ($is_all) {
			$sql = "SELECT `id`, `full_money`, `reduce_money`, `type`, `source`, `status`, `create_time`, `store_id`, `mer_id` FROM " . C('DB_PREFIX') . "shop_discount WHERE (`source`=0";
			if ($store_ids) {
				$str = implode(',', $store_ids);
				$sql .= " OR (`source`=1 AND `store_id` IN ({$str}))) AND `status`=1";
			} else {
				$sql .= " ) AND `status`=1";
			}
			$result = $this->query($sql);
		} elseif ($store_ids) {
			$result = $this->field(true)->where(array('store_id' => array('in', $store_ids, 'status' => 1)))->select();
		} else {
			return null;
		}
		$list = array();
		foreach ($result as $row) {
			if ($row['source'] == 0) {
				$list[0][] = $row;
			} elseif ($row['store_id'] && $row['source'] == 1) {
				$list[$row['store_id']][] = $row;
			}
		}
		return $list;
	}
}
?>