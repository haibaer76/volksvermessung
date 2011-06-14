<?php
if(!defined('DS'))
	define('DS', DIRECTORY_SEPARATOR);
if(!defined('PS'))
	define('PS', PATH_SEPARATOR);
require('db_config.php');
if (!defined('TMVC_BASEDIR'))
	define('TMVC_BASEDIR', APP_TMVC_DIR);


ini_set("include_path", APP_TMVC_DIR."/myapp/controllers/" . PS . '.' . PS . ini_get("include_path"));

session_start();

require(APP_TMVC_DIR . 'sysfiles' . DS . 'TinyMVC.php');
?>
