<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\LmCondition\tests\IntersectDetector;

use lukaszmakuch\ClassBasedRegistry\ClassBasedRegistry;
use lukaszmakuch\LmCondition\IntersectDetector\IntersectDetector;
use lukaszmakuch\LmCondition\IntersectDetector\IntersectDetectorProxy;
use lukaszmakuch\LmCondition\tests\BooleanCondition;
use lukaszmakuch\LmCondition\tests\ValueGreaterThan;
use PHPUnit_Framework_TestCase;

class IntersectDetectorProxyTest extends PHPUnit_Framework_TestCase
{
    protected $registry;
    protected $proxy;
    
    protected function setUp()
    {
        $this->registry = $this->getMock(ClassBasedRegistry::class);
        $this->proxy = new IntersectDetectorProxy($this->registry);
    }

    public function testRegisteringDetector()
    {
        $actualDetector = new ValueGreaterThanIntersectDetector();
        
        $this->registry->expects($this->atLeastOnce())
            ->method('associateValueWithClasses')
            ->with(
                $actualDetector,
                [BooleanCondition::class, ValueGreaterThan::class]
            );
        
        $this->proxy->register(
            $actualDetector,
            BooleanCondition::class,
            ValueGreaterThan::class
        );
    }
    
    public function testFetching()
    {
        $c1 = new BooleanCondition(true);
        $c2 = new ValueGreaterThan(false);
        
        $detector = $this->getMock(IntersectDetector::class);
        $detector->expects($this->atLeastOnce())
            ->method("intersectExists")
            ->willReturn(true);
        
        $this->registry->expects($this->atLeastOnce())
            ->method("fetchValueByObjects")
            ->will($this->returnValueMap([
                [[$c1, $c2], $detector]
            ]));
        
        $this->assertTrue($this->proxy->intersectExists($c1, $c2));
    }
    
    public function testSettingDefaultDetector()
    {
        $c1 = new BooleanCondition(true);
        $c2 = new ValueGreaterThan(false);
        
        $defaultDetector = $this->getMock(IntersectDetector::class);
        $defaultDetector->expects($this->atLeastOnce())
            ->method("intersectExists")
            ->with($c1, $c2)
            ->willReturn(true);
        
        $this->proxy->setDefault($defaultDetector);
        
        $this->registry->expects($this->atLeastOnce())
            ->method("fetchValueByObjects")
            ->will($this->throwException(new \InvalidArgumentException()));
        
        $this->assertTrue($this->proxy->intersectExists($c1, $c2));
    }
}
