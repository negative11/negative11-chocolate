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
    // Get type-specific suffix prefix.
    $suffixPrefix = $this->getSuffixPrefix();
    
    $path = $templateDirectory . DIRECTORY_SEPARATOR . $template . $suffixPrefix . FILE_EXTENSION;
		$file = \Loader::search($path, FALSE);		
    
		if ($file === FALSE)
    {
      throw new \Exception ("Invalid template name: '{$template}'. Could not find file '{$path}'.");
    }
    
		return $file;
	}
  
  /**
   * Get the suffix prefix for extending type.
   * @return type
   */
  private function getSuffixPrefix()
  {
    return static::TEMPLATE_SUFFIX_PREFIX;
  }
}