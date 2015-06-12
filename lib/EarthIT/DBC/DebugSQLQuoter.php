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
	
	public function quote($v) {
		if( $v === null ) {
			return 'NULL';
		} else if( $v === true ) {
			return 'true';
		} else if( $v === false ) {
			return 'false';
		} else if( is_integer($v) or is_float($v) ) {
			return (string)$v;
		} else {
			return "'".str_replace("'","''",(string)$v)."'";
		}
	}
	public function quoteIdentifier($string) {
		return '"'.str_replace('"','""',$string).'"';
	}
}
