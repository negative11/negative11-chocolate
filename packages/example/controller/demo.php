<?php
/**
 * A simple example controller to demonstrate use of framework components.
 * 
 * @package example 
 */
namespace controller;

class Demo extends Core
{	
	public function __construct()
	{
		if (IN_PRODUCTION)
		{
			throw new \Exception('Disabled in production');
		}
	}
	
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
	
	/**
	 * Demonstrate simple form usage. 
	 */
	public function form()
	{
		$this->setTemplate('demos/form');
	}
	
	/**
	 * Processes sample form created by $this->form().
	 */
	public function processForm()
	{
		\core::dump($_POST, $_GET);
	}
}