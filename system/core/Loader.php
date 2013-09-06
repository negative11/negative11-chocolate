<?php
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
		SYSTEM_DIRECTORY
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
			// Does file exist in exact case supplied?
			$filename = $section . DIRECTORY_SEPARATOR . $file . FILE_EXTENSION;

			if (file_exists($filename))
			{
				return $filename;
			}

			// Does file exist as lowercase?
			$filename = $section . DIRECTORY_SEPARATOR . strtolower($file) . FILE_EXTENSION;

			if (file_exists($filename))
			{
				return $filename;
			}
		}

		return FALSE;
	}
}