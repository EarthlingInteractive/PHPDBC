<?php

/**
 * Quotes 'special values' true, false, null
 */
class EarthIT_DBC_SpecialValueQuoteFunction
{
	protected $fallback;
	public function __construct( $fallback ) {
		$this->fallback = $fallback;
	}
	public function __invoke($v) {
		if( $v === true  ) return 'TRUE' ;
		if( $v === false ) return 'FALSE';
		if( $v === null  ) return 'NULL';
		return call_user_func($this->fallback, $v);
	}
}
