<?php
require_once("main/forms/start_form.php");
require_once("main/forms/set_password_form.php");
require_once("main/forms/query_password_form.php");

class begin_controller extends FW_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		$tmvc = tmvc::instance();
		$form = new StartForm();
		if ($form->validate()) {
			$form->do_processing();
		} else {
			$form->addReloadLock();
			$form->assignToSmarty($tmvc->smarty);
			$tmvc->smarty->assign('content', $tmvc->smarty->fetch('begin.tpl'));
			$tmvc->smarty->display();
		}
	}

	public function set_password() {
		$tmvc = tmvc::instance();
		$tmvc->smarty->assign('bogen_id', $_SESSION['bogen_id']);
		$form = new SetPasswordForm();
		if ($form->validate()) {
			$form->do_processing();
			$_SESSION['properties'] = array();
			header("Location: ".APP_BASE_URL."fragen/seite/1");
		} else {
			$form->addReloadLock();
			$form->assignToSmarty($tmvc->smarty);
			$tmvc->smarty->assign('content', $tmvc->smarty->fetch('set_password.tpl'));
			$tmvc->smarty->display();
		}
	}

	public function query_password() {
		$tmvc = tmvc::instance();
		$form = new QueryPasswordForm();
		if ($form->validate()) {
			$_SESSION['person_id'] = DBfunctions::getInstance()->querySingleValue(
				"SELECT id FROM t_persons WHERE bogen_id=?", "s", $_SESSION['bogen_id']);
			header("Location: ".APP_BASE_URL."fragen/seite/1");
		} else {
			$form->addReloadLock();
			$form->assignToSmarty($tmvc->smarty);
			$tmvc->smarty->assign('content', $tmvc->smarty->fetch('query_password.tpl'));
			$tmvc->smarty->display();
		}
	}

	public function change_data() {
		$tmvc = tmvc::instance();
		$tmvc->smarty->assign('content', 'Daten aendern');
		$tmvc->smarty->display();
	}
}

?>
