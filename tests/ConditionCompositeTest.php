<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

use lukaszmakuch\LmCondition\ConditionComposite;
use lukaszmakuch\LmCondition\Condition;
use lukaszmakuch\LmCondition\tests\BooleanCondition;

class ConditionCompositeTest extends \PHPUnit_Framework_TestCase
{
    protected $composite;
    protected $context;
    
    protected function setUp()
    {
        $this->composite = new ConditionComposite();
        $this->context = new \lukaszmakuch\LmCondition\tests\DummyContext();
    }
    
    public function testHoldingANDCondition()
    {
        $andCond1 = new BooleanCondition(true);
        $andCond2 = new BooleanCondition(false);
        $this->composite->addAND($andCond1)->addAND($andCond2);
        
        $this->assertEquals(
            [$andCond1, $andCond2],
            $this->composite->getANDConditions()
        );
    }
    
    public function testHoldingORCondition()
    {
        $orCond1 = new BooleanCondition(true);
        $orCond2 = new BooleanCondition(false);
        $this->composite->addOR($orCond1)->addOR($orCond2);
        
        $this->assertEquals(
            [$orCond1, $orCond2],
            $this->composite->getORConditions()
        );
    }
    
    public function testPassingANDContext()
    {
        $c = $this->getBooleanConditionThatMustBeCheckedInTheContext(true);
        $this->composite->addAND($c);
        $this->composite->isTrueIn($this->context);
    }
    
    public function testSimpleAND()
    {
        $c1 = new BooleanCondition(true);
        $c2 = new BooleanCondition(true);
        
        $this->composite->addAND($c1)->addAND($c2);
        
        $this->assertTrue($this->composite->isTrueIn($this->context));
    }
    
    public function testNeverMetAND()
    {
        $c1 = new BooleanCondition(true);
        $c2 = new BooleanCondition(false);
        
        $this->composite->addAND($c1)->addAND($c2);
        
        $this->assertFalse($this->composite->isTrueIn($this->context));
    }
    
    /**
     * @param boolean $resultTrueOrFalse
     * @return Condition
     */
    protected function getBooleanConditionThatMustBeCheckedInTheContext($resultTrueOrFalse)
    {
        $stub = $this->getMockBuilder(BooleanCondition::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $stub->method("isTrueIn")
            ->willReturn($resultTrueOrFalse);
        
        $stub->expects($this->atleastOnce())
                ->method("isTrueIn")
                ->with($this->identicalTo($this->context));
        
        return $stub;
    }
}