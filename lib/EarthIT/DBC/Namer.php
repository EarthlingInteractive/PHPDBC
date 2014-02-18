<?php

interface EarthIT_DBC_Namer
{
	public function getTableName( EarthIT_Schema_ResourceClass $c );
	public function getColumnName( EarthIT_Schema_ResourceClass $c, EarthIT_Schema_Field $c );
	public function getIndexName( EarthIT_Schema_ResourceClass $c, EarthIT_Schema_Index $c );
	public function getForeignKeyName( EarthIT_Schema_ResourceClass $c, EarthIT_Schema_Reference $c );
}
