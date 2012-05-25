<?php
/**
 * Default system controller that tells us the framework is here, and
 * that it is alive.
 */
namespace controller;
class Hello extends \controller\Core
{	
	/**
	 * Constructor. 
	 */
	public function __construct()
	{				
		// Disabled in production
		if (IN_PRODUCTION === TRUE)
		{
			\Core::error404();
		}
	}

	/**
	 * Say hello to the framework.
	 */
	public function main()
	{
		$this->setTemplate('hello');
	}
}