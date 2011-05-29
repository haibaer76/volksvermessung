<?php
if(!defined('DS'))
	define('DS', DIRECTORY_SEPARATOR);
if(!defined('PS'))
	define('PS', PATH_SEPARATOR);
if (!defined('TMVC_BASEDIR'))
	define('TMVC_BASEDIR', dirname(__FILE__) . DS . '..'. DS . 'tinymvc' . DS);

require('db_config.php');

ini_set("include_path", APP_TMVC_DIR."/myapp/controllers/" . PS . '.' . PS . ini_get("include_path"));

session_start();

require(APP_TMVC_DIR . 'sysfiles' . DS . 'TinyMVC.php');
?>
