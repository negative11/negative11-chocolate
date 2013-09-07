<?php
/**
 * Adapter for JSON-formatted output.
 */
namespace component\adapter\view;

class Json extends Template
{  
  // Expects files to be named xyz.json.{FILE_EXTENSION}
  const TEMPLATE_SUFFIX_PREFIX = '.json';
  
  /**
   * Generate adapter output.
   * Renders JSON data from generated template data in parent adapter.
   */
  public function getOutput()
  {
    list($response, $content) = $this->getTemplateOutput(); 
    
    // Issue encoded response. 
    // Any rendered data will be ignored.
    if ( ! headers_sent())
    {
      header("Content-type: application/json");
    }
    
    echo json_encode($response);
  }
}