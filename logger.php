<?php

define('LOG_FILE',	'log');
define('MODE',		'a');
define('DATE',		'H:i:s/m.d.y - ');

class logger {
	
	private $fp;
	
	/**
	 * Logger
	 * 
	 * @param String $location Where the log file should be located
	 */
	function __construct($location='') {
		$this->fp = fopen($location.LOG_FILE, MODE);
	}
	
	
	/**
	 * Log
	 * Adds a record to the log file
	 * 
	 * @param String $log The log entry
	 * @return bool true
	 */
	function log($log) {
		$write = date(DATE).$log."\r\n";
		fwrite($this->fp, $write);
		return true;
	}
	
	
	/**
	 * Log Variable
	 * Takes a variable and does a print_r
	 * 
	 * @param mixed $var The varible that is to be printed
	 * @param String $log The prefix to the variable print
	 * @return bool true
	 */	
	function logVar($var, $log=null) {
		$write = date(DATE);
		if ($log) $write .= $log.' - ';
		$write .= print_r($var, true)."\r\n";
		fwrite($this->fp, $write);
		return true;
	}
	
	
	/**
	 * Close
	 * Closes the log file
	 */
	function close() {
		fclose($this->fp);
	}
	
}