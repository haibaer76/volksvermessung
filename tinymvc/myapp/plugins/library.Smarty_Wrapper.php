<?php
require(APP_SMARTY_DIR.'/Smarty.class.php');

class Smarty_Wrapper extends Smarty {
	var $smartydir = 'smarty/';
	function __construct() {
		parent::Smarty();
		$this->compile_dir = $this->smartydir."templates_c/";
		$this->config_dir = $this->smartydir."configs/";
		$this->cache_dir = $this->smartydir."cache/";
		$this->caching = true;
		$this->force_compile = false;
		$this->template_dir = APP_TMVC_DIR."/myapp/views/";
	}

	public function display($tmpl=null) {
		parent::display(is_null($tmpl)?'main.tpl':$tmpl);
	}
}
?>
