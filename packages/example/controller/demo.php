<?php
/**
 * A simple example controller to demonstrate use of framework components.
 * 
 * @package example 
 */
namespace controller;

class Demo extends Core
{
	/**
	 * Demo lander. Displays a quick greeting and template example. 
	 */
	public function main()
	{
		// Set the template for display.
		$this->setTemplate('demo');
		
		// We can assign variables to the template like this.
		$this->template->message = "This message is added by assignment from the controller.";
	}
	
	/**
	 * Shows how to work with basic objects you create.
	 */
	public function objects()
	{
		// Try to load a model object.
		$model = new \model\User;
	}
	
	/**
	 * Demonstrates use of the error debugger. 
	 */
	public function error()
	{
		throw new \Exception ('We threw this error on purpose.');
	}
}