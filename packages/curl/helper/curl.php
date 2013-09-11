<?php

/**
 * Curl_Helper
 * Basic cURL functionality. 
 * Provides functions for GET, POST, PUT, DELETE methods.
 */

namespace helper;

class Curl
{
  /**
   * Maximum number of seconds to wait for a response from the remote server.
   */

  const CONNECTION_TIMEOUT = 10;

  /**
   * Send a GET request using cURL.
   * @param string $url to request
   * @param array $get values to send
   * @param array $options for cURL
   * @return string
   */
  public static function get($url, array $get = array(), array $options = array())
  {
    $defaults = array(
      CURLOPT_URL => $url . (strpos($url, '?') === FALSE ? '?' : '') . http_build_query($get),
      CURLOPT_HEADER => FALSE,
      CURLOPT_RETURNTRANSFER => TRUE,
      CURLOPT_TIMEOUT => self::CONNECTION_TIMEOUT
    );

    return self::send($options + $defaults);
  }

  /**
   * Send a POST request using cURL.
   * @param string $url to request
   * @param array $post POST data to send
   * @param array $options for cURL
   * @return string
   */
  public static function post($url, array $post = array(), array $options = array())
  {
    $defaults = array(
      CURLOPT_URL => $url,
      CURLOPT_POST => TRUE,
      CURLOPT_POSTFIELDS => http_build_query($post),
      CURLOPT_HEADER => FALSE,
      CURLOPT_RETURNTRANSFER => TRUE,
      CURLOPT_TIMEOUT => self::CONNECTION_TIMEOUT
    );

    return self::send($options + $defaults);
  }
  /**
   * Send a POST request using cURL and JSON-encode parameter array.
   * Sends appropriate JSON headers to complete transaction.
   * @param string $url to request
   * @param array $post POST data to send
   * @param array $options for cURL
   * @return string
   */
  public static function postJson($url, array $post = array(), array $options = array())
  {
    $json = json_encode($post);
    
    $defaults = array(
      CURLOPT_URL => $url,
      CURLOPT_POST => TRUE,
      CURLOPT_POSTFIELDS => $json,
      CURLOPT_HEADER => FALSE,
      CURLOPT_RETURNTRANSFER => TRUE,
      CURLOPT_TIMEOUT => self::CONNECTION_TIMEOUT,
      CURLOPT_HTTPHEADER => array('Content-Type: application/json', 'Accept: application/json')
    );

    return self::send($options + $defaults);
  }

  /**
   * Send a PUT request using cURL.
   * @param type $url
   * @param array $put
   * @param array $options
   * @return type 
   */
  public static function put($url, array $put = array(), array $options = array())
  {
    $defaults = array(
      CURLOPT_URL => $url,
      CURLOPT_CUSTOMREQUEST => 'PUT',
      CURLOPT_POSTFIELDS => http_build_query($put),
      CURLOPT_HEADER => FALSE,
      CURLOPT_RETURNTRANSFER => TRUE,
      CURLOPT_TIMEOUT => self::CONNECTION_TIMEOUT
    );

    return self::send($options + $defaults);
  }

  /**
   * Send a DELETE request using cURL.
   * @param type $url
   * @param array $put
   * @param array $options
   * @return type 
   */
  public static function delete($url, array $delete = array(), array $options = array())
  {
    $defaults = array(
      CURLOPT_URL => $url,
      CURLOPT_CUSTOMREQUEST => 'DELETE',
      CURLOPT_POSTFIELDS => http_build_query($delete),
      CURLOPT_HEADER => FALSE,
      CURLOPT_RETURNTRANSFER => TRUE,
      CURLOPT_TIMEOUT => self::CONNECTION_TIMEOUT
    );

    return self::send($options + $defaults);
  }

  /**
   * Execute cURL using supplied options.
   * @param array $options
   * @return type
   * @throws \Exception 
   */
  private static function send(array $options = array())
  {
    $ch = curl_init();

    curl_setopt_array($ch, $options);

    $response = curl_exec($ch);

    if ($response === FALSE)
    {
      // Bah!                
      throw new \Exception(curl_error($ch));
    }

    // Fetch response headers, etc.
    $info = curl_getinfo($ch);

    curl_close($ch);

    $result = new \stdClass();
    $result->response = $response;
    $result->info = $info;

    return $result;
  }

}