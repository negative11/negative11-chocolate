<?php
/**
 * Engine of framework.
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
		 * We strtolower to disuade case-sensitive file naming.
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
		Loader::get('config/core');
		
		// Register autload function
		spl_autoload_register('Core::autoload');
		
		// Set custom error/exception handlers
		//set_error_handler(array('Error', 'handler'));
		//set_exception_handler(array('Error', 'handler'));
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
			Error::handler
			(
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
		if ( ! IN_PRODUCTION)
		{
			$arguments = func_get_args();
			
			$dump = new component\View('dump');
			$dump->data = $arguments;
			$dump->count = count($arguments);
			$dump->display();
		}		
	}
	
} // End Core

/**
 * Custom Error and Exception handler
 */
final class Error
{
	/**
	 * Accepts PHP Errors and Exceptions and displays custom debugger.
	 *
	 * dynamic params:
	 * 1. mixed $error Object if Exception, error_number if standard
	 * PHP error.
	 * 2. string $string Optional for PHP Error
	 * 3. string $file Optional for PHP Error
	 * 4. integer $line Optional for PHP Error
	 *
	 * @static
	 */
	public static function handler()
	{
		// Exceptions will contain only an object
		$args = func_get_args();

		try
		{
			// Build debugger
			$debugger = new \component\View('debugger');

			switch (count($args))
			{
				// Errors will supply 2-5 parameters
				case 5:

					$debugger->context = $args[4];

				case 4:

					$debugger->line_number = $args[3];

				case 3:

					$debugger->file_name = $args[2];

				case 2:

					$debugger->message = $args[1];
					$debugger->error_type = self::getErrorType($args[0]);
					$debugger->backtrace = debug_backtrace();

				break;

				// Exceptions will supply only an object
				case 1:

					$exception = $args[0];
					$debugger->message = $exception->getMessage();
					$debugger->file_name = $exception->getFile();
					$debugger->line_number = $exception->getLine();
					$debugger->error_type = get_class($exception);
					$debugger->backtrace = $exception->getTrace();

				break;

				default:

					die ('Invalid number of arguments supplied to Core Exception handler.');

				break;
			}

			// Flush any open output buffers. We only want to see errors.
			ob_get_clean();

			// Show error
			Core::$draw = FALSE;
			header('HTTP/1.1 500 Internal Server Error');
			$debugger->display();
		}
		catch (\Exception $e)
		{
			print "Error occurred in Framework Exception Handler: " . $e->getMessage();
		}

		exit;
	}
	
	/**
	 * Returns string representation for supplied PHP error constant.
	 *
	 * @param $errorNumber
	 * @return string $type
	 */
	private static function getErrorType($errorNumber)
	{
		// Display custom error type. We group similar types.
		switch ($errorNumber)
		{
			case E_ERROR:
			case E_CORE_ERROR:
			case E_COMPILE_ERROR:
			case E_USER_ERROR:
				return 'Fatal Error';
			break;
			
			case E_WARNING:
			case E_CORE_WARNING:
			case E_COMPILE_WARNING:
			case E_USER_WARNING:
				return 'Warning';
			break;
			
			case E_PARSE:
				return 'Parse Error';
			break;
			
			case E_NOTICE:
			case E_USER_NOTICE:
				return 'Notice';
			break;	
			
			case E_STRICT:
				return 'Strict Error';
			break;
			
			case E_RECOVERABLE_ERROR:
				return 'Recoverable Error';
			break;
			
			case E_DEPRECATED:
			case E_USER_DEPRECATED:
				return 'Deprecated';
			break;
			
			default:
				return 'Unknown Error';
			break;			
		}
	}

} // End Error

/**
 * Fetches files as requested
 */
final class Loader
{	
	/**
	 * Cascade order.
	 * We will iterate through these when searching for files.
	 * Elements earliest in array will be overloaded by elements later
	 * in the array.
	 * Typically, this should contain only the system section by 
	 * default, with additional sections added by configuration or
	 * throughout initialization.
	 */
	public static $sections = array 
	(
		'system'
	);

	/**
	 * Does the searching and loading file.
	 * If the file does not exist, an Exception will be thrown.
	 *
	 * @static
	 * @param $file
	 * @return bool
	 * @throws Exception
	 */
	public static function get($file)
	{
		$filename = self::search($file);
		if ($filename)
		{
			self::load($filename);
			return TRUE;
		}
		
		return FALSE;
	}
	
	/**
	 * Loads file.
	 * 
	 * @param $path
	 */
	public static function load($path)
	{
		require_once $path;
	}

	/**
	 * Searches for supplied file in folder of supplied type.
	 * If found, returns the path of the found file, which can then be
	 * loaded.
	 *
	 * @static
	 * @param $file
	 * @return bool|string
	 */
	public static function search($file)
	{				
		foreach (array_reverse(self::$sections) as $section)
		{
			$filename = ENVIRONMENT_ROOT . '/' . $section . '/' . strtolower($file) 
					. FILE_EXTENSION;

			if (file_exists($filename))
			{
				return $filename;
			}

		}
		
		return FALSE;
	}
	
} // End Loader

/**
 * Global Registry for the handling of system-wide variables and 
 * parameters.
 */
