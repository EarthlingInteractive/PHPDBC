<?php

class EarthIT_DBC_DoctrineStatementBuilder
{
	protected $conn;
	public function __construct( $conn ) {
		$this->conn = $conn;
	}
	
	public function expressionToStatement( EarthIT_DBC_SQLExpression $exp ) {
		$counter = 0;
		$flattened = EarthIT_DBC_SQLExpressionUtil::flatten( $exp, $counter );
		
		// I can't figure a way to bind database identifiers.
		// Therefore doing own quoting.
		
		$quotedParams = array();

		foreach( $flattened->getParamValues() as $k=>$v ) {
			if( $v instanceof EarthIT_DBC_SQLIdentifier ) {
				$quotedParams["{".$k."}"] = $this->conn->quoteIdentifier($v->getIdentifier());
			} else if( $v === null ) {
				$quotedParams["{".$k."}"] = 'NULL';
			} else if( $v === true ) {
				$quotedParams["{".$k."}"] = 'true';
			} else if( $v === false ) {
				$quotedParams["{".$k."}"] = 'false';
			} else if( is_integer($v) or is_float($v) ) {
				$quotedParams["{".$k."}"] = (string)$v;
			} else {
				$quotedParams["{".$k."}"] = $this->conn->quote($v);
			}
		}
		
		$fullSql = strtr( $flattened->getTemplate(), $quotedParams );
		$stmt = $this->conn->prepare($fullSql);
		return $stmt;
	}
	
	public function makeStatement( $sql, $params=array() ) {
		return $this->expressionToStatement( new EarthIT_DBC_BaseSQLExpression($sql, $params) );
	}
}
