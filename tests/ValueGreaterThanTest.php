<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\LmCondition\tests;

class ValueGreaterThanTest extends \PHPUnit_Framework_TestCase
{
    protected $c;
    
    public function setUp()
    {
        $this->c = new ValueGreaterThan(42);
    }
    
    public function testTrueCondition()
    {
        $this->assertTrue($this->c->isTrueIn(new KeyValueContext(['value' => 43])));
    }
    
    public function testFalseCondition()
    {
        $this->assertFalse($this->c->isTrueIn(new KeyValueContext(['value' => 42])));
    }
    
    public function testGettingMustBeGreaterThanValue()
    {
        $this->assertEquals(42, $this->c->getMustBeGreaterThanValue());
    }
}
