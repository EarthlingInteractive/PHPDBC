<?php

/**
 * Represents the name of a table or column or something
 */
class EarthIT_DBC_SQLIdentifier
{
	protected $identifier;
	public function __construct( $identifier ) {
		$this->identifier = $identifier;
	}
	public function getIdentifier() {
		return $this->identifier;
	}
}
