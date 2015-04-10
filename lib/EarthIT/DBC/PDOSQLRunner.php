<?php

class EarthIT_DBC_PDOSQLRunner implements EarthIT_DBC_SQLRunner
{
	protected $quoter;
	
	public function __construct( PDO $conn, callable $identifierQuoteFunction ) {
		$this->conn = $conn;
		$this->quoter = new EarthIT_DBC_CustomQuoter(array($conn,'quote'), $identifierQuoteFunction);
	}
	
	public function doRawQuery( $sql ) {
		$this->conn->exec($sql);
	}
	
	protected function rewriteForPdo( &$sql, array &$params ) {
		$exp = EarthIT_DBC_SQLExpressionUtil::expression($sql,$params);
		/*
		 * It would be nice to be able to use prepared statements.  However:
		 * - PDO has problems with the same parameter being used more than once
		 * - PDO doesn't know how to quote identifiers
		 */
		$sql = EarthIT_DBC_SQLExpressionUtil::queryToSql($exp,$this->quoter);
		$params = [];
	}
	
	public function fetchRows( $sql, array $params=array() ) {
		$this->rewriteForPdo($sql, $params);
		$stmt = $this->conn->prepare($sql);
		$stmt->execute($params);
		return $stmt->fetchAll();
	}
	
	public function doQuery( $sql, array $params=array() ) {
		$this->rewriteForPdo($sql, $params);
		$stmt = $this->conn->prepare($sql);
		$stmt->execute($params);
	}
}
