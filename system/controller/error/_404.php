<?php
/**
 * Standard 404 template
 * 
 * @package core
 */
namespace controller\error;
class _404 extends \controller\Core
{	
	/**
	 * Force display.
	 */
	public function __construct()
	{
		$this->setTemplate('404');
		$this->disableAutoDraw();
		$this->draw();
	}
}
