<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\LmCondition\EqualityComparator;

use InvalidArgumentException;
use lukaszmakuch\LmCondition\tests\BooleanCondition;
use lukaszmakuch\LmCondition\tests\ValueGreaterThan;
use PHPUnit_Framework_TestCase;

class BooleanConditionDerivative extends BooleanCondition {}

class EqualityComparatorProxyTest extends PHPUnit_Framework_TestCase
{
    protected $proxy;
    
    protected function setUp()
    {
        $this->proxy = new EqualityComparatorProxy();
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testThrowingExceptionIfNoComparatorRegistered()
    {
        $c1 = new BooleanCondition(true);
        $c2 = new BooleanCondition(true);
        $this->proxy->equal($c1, $c2);
    }
    
    public function testRegisteringAndUsingComparator()
    {
        $actualComparator = $this->getComparatorStub(true);
        $this->proxy->register(
            $actualComparator, 
            BooleanCondition::class, 
            BooleanCondition::class
        );
        
        $c1 = new BooleanCondition(true);
        $c2 = new BooleanCondition(true);
        
        $actualComparator->expects($this->atLeastOnce())
            ->method("equal")
            ->with($c1, $c2);
        $this->assertTrue($this->proxy->equal($c1, $c2));
    }
    
    public function testRegisteringForDifferentClasses()
    {
        $actualComparator = $this->getComparatorStub(true);
        $this->proxy->register(
            $actualComparator, 
            BooleanCondition::class, 
            ValueGreaterThan::class
        );
        
        $c1 = new BooleanCondition(true);
        $c2 = new ValueGreaterThan(123);
        
        $actualComparator->expects($this->atLeastOnce())
            ->method("equal")
            ->with($c1, $c2);
        
        $this->assertTrue($this->proxy->equal($c1, $c2));
    }
    
    public function testDerivatives()
    {
        $actualComparator = $this->getComparatorStub(true);
        $this->proxy->register(
            $actualComparator, 
            BooleanCondition::class, 
            BooleanConditionDerivative::class
        );
        
        $c1 = new BooleanCondition(true);
        $c2 = new BooleanConditionDerivative(true);
        
        $actualComparator->expects($this->atLeastOnce())
            ->method("equal")
            ->with($c1, $c2);
        
        $this->assertTrue($this->proxy->equal($c1, $c2));
    }
    
    public function testDifferentOrderOfGivenObjects()
    {
        $actualComparator = $this->getComparatorStub(true);
        $this->proxy->register(
            $actualComparator, 
            BooleanCondition::class, 
            BooleanConditionDerivative::class
        );
        
        $c1 = new BooleanConditionDerivative(true);
        $c2 = new BooleanCondition(true);
        
        $actualComparator->expects($this->atLeastOnce())
            ->method("equal")
            ->with($c1, $c2);
        
        $this->assertTrue($this->proxy->equal($c1, $c2));
    }
    
    protected function getComparatorStub($cmpResult)
    {
        $stub = $this->getMock(EqualityComparator::class);
        $stub->method("equal")->willReturn($cmpResult);
        return $stub;
    }
}