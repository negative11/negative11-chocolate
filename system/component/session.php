<?php
/**
 * Default session handler.
 * Handles automatic provision of a session for entire framework.
 * Provides convention for custom handler.
 */
namespace component;
class Session
{	
	/**
	 * Constructor. 
	 * Initializes basic file-based session. 
	 */
	public function __construct()
	{
		$config = \Config::get('session');
		
		// Set session lifetime.
		ini_set('session.gc_maxlifetime', $config['lifetime']);
		
    // Set the session cookie name.
    session_name($config['name']);
    
		// Set basic session cookie parameters.
		$cookie = $config['cookie'];
    
		session_set_cookie_params
		(
			$config['lifetime'],
			$cookie['path'],
			$cookie['domain'],
			$cookie['secure'],
			$cookie['httponly']				
		);
		
		session_start();
	}
}