<?php

// Load the PHPUnit framework setup file.
define ('ENVIRONMENT_ROOT', realpath(dirname(dirname(dirname(__DIR__)))));
require_once  ENVIRONMENT_ROOT . DIRECTORY_SEPARATOR . 'phpunit' . DIRECTORY_SEPARATOR . 'kickstart.php';

class MysqlComponentTest extends PHPUnit_Framework_TestCase
{  
  private $component;
  
  public function setup()
  {
    $this->component = new component\Mysql();
    
    $sql = "CREATE TABLE IF NOT EXISTS `test1` (
      `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
      `value` VARCHAR(255) NOT NULL ,
      PRIMARY KEY (`id`) );
    ";
    
    $result = $this->component->query($sql);    
    $this->assertInstanceOf('component\mysqli\Result', $result);
  }
  
  public function teardown()
  {
    $sql = "DROP TABLE `example`.`test1`;";
    $result = $this->component->query($sql);
    $this->assertInstanceOf('component\mysqli\Result', $result);
  }
  
  public function testCrudWithBinds()
  {        
    // Create with binds.
    $result = $this->component->query(
      "INSERT INTO `test1` SET `value` = ?",
      'tacos'
    );
    
    $this->assertInstanceOf('component\mysqli\Result', $result);
    $this->assertEquals(1, $result->getAffected());
    $this->assertEquals(1, $result->getInsertId());
    
    // Read with binds.
    $result = $this->component->query(
      "SELECT * FROM `test1` WHERE `value` = ?",
      'tacos'
    );
    
    $this->assertInstanceOf('component\mysqli\Result', $result);
    $this->assertInstanceOf('mysqli_result', $result->getResult());
    $this->assertEquals(1, $result->getCount());
    
    // Update with binds.
    $result = $this->component->query(
      "UPDATE `test1` SET `value` = ? WHERE `value` = ?",
      'burritos',
      'tacos'
    );
    
    $this->assertInstanceOf('component\mysqli\Result', $result);
    $this->assertEquals(1, $result->getAffected());
    
    // Delete with binds.
    $result = $this->component->query(
      "DELETE FROM `test1` WHERE `value` = ?",
      'burritos'
    );
    
    $this->assertInstanceOf('component\mysqli\Result', $result);
    $this->assertEquals(1, $result->getAffected());
  }
  
  public function testInsertWithLiteral()
  {
    $timestamp = time();
    
    // Create with binds.
    $result = $this->component->query(
      "INSERT INTO `test1` SET `value` = ?",
      new helper\mysql\Literal("NOW()")
    );
    
    $this->assertInstanceOf('component\mysqli\Result', $result);
    $this->assertEquals(1, $result->getAffected());
    $this->assertEquals(1, $result->getInsertId());
    
    $result = $this->component->query(
      "SELECT UNIX_TIMESTAMP(`value`) AS `ts` FROM `test1`"
    );

    $this->assertInstanceOf('component\mysqli\Result', $result);
    $this->assertInstanceOf('mysqli_result', $result->getResult());
    $this->assertEquals(1, $result->getCount());
    $this->assertEquals($timestamp, $result->current()->ts);
  }
}