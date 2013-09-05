<?php

// Load the PHPUnit framework setup file.
define ('ENVIRONMENT_ROOT', realpath(dirname(dirname(dirname(__DIR__)))));
require_once  ENVIRONMENT_ROOT . DIRECTORY_SEPARATOR . 'phpunit' . DIRECTORY_SEPARATOR . 'kickstart.php';

class CurlHelperTest extends PHPUnit_Framework_TestCase
{  
  public function testGetMethod()
  {
    $url = 'http://strem.in/rss/latest_streams';
    $response = helper\Curl::get($url);
    $this->assertEquals(200, $response->info['http_code']);    
  }
  
  public function testPostMethod()
  {
    $url = 'http://strem.in/rss/latest_streams';
    $response = helper\Curl::post($url);
    $this->assertEquals(200, $response->info['http_code']);    
  }
  
  public function testPutMethod()
  {
    $url = 'http://strem.in/rss/latest_streams';
    $response = helper\Curl::put($url);
    $this->assertEquals(200, $response->info['http_code']);    
  }
  
  public function testDeleteMethod()
  {
    $url = 'http://strem.in/rss/latest_streams';
    $response = helper\Curl::delete($url);
    $this->assertEquals(200, $response->info['http_code']);    
  }
}