<?php
/**
 * A simple example controller to demonstrate use of framework components.
 * 
 * @package example 
 */
namespace controller;

class Demo extends Core
{
	public function main()
	{
		$this->setTemplate('demo');
	}
}