<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\LmCondition\tests;

class KeyValueContextTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var KeyValueContext 
     */
    protected $context;
    
    protected function setUp()
    {
        $this->context = new KeyValueContext();
    }
    
    public function testSettingAndGettingProperValue()
    {
        $this->context->set("myKey", "myVal");
        $this->assertEquals("myVal", $this->context->get("myKey"));
    }
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testThrowingExceptionWhenWrongKey()
    {
        $this->context->get("not_existing_key");
    }
}
