<?php
/**
 * HTML Adapter for standard web-page output.
 */
namespace component\adapter\view;

class Html extends Template
{  
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