<?php

/**
 * Represents a namespace path like
 *   "someschema"."sometable" or
 *   "someschema"."sometable"."somecolumn"
 */
class EarthIT_DBC_SQLNamespacePath implements EarthIT_DBC_SQLExpression
{
	protected $components;
	protected $tplPrefix;
	
	/**
	 * @param array $components namespace path components.
	 *  Normally each element will itself be an SQLIdentifier.
	 */
	public function __construct( array $components ) {
		$this->components = $components;
		$this->tplPrefix = EarthIT_DBC_SQLExpressionUtil::newParamName();
	}
	
	public function getComponents() {
		return $this->components;
	}
	
	public function getTemplate() {
		$tplParts = array();
		foreach( $this->components as $i=>$c ) {
			$tplParts[] = '{'.$this->tplPrefix.$i.'}';
		}
		return implode('.',$tplParts);
	}
	
	public function getParamValues() {
		$params = array();
		foreach( $this->components as $i=>$c ) {
			$params[$this->tplPrefix.$i] = $c;
		}
		return $params;
	}
}
