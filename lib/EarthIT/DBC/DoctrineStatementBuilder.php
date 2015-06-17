<?php

class EarthIT_DBC_DoctrineStatementBuilder
{
	protected $conn;
	protected $quoter;
	
	public function __construct( $conn, $quoter=null ) {
		$this->conn = $conn;
		if( $quoter === null ) {
			$quoter = new EarthIT_DBC_DoctrinePostgresQuoter($this->conn);
		}
		$this->quoter = $quoter;
	}
	
	public function expressionToSql( EarthIT_DBC_SQLExpression $exp ) {
		return EarthIT_DBC_SQLExpressionUtil::queryToSql($exp, $this->quoter);
	}

	public function expressionToStatement( EarthIT_DBC_SQLExpression $exp ) {
		return $this->conn->prepare($this->expressionToSql($exp));
	}
	
	public function makeStatement( $e, array $params=array() ) {
		return $this->expressionToStatement( EarthIT_DBC_SQLExpressionUtil::expression($e, $params) );
	}
}
