<?php
require_once('main/myquickform.php');
require_once('HTML/QuickForm/altselect.php');

class Question {

	public $property;
	public $label;

	public function __construct($xml) {
		$this->property = (string)$xml->attributes()->property;
		$this->label = (string)$xml->label;
	}

	public function addToForm($form, $currentValue) {
		die("You must implement this method in a subclass!");
	}

	public function processFormValues($values) {
		die("You must implement this method in a subclass");
	}

	public function getSessionValue() {
		return $_SESSION['properties'][$this->property];
	}
}

class SelectSingleQuestion extends Question {

	private $possibleValues;
	private $altlabel;

	public function __construct($xml) {
		parent :: __construct($xml);
		$this->possibleValues=array();
		foreach($xml->value as $val)
			$this->possibleValues[] = (string)$val;
		if (!is_null($xml->altlabel))
			$this->altlabel = (string)($xml->altlabel);
	}

	public function addToForm($form, $currentValue) {
		$hlp = array();
		foreach($this->possibleValues as $val)
			$hlp[$val] = $val;
		$elem = $form->addElement('altselect', $this->property, $this->label, $hlp);
		$elem->setListType('ul');
		if (!is_null($this->altlabel) && strlen($this->altlabel)>0) {
			$elem->setIncludeOther(true);
			$elem->otherLabel = $this->altlabel;
			$elem->otherText = '';
		}
		$form->addRule($this->property, 'Bitte gib etwas an!', 'required');
		if (isset($_SESSION['properties'][$this->property]))
			$elem->setSelected($_SESSION['properties'][$this->property]);
		$elem->setAttribute('id', "attr_{$this->property}");
	}

	public function processFormValues($values) {
		if(is_array($values[$this->property]))
			$_SESSION['properties'][$this->property] = current($values[$this->property]);
		else
			$_SESSION['properties'][$this->property] = $values[$this->property];
	}
}

class SelectMultiQuestion extends Question {

	private $possibleValues;
	private $altlabel;

	public function __construct($xml) {
		parent :: __construct($xml);
		$this->possibleValues = array();
		foreach($xml->value as $value)
			$this->possibleValues[] = (string)$value;
		$this->altlabel=(string)$xml->altlabel;
	}

	public function addToForm($form, $currentValue) {
		$hlp = array();
		foreach($this->possibleValues as $val)
			$hlp[$val] = $val;

		$elem = $form->addElement('altselect', $this->property, $this->label, $hlp);
		$elem->setListType('ul');
		if (!is_null($this->altlabel)) {
			$elem->setIncludeOther(true);
			$elem->otherLabel = $this->altlabel;
			$elem->otherText = '';
		}
		$elem->setMultiple(true);
		if (isset($_SESSION['properties'][$this->property])) {
			$arr = explode("||", $_SESSION['properties'][$this->property]);
			$sel = array();
			$alt = "";
			foreach($arr as $s) {
				if (array_search($s, $this->possibleValues)===FALSE)
					$sel[] = $s;
				else {
					if (strlen($alt)>0)
						$alt.="||";
					$alt.=$s;
				}
			}
			if (strlen($alt)>0)
				$sel[] = $alt;
			$elem->setSelected($sel);
		}
		$elem->otherTextMultiple = $this->altlabel;
		$elem->setAttribute('id', "attr_{$this->property}");
		$form->addRule($this->property, 'Bitte gib etwas an!', 'cbAltSelectOneNeeded');
	}

	public function processFormValues($values) {
		$_SESSION['properties'][$this->property] = implode('||', $values[$this->property]);
	}

	public function getSessionValue() {
		return implode(", ", explode("||", $_SESSION['properties'][$this->property]));
	}
}

class IntQuestion extends Question {

	private $min=null;
	private $max=null;
	public function __construct($xml) {
		parent :: __construct($xml);
		$attrs = $xml->attributes();
		$this->min=$attrs['min'];
		$this->max=$attrs['max'];
	}

