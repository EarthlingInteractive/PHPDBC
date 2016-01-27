<?php

class EarthIT_DBC_PDOSQLRunnerTest extends EarthIT_DBC_TestCase
{
	protected function makePostgresSqlRunner() {
		if( !in_array('pgsql',PDO::getAvailableDrivers()) ) {
			$this->markTestSkipped("pgsql PDO driver not available");
			return null;
		}
		$PDO = $this->registry->postgresPdo;
		return EarthIT_DBC_PDOSQLRunner::make($PDO);
	}
	
	public function testMakeAPostgresOne() {
		$runner = $this->makePostgresSqlRunner();
		if( $runner === null ) return;
		$quoted = $runner->quoteParams(
			'SELECT {col} FROM {tab} WHERE {var} < {val}',
			array(
				'col' => new EarthIT_DBC_SQLIdentifier('some_column'),
				'tab' => new EarthIT_DBC_SQLIdentifier('some_table'),
				'var' => new EarthIT_DBC_SQLIdentifier('some_other_column'),
				'val' => 'three hundred'
			)
		);
		$this->assertEquals(
			'SELECT "some_column" FROM "some_table" WHERE "some_other_column" < \'three hundred\'',
			$quoted);
	}
	
	public function testSelect() {
		$runner = $this->makePostgresSqlRunner();
		if( $runner === null ) return;
		$rows = $runner->fetchRows(
			"SELECT {val1} AS {key} UNION SELECT {val2} AS {key}",
			array(
				'val1' => 'aabb',
				'val2' => 'bbcc',
				'key' => new EarthIT_DBC_SQLIdentifier('thing')
			)
		);
		$this->assertEquals( 2, count($rows) );
		$this->assertEquals( 'aabb', $rows[0]['thing'] );
		$this->assertEquals( 'bbcc', $rows[1]['thing'] );
	}
	
	protected function doBobQuery() {
		$runner = $this->makePostgresSqlRunner();
		$q = EarthIT_DBC_SQLExpressionUtil::expression("SELECT {val} AS {name}", array(
			'val' => 23,
			'name' => EarthIT_DBC_SQLExpressionUtil::identifier('bob')
		));
		return $runner->doQuery2($q);
	}
	
	public function testSelect2() {
		$rowCount = 0;
		foreach( $this->doBobQuery() as $r ) {
			$this->assertEquals(23, $r['bob']);
			++$rowCount;
		}
		$this->assertEquals(1, $rowCount);
	}
	
	public function testSelect2b() {
		$roze = $this->doBobQuery()->getRows();
		$this->assertEquals( array(array('bob'=>23)), $roze );
	}
}
