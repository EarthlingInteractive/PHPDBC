<?php

class EarthIT_DBC_DoctrineStatementBuilder
{
	protected $conn;
	public function __construct( $conn ) {
		$this->conn = $conn;
	}
	
	public function expressionToSql( EarthIT_DBC_SQLExpression $exp ) {
		return EarthIT_DBC_SQLExpressionUtil::queryToSql($exp, $this->conn);
	}

	public function expressionToStatement( EarthIT_DBC_SQLExpression $exp ) {
		return $this->conn->prepare($this->expressionToSql($exp));
	}
	
	public function makeStatement( $e, array $params=array() ) {
		return $this->expressionToStatement( EarthIT_DBC_SQLExpressionUtil::expression($e, $params) );
	}
}
