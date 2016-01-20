<?php

abstract class EarthIT_DBC_TestCase extends PHPUnit_Framework_TestCase
{
	protected $registry;
	public function setUp() {
		global $EarthIT_DBC_TestRegistry;
		if( $EarthIT_DBC_TestRegistry === null ) throw new Exception('No $EarthIT_DBC_TestRegistry!');
		$this->registry = $EarthIT_DBC_TestRegistry;
	}
}
