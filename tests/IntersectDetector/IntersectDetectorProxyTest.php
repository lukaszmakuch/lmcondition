<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\LmCondition\tests\IntersectDetector;

use lukaszmakuch\ClassBasedRegistry\ClassBasedRegistry;
use lukaszmakuch\LmCondition\IntersectDetector\IntersectDetectorProxy;
use lukaszmakuch\LmCondition\tests\BooleanCondition;
use lukaszmakuch\LmCondition\tests\ValueGreaterThan;
use PHPUnit_Framework_TestCase;

class IntersectDetectorProxyTest extends PHPUnit_Framework_TestCase
{
    protected $registry;
    protected $proxy;
    protected $actualDetector;
    protected $c1;
    protected $c2;
    
    protected function setUp()
    {
        $this->registry = $this->getMock(ClassBasedRegistry::class);
        $this->proxy = new IntersectDetectorProxy($this->registry);
        $this->actualDetector = new ValueGreaterThanIntersectDetector();
        $this->c1 = new BooleanCondition(true);
        $this->c2 = new ValueGreaterThan(false);
    }

    public function testRegisteringDetector()
    {
        $this->registry->expects($this->atLeastOnce())
            ->method('associateValueWithClasses')
            ->with(
                $this->actualDetector,
                [BooleanCondition::class, ValueGreaterThan::class]
            );
        
        $this->proxy->register(
            $this->actualDetector,
            BooleanCondition::class,
            ValueGreaterThan::class
        );
    }
}
