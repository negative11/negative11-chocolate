<?php
/**
 * Example adapter to demonstrate extension of \component\Adapter.
 * Manipulates inbound data and returns altered output.
 */
namespace component;

class Example extends Adapter
{
  /**
   * Generate modified output using input data while working with parameters.
   * The original input will be left untouched.
   * @return type
   */
  public function getOutput()
  {
    // Set output to input values.
    // Initialize output array if necessary.
    if (is_array($this->input))
    {
      $output = $this->input;
    }
    else
    {
      $output = array();
    }
    
    // Override input data with parameter value.
    // Creates value if not set.
    foreach ($this->parameters as $key => $value)
    {
      $output[$key] = $value;
    }
    
    return $output;
  }
}