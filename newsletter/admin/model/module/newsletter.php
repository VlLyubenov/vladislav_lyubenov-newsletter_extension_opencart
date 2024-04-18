<?php
namespace Opencart\Admin\Model\Extension\Opencart\Module;
/**
 * Class newsletter
 *
 * @package Opencart\Admin\Model\Extension\Opencart\Module
 */
class Newsletter extends \Opencart\System\Engine\Model {
	/**
	 * Install
	 *
	 * @return void
	 */
	public function install(): void {
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "newsletter` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `module_name` varchar(355),
		  `title` varchar(355),
		  `text` longtext,
		  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
	}

	/**
	 * Uninstall
	 *
	 * @return void
	 */
	public function uninstall(): void {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "newsletter`");
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save (string $module_name, string $title, string $text): void {

		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "newsletter`
								   WHERE module_name='" .$module_name. "'");
		$num_rows = $query->row['total'];

		if (!$num_rows = 0)
		{
		$this->db->query("UPDATE `" . DB_PREFIX . "newsletter`
						  SET title='" .$title. "', text='" .$text. "'
						  WHERE module_name='" .$module_name. "'");
		}
		else
		{
			$this->db->query("INSERT INTO `" . DB_PREFIX . "newsletter` (module_name, title, text)
							  VALUES ('" .$module_name. "', '" .$title. "', '" .$text. "')
							  ");
		}
	}





}
