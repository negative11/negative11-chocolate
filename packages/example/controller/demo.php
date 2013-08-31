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
  
  /**
   * Demonstrate the \component\Example adapter.
   * Also demonstrates JSON output using specialized view adapter.
   */
  public function adapter()
  {
    // Original input.
    $input = array(
      'a' => 1,
      'b' => 2,
      'c' => 3
    );
    
    // We want to override and add some values.
    $altValues = array(
      'c' => array(4,5,6),
      'd' => 'hello world'
    );
    
    // Build adapter object.
    $adapter = new \component\Example($input, $altValues);
    
    // Get modified output.
    $output = $adapter->getOutput();
    
    // Attach original, untouched input data.
    $output['originalValues'] = $adapter->getInput();
    
    // Issue JSON response.
    $view = new \component\View('response', 'json');
    $view->data = $output;
    $view->status = 'generated';
    $view->display();
  }
}