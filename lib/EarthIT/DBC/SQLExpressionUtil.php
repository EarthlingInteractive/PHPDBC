<?php

class EarthIT_DBC_SQLExpressionUtil
{
	/**
	 * Transform an SQLExpression so that all parameter values
	 * are scalars, null, or SQLIdentifiers.
	 */
	public static function flatten( EarthIT_DBC_SQLExpression $exp, &$counter ) {
		$sql = $exp->getTemplate();
		$paramValues = array();
		
		foreach( $exp->getParamValues() as $k => $v ) {
			if( is_scalar($v) || $v === null || $v instanceof EarthIT_DBC_SQLIdentifier ) {
				$paramValues[$k] = $v;
			} else if( is_array($v) ) {
				if( count($v) == 0 ) throw new Exception("Can't generate query with zero-length list for parameter '$k'");
				$placeholders = array();
				foreach( $v as $subValue ) {
					$ph = "li_$counter";
					$paramValues[$ph] = $subValue;
					$placeholders[] = "{".$ph."}";
					++$counter;
				}
				$sql = strtr($sql, "{".$k."}", "(".implode(', ',$placeholders).")");
			} else if( $v instanceof EarthIT_DBC_SQLExpression ) {
				$flattened = self::flatten( $v, $counter );
				$sql = strtr($sql, "{".$k."}", $flattened->getTemplate());
				foreach( $flattened->getParamValues() as $k=>$v ) {
					$paramValues[$k] = $v;
				}
			} else {
				throw new Exception("Don't know how to incorporate ".$v." into query.");
			}
		}
		
		return new EarthIT_DBC_BaseSQLExpression($sql, $paramValues);
	}
}
