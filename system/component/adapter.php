<?php
/**
 * Adapter component.
 * @abstract Extend to implement required methods.
 */
namespace component;

abstract class Adapter
{
  /**
   * Input data.
   * This data can be manipulated during getOutput().
   * @var type 
   */
  protected $input;
  
  /**
   * Parameters that affect the input data.
   * @var type 
   */
  protected $parameters;
  
  /**
   * __construct.
   * Applies inbound data and parameters.
   * @param type $input
   * @param array $parameters
   */
  public function __construct($input, array $parameters = array())
  {
    $this->input = $input;
    $this->parameters = $parameters;
  }
  
  /**
   * Get original input.
   */
  public function getInput()
  {
    return $this->input;
  }
  
  /**
   * Get original parameters array.
   */
  public function getParameters()
  {
    return $this->parameters;
  }
  
  /**
   * Override to implement modifications to $this->input. 
   */
  abstract public function getOutput();
}