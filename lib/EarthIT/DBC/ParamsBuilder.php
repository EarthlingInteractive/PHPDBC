<?php

class EarthIT_DBC_ParamsBuilder
{
	protected $counter;
	protected $params;
	
	public function __construct(array &$params=array(), $counter=0) {
		$this->params =& $params;
		$this->counter = $counter;
	}
	
	public function getParams() {
		return $this->params;
	}
	
	/**
	 * Create a new parameter and return its name.
	 * The second value (which defaults to null)
	 * will be bound.
	 */
	public function newParam($prefix, $v=null) {
		$name = $prefix.'_'.($this->counter++);
		$this->params[$name] = $v;
		return $name;
	}
	
	/**
	 * Bind a value to a new parameter,
	 * returning the name of the new parameter
	 */
	public function bind($v) {
		return $this->newParam('v',$v);
	}
}
