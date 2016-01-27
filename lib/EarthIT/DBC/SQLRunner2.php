<?php

interface EarthIT_DBC_SQLRunner2
{
	const ST_GENERIC = 'generic';
	const ST_SELECT = 'select';
	const ST_INSERT = 'insert';
	const ST_DELETE = 'delete';
	const ST_UPDATE = 'update';
	
	/**
	 * Run a query!
	 * 
	 * $type is necessary because some drivers require INSERTs to be
	 * run differently than SELECTs.  This may affect what properties
	 * of the SQLResult object are available after running the query.
	 * getRows() is only guaranteed to return something if type =
	 * select, getLastInserId() when type = insert, etc.
	 * When type = 'generic', the returned value contains no information.
	 * 
	 * When using Postgres, you can usually leave type as ST_SELECT
	 * even when doing INSERTs, which is handy in conjunction with
	 * "INSERT ... RETURNING ..." queries.
	 * 
	 * @param EarthIT_DBC_SQLExpression $exp query to be run
	 * @param string $type one of the ST_* contants indicating what type of query this is.
	 * @param array $options driver-specific options
	 * @return EarthIT_DBC_QueryResult
	 * @throws anything, potentially
	 */
	public function doQuery2( EarthIT_DBC_SQLExpression $exp, $type=self::ST_SELECT, array $options=array() );
}
