<?php

require_once __DIR__ . '/../../../PhraseanetPHPUnitAbstract.class.inc';

/**
 * Test class for XcacheCache.
 * Generated by PHPUnit on 2012-02-21 at 16:39:57.
 */
class XcacheCacheTest extends \PHPUnit_Framework_TestCase
{

  /**
   * @var XcacheCache
   */
  protected $object;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  public function setUp()
  {
    if(!function_exists('xcache_info'))
    {
      $this->markTestSkipped('Xcache not loaded');
    }
    
    $this->object = new \Alchemy\Phrasea\Cache\XcacheCache;
  }

  public function testIsServer()
  {
    $this->assertTrue(is_bool($this->object->isServer()));
  }

  public function testGetStats()
  {
    $this->assertTrue(is_array($this->object->getStats()) || is_null($this->object->getStats()));
  }

  public function testGet()
  {
    // Remove the following lines when you implement this test.
    $this->markTestIncomplete(
      'This test has not been implemented yet.'
    );
  }

  public function testDeleteMulti()
  {
    // Remove the following lines when you implement this test.
    $this->markTestIncomplete(
      'This test has not been implemented yet.'
    );
  }

}

