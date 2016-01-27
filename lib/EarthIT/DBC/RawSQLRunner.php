<?php

interface EarthIT_DBC_RawSQLRunner
{
	/**
	 * Run that SQL!
	 * This should support running blocks of SQL that contain multiple statements.
	 * 
	 * Nothing is returned, but this should throw an exception if the
	 * SQL could not be run successfully.
	 * 
	 * @returns nothing
	 */
	public function doRawQuery( $sql );
}
