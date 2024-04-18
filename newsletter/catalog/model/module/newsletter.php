<?php
namespace Opencart\Catalog\Model\Extension\Opencart\Module;
/**
 * Class Newsletter
 *
 * @package Opencart\Catalog\Model\Extension\Opencart\Module
 */
class Newsletter extends \Opencart\Catalog\Model\Catalog\Product {

	/**
	 * Newsletter
	 *
	 *
	 * @return array
	 */
	public function getNewsletter ($name) {

		$query = $this->db->query("SELECT * FROM `" .DB_PREFIX. "newsletter` WHERE module_name='" .$name. "'");
		return $query->rows;

	}


}
