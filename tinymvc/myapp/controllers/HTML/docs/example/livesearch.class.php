<?php
/**
 * Simple test class for doing fake livesearch
 *
 * @category   HTML
 * @package    AJAX
 * @author     Joshua Eichorn <josh@bluga.net>
 * @copyright  2005 Joshua Eichorn
 * @license    http://www.opensource.org/licenses/lgpl-license.php  LGPL
 * @version    Release: 0.4.0
 * @link       http://pear.php.net/package/HTML_AJAX
 */
 // $Id: livesearch.class.php,v 1.1.1.1 2006/03/17 14:27:59 thesee Exp $
class livesearch {
	/**
	 * Items to search against
	 */
	var $livesearch = array(
		1 => 'Orange',
		2 => 'Apple',
		3 => 'Pear',
		4 => 'Banana',
		5 => 'Blueberry',
		);
	var $livesearch2 = array(
		1 => 'Orange-2',
		2 => 'Apple-2',
		3 => 'Pear-2',
		4 => 'Banana-2',
		5 => 'Blueberry-2',
		);
	/**
	 * Perform a search
	 *
	 * @return array
	 */
	function search($input) {
		$ret = array();
		foreach($this->livesearch as $key => $value) {
			if (stristr($value,$input)) {
				$ret[$key] = $value;
			}
		}
		return $ret;
	}

	function search2($input) {
		$ret = array();
		foreach($this->livesearch2 as $key => $value) {
			if (stristr($value,$input)) {
				$ret[$key] = $value;
			}
		}
		return $ret;
	}
}
?>