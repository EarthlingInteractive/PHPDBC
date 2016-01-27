<?php

interface EarthIT_DBC_SQLExpression
extends EarthIT_DBC_SQLQueryComponent
{
	public function getTemplate();
	public function getParamValues();
}
