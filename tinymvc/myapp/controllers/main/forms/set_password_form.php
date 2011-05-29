<?php

require_once('main/myquickform.php');
require_once('database/dbfunctions.php');

class SetPasswordForm extends MyQuickForm {

	public function __construct() {
		MyQuickForm::MyQuickForm('form_setpasswd', 'post', APP_BASE_URL.'begin/set_password', '', '', true);
		$this->applyFilter('__ALL__', 'trim');
		$this->addElement('hidden', 'bogen_id', $_SESSION['bogen_id']);
		$this->addElement('password', 'pw1', 'Neues Passwort:', array('class' => 'cls-input-password'));
		$this->addElement('password', 'pw2', 'Passwort wiederholen:', array('class' => 'cls-input-password'));
		$this->addRule('pw1', 'Das Passwort muss mindestens 5 Zeichen lang sein', 'minlength', 5);
		$this->addRule(array('pw1', 'pw2'), 'Die eingegebenen Passw&ouml;rter stimmen nicht &uuml;berein', 'compare');
		$this->registerRule('cbBogenIdMustMatch', 'callback', 'cbBogenIdMustMatch', $this);
		$this->addRule('bogen_id', 'Die Bogen-ID muss passen!', 'cbBogenIdMustMatch');
	}

	function cbBogenIdMustMatch($bogenId) {
		return $bogenId==$_SESSION['bogen_id'];
	}

	function do_processing() {
		$values = $this->exportValues();
		$pw_salt = sha1(rand());
		DBfunctions::getInstance()->doSingleCall(
			"INSERT INTO t_persons(bogen_id, password_hash, password_salt) VALUES (?, SHA1(CONCAT(?, ?)), ?)", "ssss", $_SESSION['bogen_id'], $values['pw1'], $pw_salt, $pw_salt);
		$_SESSION['person_id'] = DBfunctions::getInstance()->last_insert_id();
		$this->resetToken();
	}
}


?>
