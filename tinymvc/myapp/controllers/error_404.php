<?php

class error_404_Controller extends FW_Controller {

	function __construct() {
		parent::__construct();
	}

	public function index() {
		$tmvc = tmvc::instance();

		$tmvc->smarty->assign('controller_name', ERROR_404_WANTED_PAGE);
		$tmvc->smarty->display('error_404.tpl');
	}
}
?>
