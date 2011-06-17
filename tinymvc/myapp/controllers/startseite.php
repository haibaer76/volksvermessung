<?php
class startseite_controller extends FW_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		$tmvc = tmvc::instance();
		$tmvc->smarty->assign('content', $tmvc->smarty->fetch('startseite.tpl'));
		$tmvc->smarty->display();
	}
}
?>
