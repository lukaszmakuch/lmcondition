<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\LmCondition\tests;

class BooleanTest extends \PHPUnit_Framework_TestCase
{
    protected $context;
    
    protected function setUp()
    {
        $this->context = new DummyContext();
    }
    
    public function testTrueCondition()
    {
        $c = new BooleanCondition(true);
        $this->assertTrue($c->isTrueIn($this->context));
    }
    
    public function testFalseCondition()
    {
        $c = new BooleanCondition(false);
        $this->assertFalse($c->isTrueIn($this->context));
    }
    
    public function testNegatedBoolean()
    {
        $c = new BooleanCondition(false);
        $c->negate();
        $this->assertTrue($c->isTrueIn($this->context));
    }
}
