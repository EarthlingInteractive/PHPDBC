<?php

interface EarthIT_DBC_Namer
{
	public function getTableName( EarthIT_Schema_ResourceClass $rc );
	public function getColumnName( EarthIT_Schema_ResourceClass $rc, EarthIT_Schema_Field $f );
	public function getIndexName( EarthIT_Schema_ResourceClass $rc, EarthIT_Schema_Index $i );
	public function getForeignKeyName( EarthIT_Schema_ResourceClass $rc, EarthIT_Schema_Reference $r );
}
