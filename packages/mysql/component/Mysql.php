<?php
namespace component;

/**
 * MySQL abstraction layer provides the following 
 * functionality:
 * 		
 * 	Uses PHP MySQL Improved Extension. 
 * 	@see http://us3.php.net/mysqli
 *
 * 	Connects to MySQL using parameters defined in config.
 * 		
 * 	Query binding via arbitrary arguments supplied to the query()
 * 	method. Also prepares data with mysqli_real_escape_string.
 * 
 * 	Results returned in the form of result objects.
 */
class Mysql
{
  /**
   * Configuration settings.
   * @var type 
   */
  protected static $config = array();
  
  /**
   * MySQLi connection handle.
   * @var type 
   */
  protected $connection;
  
  /**
   * __construct.
   * @param type $connectionName
   */
  public function __construct($connectionName = 'default')
  {
    $this->setupConfiguration($connectionName);
  }
  
  /**
   * Establishes configuration for supplied connection name.
   * @param type $connectionName
   * @throws \Exception
   */
  private function setupConfiguration($connectionName)
  {    
    $config = \Config::get("mysql.{$connectionName}");
    if (is_array($config))
    {
      self::$config = $config;
    }
    else
    {
      throw new \Exception("Unknown MySQL connection configuration '{$connectionName}'.");
    }
  }
  
  /**
   * Return configuration settings.
   * @return type
   */
  public function getConfiguration()
  {
    return self::$config;
  }
   
  /**
   * Connect using configuration settings.
   */
  public function connect()
  {
    if ($this->connection === NULL)
    {
      $config = $this->getConfiguration();
      $host = isset($config['host']) ? $config['host'] : NULL;
      $username = isset($config['username']) ? $config['username'] : NULL;
      $password = isset($config['password']) ? $config['password'] : NULL;
      $database = isset($config['database']) ? $config['database'] : NULL;
      $port = isset($config['port']) ? $config['port'] : NULL;
      $socket = isset($config['socket']) ? $config['socket'] : NULL;

      $this->connection = new \mysqli($host, $username, $password, $database, $port, $socket);
    }
  }
  
  /**
	 * Binds query parameters if supplied.
	 * Delegates execution of supplied query to mysqli\Result object.
	 * @param string $sql
	 * @param mixed Additional unlimited arguments are treated as query
	 * binds.
	 * @return component\mysqli\Result $result
	 */
	public function query($sql, $binds = NULL)
	{
    // Establish connection if necessary.
    $this->connect();
    
		// First argument must be $sql
		$arguments = func_get_args();
		$sql = array_shift($arguments);
		
		// Any subsequent args are considered binds
		if (count($arguments) >= 1)
		{
			// Binds can be passed as an array, or as individual arguments, but not both.
			if (is_array($arguments[0]))
			{
				$arguments = $arguments[0];
			}
				
			$binds = $arguments;
						
			// Distinguish binds from actual punctuation.
			$macro = '_BIND_';
			$sql = str_replace('?', $macro, $sql);

			foreach ($binds as $bind)
			{
				// Safe values only. Literal statements are ok.
				if ($bind instanceof \helper\mysql\literal)
				{
					$value = (string) $bind;
				}
				else
				{
					$value = $this->escape($bind);
					if (!is_numeric($value))
					{
						$value = "'{$value}'";
					}
				}
				
				// Replace bind
				$next = strpos($sql, $macro);
				$sql = substr($sql, 0, $next) . $value . substr($sql, ($next + strlen($macro)));
			}						
		
			// Flush any leftover binds. 
			$sql = str_replace($macro, '?', $sql);
		}
		
		// Generate result object. Hand of connection and query.
		return new mysqli\Result($this->connection, trim($sql));
	}
  
  /**
	 * Return real_escape_string() safe value.
	 * 
	 * @param type $value
	 * @return type 
	 */
	public function escape($value)
	{
    // Establish connection if necessary.
    $this->connect();
    
		return $this->connection->real_escape_string($value);
	}
}