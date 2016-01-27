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
	public static function flatten( EarthIT_DBC_SQLQueryComponent $exp, &$counter ) {
		if( $exp instanceof EarthIT_DBC_SQLIdentifier ) {
			return new EarthIT_DBC_BaseSQLExpression("{identifier}", array('identifier'=>$exp));
		}
		
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
	public static function queryToSql( EarthIT_DBC_SQLQueryComponent $exp, $quoter ) {
		$counter = 0;
		$flattened = self::flatten( $exp, $counter );
		
		// I can't figure a way to bind database identifiers.
		// Therefore doing own quoting.
		
		$quotedParams = array();

		foreach( $flattened->getParamValues() as $k=>$v ) {
			if( $v instanceof EarthIT_DBC_SQLIdentifier ) {
				$quotedParams["{".$k."}"] = $quoter->quoteIdentifier($v->getIdentifier());
			} else {
				$quotedParams["{".$k."}"] = $quoter->quote($v);
			}
		}
		
		return strtr( $flattened->getTemplate(), $quotedParams );
	}
	
	public static function debugSql( $sql, array $params=array() ) {
		return self::queryToSql(
			self::expression($sql, $params),
			EarthIT_DBC_DebugSQLQuoter::getInstance());
	}
	
	protected static function describeType( $thing ) {
		if( $thing === null ) return 'null';
		if( $thing === true ) return 'true';
		if( $thing === false ) return 'false';
		if( is_object($thing) ) return 'a '.get_class($thing);
		return 'a '.gettype($thing);
	}
	
	//// Handy functions for normalizing parameters
	//// that might be either (SQL string, parameters) or (SQLExpression, [])
	
	public static function templateAndParamValues($e, array $params=array()) {
		if( $e instanceof EarthIT_DBC_SQLQueryComponent ) {
			if( count($params) > 0 ) {
				throw new Exception("Doesn't make sense to provide both an SQLQueryComponent object and parameters.");
			}
			if( $e instanceof EarthIT_DBC_SQLIdentifier ) {
				return array("{identifier}", 'identifier'=>$exp);
			} else if( $e instanceof EarthIT_DBC_SQLExpression ) {
				return array($e->getTemplate(), $e->getParamValues());
			}
		} else if( is_string($e) ) {
			return array($e, $params);
		}
		
		throw new Exception("Expected string (of SQL), EarthIT_DBC_SQLExpression, or EarthIT_DBC_SQLIdentifier; got ".self::describeType($e));
	}
	
	public static function identifier($id) {
		if( is_array($id) ) {
			if( count($id) == 0 ) throw new Exception("Can't make a zero-length path!");
			if( count($id) == 1 ) foreach($id as $comp) return self::identifier($comp);
			
			$components = array();
			foreach( $id as $comp ) $components[] = self::identifier($comp);
			return new EarthIT_DBC_SQLNamespacePath($components);
		} else {
			return new EarthIT_DBC_SQLIdentifier($id);
		}
	}
	
	public static function expression($e, array $params=array() ) {
		if( $e instanceof EarthIT_DBC_SQLQueryComponent ) {
			if( count($params) > 0 ) {
				throw new Exception("Doesn't make sense to provide both an SQLExpression object and parameters.");
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
		$nameParts = array();
		foreach( $prefix as $p ) $nameParts[] = $p;
		foreach( $rc->getDbNamespacePath() as $ns ) $nameParts[] = $ns;
		$nameParts[] = $namer->getTableName($rc);
		return self::identifier($nameParts, true);
	}
}
