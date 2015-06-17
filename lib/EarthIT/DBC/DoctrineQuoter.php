<?php

/**
 * Uses specified functions for quoting values and identifiers
 */
class EarthIT_DBC_DoctrineQuoter implements EarthIT_DBC_Quoter
{
	protected $conn;
	
	public function __construct( Doctrine\DBAL\Driver\Connection $conn ) {
		$this->conn = $conn;
	}
	
	public function quote( $v ) {
		// You might think you can just figure the PDO::PARAM_* of the value
		// and pass that as the second argument to $this->conn->quote(v, type).
		// But that doesn't seem to work.  quote(false, 5) = empty string.
		// So forget that approach.
		if( $v === null ) {
			return 'NULL';
		} else if( $v === true ) {
			return 'TRUE';
		} else if( $v === false ) {
			return 'FALSE';
		} else if( is_integer($v) or is_float($v) ) {
			return (string)$v;
		} else if( is_string($v) ) {
			return $this->conn->quote($v);
		} else {
			throw new Exception(get_class($this)." doesn't know how to quote ".var_export($v,true));
		}
	}
	
	public function quoteIdentifier( $id ) {
		return $this->conn->quoteIdentifier($id);
	}
}
