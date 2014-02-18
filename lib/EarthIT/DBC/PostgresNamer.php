<?php

/**
 * Names things in the typical Postgres fashion, which is
 * alllowercaseandsquishedtogether
 */
class EarthIT_DBC_PostgresNamer implements EarthIT_DBC_Namer
{
	protected function toLowerCase( $phrase ) {
		// Ignoring non-US-English chars for now
		return preg_replace('/[^a-z0-9]/', '', strtolower($phrase));
	}
	
	public function getTableName( EarthIT_Schema_ResourceClass $c ) {
		return $this->toLowerCase($c->getName());
	}	
	public function getColumnName( EarthIT_Schema_ResourceClass $c, EarthIT_Schema_Field $f ) {
		return $this->toLowerCase($f->getName());
	}
	public function getIndexName( EarthIT_Schema_ResourceClass $c, EarthIT_Schema_Index $i ) {
		return $this->toLowerCase($c->getName());
	}
	public function getForeignKeyName( EarthIT_Schema_ResourceClass $c, EarthIT_Schema_Reference $r ) {
		return $this->toLowerCase($c->getName()).$this->toLowerCase($r->getName());
	}
}
