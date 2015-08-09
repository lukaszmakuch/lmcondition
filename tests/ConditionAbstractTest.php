<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\LmCondition\tests;

use lukaszmakuch\LmCondition\Condition;
use lukaszmakuch\LmCondition\ConditionAbstract;
use PHPUnit_Framework_TestCase;

class ConditionAbstractTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Condition
     */
    protected $c;
    
    /**
     * @var Context
     */
    protected $context;
    
    protected function setUp()
    {
        $this->c = $this->getMockBuilder(ConditionAbstract::class)
            ->setMethods(['isTrueInImpl'])
            ->getMock();
        
        $this->context = new DummyContext();
    }
    
    public function testNotNegatedByDefault()
    {
        $this->assertFalse($this->c->isNegated());
    }
    
    public function testNegating()
    {
        $this->c->negate();
        $this->assertTrue($this->c->isNegated());
    }
    
    public function testNegatingTwoTimes()
    {
        $this->c->negate();
        $this->c->negate();
        $this->assertTrue($this->c->isNegated());
    }
    
    public function testRemovingNegation()
    {
        $this->c->negate();
        $this->c->removeNegation();
        $this->assertFalse($this->c->isNegated());
    }
    
    public function testIsTrueImplReturningFalse()
    {
        $this->setIsTrueInImplReturnedValueTo(false);
        $this->assertFalse($this->c->isTrueIn($this->context));
    }
    
    public function testIsTrueImplReturningTrue()
    {
        $this->setIsTrueInImplReturnedValueTo(true);
        $this->assertTrue($this->c->isTrueIn($this->context));
    }
    
    public function testTakingNegationIntoAccount()
    {
        $this->setIsTrueInImplReturnedValueTo(true);
        $this->c->negate();
        $this->assertFalse($this->c->isTrueIn($this->context));
    }
    
    protected function setIsTrueInImplReturnedValueTo($trueOrFalse)
    {
        $this->c->method('isTrueInImpl')
            ->will($this->returnValue($trueOrFalse));
    }
}
