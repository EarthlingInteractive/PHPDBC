<?php

interface EarthIT_DBC_Quoter {
	/** Encode a literal value */
	public function quote( $value );
	/** Encode a string */
	public function quoteIdentifier( $id );
}
