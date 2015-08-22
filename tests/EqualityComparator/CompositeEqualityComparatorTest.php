<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\LmCondition\EqualityComparator;

use lukaszmakuch\LmCondition\CompositeSimplifier\ORRemover\Composer;
use lukaszmakuch\LmCondition\CompositeSimplifier\ORRemover\Decomposer;
use lukaszmakuch\LmCondition\CompositeSimplifier\ORRemover\ORRemover;
use lukaszmakuch\LmCondition\ConditionComposite;
use lukaszmakuch\LmCondition\tests\EqualityComparator\ValueGreaterThanEqCmp;
use lukaszmakuch\LmCondition\tests\ValueGreaterThan;
use PHPUnit_Framework_TestCase;

class CompositeEqualityComparatorTest extends PHPUnit_Framework_TestCase
{
    protected $cmp;
    protected $leafEqualityCmp;
    protected $ORRemover;
    
    protected function setUp()
    {
        $ORRemoverDecomposer = new Decomposer();
        $ORRemoverComposer = new Composer();
        $this->ORRemover = new ORRemover($ORRemoverDecomposer, $ORRemoverComposer);
        $this->leafEqualityCmp = new ValueGreaterThanEqCmp();
        $this->cmp = new CompositeEqualityComparator($this->leafEqualityCmp, $this->ORRemover);
    }
    
    public function testComparingEqualComposites()
    {
        // (x > 7) and (!(x > 42) or (x > 99))
        $comp1 = new ConditionComposite();
        // (x > 7)
        $comp1_1 = new ValueGreaterThan(7);
        // ()
        $comp1_2 = new ConditionComposite();
        // !(x > 42)
        $comp1_2_1 = new ValueGreaterThan(42);
        $comp1_2_1->negate();
        // (x > 99)
        $comp1_2_2 = new ValueGreaterThan(99);
        //(!(x > 42) or (x > 99))
        $comp1_2->addOR($comp1_2_1)->addOR($comp1_2_2);
        //// (x > 7) and (!(x > 42) or (x > 99))
        $comp1->addAND($comp1_1)->addAND($comp1_2);
        
        
        // (!(x > 42) and (x > 7)) or ((x > 7) and (x > 99))
        $comp2 = new ConditionComposite();
        // ()
        $comp2_1 = new ConditionComposite();
        // ()
        $comp2_2 = new ConditionComposite();
        $comp2->addOR($comp2_1)->addOR($comp2_2);
        // !(x > 42)
        $comp2_1_1 = new ValueGreaterThan(42);
        $comp2_1_1->negate();
        // (x > 7)
        $comp2_1_2 = new ValueGreaterThan(7);
        // !(x > 42) and (x > 7))
        $comp2_1->addAND($comp2_1_1)->addAND($comp2_1_2);
        // (x > 7)
        $comp2_2_1 =  new ValueGreaterThan(7);
        // (x > 99)
        $comp2_2_2 =  new ValueGreaterThan(99);
        // ((x > 7) and (x > 99))
        $comp2_2->addAND($comp2_2_1)->addAND($comp2_2_2);
        
        $this->assertTrue($this->cmp->equal($comp1, $comp2));
    }
    
    public function testComparingDifferentComposites()
    {
        // (x > 7) and (!(x > 42) or (x > 99))
        $comp1 = new ConditionComposite();
        // (x > 7)
        $comp1_1 = new ValueGreaterThan(7);
        // ()
        $comp1_2 = new ConditionComposite();
        // !(x > 42)
        $comp1_2_1 = new ValueGreaterThan(42);
        $comp1_2_1->negate();
        // (x > 99)
        $comp1_2_2 = new ValueGreaterThan(99);
        //(!(x > 42) or (x > 99))
        $comp1_2->addOR($comp1_2_1)->addOR($comp1_2_2);
        //// (x > 7) and (!(x > 42) or (x > 99))
        $comp1->addAND($comp1_1)->addAND($comp1_2);
        
        
        // (!(x > 42) and (x > 7)) or ((x > 7) and !(x > 99))
        $comp2 = new ConditionComposite();
        // ()
        $comp2_1 = new ConditionComposite();
        // ()
        $comp2_2 = new ConditionComposite();
        $comp2->addOR($comp2_1)->addOR($comp2_2);
        // !(x > 42)
        $comp2_1_1 = new ValueGreaterThan(42);
        $comp2_1_1->negate();
        // (x > 7)
        $comp2_1_2 = new ValueGreaterThan(7);
        // !(x > 42) and (x > 7))
        $comp2_1->addAND($comp2_1_1)->addAND($comp2_1_2);
        // (x > 7)
        $comp2_2_1 =  new ValueGreaterThan(7);
        // (x > 99)
        $comp2_2_2 =  new ValueGreaterThan(99);
        $comp2_2_2->negate();
        // ((x > 7) and (x > 99))
        $comp2_2->addAND($comp2_2_1)->addAND($comp2_2_2);
        
        $this->assertFalse($this->cmp->equal($comp1, $comp2));
    }
}