	public function addToForm($form, $currentValue) {
		$form->addElement('text', $this->property, $currentValue);
		$form->addRule($this->property, 'Gib einen Wert ein!', 'required');
		$form->addRule($this->property, 'Gib eine Zahl ein!', 'numeric');
		if (!is_null($this->min) || !is_null($this->max)) {
			$form->registerRule("cb_minmax_{$this->property}", 'callback', 'cbMinMax', $this);
			if (is_null($this->min))
				$str = "Bitte gib eine Zahl nicht gr&ouml;&szlig;er als {$this->max} ein";
			else if (is_null($this->max))
				$str = "Bitte gib eine Zahl von mindestens {$this->min} ein!";
			else
				$str = "Bitte gib eine Zahl von {$this->min} bis {$this->max} ein!";
			$form->addRule($this->property, $str, "cb_minmax_{$this->property}");
		}
		$form->addDefault($this->property, $_SESSION['properties'][$this->property]);
	}

	function cbMinMax($val) {
		if (!is_null($this->min) && intval($val)<$this->min)
			return false;
		if (!is_null($this->max) && intval($val)>$this->max)
			return false;
		return true;
	}

	public function processFormValues($values) {
		$_SESSION['properties'][$this->property] = $values[$this->property];
	}
}

class QuestionPage {

	public $questions;
	public $heading;

	private $template;
	private $pageNumber;

	public function __construct($xml, $pageIndex) {
		$this->questions = array();
		$this->heading = (string)$xml->heading;
		$attrs = $xml->attributes();
		if (isset($attrs['template'])) {
			$this->template = $attrs['template'];
		} else {
			$this->template = 'default_question_page.tpl';
		}
		$this->pageNumber = $pageIndex+1;

		foreach($xml->question as $question) {
			$attrs = $question->attributes();
			switch($attrs['type']) {
			case 'select_single':
				$this->questions[] = new SelectSingleQuestion($question);
				break;
			case 'int':
				$this->questions[] = new IntQuestion($question);
				break;
			case 'select_multi':
				$this->questions[] = new SelectMultiQuestion($question);
				break;
			default:
				trigger_error("Don't know how to handle type {$attrs['type']}");
			}
		}
	}

	public function createForm() {
		$form = new MyQuickForm('form_questions', 'post', APP_BASE_URL."fragen/seite/{$this->pageNumber}", '', '', true);
		foreach($this->questions as $q)
			$q->addToForm($form);
		return $form;
	}

	public function buildContent($smarty) {
		$smarty->assign('question_heading', $this->heading);
		$smarty->assign('questions', $this->createSmartyQuestionArray());
		$smarty->assign('page_number', $this->pageNumber);
		return $smarty->fetch($this->template);
	}

	private function createSmartyQuestionArray() {
		$ret = array();
		foreach($this->questions as $question) {
			$ret[] = array(
				'property' => $question->property,
				'label' => htmlspecialchars($question->label)
			);
		}
		return $ret;
	}

	public function processForm($form) {
		$values = $form->exportValues();
		foreach($this->questions as $question) {
			$question->processFormValues($values);
		}
	}
}

class QuestionCollection {

	public $questionPages;

	public function __construct($questionFile) {
		$questionxml = simplexml_load_file($questionFile);
		$this->questionPages = array();
		$index = 0;
		foreach($questionxml->page as $page) {
			$this->questionPages[] = new QuestionPage($page, $index);
			$index++;
		}
	}

	public function buildOutput($smarty) {
		$propArr = array();
		foreach($this->questionPages as $qp) {
			foreach($qp->questions as $q) {
				$propArr[] = array(
					'label' => $q->label,
					'value' => $q->getSessionValue()
				);
			}
		}
		$smarty->assign('properties', $propArr);
		return $smarty->fetch('answers.tpl');
	}
}

?>
