<?php

class EarthIT_DBC_PDOSQLRunnerTest extends EarthIT_DBC_TestCase
{
	public function testMakeAPostgresOne() {
		$PDO = $this->registry->pdo;
		$runner = EarthIT_DBC_PDOSQLRunner::make($PDO);
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
		$PDO = $this->registry->pdo;
		$runner = EarthIT_DBC_PDOSQLRunner::make($PDO);
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
}
