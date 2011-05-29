<?php
/**
 * Advanced usage of HTML_AJAX_Server
 * Allows for a single server to manage exporting a large number of classes without high overhead per call
 * Also gives a single place to handle setup tasks especially useful if session setup is required
 *
 * The server responds to ajax calls and also serves the js client libraries, so they can be used directly from the PEAR data dir
 * 304 not modified headers are used when server client libraries so they will be cached on the browser reducing overhead
 *
 * @category   HTML
 * @package    AJAX
 * @author     Joshua Eichorn <josh@bluga.net>
 * @copyright  2005 Joshua Eichorn
 * @license    http://www.opensource.org/licenses/lgpl-license.php  LGPL
 * @version    Release: 0.4.0
 * @link       http://pear.php.net/package/HTML_AJAX
 */
 // $Id: auto_server.php,v 1.1.1.1 2006/03/17 14:27:59 thesee Exp $

 // include the server class
include 'HTML/AJAX/Server.php';


// extend HTML_AJAX_Server creating our own custom one with init{ClassName} methods for each class it supports calls on
class LiveServer extends HTML_AJAX_Server {
	// this flag must be set to on init methods
	var $initMethods = true;

	// init method for the livesearch class, includes needed files an registers it for ajax
	function initLivesearch() {
		include 'livesearch.class.php';
		$this->registerClass(new livesearch());
	}
}

// create an instance of our test server
$server = new LiveServer();

// you can use HTML_AJAX_Server to deliver your own custom javascript libs, when used with comma seperated client lists you can
// use just one javascript include for all your library files
// example url: auto_server.php?client=auto_server.php?client=Util,Main,Request,HttpClient,Dispatcher,Behavior,customLib
$server->registerJSLibrary('QfLiveSearch','live.js','./');

// handle requests as needed
$server->handleRequest();
?>