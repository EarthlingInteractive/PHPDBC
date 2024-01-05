<?php
use PHPUnit\Framework\TestCase;

abstract class EarthIT_DBC_TestCase extends TestCase
{
	protected $registry;
	public function setUp() : void {
		global $EarthIT_DBC_TestRegistry;
		if( $EarthIT_DBC_TestRegistry === null ) throw new Exception('No $EarthIT_DBC_TestRegistry!');
		$this->registry = $EarthIT_DBC_TestRegistry;
	}
}
