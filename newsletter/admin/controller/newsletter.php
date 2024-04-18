<?php
namespace Opencart\Admin\Controller\Extension\Opencart\Module;
/**
 * Class Newsletter
 *
 * @package Opencart\Admin\Controller\Extension\Opencart\Module
 */
class Newsletter extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('extension/opencart/module/newsletter');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module')
		];

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = [
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/opencart/module/newsletter', 'user_token=' . $this->session->data['user_token'])
			];
		} else {
			$data['breadcrumbs'][] = [
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/opencart/module/newsletter', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'])
			];
		}

		if (!isset($this->request->get['module_id'])) {
			$data['save'] = $this->url->link('extension/opencart/module/newsletter.save', 'user_token=' . $this->session->data['user_token']);
		} else {
			$data['save'] = $this->url->link('extension/opencart/module/newsletter.save', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id']);
		}

		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module');

		if (isset($this->request->get['module_id'])) {
			$this->load->model('setting/module');

			$module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
		}

		if (isset($module_info['name'])) {
			$data['name'] = $module_info['name'];
		} else {
			$data['name'] = '';
		}

		if (isset($module_info['title'])) {
			$data['title'] = $module_info['title'];
		} else {
			$data['title'] = '';
		}

		if (isset($module_info['text'])) {
			$data['text'] = $module_info['text'];
		} else {
			$data['text'] = '';
		}


		if (isset($module_info['status'])) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}

		if (isset($this->request->get['module_id'])) {
			$data['module_id'] = (int)$this->request->get['module_id'];
		} else {
			$data['module_id'] = 0;
		}

		// $data['report'] = $this->getReport();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');


		$this->response->setOutput($this->load->view('extension/opencart/module/newsletter', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('extension/opencart/module/newsletter');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/opencart/module/newsletter')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		if ((oc_strlen($this->request->post['name']) < 3) || (oc_strlen($this->request->post['name']) > 64)) {
			$json['error']['name'] = $this->language->get('error_name');
		}

		if (!$json) {
			$this->load->model('setting/module');

			if (!$this->request->post['module_id']) {
				$json['module_id'] = $this->model_setting_module->addModule('opencart.newsletter', $this->request->post);
			} else {

				$this->model_setting_module->editModule($this->request->post['module_id'], $this->request->post);

				$this->load->model('extension/opencart/module/newsletter');
				$this->model_extension_opencart_module_newsletter->save($this->request->post['name'], $this->request->post['title'], $this->request->post['text']);

			}


			// $json['success'] = $this->language->get('text_success');
			$json['success'] = $this->request->post;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}


	/**
	 * Install
	 *
	 * @return void
	 */
	public function install(): void {
		if ($this->user->hasPermission('modify', 'extension/opencart/module/newsletter')) {
			$this->load->model('extension/opencart/module/newsletter');

			$this->model_extension_opencart_module_newsletter->install();
		}
	}

	/**
	 * Uninstall
	 *
	 * @return void
	 */
	public function uninstall(): void {
		if ($this->user->hasPermission('modify', 'extension/opencart/module/newsletter')) {
			$this->load->model('extension/opencart/module/newsletter');

			$this->model_extension_opencart_module_newsletter->uninstall();
		}
	}

	// /**
	//  * Report
	//  *
	//  * @return void
	//  */
	// public function report(): void {
	// 	$this->load->language('extension/opencart/module/newsletter');

	// 	$this->response->setOutput($this->getReport());
	// }

	// /**
	//  * Get Report
	//  *
	//  * @return string
	//  */
	// public function getReport(): string {
	// 	if (isset($this->request->get['page']) && $this->request->get['route'] == 'extension/opencart/module/newsletter.report') {
	// 		$page = (int)$this->request->get['page'];
	// 	} else {
	// 		$page = 1;
	// 	}

	// 	$limit = 10;

	// 	$data['reports'] = [];

	// 	$this->load->model('extension/opencart/module/newsletter');
	// 	$this->load->model('catalog/product');

	// 	$results = $this->model_extension_opencart_module_newsletter->getReports(($page - 1) * $limit, $limit);

	// 	foreach ($results as $result) {
	// 		$product_info = $this->model_catalog_product->getProduct($result['product_id']);

	// 		if ($product_info) {
	// 			$product = $product_info['name'];
	// 		} else {
	// 			$product = '';
	// 		}

	// 		$data['reports'][] = [
	// 			'product' => $product,
	// 			'total'   => $result['total'],
	// 			'edit'    => $this->url->link('catalog/product.edit', 'user_token=' . $this->session->data['user_token'] . '&product_id=' . $result['product_id'])
	// 		];
	// 	}

	// 	$report_total = $this->model_extension_opencart_module_newsletter->getTotalReports();

	// 	$data['pagination'] = $this->load->controller('common/pagination', [
	// 		'total' => $report_total,
	// 		'page'  => $page,
	// 		'limit' => $limit,
	// 		'url'   => $this->url->link('extension/opencart/module/newsletter.report', 'user_token=' . $this->session->data['user_token'] . '&page={page}')
	// 	]);

	// 	$data['results'] = sprintf($this->language->get('text_pagination'), ($report_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($report_total - $limit)) ? $report_total : ((($page - 1) * $limit) + $limit), $report_total, ceil($report_total / $limit));

	// 	return $this->load->view('extension/opencart/module/newsletter_report', $data);
	// }

	/**
	 * Sync
	 *
	 * @return void
	 */
	public function sync(): void {
		$this->load->language('extension/opencart/module/newsletter');

		$json = [];

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		if (!$this->user->hasPermission('modify', 'extension/opencart/module/newsletter')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('extension/opencart/module/newsletter');
			$this->load->model('catalog/product');
			$this->load->model('sale/order');

			$total = $this->model_catalog_product->getTotalProducts();
			$limit = 10;

			$start = ($page - 1) * $limit;
			$end = $start > ($total - $limit) ? $total : ($start + $limit);

			$product_data = [
				'start' => ($page - 1) * $limit,
				'limit' => $limit
			];

			$results = $this->model_catalog_product->getProducts($product_data);

			foreach ($results as $result) {
				$product_total = $this->model_sale_order->getTotalProductsByProductId($result['product_id']);

				if ($product_total) {
					$this->model_extension_opencart_module_newsletter->editTotal($result['product_id'], $product_total);
				} else {
					$this->model_extension_opencart_module_newsletter->delete($result['product_id']);
				}
			}

			if ($end < $total) {
				$json['text'] = sprintf($this->language->get('text_next'), $end, $total);

				$json['next'] = $this->url->link('extension/opencart/module/newsletter.sync', 'user_token=' . $this->session->data['user_token'] . '&page=' . ($page + 1), true);
			} else {
				$json['success'] = sprintf($this->language->get('text_next'), $end, $total);

				$json['next'] = '';
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
