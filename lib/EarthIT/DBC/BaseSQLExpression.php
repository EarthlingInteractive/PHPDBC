<?php

class EarthIT_DBC_BaseSQLExpression implements EarthIT_DBC_SQLExpression
{
	protected $template;
	protected $paramValues;
	
	public function __construct( $template, $paramValues=array() ) {
		$this->template = $template;
		$this->paramValues = $paramValues;
	}
	
	public function getTemplate() { return $this->template; }
	public function getParamValues() { return $this->paramValues; }
}
