<?php

require_once('main/myquickform.php');
require_once('database/dbfunctions.php');

class StartForm extends MyQuickForm {

	public function __construct() {
		MyQuickForm::MyQuickForm('form_begin', 'post', APP_BASE_URL, '', '', true);
		$this->applyFilter('__ALL__', 'trim');
		$this->addElement('text', 'bogen_id', '', array('class' => 'cls-input-bogen-id'));
		$this->registerRule('cbBogenIdMustBeValid', 'callback', 'cbBogenIdMustBeValid', $this);
		$this->addRule('bogen_id', 'Ung&uuml;tige Bogen-ID!', 'cbBogenIdMustBeValid');
	}

	function cbBogenIdMustBeValid($passId) {
		return strlen($passId)>0;
	}

	function do_processing() {
		$values = $this->exportValues();
		$_SESSION['bogen_id'] = $values['bogen_id'];
		$_SESSION['pages_filled'] = array();
		$_SESSION['properties'] = array();
		// testen, ob Benutzer schon mal Daten eingetragen hat oder nicht
		// falls nein, Umleiten zur Passwort-Setzen seite, falls ja, Umleiten
		// zur Passwort-Eingabe-Seite
		$dbf = DBfunctions::getInstance();
		$id = $dbf->querySingleValue(
			"SELECT id FROM t_persons WHERE bogen_id=?", "s", $values['bogen_id']
		);
		$this->resetToken();
		if ($id>0) {
			// Person existiert => umleiten zur Passwort-Seite
			header('Location: '.APP_BASE_URL."begin/query_password");
		} else {
			// Person existiert nicht => Passwort setzen lassen
			header('Location: '.APP_BASE_URL."begin/set_password");
		}
	}
}
?>
