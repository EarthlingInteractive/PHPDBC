<?php

interface EarthIT_DBC_Quoter
{
	/** Encode a literal value (will handle strings, numbers, true/false, and null) */
	public function quote( $value );
	/** Encode a string */
	public function quoteIdentifier( $id );
}
