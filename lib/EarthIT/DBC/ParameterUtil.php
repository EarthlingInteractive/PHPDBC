<?php

class EarthIT_DBC_ParameterUtil
{
	public static function newParamName($prefix) {
		static $number;
		static $randy;
		
		if( $number === null ) $number = 0;
		if( $randy === null ) $randy = 'pu'.mt_rand(1000000000,9999999999);
		
		return $prefix.'_'.$randy.'_'.($number++);
	}
}
