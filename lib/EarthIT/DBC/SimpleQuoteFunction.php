<?php

class EarthIT_DBC_SimpleQuoteFunction
{
	protected $openQuote;
	protected $closeQuote;
	protected $contentRegex;
	
	public function __construct($openQuote, $closeQuote, $contentRegex=null) {
		$this->openQuote = $openQuote;
		$this->closeQuote = $closeQuote;
		if( $contentRegex === null ) {
			$contentRegex = '[A-Za-z0-9_]'; // A reasonable default‽
		}
		$this->contentRegex = $contentRegex;
	}
	
	public function __invoke($content) {
		if( preg_match('#'.$this->contentRegex.'#', $content) ) {
			return $this->openQuote.$content.$this->closeQuote;
		} else {
			throw new Exception("Refusing to quote '$content' because it does not match the regex '{$this->contentRegex}'");
		}
	}
}
