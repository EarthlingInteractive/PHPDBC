<?php

/**
 * Uses specified functions for quoting values and identifiers
 */
class EarthIT_DBC_CustomQuoter
{
	protected $valueQuoter;
	protected $identifierQuoter;
	
	public function __construct( $valueQuoter, $idQuoter ) {
		$this->valueQuoter = $valueQuoter;
		$this->identifierQuoter = $idQuoter;
	}
	public function quote( $value ) {
		return call_user_func($this->valueQuoter, $value);
	}
	public function quoteIdentifier( $id ) {
		return call_user_func($this->identifierQuoter, $id);
	}
}
