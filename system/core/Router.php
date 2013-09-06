<?php
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
		$frontController = \Config::get('core.front_controller') . FILE_EXTENSION;
		if (substr($current, 0, (strlen($frontController))) == $frontController)
		{
			$current = substr($current, (strlen($frontController)));
		}

		// Remove any remaining leading/trailing slashes
		$current = trim($current, '/');

		// Check for rewrites matching configuration values.
		$matched = $current;
		$rewrites = \Config::get('rewrites');
		if (!empty($rewrites))
		{
			foreach ($rewrites as $match => $replace)
			{
				$match = trim($match, '/');

				// Exact match?
				if ($match == $current)
				{
					$matched = $replace;
				}

				// Regex match?
				elseif (preg_match('#^' . $match . '$#u', $current))
				{
					if (strpos($replace, '$'))
					{
						// Replacement requires arguments that were parsed during match.
						$matched = preg_replace('#^' . $match . '$#u', $replace, $current);
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
			self::$controller = \Config::get('core.default_controller');
		}
		else
		{
			$controller = '';
			while (count($parts))
			{
				$controller .= array_shift($parts);

				if (Loader::search('controller' . DIRECTORY_SEPARATOR . $controller))
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
					$controller .= DIRECTORY_SEPARATOR;
				}
			}
		}

		if (empty(self::$method))
		{
			self::$method = \Config::get('core.default_controller_method');
		}
	}

	/**
	 * Return request path using preferred $_SERVER variable.
	 * 
	 * @note You may need to change the variable set in parameters.php that is used to determine
	 * the path. Some $_SERVER vars are not consistent across installations, .htaccess files, 
	 * etc.
	 */
	private static function getRequestPath()
	{
		switch (CORE_SERVER_VAR)
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
}