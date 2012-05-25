<?php
/**
 * Demonstrates use of controller in subdirectories.
 * With namespacing, you can organize controllers any way you like. 
 */
namespace controller\sub;

class Nested extends \controller\Core
{
	public function main()
	{
		$this->setTemplate('sub/nested');
	}
}