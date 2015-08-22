<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\LmCondition\tests\IntersectDetector;

use lukaszmakuch\LmCondition\tests\ValueGreaterThan;
use PHPUnit_Framework_TestCase;

class ValueGreaterThanIntersectDetectorTest extends PHPUnit_Framework_TestCase
{
    protected $detector;
    
    protected function setUp()
    {
        $this->detector = new ValueGreaterThanIntersectDetector();
    }
    
    public function testComparingEqualConditions()
    {
        $this->assertTrue($this->detector->intersectExists(
            new ValueGreaterThan(5), 
            new ValueGreaterThan(10)
        ));
    }
    
    public function testComparingDifferentConditions()
    {
        $c1 = new ValueGreaterThan(100);
        $c2 = new ValueGreaterThan(10);
        $c2->negate();
        
        $this->assertFalse($this->detector->intersectExists(
            $c1,
            $c2
        ));
    }
    
    public function testNegationSupport1()
    {
        $c1 = new ValueGreaterThan(5);
        $c2 = new ValueGreaterThan(10);
        $c2->negate();
        
        $this->assertTrue($this->detector->intersectExists(
            $c1,
            $c2
        ));
        
        $c1 = new ValueGreaterThan(10);
        $c1->negate();
        $c2 = new ValueGreaterThan(5);
        
        $this->assertTrue($this->detector->intersectExists(
            $c1,
            $c2
        ));
    }
}
