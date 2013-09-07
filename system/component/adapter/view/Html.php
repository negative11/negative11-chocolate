<?php
/**
 * HTML Adapter for standard web-page output.
 */
namespace component\adapter\view;

class Html extends Template
{  
  // Expects files to be named xyz.html.{FILE_EXTENSION}
  const TEMPLATE_SUFFIX_PREFIX = '.html';
  
  /**
   * Renders Html output from content created in parent adapter.
   */
  public function getOutput()
  {
    list($response, $content) = $this->getTemplateOutput(); 
    
    // Stream rendered template content.
    // Returned response is ignored.
    header("Content-type: text/html");
    echo $content;   
  }
}