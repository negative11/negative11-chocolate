<?php
/**
 * Adapter for standard output utilizing a template.
 */
namespace component\adapter\view;

abstract class Template extends \component\Adapter
{  
  /**
   * Generate adapter output.
   * Returns generated content.
   * Extend with child class for a specific output type.
   */
  protected function getTemplateOutput()
  {
    // We need to fetch file manually to allow local variable namespace.
		$file = $this->loadTemplate($this->input['templateDirectory'], $this->input['template']);		
        
    // Begin output buffer.    
    ob_start();
    
    // Get global and template variables into local namespace.
    extract($this->input['globals']);
    extract($this->input['data']); 
    
    // Capture any returned data.
    $response = require $file;    
    
    // Capture content rendered by template.
    $content = ob_get_clean();
    
		return array($response, $content);    
  }
  
  /**
   * Load the template provided in input data.
   * @param type $templateDirectory
   * @param type $template
   * @return type
   * @throws \Exception
   */
	private function loadTemplate($templateDirectory, $template)
	{		
		$file = \Loader::search($templateDirectory . DIRECTORY_SEPARATOR . $template);		
    
		if ($file === FALSE)
    {
      throw new \Exception ("Invalid template name: '{$template}'");
    }
    
		return $file;
	}
}