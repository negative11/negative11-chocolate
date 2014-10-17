<?php
/**
 * Allows supplied string to be output directly during
 * query binding.
 * 
 * Useful when a MySQL function needs to be used.
 */
namespace helper\mysql;
class Literal
{
	protected $literal;
	
	/**
	 * Constructor.
	 * Stores literal string for output later.
	 */
	public function __construct($literal)
	{
		$this->literal = $literal;
	}
	
	/**
	 * Output literal.
	 */
	public function __toString()
	{
		return (string) $this->literal;
	}
}