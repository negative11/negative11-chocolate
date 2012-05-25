<?php
// Apply system-wide configuration parameters
\Registry::$config['core'] = array
(
	/**
	 * Front controller file.
	 */
	'front_controller' => 'index',
	/**
	 * The default controller that is loaded when none is supplied.
	 */
	'default_controller' => 'hello',
	/**
	 * The default method that is loaded for a controller when no other
	 * method is supplied.
	 */
	'default_controller_method' => 'main',
);

\Registry::$config['session'] = array
(
	// Expiration time in seconds.
	'lifetime' => 7200,
	'name' => 'phpsession',
);