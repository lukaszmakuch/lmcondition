<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

use lukaszmakuch\LmCondition\Condition;
use lukaszmakuch\LmCondition\ConditionComposite;
use lukaszmakuch\LmCondition\Exception\ImpossibleToCheckCondition;
use lukaszmakuch\LmCondition\tests\BooleanCondition;
use lukaszmakuch\LmCondition\tests\DummyContext;

class ConditionCompositeTest extends PHPUnit_Framework_TestCase
{
    protected $composite;
    protected $context;
    
    protected function setUp()
    {
        $this->composite = new ConditionComposite();
        $this->context = new DummyContext();
    }
    
    /**
     * @expectedException lukaszmakuch\LmCondition\Exception\ImpossibleToCheckCondition
     */
    public function testExceptionIfUsingEmpty()
    {
        $this->composite->isTrueIn($this->context);
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
    
    public function testPassingORContext()
    {
        $c = $this->getBooleanConditionThatMustBeCheckedInTheContext(true);
        $this->composite->addOR($c);
        $this->composite->isTrueIn($this->context);
    }
    
    
    public function testSimpleOR()
    {
        $c1 = new BooleanCondition(false);
        $c2 = new BooleanCondition(true);
        
        $this->composite->addOR($c1)->addOR($c2);
        
        $this->assertTrue($this->composite->isTrueIn($this->context));
    }
    
    public function testNeverMetOR()
    {
        $c1 = new BooleanCondition(false);
        $c2 = new BooleanCondition(false);
        
        $this->composite->addOR($c1)->addOR($c2);
        
        $this->assertFalse($this->composite->isTrueIn($this->context));
    }
    
    public function testBothOR_AND_AND()
    {
       $c1 = new BooleanCondition(false);
       $c2 = new BooleanCondition(true);
        
       $this->composite->addOR($c1)->addOR($c2);
       $this->assertTrue($this->composite->isTrueIn($this->context));
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
        
        $stub->expects($this->atLeastOnce())
                ->method("isTrueIn")
                ->with($this->identicalTo($this->context));
        
        return $stub;
    }
}