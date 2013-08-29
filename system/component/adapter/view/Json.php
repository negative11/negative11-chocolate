<?php
/**
 * Adapter for JSON-formatted output.
 */
namespace component\adapter\view;

class Json extends Template
{  
  /**
   * Generate adapter output.
   * Renders JSON data from generated template data in parent adapter.
   */
  public function getOutput()
  {
    $content = parent::getOutput(); 
    
    header("Content-type: application/json");
    echo json_encode($content);
  }
}