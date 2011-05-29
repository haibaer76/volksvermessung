<?php
/*
 * Created on 01.04.2008
 *
 */
class DBfunctions {

	/**
	 * Database $mysqli Instance
	 */
	var $mysqli = null;
	/**
	 * Main Instance
	 */
	private static $instance = NULL;

	private $stmt_counter = 0;

	/**
	 * Konstructor 
	 */
	private function __construct() {
		$this->do_connect();
	}

	private function do_connect() {
		$url = APP_DB_HOST;
		$user = APP_DB_USER;
		$passwd = APP_DB_PASS;
		$dbname = APP_DB_NAME;
		$this->mysqli = mysqli_init();
		$this->mysqli->real_connect($url, $user, $passwd, $dbname);
		$res = $this->mysqli->set_charset("utf8");
	 	if (!$res)
			throw new Exception("Could not set charset :".$this->mysqli->error);
		if (mysqli_connect_error()) {
				throw new Exception('Connect Error (' . mysqli_connect_errno() . ') '
								. mysqli_connect_error());
		}
	}
	
	/**
	 * Eine Instanz der Klasse
	 */
	public static function getInstance() {
		if (self :: $instance == NULL) {
			self :: $instance = new DBfunctions();
		}
		return self :: $instance;
	}
	
	/**
	 * Eine MSLI Instanz
	 */
	public function getMSLI() {
		return $this->mysqli;
	}

	/**
	 * Checks the connection of the database
	 */
	public function check_Connect() {
		/* check connection */
		if (mysqli_connect_errno()) {
			syso_Model::myecho($mysqli->error );	
			return false;
		}
		syso_Model::myecho($mysqli->error );	
		return true;		
	}

	private $ignore_counter = false;

	public function getSingleAttributeValue($inserate_id, $attribute_id) {
		$hlp = $this->queryHashArray(
			"CALL getAttributeValue(?, ?)", "ii", $inserate_id, $attribute_id);
		return $hlp[0]['value'];
	}

	public function getSingleAttributeValueEx($inserate_id, $cat_order, $att_order) {
		$hlp = $this->queryHashArray(
			"CALL getAttributeValueEx(?, ?, ?)", "iii", $inserate_id, $cat_order, $att_order);
		return $hlp[0]['value'];
	}

	public function prepareStmt() {
		if (!$this->ignore_counter) {
			$this->stmt_counter++;
			if ($this->stmt_counter>100000) {
				$this->mysqli->close();
				$this->do_connect();
			}
		}
		$query = func_get_arg(0);
		$this->mysqli->commit();
		$this->mysqli->next_result();
		return $this->mysqli->prepare($query);
	}

	public function querySingleValue(/* $query, $arg_types="", $arg1, $arg2, ... */) {
		$stmt = $this->prepareStmt(func_get_arg(0));
		if (!$stmt)
			die($this->mysqli->error);
		if (func_num_args()>=2 && strlen(func_get_arg(1))>0) {
			$arr = array();
			$arg_types = func_get_arg(1);
			$evalStr = '$stmt->bind_param($arg_types';
			$arg2 = func_get_arg(2);
			if (is_array($arg2)) {
				for ($i=0;$i<count($arg2);$i++)
					$evalStr.=', $arg2['.$i.']';
			} else {
				for ($i=2; $i<func_num_args(); $i++) {
					$arr[$i-2] = func_get_arg($i);
					$evalStr.= ',$arr['.($i-2).']';
				}
			}
			$evalStr.=") or die();";
			eval($evalStr);
		}
		$stmt->execute() or die($this->mysqli->error);
		$res=$stmt->store_result();
		if (!$res)
			throw new Exception($this->mysqli->error);
		$stmt->bind_result($ret);
		$stmt->fetch();
		$stmt->close();
		return $ret;
	}

	public function last_insert_id() {
		$this->ignore_counter = true;
		$ret = $this->querySingleValue("SELECT last_insert_id()");
		$this->ignore_counter = false;
		return $ret;
	}

	public function doSingleCall(/* $query, $arg_types="", $arg1, $arg2, ... */) {
		$stmt=$this->prepareStmt(func_get_arg(0));
		if ($stmt==null)
			throw new Exception($this->mysqli->error);
		if (func_num_args()>=2 && strlen(func_get_arg(1))>0) {
			$arr = array();
			$arg_types = func_get_arg(1);
			$evalStr = '$stmt->bind_param($arg_types';
			$arg2 = func_get_arg(2);
			if (is_array($arg2)) {
				for ($i=0;$i<count($arg2);$i++)
					$evalStr.=', $arg2['.$i.']';
			} else {
				for ($i=2; $i<func_num_args(); $i++) {
					$arr[$i-2] = func_get_arg($i);
					$evalStr.= ',$arr['.($i-2).']';
				}
			}
			$evalStr.=");";
			eval($evalStr);
		}
		$res=$stmt->execute();
	  if (!$res)
			throw new Exception($this->mysqli->error);
		$ret = $stmt->affected_rows;
		$stmt->close();
		return $ret;
	}

