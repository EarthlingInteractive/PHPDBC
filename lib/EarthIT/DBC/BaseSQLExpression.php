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
	
	public function __toString() {
		$sql = EarthIT_DBC_SQLExpressionUtil::queryToSql($this, EarthIT_DBC_DebugSQLQuoter::getInstance());
		return "-- Commented out to make sure you don't run it.\n--".str_replace("\n","\n-- ",$sql);
	}
}
