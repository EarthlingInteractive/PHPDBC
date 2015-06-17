<?php

/**
 * Uses specified functions for quoting values and identifiers
 */
class EarthIT_DBC_MSSQLQuoter implements EarthIT_DBC_Quoter
{
	protected $stringQuoteFunction;
	
	public function __construct( $stringQuoter ) {
		if( method_exists($stringQuoter,'quote') ) {
			$this->stringQuoteFunction = array($stringQuoter,'quote');
		} else if( is_callable($stringQuoter) ) {
			$this->stringQuoteFunction = $stringQuoter;
		} else {
			throw new Exception("String quoter must have a #quote method or be callable.");
		}
	}
	
	public function quote( $v ) {
		if( $v === null ) {
			return 'NULL';
		} else if( $v === true ) {
			return '1';
		} else if( $v === false ) {
			return '0';
		} else if( is_integer($v) or is_float($v) ) {
			return (string)$v;
		} else if( is_string($v) ) {
			return call_user_func($this->stringQuoteFunction, $v);
		} else {
			throw new Exception(get_class($this)." doesn't know how to quote ".var_export($v,true));
		}
	}
	
	public function quoteIdentifier( $id ) {
		// TODO: Ensure $id doesn't contain square brackets, or escape them or something.
		return "[$id]";
	}
}
