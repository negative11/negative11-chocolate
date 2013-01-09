<?php
/**
 * Custom Error and Exception handler.
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
					die('Invalid number of arguments supplied to Core Exception handler.');
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
}