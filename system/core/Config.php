<?php
/**
 * Configuration manager.
 * Loads and caches configuration values from files in config folders.
 * Methods provide interface for retrieving or overloading configuration values.
 */
class Config
{

	private static $config = array();

	/**
	 * Set configuration value.
	 * Used to cache a value, or override an existing value.
	 * 
	 * @param type $key
	 * @param type $value
	 */
	public static function set($key, $value)
	{
		// Break on '.' delimiter.
		$pieces = explode('.', $key);

		// Set each nested key recursively.
		$config = &self::$config;

		while (is_string($offset = array_shift($pieces)))
		{
			if (count($pieces))
			{
				$config = &$config[$offset];
			}
			else
			{
				$config[$offset] = $value;
			}
		}

		unset($config);
	}

	/**
	 * Get configuration value.
	 * 
	 * @param type $key
	 * @return type
	 */
	public static function get($key)
	{
		// Break on '.' delimiter.
		$pieces = explode('.', $key);

		// Everything before the first '.' is a file.
		$file = array_shift($pieces);
		if (!isset(self::$config[$file]))
			self::loadConfig($file);

		// Everything remaining '.' is a nested array key. 
		// For example, a.b.c = $config[a][b][c]
		$value = isset(self::$config[$file]) ? self::$config[$file] : NULL;
		while (is_string($offset = array_shift($pieces)))
		{
			$value = isset($value[$offset]) ? $value[$offset] : NULL;
		}

		return $value;
	}

	/**
	 * Load configuration file.
	 * 
	 * @param type $file
	 */
	private static function loadConfig($file)
	{
		$filename = Loader::search('config' . DIRECTORY_SEPARATOR . $file);

		if ($filename)
		{
			// Fetch valid configuration array.
			// Cache will prevent multiple includes.
			// In the event that it does not, honor the load.
			include($filename);

			// Cache configuration contents.
			if (isset($config) && is_array($config))
			{
				self::set($file, $config);
			}
		}
	}

}