	// the selected array must be named as val
	public function querySingleArray(/* $query, $arg_types="", $arg1, $arg2, ... */) {
		$evalstr = '$hlp = $this->queryHashArray(';
		$func_args = array();
		for ($i=0;$i<func_num_args();$i++)
			$func_args[] = func_get_arg($i);
		for ($i=0;$i<func_num_args();$i++) {
			if ($i>0)
				$evalstr.=",";
			$evalstr.="\$func_args[$i]";
		}
		$evalstr.=");";
		eval($evalstr);
		$ret = array();
		foreach ($hlp as $h) {
			$ret[] = $h['val'];
		}
		return $ret;
	}

	public function queryHashArray(/* $query, $arg_types="", $arg1, $arg2, ... */) {
		$stmt=$this->prepareStmt(func_get_arg(0));
		if (!$stmt)
			die($this->getMSLI()->error);
		if (func_num_args()>=2 && strlen(func_get_arg(1))>0) {
			$arr = array();
			$arg_types = func_get_arg(1);
			$evalStr = '$stmt->bind_param($arg_types';
			$arg2 = func_get_arg(2);
			if (is_array($arg2)) {
				for ($i=0;$i<count($arg2);$i++)
					$evalStr.=', $arg2['.$i.']';
			} else {
				for ($i=2; $i<func_num_args(); $i++) {
					$arr[$i-2] = func_get_arg($i);
					$evalStr.= ',$arr['.($i-2).']';
				}
			}
			$evalStr.=");";
			eval($evalStr);
		}
		return self::createResultHashArrayFromStatement($stmt);
	}


	public static function createResultHashArrayFromStatement($stmt) {
		$res=$stmt->execute();
	  if (!$res)
			die("Error:".self::getInstance()->getMSLI()->error);
		$stmt->store_result();
		$ret = array();
		$evalStr = '$stmt->bind_result(';
		$first = true;
		$meta = $stmt->result_metadata();
		$index = 0;
		$keys=array();
		while ($finfo = $meta->fetch_field()) {
			if ($first)
				$first = false;
			else
				$evalStr.=',';
			$evalStr.="\$v$index";
			array_push($keys, $finfo->name);
			$index++;
		}
		$evalStr.=");";
		eval($evalStr);
		while ($stmt->fetch()) {
			$res=array();
			for($i=0;$i<$index;$i++)
				eval("\$res['".$keys[$i]."']=\$v$i;");
			array_push($ret, $res);
		}
		$stmt->close();
		return $ret;
	}
	/**
	 * Runs a Stored Procedure
     * noch in Arbeit !!!
     * Vieleicht für künftige Projekte !!!
     * Klapp nicht auf php 6 warten. TJA !!!
	 */
	public function run_SP($format_string, $procedure, $values) {
		// Erstellung des Statement Querys 
		$mysqli = self :: $mysqli;
		$query = "CALL " . "$procedure" . "(";
		$count = count($values);
		if ($count >= 1) {
			$query = $query . "?";
			for ($index = 1; $index < $count; $index++) {
				$query = $query . ",?";
			}
		}
		$query = $query . ");";
		//echo ($query);
		$dbst = $mysqli->prepare($query);
		// Erstellung des Bindens der Parameter
		$bind = "\$dbst->bind_param(";
		if ($count >= 1) {
			$bind = $bind."\"".$format_string."\""; 		
			if (is_array($values)) {	
										
				for ($index = 0; $index <= $count-1; $index++) {
					$bind = $bind.",\$values["."$index"."]";
				}
			} else {
					$bind = $bind.",\$values";
			}
		}
		$bind = $bind . ");";
		//echo ($bind);
		eval($bind);
		/* execute prepared statement */
		mysqli_stmt_fetch($dbst);
		syso_Model::myecho($mysqli->error );	
		return $dbst->execute();		
	}
	
	/**
	 * schließt letzte Verbindung und baut eine neue auf
	 * nur im Notfall verwenden bei Massiven SYK Problemen wenn keine sync mehr  
	 */
	public function reconnect() {		
			self :: $mysqli->close();
			$ini_inst = ini :: getInstance();
		    self :: $mysqli = new mysqli($ini_inst->getURL(), $ini_inst->getUSER(), $ini_inst->getPASSWORD(), $ini_inst->getDatabasename());
		    syso_Model::myecho($mysqli->error );	
		    return  self :: $mysqli;
	}
	
	/**
	 * schließt letzte Verbindung
	 */
	public function close() {				
			self :: $mysqli->close();
			syso_Model::myecho($mysqli->error );	
	}
}
?>
