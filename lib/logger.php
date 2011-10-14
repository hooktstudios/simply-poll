<?php

define('LOG_FILE',	'log');
define('MODE',		'a');
define('DATE',		'H:m:s/m.d.y - ');

class logger {

	private $fp;

	function __construct() {
		$this->fp = fopen(LOG_FILE, MODE);
	}

	function log($log) {
		$write = date(DATE).$log."\r\n";
		fwrite($this->fp, $write);
	}

	function logVar($log) {
		$write = date(DATE).print_r($log, true)."\r\n";
		fwrite($this->fp, $write);
	}

	function close() {
		fclose($this->fp);
	}

}