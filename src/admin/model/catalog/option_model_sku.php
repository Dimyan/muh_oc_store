<?php
class ModelCatalogOptionModelSku extends Model {
	public function editOptionModelSku($option_id, $data) {
		if (isset($data['option_value'])) {
			foreach ($data['option_value'] as $option_value) {
				$this->db->query("UPDATE `" . DB_PREFIX . "option_value` SET `model` = '" . $this->db->escape($option_value['model']) . "', `sku` = '" . $this->db->escape($option_value['sku']) . "' WHERE `option_id` = '" . (int)$option_id . "'");
			}
		}
	}
}
?>