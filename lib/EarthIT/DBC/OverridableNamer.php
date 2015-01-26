<?php

class EarthIT_DBC_OverridableNamer implements EarthIT_DBC_Namer
{
	protected $defaultNamer;
	
	public function __construct(EarthIT_DBC_Namer $defaultNamer) {
		$this->defaultNamer = $defaultNamer;
	}
	
	protected function getNameOverride(EarthIT_Schema_SchemaObject $obj) {
		return $obj->getFirstPropertyValue('http://ns.nuke24.net/Schema/RDB/nameInDatabase', null);
	}
	
	public function getTableName( EarthIT_Schema_ResourceClass $c ) {
		if( ($name = $c->getTableNameOverride()) !== null ) return $name;
		if( ($name = $this->getNameOverride($c)) !== null ) return $name;
		return $this->defaultNamer->getTableName($c);;
	}	
	public function getColumnName( EarthIT_Schema_ResourceClass $c, EarthIT_Schema_Field $f ) {
		if( ($name = $f->getColumnNameOverride()) !== null ) return $name;
		if( ($name = $this->getNameOverride($f)) !== null ) return $name;
		return $this->defaultNamer->getColumnName($c,$f);
	}
	public function getIndexName( EarthIT_Schema_ResourceClass $c, EarthIT_Schema_Index $i ) {
		if( ($name = $this->getNameOverride($f)) !== null ) return $name;
		return $this->defaultNamer->getIndexName($c,$i);
	}
	public function getForeignKeyName( EarthIT_Schema_ResourceClass $c, EarthIT_Schema_Reference $r ) {
		if( ($name = $this->getNameOverride($r)) !== null ) return $name;
		return $this->defaultNamer->getForeignKeyName($c,$r);
	}
}
