<?php
/**
 * A controller to demonstrate use of adapter output.
 * 
 * @package example 
 */
namespace controller;

class Adapter extends Core
{
  public function __construct()
	{
		if (IN_PRODUCTION)
		{
			throw new \Exception('Disabled in production');
		}
	}
  
  /**
   * This controller renders JSON output.
   * @param type $data
   */
  protected function renderJson($data)
  {
    header("Content-type: application/json");
    echo json_encode($data);
  }
  
  /**
	 * Adapter lander. Displays a result and simple message.
	 */
	public function main()
	{
		// Disable ordinary autodraw.
		$this->disableAutoDraw();
    
    $data = new \stdClass();
    $data->result = 'success';
    $data->message = 'This is a sample message';
    
    $this->renderJson($data);
	}
  
  /**
   * Demonstrate the \component\Example adapter.
   */
  public function example()
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
    $this->renderJson($output);
  }
}