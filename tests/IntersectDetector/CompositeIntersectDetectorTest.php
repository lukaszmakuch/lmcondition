<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\LmCondition\IntersectDetector;

use lukaszmakuch\LmCondition\CompositeSimplifier\ORRemover\Composer;
use lukaszmakuch\LmCondition\CompositeSimplifier\ORRemover\Decomposer;
use lukaszmakuch\LmCondition\CompositeSimplifier\ORRemover\ORRemover;
use lukaszmakuch\LmCondition\ConditionComposite;
use lukaszmakuch\LmCondition\tests\IntersectDetector\ValueGreaterThanIntersectDetector;
use lukaszmakuch\LmCondition\tests\ValueGreaterThan;
use PHPUnit_Framework_TestCase;

class CompositeIntersectDetectorTest extends PHPUnit_Framework_TestCase
{
    protected $detector;
    
    protected function setUp()
    {
        $this->detector = new CompositeIntersectDetector(
            new ValueGreaterThanIntersectDetector(),
            new ORRemover(new Decomposer(), new Composer())
        );
    }
    
    public function testCompositesWithIntersection()
    {
        // (x > 999) or ((x > 7) and (x <= 42))
        $comp1 = new ConditionComposite();
        // (x > 999)
        $comp1_1 = new ValueGreaterThan(999);
        // ()
        $comp1_2 = new ConditionComposite();
        $comp1->addOR($comp1_1)->addOR($comp1_2);
        // (x > 7)
        $comp1_2_1 = new ValueGreaterThan(7);
        // (x <= 42)
        $comp1_2_2 = new ValueGreaterThan(42);
        $comp1_2_2->negate();
        $comp1_2->addAND($comp1_2_1)->addAND($comp1_2_2);
        
        // (x > 100) and (x <= 1000)
        $comp2 = new ConditionComposite();
        // (x > 100)
        $comp2_1 = new ValueGreaterThan(100);
        // (x <= 1000)
        $comp2_2 = new ValueGreaterThan(1000);
        $comp2_2->negate();
        $comp2->addAND($comp2_1)->addAND($comp2_2);
        
        $this->assertTrue($this->detector->intersectExists($comp1, $comp2));
    }
    
    public function testCompositesWithoutIntersection()
    {
        // (x > 999) or ((x > 7) and (x <= 42))
        $comp1 = new ConditionComposite();
        // (x > 999)
        $comp1_1 = new ValueGreaterThan(999);
        // ()
        $comp1_2 = new ConditionComposite();
        $comp1->addOR($comp1_1)->addOR($comp1_2);
        // (x > 7)
        $comp1_2_1 = new ValueGreaterThan(7);
        // (x <= 42)
        $comp1_2_2 = new ValueGreaterThan(42);
        $comp1_2_2->negate();
        $comp1_2->addAND($comp1_2_1)->addAND($comp1_2_2);
        
        // (x > 100) and (x <= 999)
        $comp2 = new ConditionComposite();
        // (x > 100)
        $comp2_1 = new ValueGreaterThan(100);
        // (x <= 999)
        $comp2_2 = new ValueGreaterThan(999);
        $comp2_2->negate();
        $comp2->addAND($comp2_1)->addAND($comp2_2);
        
        $this->assertFalse($this->detector->intersectExists($comp1, $comp2));
    }
}