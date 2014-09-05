<?php

/**
 * Names things in the typical Postgres fashion, which is
 * alllowercaseandsquishedtogetherwith_optional_underscores,
 * (underscores are kept if they're part of the original name)
 */
class EarthIT_DBC_PostgresNamer implements EarthIT_DBC_Namer
{
	protected function squish( $phrase ) {
		return preg_replace('/[^a-z0-9_]/', '', strtolower($phrase));
	}
	
	public function getTableName( EarthIT_Schema_ResourceClass $c ) {
		return $this->squish($c->getName());
	}	
	public function getColumnName( EarthIT_Schema_ResourceClass $c, EarthIT_Schema_Field $f ) {
		return $this->squish($f->getName());
	}
	public function getIndexName( EarthIT_Schema_ResourceClass $c, EarthIT_Schema_Index $i ) {
		return $this->squish($c->getName());
	}
	public function getForeignKeyName( EarthIT_Schema_ResourceClass $c, EarthIT_Schema_Reference $r ) {
		return $this->squish($c->getName()).$this->squish($r->getName());
	}
}
