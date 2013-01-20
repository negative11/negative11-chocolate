<?php
/**
 * Core engine of framework.
 */
final class Core
{
	/**
	 * When errors occur, we want to ignore any template draws other 
	 * than our error handlers.
	 */
	public static $draw = TRUE;

	/**
	 * Handles dynamic call of classes.
	 *
	 * @param $class
	 */
	public static function autoload($class)
	{
		/**
		 * All files are loaded by namespace.
		 */
		$class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
		Loader::get($class);
	}

	/**
	 * Handles display of 404 Page Not Found errors
	 */
	public static function error404()
	{
		header('HTTP/1.0 404 Not Found');
		new \controller\error\_404;

		// Shut all other Views down.
		// Note: This will not stop controller __destruct from rendering, so use disableAutoDraw().
		self::$draw = FALSE;
		exit;
	}

	/**
	 * Initialize the framework.
	 */
	public static function initialize()
	{
		// Start buffer
		ob_start();

		// Get system configuration parameters
		Loader::get('config' . DIRECTORY_SEPARATOR . 'core');

		// Register autload function
		spl_autoload_register('Core::autoload');

		// Set custom error/exception handlers
		set_error_handler(array('Error', 'handler'));
		set_exception_handler(array('Error', 'handler'));
		register_shutdown_function(array(__CLASS__, 'shutdown'));

		// Provide framework with a session.
		new component\Session;
	}

	/**
	 * Run the framework.
	 * 
	 * @static
	 * @return bool
	 */
	public static function run()
	{
		// Router determines controller, method, and arguments
		Router::current();

		// Load controller if it exists
		$exists = Loader::get('controller' . DIRECTORY_SEPARATOR . Router::$controller);

		if ($exists)
		{
			return self::runController(Router::$controller);
		}

		// We couldn't find a controller.
		self::error404();
	}

	/**
	 * Execute specified Controller.
	 * 
	 * @param string $controller
	 * @return boolean
	 */
	private static function runController($controller)
	{
		$controller = '\controller\\' . str_replace(DIRECTORY_SEPARATOR, '\\', $controller);

		$object = new $controller;

		// Check for default/hidden methods
		$hidden = (bool) (substr(Router::$method, 0, 1) === '_');

		// Ensure that the controller method is callable		
		$method = array($object, Router::$method);

		// Ensure that controller is enabled.
		if (!$controller::ENABLED)
		{
			throw new \Exception('Controller is disabled.');
		}

		if (is_callable($method) && $hidden === FALSE)
		{
			// Run controller method
			call_user_func_array($method, Router::$arguments);
			return TRUE;
		}

		// We couldn't load the method.
		self::error404();
	}

	/**
	 * Shut it down.
	 * Cleans up execution.
	 * Flushes buffers.
	 * Also acts as last-ditch effort to grab fatal errors to display
	 * debugger.
	 */
	public static function shutdown()
	{
		// If an error occurred, we'll call our handler.
		$error = error_get_last();

		if (isset($error))
		{
			/*
			 * We must call directly.
			 * Throwing Exception will yield:
			 * Exception thrown without a stack frame in Unknown on line 0
			 */
			Error::handler(
				$error['type'], 
				$error['message'], 
				$error['file'], 
				$error['line'], 
				array('Core::shutdown()')
			);
		}

		// Flush buffer
		print ob_get_clean();
	}

	/**
	 * Dump helper.
	 * Outputs arbitrary number of arguments with fancy display.
	 *
	 * @param ... ... ...
	 */
	public static function dump()
	{
		if (!IN_PRODUCTION)
		{
			$arguments = func_get_args();

			$dump = new component\View('dump');
			$dump->data = $arguments;
			$dump->count = count($arguments);
			$dump->display();
		}
	}
}
