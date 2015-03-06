<?php

class EarthIT_DBC_SQLExpressionUtil
{
	public static function newParamName() {
		static $prefixPrefix;
		static $nextPrefixNumber;
		if( $prefixPrefix === null ) $prefixPrefix = 'seup'.mt_rand(100000000,999999999);
		if( $nextPrefixNumber === null ) $nextPrefixNumber = 1;
		return $prefixPrefix.($nextPrefixNumber++);
	}
	
	/**
	 * Transform an SQLExpression so that all parameter values
	 * are scalars, null, or SQLIdentifiers.
	 */
	public static function flatten( EarthIT_DBC_SQLExpression $exp, &$counter ) {
		$sql = $exp->getTemplate();
		$paramValues = array();
		$replacements = array();

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
				$replacements["{".$k."}"] = "(".implode(', ',$placeholders).")";
			} else if( $v instanceof EarthIT_DBC_SQLExpression ) {
				$flattened = self::flatten( $v, $counter );
				$replacements["{".$k."}"] = $flattened->getTemplate();
				foreach( $flattened->getParamValues() as $subKey=>$subValue ) {
					$paramValues[$subKey] = $subValue;
				}
			} else {
				throw new Exception("Don't know how to incorporate ".$v." into query.");
			}
		}
		
		return new EarthIT_DBC_BaseSQLExpression(strtr($sql, $replacements), $paramValues);
	}
	
	/**
	 * @param $exp The expression to encode
	 * @param $quoter any object that can quote(string):string and quoteIdentifier(string):string
	 * @return string SQL with all placeholder substituted with quoted parameter values
	 */
	public static function queryToSql( EarthIT_DBC_SQLExpression $exp, $quoter ) {
		$counter = 0;
		$flattened = self::flatten( $exp, $counter );
		
		// I can't figure a way to bind database identifiers.
		// Therefore doing own quoting.
		
		$quotedParams = array();

		foreach( $flattened->getParamValues() as $k=>$v ) {
			if( $v instanceof EarthIT_DBC_SQLIdentifier ) {
				$quotedParams["{".$k."}"] = $quoter->quoteIdentifier($v->getIdentifier());
			} else if( $v === null ) {
				$quotedParams["{".$k."}"] = 'NULL';
			} else if( $v === true ) {
				$quotedParams["{".$k."}"] = 'true';
			} else if( $v === false ) {
				$quotedParams["{".$k."}"] = 'false';
			} else if( is_integer($v) or is_float($v) ) {
				$quotedParams["{".$k."}"] = (string)$v;
			} else {
				$quotedParams["{".$k."}"] = $quoter->quote($v);
			}
		}
		
		return strtr( $flattened->getTemplate(), $quotedParams );
	}
	
	protected static function describeType( $thing ) {
		if( $thing === null ) return 'null';
		if( $thing === true ) return 'true';
		if( $thing === false ) return 'false';
		if( is_object($thing) ) return 'a '.get_class($thing);
		return 'a '.gettype($thing);
	}
	
	public static function expression($e, array $params=array() ) {
		if( $e instanceof EarthIT_DBC_SQLExpression ) {
			if( count($params) > 0 ) {
				throw new Exception("Doesn't make sense to include parameters with a SQLExpression object.");
			}
			return $e;
		} else if( is_string($e) ) {
			return new EarthIT_DBC_BaseSQLExpression($e, $params);
		} else {
			throw new Exception("Expected string (of SQL) or EarthIT_DBC_SQLExpression; got ".self::describeType($e));
		}
	}
	
	/**
	 * Return an EarthIT_DBC_SQLExpression that identifies the table.
	 */
	public static function tableExpression(
		EarthIT_Schema_ResourceClass $rc,
		EarthIT_DBC_Namer $namer,
		$prefix=array()
	) {
		$components = array();
		foreach( $prefix as $p ) {
			$components[] = new EarthIT_DBC_SQLIdentifier($p);
		}
		foreach( $rc->getDbNamespacePath() as $ns ) {
			$components[] = new EarthIT_DBC_SQLIdentifier($ns);
		}
		$components[] = new EarthIT_DBC_SQLIdentifier($namer->getTableName($rc));
		return new EarthIT_DBC_SQLNamespacePath($components);
	}
}
