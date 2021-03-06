<?php

class Home extends Controller {
	public function index() {

		// SITE DETAILS
		$data['app']['url']			= $this->config->get('base_url');
		$data['app']['title']		= $this->config->get('site_title');
		$data['app']['theme']		= $this->config->get('app_theme');

		// ADD STYLES / SCRIPTS
		$this->document->add_style('core.css');
		$this->document->add_style('home.index.css');
		$this->document->add_script('core.js');

		// HEADER / FOOTER
		$data['template']['header']		= $this->load->controller('common/header', $data);
		$data['template']['navbar']		= $this->load->controller('common/navbar', $data);
		$data['template']['footer']		= $this->load->controller('common/footer', $data);

		// RENDER VIEW
		$this->load->view('home/index', $data);

	}
}

?>