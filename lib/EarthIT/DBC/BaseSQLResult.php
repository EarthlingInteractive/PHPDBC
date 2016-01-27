<?php

/**
 * A really simple SQLResult class that you can use if you don't
 * want to do anything fancy like lazy initialization.
 */
class EarthIT_DBC_BaseSQLResult implements EarthIT_DBC_SQLResult
{
	protected $rows;
	protected $affectedRowCount;
	protected $insertId;
	public function __construct( array $rows, $affectedRowCount, $insertId ) {
		$this->rows = $rows;
		$this->affectedRowCount = $affectedRowCount;
		$this->insertId = $insertId;
	}
	
	public function getIterator() {
		return new ArrayIterator( $this->getRows() );
	}
	
	public function getRows() {
		return $this->rows;
	}
	
	public function getAffectedRowCount() {
		return $this->affectedRowCount;
	}
	
	public function getLastInsertId() {
		return $this->insertId;
	}
}
