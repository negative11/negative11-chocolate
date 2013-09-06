<?php

// Load the PHPUnit framework setup file.
if ( ! defined ('ENVIRONMENT_ROOT')) define ('ENVIRONMENT_ROOT', realpath(dirname(dirname(dirname(__DIR__)))));
require_once  ENVIRONMENT_ROOT . DIRECTORY_SEPARATOR . 'phpunit' . DIRECTORY_SEPARATOR . 'kickstart.php';

class MysqlCrudTest extends PHPUnit_Framework_TestCase
{  
  private $component;
  
  public function setup()
  {
    $this->component = new component\Mysql();
    
    $sql = "CREATE TABLE IF NOT EXISTS `sampleObject` (
      `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
      `abc` VARCHAR(255) NOT NULL ,
      `def` VARCHAR(255) NOT NULL ,
      `ghi` VARCHAR(255) NOT NULL ,
      PRIMARY KEY (`id`) );
    ";
    
    $result = $this->component->query($sql);    
    $this->assertInstanceOf('component\mysql\Result', $result);
  }
  
  public function teardown()
  {
    $sql = "DROP TABLE `example`.`sampleObject`;";
    $result = $this->component->query($sql);
    $this->assertInstanceOf('component\mysql\Result', $result);
  }
  
  public function testSampleCrudModel()
  {
    $crud = new \model\Sample($this->component);
    $this->assertInstanceOf('\model\Crud', $crud);
    
    // Create.
    $data = array(
      'abc' => 123,
      'def' => 456,
      'ghi' => "Six was afraid"
    );
    
    $insertId = $crud->create($data);
    $this->assertTrue(is_int($insertId));
    
    // Read it back.
    $object = $crud->readOne(array('id' => $insertId));
    $this->assertInstanceOf('\stdClass', $object);
    $this->assertEquals($data['abc'], $object->abc);
    $this->assertEquals($data['def'], $object->def);
    $this->assertEquals($data['ghi'], $object->ghi);
    
    // Update a column.
    $newValue = 789;
    $affectedRows = $crud->update(array('id' => $insertId), array('ghi' => $newValue), $limit = 1);
    $this->assertEquals(1, $affectedRows);
    
    // Read it back.
    $object = $crud->readOne(array('id' => $insertId));
    $this->assertInstanceOf('\stdClass', $object);
    $this->assertEquals($data['abc'], $object->abc);
    $this->assertEquals($data['def'], $object->def);
    $this->assertEquals($newValue, $object->ghi);
    
    // Delete.
    $deletedRows = $crud->delete(array('id' => $insertId), $limit = 1);
    $this->assertEquals(1, $deletedRows);
    
    // Insert several rows.
    for ($i = 0; $i < 3; $i++)
    {
      $insertId = $crud->create($data);
      $this->assertTrue(is_int($insertId));
    }
    
    // Select a few rows.
    // Apply order by, limit, with offset.
    $rows = $crud->read(array(), array('id' => 'DESC'), $limit = 2, $offset = 0);
    foreach ($rows as $object)
    {
      $this->assertInstanceOf('\stdClass', $object);
      $this->assertEquals($data['abc'], $object->abc);
      $this->assertEquals($data['def'], $object->def);
      $this->assertEquals($data['ghi'], $object->ghi);
    }
    
    // Update all the rows.
    $affectedRows = $crud->update(array('ghi' => $data['ghi']), array('ghi' => $newValue));
    $this->assertEquals(3, $affectedRows);
    
    // Delete all the rows.
    $deletedRows = $crud->delete();
    $this->assertEquals(3, $deletedRows);
  }
}