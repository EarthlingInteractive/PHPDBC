<?php

class EarthIT_DBC_DoctrineSQLRunner implements EarthIT_DBC_SQLRunner
{
	protected $conn;
	protected $quoter;
	
	public function __construct( $conn, $quoter=null ) {
		if( !is_object($conn) ) {
			throw new Exception("Some object required for DoctrineSQLRunner ".
			                    "constructor argument; given ".gettype($conn));
		}
		$this->conn = $conn;
		if( $quoter === null ) {
			$quoter = new EarthIT_DBC_DoctrinePostgresQuoter($this->conn);
		}
		$this->quoter = $quoter;
	}
	
	public function doRawQuery( $sql ) {
		$this->conn->exec($sql);
	}
	
	public function doQuery( $sql, array $params=array() ) {
		$stb = new EarthIT_DBC_DoctrineStatementBuilder($this->conn, $this->quoter);
		$stmt = $stb->makeStatement($sql, $params);
		$stmt->execute();
	}
	
	public function fetchRows( $sql, array $params=array() ) {
		$stb = new EarthIT_DBC_DoctrineStatementBuilder($this->conn, $this->quoter);
		$stmt = $stb->makeStatement($sql, $params);
		$stmt->execute();
		return $stmt->fetchAll();
	}	
}
