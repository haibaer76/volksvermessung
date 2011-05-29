<?php
require_once('HTML/QuickForm.php');
require_once('HTML/QuickForm/Renderer/ArraySmarty.php');

class MyQuickForm extends HTML_QuickForm {
	private $name;
	private $savedDefaults = array();

	function MyQuickForm($formName = "", $method = "post", $action='', $target='', $attributes = null, $trackSubmit = false) {
		HTML_QuickForm::HTML_QuickForm($formName, $method, $action, $target, $attributes, $trackSubmit);

		$this->name = $formName;
		$this->addElement('hidden', '__qf_token_'.$this->name, $this->name."EMPTY");
    $this->registerRule('prevent_from_reload', 'callback', 'cbFormShouldOnlyBeSubmittedOnce');
		$this->addRule('__qf_token_'.$formName, "Form was already sent", 'prevent_from_reload');
		$this->registerRule('cbAltSelectOneNeeded', 'callback', 'cbAltSelectOneNeeded', $this);
	}

	public function addReloadLock() {
		$token = md5(rand());
		$this->getElement('__qf_token_'.$this->name)->setValue($this->name."::$token");
		$_SESSION['__qf_token_'.$this->name] = $token;
	}

	function assignToSmarty($smarty) {
		$formRenderer = new HTML_QuickForm_Renderer_ArraySmarty($smarty);
		$formRenderer->setErrorTemplate('{if $error}<span class="cls-form-error" style="color: red; font-size: 0.7em;">{$error}</span><br/>{/if}{$html}');
		$this->accept($formRenderer);
		$smarty->assign($this->name."_form_data", $formRenderer->toArray());
	}

	function process($callback, $mergeFiles=true) {
		$ret = parent::process($callback, $mergeFiles);
		$this->resetToken();
		return $ret;
	}

	function resetToken() {
		$_SESSION['__qf_token_'.$this->name] = "EMPTY";
	}

	function setDefaults($defaults, $filter = null) {
		$this->savedDefaults = $defaults;
		parent::setDefaults($defaults, $filter);
	}

	function addDefault($name, $value) {
		$this->savedDefaults[$name] = $value;
		parent::setDefaults($this->savedDefaults);
	}

	function cbAltSelectOneNeeded($data) {
		$arr = array();
		foreach($data as $d) {
			if(!is_null($d) && strlen($d)>0)
				$arr[] = $d;
		}
		return count($arr)>0;
	}
}

function cbFormShouldOnlyBeSubmittedOnce($data) {
	$arr = explode("::", $data);
	error_log("Token = ".$arr[1]);
	$token = $_SESSION['__qf_token_'.$arr[0]];
	error_log("Session-Token=$token");
	return $token == $arr[1];
}
?>
