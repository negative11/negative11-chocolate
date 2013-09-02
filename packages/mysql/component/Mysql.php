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
 * 
 *  Provides helper methods for building queries from dynamic data.
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
   * Delegates execution of supplied query to mysql\Result object.
   * @param string $sql
   * @param mixed Additional unlimited arguments are treated as query
   * binds.
   * @return component\mysql\Result $result
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
    return new mysql\Result($this->connection, trim($sql));
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

  /**
   * Make `column` = ? binds out of supplied associative array.
   * @param array $array
   * @param string $delimiter ',', AND, OR, etc.
   * @return string
   */
  public function bind(array $array = array(), $delimiter = ',')
  {
    if (count($array))
    {
      $binds = array();
      foreach (array_keys($array) as $key)
      {
        $binds[] = "`{$key}` = ?";
      }

      return implode(" {$delimiter} ", $binds);
    }

    return NULL;
  }

  /**
   * Creates ORDERBY statement from array values.
   * @param array $orderby
   * @return string
   */
  public function orderby(array $orderby = array())
  {
    if (count($orderby))
    {
      $sorts = array();

      foreach ($orderby as $column => $value)
      {
        $sorts[] = "`{$column}` {$value}";
      }

      return ' ORDER BY ' . implode(',', $sorts);
    }

    return NULL;
  }

  /**
   * Creates WHERE statement from array values.
   * Uses simple WHERE col = val patterns.	 * 
   * @param array $where
   * @return string
   */
  public function where(array $where = array())
  {
    if (count($where))
    {
      $conditions = array();

      foreach ($where as $column => $value)
      {
        if (is_numeric($value))
        {
          $value = $this->escape($value);
          $conditions[] = "`{$column}` = {$value}";
        }
        elseif ($value instanceof \helper\mysql\Literal)
        {
          $conditions[] = "{$column} {$value}";
        }
        else
        {
          $value = $this->escape($value);
          $conditions[] = "`{$column}` = '{$value}'";
        }
      }

      return ' WHERE ' . implode(' AND ', $conditions);
    }

    return NULL;
  }

  /**
   * Build LIMIT statement.
   * @param integer $limit
   * @param integer $offset
   * 
   * @return string
   */
  public function limit($limit = NULL, $offset = NULL)
  {
    if (isset($limit, $offset))
    {
      return ' LIMIT ' . (int) $offset . ',' . (int) $limit;
    }
    elseif (isset($limit))
    {
      return ' LIMIT ' . (int) $limit;
    }

    return NULL;
  }

  /**
   * Build IN statement.
   * This method will convert all items to quoted/unquoted based on type.
   * @param string $column
   * @param array $values
   * @return string
   */
  public function in($column, array $values)
  {
    $ins = array();

    foreach ($values as $value)
    {
      if (is_numeric($value) || $value instanceof \helper\mysql\Literal)
      {
        $value = $this->escape($value);
        $ins[] = $value;
      }
      else
      {
        $value = $this->escape($value);
        $ins[] = "'{$value}'";
      }
    }

    $in = " `{$column}` IN (" . implode(',', $ins) . ')';
    return $in;
  }

  /**
   * Builds a NOT IN statement.
   * @param string $column
   * @param array $values
   * @return string
   */
  public function notin($column, array $values)
  {
    $ins = array();

    foreach ($values as $value)
    {
      if (is_numeric($value) || $value instanceof \helper\mysql\Literal)
      {
        $value = $this->escape($value);
        $ins[] = $value;
      }
      else
      {
        $value = $this->escape($value);
        $ins[] = "'{$value}'";
      }
    }

    $in = " `{$column}` NOT IN (" . implode(',', $ins) . ')';
    return $in;
  }

}