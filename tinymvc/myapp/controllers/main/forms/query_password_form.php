<?php

require_once('main/myquickform.php');
require_once('database/dbfunctions.php');

class QueryPasswordForm extends MyQuickForm {

	public function __construct() {
		MyQuickForm::MyQuickForm('form_querypasswd', 'post', APP_BASE_URL.'begin/query_password', '', '', true);
		$this->applyFilter('__ALL__', 'trim');
		$this->addElement('password', 'passwd', 'Passwort:', array('class' => 'cls-input-password'));
		$this->registerRule('cbPasswordMustMatch', 'callback', 'cbPasswordMustMatch', $this);
		$this->addRule('passwd', 'Das Passwort ist falsch!', 'cbPasswordMustMatch');
	}

	function cbPasswordMustMatch($passwd) {
		$count = DBfunctions::getInstance()->querySingleValue(
			"SELECT COUNT(*) FROM t_persons WHERE bogen_id=? AND SHA1(CONCAT(?, password_salt))=password_hash", "ss", $_SESSION['bogen_id'], $passwd);
		return $count>0;
	}
}
?>
