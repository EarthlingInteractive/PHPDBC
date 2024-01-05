<?php
use PHPUnit\Framework\TestCase;

class EarthIT_DBC_ParamsBuilderTest extends TestCase
{
	public function testHelloWorld() {
		$pb = new EarthIT_DBC_ParamsBuilder();
		$hello = $pb->newParam('h', 'Hello');
		$world = $pb->newParam('w', 'world');
		$exlam = $pb->bind('!');
		$this->assertEquals("h_0", $hello);
		$this->assertEquals("w_1", $world);
		$this->assertEquals("v_2", $exlam);
		$this->assertEquals( array(
			'h_0' => 'Hello',
			'w_1' => 'world',
			'v_2' => '!'
		), $pb->getParams());
	}
	
	public function testArrayRef() {
		$myParams = array('foot'=>'fungus');
		$pb = new EarthIT_DBC_ParamsBuilder($myParams, 5);
		$hello = $pb->newParam('h', 'Hello');
		$world = $pb->newParam('w', 'world');
		$exlam = $pb->bind('!');
		$this->assertEquals("h_5", $hello);
		$this->assertEquals("w_6", $world);
		$this->assertEquals("v_7", $exlam);
		$this->assertEquals( array(
			'foot' => 'fungus',
			'h_5' => 'Hello',
			'w_6' => 'world',
			'v_7' => '!'
		), $myParams);
	}
}
