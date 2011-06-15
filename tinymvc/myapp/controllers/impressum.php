<?php

class impressum_controller extends FW_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		$tmvc = tmvc::instance();
		$tmvc->smarty->assign('content', $tmvc->smarty->fetch('impressum.tpl'));
		$tmvc->smarty->display();
	}
}

?>
