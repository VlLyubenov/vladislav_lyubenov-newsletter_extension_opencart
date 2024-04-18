<?php

namespace Opencart\Catalog\Controller\Extension\Opencart\Module;
/**
 * Class Newsletter
 *
 * @package Opencart\Catalog\Controller\Extension\Opencart\Module
 */
class Newsletter extends \Opencart\System\Engine\Controller {
    /**
     * Index
     *
     * @param array<string, mixed> $setting
     *
     * @return string
     */


	public function index (array $setting) {
        $this->load->language('extension/opencart/module/newsletter');
		$this->load->model('extension/opencart/module/newsletter');

		$name = $setting['name'];
		$data = $this->model_extension_opencart_module_newsletter->getNewsletter($name);

		return $this->load->view('extension/opencart/module/newsletter', $data[0]);

	}

}
