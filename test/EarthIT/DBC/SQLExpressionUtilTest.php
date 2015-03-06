<?php

class EarthIT_DBC_SQLExpressionUtilTest extends PHPUnit_Framework_TestCase
{
	protected function assertSqlEquals($expected, EarthIT_DBC_SQLExpression $expression) {
		$this->assertEquals($expected, EarthIT_DBC_SQLExpressionUtil::queryToSql(
			$expression, EarthIT_DBC_DebugSQLQuoter::getInstance()));
	}
	
	protected function _testTableExpression($expectedSql, $outerPrefix, $innerPrefix, $rcName) {
		$rc = EarthIT_Schema_ResourceClass::__set_state(array(
			'name' => $rcName,
			'dbNamespacePath' => $innerPrefix
		));
		$namer = new EarthIT_DBC_PostgresNamer();
		$expr = EarthIT_DBC_SQLExpressionUtil::tableExpression($rc, $namer, $outerPrefix);
		$this->assertSqlEquals($expectedSql, $expr);
	}
	
	public function testBareTableExpression() {
		$this->_testTableExpression('"hoobawooba"', array(), array(), 'hooba wooba');
	}
	public function testInnerNamespaceTableExpression() {
		$this->_testTableExpression('"qqx"."hoobawooba"', array(), array('qqx'), 'hooba wooba');
	}
	public function testOuterNamespaceeTableExpression() {
		$this->_testTableExpression('"xyz"."abc"."qqx"."hoobawooba"', array('xyz','abc'), array('qqx'), 'hooba wooba');
	}
}
