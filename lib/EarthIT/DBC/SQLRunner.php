<?php

interface EarthIT_DBC_SQLRunner extends EarthIT_DBC_RawSQLRunner
{
	public function doQuery( $sql, array $params=array() );
	/**
	 * @param string $sql SQL with embedded {variables}
	 * @param array $params associative array of variable names (the
	 *   strings between the curly braces in $sql) to values (scalars,
	 *   arrays, SQLExpressions, or SQLLiterals)
	 */
	public function fetchRows( $sql, array $params=array() );
}
