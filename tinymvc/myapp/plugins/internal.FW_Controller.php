<?php

class FW_Controller extends TinyMVC_Controller {
	public function __construct() {
		parent::__construct();
		header("Content-Type: text/html; charset=utf-8");
		$this->load->library("URI");
		$this->load->library('Smarty_Wrapper', 'smarty');
		$tmvc = tmvc::instance();
		$tmvc->smarty = $this->smarty;
		$tmvc->smarty->assign('base_url', APP_BASE_URL);
	}
}
?>