final class Registry
{
	// All configuration parameters defined in files go here.
	public static $config = array();
	
	// Use this array to store any arbitrary information you like.
	public static $info = array();
	
} // End Registry

/**
 * The router determines which controller should be invoked by the 
 * current URI request.
 */
final class Router
{
	/**
	 * Components extracted from URI and used by core to load 
	 * controllers.
	 */
	public static $controller;
	public static $method;
	public static $arguments = array();
	
	/**
	 * Determines the appropriate controller, method, and arguments 
	 * from the current URI request.
	 * Where necessary, defaults will be employed.
	 * Values are stored in local static members for use by the core.
	 */
	public static function current()
	{		
		$current = self::getRequestPath();
		
		// Are we being run from command line?
		if (PHP_SAPI === 'cli')
		{							
			// $argv and $argc are disabled by default in some configurations.
			$argc = isset($argc) ? $argc : $_SERVER['argc'];
			
			if ($argc > 0)
			{
				$args = isset($argv) ? $argv : $_SERVER['argv'];				
				
				// Merge all cli arguments as if they were in a uri from web context.
				$current = implode('/', $args);
			}
			else
			{
				$current = self::getRequestPath();
			}
		}
		
		// Remove dot paths
		$current = preg_replace('#\.[\s./]*/#', '', $current);
		
		// Remove leading slash
		$current = ltrim($current, '/');
		
		// Reduce multiple slashes
		$current = preg_replace('#//+#', '/', $current);
		
		// Remove front controller from URI if present (depends on variable used)
		$frontController = \Registry::$config['core']['front_controller'] . FILE_EXTENSION;		
		if (substr($current, 0, (strlen($frontController))) == $frontController)
		{
			$current = substr($current, (strlen($frontController)));
		}
		
		// Remove any remaining leading/trailing slashes
		$current = trim($current, '/');
		
		// Check for rewrites matching configuration values.
		Loader::get('config/rewrite');
		$matched = $current;
		if (!empty(\Registry::$config['rewrites']))
		{
			foreach (\Registry::$config['rewrites'] as $match => $replace)
			{
				$match = trim($match, '/');

				// Exact match?
				if ($match == $current)
				{
					$matched = $replace;
				}
				
				// Regex match?
				elseif (preg_match('#^'.$match.'$#u', $current))
				{
					if (strpos($replace, '$'))
					{
						// Replacement requires arguments that were parsed during match.
						$matched = preg_replace('#^'.$match.'$#u', $replace, $current);
					}
					else
					{
						// Found match, no parsed arguments required.
						$matched = $replace;
					}
				}
			}
		}
		$current = $matched;
		
		$parts = array();
		if (strlen($current) > 0)
		{
			$parts = explode('/', $current);
		}
		
        if (empty($parts))
        {
            self::$controller = \Registry::$config['core']['default_controller'];
        }
        else 
        {
            $controller = '';
            while (count($parts))
            {
                $controller .= array_shift($parts);

                if (Loader::search("controller/{$controller}"))
                {
                    self::$controller = $controller;

                    if (count($parts))
                    {
                        self::$method = array_shift($parts);
                    }

                    if (count($parts))
                    {
                        self::$arguments = $parts;
                    }
                }
                else
                {
                    $controller .= '/';
                }
            }
        }
        
        if (empty(self::$method))
        {
            self::$method = \Registry::$config['core']['default_controller_method'];
        }        
	}
	
	/**
	 * Return request path using preferred $_SERVER variable.
	 * 
	 * @note You may need to change the variable set in kickstart.php that is used to determine
	 * the path. Some $_SERVER vars are not consistent across installations, .htaccess files, 
	 * etc.
	 */
	private static function getRequestPath()
	{		
		switch (\CORE_SERVER_VAR)
		{
			case 'REQUEST_URI':
				$useVar = strtok($_SERVER['REQUEST_URI'], '?');
			break;
			
			case 'PATH_INFO':
				$useVar = $_SERVER['PATH_INFO'];
			break;
			
			case 'PHP_SELF':
			default:
				$useVar = $_SERVER['PHP_SELF'];
			break;
		}
		
		return $useVar;
	}
	
	/**
	 * Redirect to another location.
	 * 
	 * @param $location
	 */
	public static function redirect($location = '/')
	{
		header('HTTP/1.1 302 Moved Temporarily');
		header("Location: {$location}");
		exit;		
	}
	
} // End Router

/**
 * Singleton Manager.
 * @see http://johnsquibb.com/articles/singleton-of-singletons
 */
final class Singleton
{
	/**
	 * Maintains collection of instantiated classes
	 */
	private static $instances = array();
	
	/**
	 * Overload constructor
	 */
	private function __construct(){}
	
	/**
	 * Manages instantiation of classes
	 * 
	 * @param $class
	 * 
	 * @return object instance
	 */
	public static function instance($class)
	{		
		//instantiate class as necessary
		self::create($class);	
		
		//return instance
		return self::$instances[$class];
	}
	
	/**
	 * Creates the instances
	 * 
	 * @param $class
	 * 
	 * @return none
	 */
	private static function create($class)
	{
		//check if an instance of requested class exists
		if (!array_key_exists($class , self::$instances))
		{
			self::$instances[$class] = new $class;
		}
	}
} // End Singleton