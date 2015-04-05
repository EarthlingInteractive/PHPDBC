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
	
	//// Test boring utility functions
	
	public function testExpressionToExpression() {
		$exp = new EarthIT_DBC_BaseSQLExpression("SELECT {thing}", array('thing'=>42));
		$this->assertSame($exp, EarthIT_DBC_SQLExpressionUtil::expression($exp));
	}
	public function testExpressionToExpressionWithParameters() {
		$exp = new EarthIT_DBC_BaseSQLExpression("SELECT {thing}", array('thing'=>42));
		try {
			EarthIT_DBC_SQLExpressionUtil::expression($exp, array('thing'=>43));
			$this->fail("expression(SQLExpression, non-empty-array) should have errored.");
		} catch( Exception $e ) {}
	}
	public function testExpressionToTemplateAndParamValues() {
		$exp = new EarthIT_DBC_BaseSQLExpression("SELECT {thing}", array('thing'=>42));
		list($template, $params) = EarthIT_DBC_SQLExpressionUtil::templateAndParamValues($exp);
		$this->assertSame($exp->getTemplate(), $template);
		$this->assertSame($exp->getParamValues(), $params);
	}
	public function testExpressionToTemplateAndParamValuesWithParameters() {
		$exp = new EarthIT_DBC_BaseSQLExpression("SELECT {thing}", array('thing'=>42));
		try {
			EarthIT_DBC_SQLExpressionUtil::templateAndParamValues($exp, array('thing'=>43));
			$this->fail("templateAndParamValues(SQLExpression, non-empty-array) should have errored.");
		} catch( Exception $e ) {}
	}
	
	public function testTemplateAndParamValuesToExpression() {
		list($template,$params) = array("SELECT {thing}", array('thing'=>42));
		$exp = new EarthIT_DBC_BaseSQLExpression($template,$params);
		$this->assertEquals($exp, EarthIT_DBC_SQLExpressionUtil::expression($template, $params));
	}
	public function testTemplateAndParamValuesToTemplateAndParamValues() {
		list($template,$params) = array("SELECT {thing}", array('thing'=>42));
		list($templateB,$paramsB) = EarthIT_DBC_SQLExpressionUtil::templateAndParamValues($template, $params);
		$this->assertSame($template, $templateB);
		$this->assertSame($params, $paramsB);
	}
}
