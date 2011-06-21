<?php
require_once('main/classes/questions.php');

class fragen_controller extends FW_Controller {

	private $questionCollection;

	function __construct() {
		parent::__construct();
		$this->questionCollection = new QuestionCollection(APP_QUESTION_FILE);
	}

	function seite() {
		$tmvc = tmvc::instance();
		$page_number = intval($tmvc->URI->segment(2));
		if ($page_number<1 || $page_number>count($this->questionCollection->questionPages))
			trigger_error("Illegal Page Number $page_number!");
		$tst = 1;
		while($tst<$page_number) {
			if (!isset($_SESSION['pages_filled'][$tst]))
				header("Location: ".APP_BASE_URL."fragen/seite/$tst");
			$tst++;
		}
		$page_index = $page_number-1;
		$questionPage = $this->questionCollection->questionPages[$page_index];
		$form = $questionPage->createForm();
		if ($form->validate()) {
			$questionPage->processForm($form);
			$_SESSION['pages_filled'][$page_number] = true;
			$form->resetToken();
			if ($page_number==count($this->questionCollection->questionPages))
				header("Location: ".APP_BASE_URL."fragen/abschliessen");
			else
				header("Location: ".APP_BASE_URL."fragen/seite/".($page_number+1));
		} else {
			$form->addReloadLock();
			$tmvc->smarty->assign('total_pages', count($this->questionCollection->questionPages));
			if ($page_number>1)
				$tmvc->smarty->assign('previous_question_link', 'fragen/seite/'.($page_number-1));
			$form->assignToSmarty($tmvc->smarty);
			$tmvc->smarty->assign('content', $questionPage->buildContent($tmvc->smarty));
			$tmvc->smarty->display();
		}
	}

	public function abschliessen() {
		for($i=0;$i<count($this->questionCollection->questionPages);$i++) {
			if (!$_SESSION['pages_filled'][$i+1])
				header("Location: ".APP_BASE_URL."fragen/seite/".($i+1));
		}
		$tmvc = tmvc::instance();
		$form = new MyQuickForm('form_finish', 'post', APP_BASE_URL.'fragen/abschliessen', '', '', true);
		if ($form->validate()) {
			DBfunctions::getInstance()->doSingleCall(
				"DELETE FROM t_person_properties WHERE person_id=?", "i", $_SESSION['person_id']);
			foreach($_SESSION['properties'] as $prop => $val) {
				DBfunctions::getInstance()->doSingleCall(
					"INSERT INTO t_person_properties(person_id, property, value) VALUES (?, ?, ?)", "iss", $_SESSION['person_id'], $prop, $val);
			}
			DBfunctions::getInstance()->doSingleCall(
				"UPDATE t_persons SET submitted_at=NOW() WHERE id=?", "i", $_SESSION['person_id']);
			$form->resetToken();
			header("Location: ".APP_BASE_URL."ende");
		} else {
			$form->addReloadLock();
			$form->assignToSmarty($tmvc->smarty);
			$tmvc->smarty->assign('content', $this->questionCollection->buildOutput($tmvc->smarty));
			$tmvc->smarty->display();
		}
	}
}
?>
