<?php

class EarthIT_DBC_CustomQuoterTest extends PHPUnit_Framework_TestCase
{
	public function testIdentifiersQuoted() {
		$quoter = new EarthIT_DBC_CustomQuoter(
			new EarthIT_DBC_SimpleQuoteFunction('{{','}}'), // An unrealistic use case
			new EarthIT_DBC_SimpleQuoteFunction('<','>')
		);
		$this->assertEquals(
			"SELECT <joe> FROM <blow> WHERE <blow>.foo = {{seven}}",
			EarthIT_DBC_SQLExpressionUtil::queryToSql(
				EarthIT_DBC_SQLExpressionUtil::expression(
					"SELECT {col} FROM {tab} WHERE {tab}.foo = {v}",
					['col' => new EarthIT_DBC_SQLIdentifier('joe'),
					 'tab' => new EarthIT_DBC_SQLIdentifier('blow'),
					 'v' => 'seven']),
				$quoter));
	}
}
