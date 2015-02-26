<?php

/**
 * A quoter to use for debugging purposes.
 * DO NOT USE TO ENCODE QUERIES THAT WILL BE SENT TO AN ACTUAL DATABASE.
 * Use the quoting functions provided by the database connection, instead.
 */
class EarthIT_DBC_DebugSQLQuoter
{
	public static function getInstance() {
		return new self;
	}
	
	private function __construct() { }
	
	public function quote($string) {
		return "'".str_replace("'","''",$string)."'";
	}
	public function quoteIdentifier($string) {
		return '"'.str_replace('"','""',$string).'"';
	}
}
