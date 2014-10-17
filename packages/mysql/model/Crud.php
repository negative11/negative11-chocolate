<?php
/**
 * Simple CRUD model for create, read, update, delete of single rows.  
 * Can be used directly, but is typically extended by another model to add 
 * methods to its base.
 */
namespace model;
abstract class Crud
{
	/**
	 * @var \component\Mysql
	 */
	protected $db;
	
	/**
	 * @var string Table to read from.
	 */
	protected $table;
				
	/**
   * __construct.
   * @param \component\Mysql $db
   */
	public function __construct(\component\Mysql $db)
	{
		$this->db = $db;
	}
	
	/**
	 * Add a new row to table.
	 * @param array $data Array of field => value pairs.
	 * @return mysqli_insert_id()
	 */
	public function create(array $data)
	{
		try
		{
			$result = $this->db->query
			(
				'INSERT INTO `' . $this->table . '`' . 
				' SET ' . $this->db->bind($data),
				array_values($data)
			);
			
			return $result->getInsertId();
		}
		catch (\Exception $e)    
		{
			throw new \Exception ('Could not create row: ' . $e->getMessage());
		}		 
	}
	
	/**
	 * Read rows from table.
	 * @param array $where WHERE conditions
	 * @param array $orderby ORDERBY conditions
	 * @param integer $limit Optional limit
	 * @param integer $offset Optional offset
	 * 
	 * @return array
	 */
	public function read(array $where = array(), array $orderby = array(), $limit = NULL, $offset = NULL)
	{
		$rows = array();
		
		$where_statement = $this->db->where($where);
		$orderby_statement = $this->db->orderby($orderby);
		$limit_statement = $this->db->limit($limit, $offset);
		
		try
		{
			$result = $this->db->query(
        "SELECT * FROM 
          `{$this->table}` 
          {$where_statement} 
          {$orderby_statement} 
          {$limit_statement}
        "
      );
			
			if ($result->getCount())
			{
				foreach ($result as $row)
				{
					$rows[] = $row;
				}
			}
		}
		catch (\Exception $e)    
		{
			throw new \Exception ('Could not get rows: ' . $e->getMessage());
		}	
		
		return $rows;
	}
  
  /**
   * Perform read query with limit of 1.
   * @param array $where WHERE conditions
   * @param array $orderby ORDERBY conditions
   * @return type
   */
  public function readOne(array $where = array(), array $orderby = array())
  {
    $rows = $this->read($where, $orderby, 1);
    if (count($rows))
    {
      return array_shift($rows);
    }
  }
	
	/**
	 * Update rows in table.
	 * @param array $where WHERE conditions
	 * @param array $data fields to update
   * @param type $limit Number of rows to limit to
	 * @return integer affected rows
	 */
	public function update(array $where = array(), array $data = array(), $limit = NULL)
	{
		$where_statement = $this->db->where($where);
		$limit_statement = $this->db->limit($limit);
    
		try
		{
			$result = $this->db->query
			(
				"UPDATE `{$this->table}` SET " . 
				$this->db->bind($data, ',') . 
				" {$where_statement} 
          {$limit_statement}
        ",
				array_values($data)				
			);
			
			return $result->getAffected();
		}
		catch (\Exception $e)    
		{
			throw new \Exception ('Could not update rows: ' . $e->getMessage());
		}		 
	}	
	
	/**
	 * Delete rows in table.
	 * @param array $where WHERE conditions
   * @param type $limit Number of rows to limit to
	 * @return integer affected rows
	 */
	public function delete(array $where = array(), $limit = NULL)
	{
		$where_statement = $this->db->where($where);
		$limit_statement = $this->db->limit($limit);
    
		try
		{
      $result = $this->db->query(
        "DELETE FROM `{$this->table}` {$where_statement} {$limit_statement}"
      );
			return $result->getAffected();
		}
		catch (\Exception $e)    
		{
			throw new \Exception ('Could not delete rows: ' . $e->getMessage());
		}
	}
	
}