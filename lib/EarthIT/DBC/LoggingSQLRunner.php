<?php

class EarthIT_DBC_SQLRanEvent {
	protected $sql;
	protected $params;
	protected $beginTime;
	protected $endTime;
	protected $methodName;
	protected $error;
	protected $options;
	public function __construct($sql, $params, $beginTime, $endTime, $error=null, $methodName=null, $options=array()) {
		$this->sql = $sql;
		$this->params = $params;
		$this->beginTime = $beginTime;
		$this->endTime = $endTime;
		$this->methodName = $methodName;
		$this->error = $error;
		$this->options = $options;
	}
	
	public function getBeginTime() { return $this->beginTime; }
	public function getEndTime() { return $this->endTime; }
	public function getDuration() {
		return $this->endTime - $this->beginTime;
	}
	
	public function __toString() {
		return
			"-- ".($this->methodName ? $this->methodName." " : "").
			date('c',$this->beginTime)." to ".date('c',$this->endTime).
			" (".($this->endTime - $this->beginTime)." seconds)\n".
			EarthIT_DBC_SQLExpressionUtil::debugSql($this->sql, $this->params);
	}
}

class EarthIT_DBC_LoggingSQLRunner
implements EarthIT_DBC_SQLRunner, EarthIT_DBC_SQLRunner2
{
	protected $backingSqlRunner;
	protected $logFunction;
	public function __construct($backingSqlRunner, $logFunction) {
		$this->backingSqlRunner = $backingSqlRunner;
		$this->logFunction = $logFunction;
	}
	
	protected function log($sql,$params,$beginTime,$endTime,$error=null,$method=null,$options=array()) {
		call_user_func($this->logFunction, new EarthIT_DBC_SQLRanEvent($sql,$params,$beginTime,$endTime,$error,$method,$options));
	}
	
	public function doQuery( $sql, array $params=array() ) {
		$beginTime = microtime(true);
		try {
			$this->backingSqlRunner->doQuery($sql, $params);
		} catch( Exception $e ) {
			$endTime = microtime(true);
			$this->log($sql,$params,$beginTime,$endTime,$error,'doQuery');
			throw $e;
		}
		$endTime = microtime(true);
		$this->log($sql,$params,$beginTime,$endTime,null,'doQuery');
	}
	
	public function fetchRows( $sql, array $params=array() ) {
		$beginTime = microtime(true);
		try {
			$res = $this->backingSqlRunner->fetchRows($sql, $params);
		} catch( Exception $e ) {
			$endTime = microtime(true);
			$this->log($sql,$params,$beginTime,$endTime,$error,'fetchRows');
			throw $e;
		}
		$endTime = microtime(true);
		$this->log($sql,$params,$beginTime,$endTime,null,'fetchRows');
		return $res;
	}
	
	public function doRawQuery( $sql ) {
		$beginTime = microtime(true);
		try {
			$this->backingSqlRunner->doRawQuery($sql);
		} catch( Exception $e ) {
			$endTime = microtime(true);
			$this->log($sql,null,$beginTime,$endTime,$error,'doRawQuery');
			throw $e;
		}
		$endTime = microtime(true);
		$this->log($sql,null,$beginTime,$endTime,null,'doRawQuery');
	}
	
	public function doQuery2( EarthIT_DBC_SQLExpression $exp, $type=self::ST_SELECT, array $options=array() ) {
		$beginTime = microtime(true);
		try {
			$res = $this->backingSqlRunner->doQuery2($exp, $type, $options);
		} catch( Exception $e ) {
			$endTime = microtime(true);
			$this->log($sql,null,$beginTime,$endTime,$error,$type,$options);
			throw $e;
		}
		$endTime = microtime(true);
		$this->log($sql,null,$beginTime,$endTime,null,$type,$options);
		return $res;
	}
}
