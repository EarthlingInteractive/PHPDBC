<?php

// Note: This is basically the same as Nife_DBC_QueryResult,
// except its also an IteratorAggregate.
// In the future maybe just use the Nife classes.

interface EarthIT_DBC_SQLResult extends IteratorAggregate
{
	/**
	 * @returns array an array of associative arrays, one per result row;
	 *   should return an empty array if the query returned no rows.
	 */
	public function getRows(); 
	
	/**
	 * @return int for queries that modifiy the DB, the number of affected rows,
	 *   0 for pure selects, and null if unknown.
	 */
	public function getAffectedRowCount();
	
	/**
	 * If new records with auto-incremented IDs were added, this should return
	 * the latest such ID.
	 */
	public function getLastInsertId();
	
	/**
	 * @return Iterator that will iterate over the result rows.
	 *   This can be expected to be called only once and not in combination
	 *   with getRows.
	 * 
	 * Commented out because already defined by IteratorAggregate.
	 */
	//public function getIterator();
}
