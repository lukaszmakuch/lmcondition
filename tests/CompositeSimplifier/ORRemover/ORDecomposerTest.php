<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\LmCondition\CompositeSimplifier\ORRemover;

use lukaszmakuch\LmCondition\ConditionComposite;
use lukaszmakuch\LmCondition\tests\BooleanCondition;
use lukaszmakuch\LmCondition\tests\FakeCondition;
use PHPUnit_Framework_TestCase;

/**
 * Description of ORDecomposerTest
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ORDecomposerTest extends PHPUnit_Framework_TestCase
{
    protected $decomposer;
    
    protected function setUp()
    {
        $this->decomposer = new ORDecomposer();
    }
    
    /**
     * (A AND B)
     * 
     * should be decomposed to:
     * 
     * [
     *     [A, B],
     * ]
     */
    public function testDecomposingSimplestAND()
    {
        $A = new FakeCondition("A");
        $B = new FakeCondition("B");
        
        //(A AND B)
        $composite = new ConditionComposite();
        $composite->addAND($A)->addAND($B);
        
        $expected = [
            [$A, $B],
        ];
        $decompositionResult = $this->decomposer->decomposeOR($composite);
        
        $this->assertTrue($this->decomposedStructuresAreIdentical($expected, $decompositionResult));
        
    }
    
    /**
     * (A AND B AND (C AND D))
     * 
     * should be decomposed to:
     * 
     * [
     *     [A, B, C, D],
     * ]
     */
    public function testDecomposingComplexAND()
    {
        $A = new FakeCondition("A");
        $B = new FakeCondition("B");
        $C = new FakeCondition("C");
        $D = new FakeCondition("D");
        
        //(A AND B)
        $comp1 = new ConditionComposite();
        $comp1->addAND($A)->addAND($B);
        //(C AND D)
        $comp2 = new ConditionComposite();
        $comp2->addAND($C)->addAND($D);
        //(A AND B AND (C AND D))
        $composite = new ConditionComposite();
        $composite->addAND($comp1)->addAND($comp2);
        
        
        $expected = [
            [$A, $B, $C, $D],
        ];
        $decompositionResult = $this->decomposer->decomposeOR($composite);
        
        $this->assertTrue($this->decomposedStructuresAreIdentical(
            $expected,
            $decompositionResult
        ));
        
    }
    
    /**
     * (A OR B)
     * 
     * should be decomposed to:
     * 
     * [
     *     [A],
     *     [B],
     * ]
     */
    public function testDecomposingSimplestOR()
    {
        $A = new FakeCondition("A");
        $B = new FakeCondition("B");
        
        //(A OR B)
        $composite = new ConditionComposite();
        $composite->addOR($A)->addOR($B);
        
        $expected = [
            [$A],
            [$B],
        ];
        $decompositionResult = $this->decomposer->decomposeOR($composite);
        
        $this->assertTrue($this->decomposedStructuresAreIdentical(
            $expected, 
            $decompositionResult
        ));
        
    }
    
    
    /**
     * (A AND (B OR C))
     * 
     * should be decomposed to:
     * 
     * [
     *     [A, B],
     *     [A, C]
     * ]
     * 
     */
    public function testANDWithOR()
    {
        //leaves
        $A = new FakeCondition("A");
        $B = new FakeCondition("B");
        $C = new FakeCondition("C");
        
        //(B OR C)
        $comp1 = new ConditionComposite();
        $comp1->addOR($B)->addOR($C);
        //(A AND (B OR C))
        $comp2 = new ConditionComposite();
        $comp2->addAND($A);
        $comp2->addAND($comp1);
        
        $expected = [
            [$A, $B],
            [$A, $C],
        ];
        $decompositionResult = $this->decomposer->decomposeOR($comp2);
        
        $this->assertTrue($this->decomposedStructuresAreIdentical(
            $expected,
            $decompositionResult
        ));
    }
    
    protected function decomposedStructuresAreIdentical(array $arr1, array $arr2)
    {
        return $this->arraysAreIdentical($arr1, $arr2, function ($elem1, $elem2) {
            return $this->arrayHoldIdenticalSetOfElements($elem1, $elem2);
        });
    }
    
    protected function arrayHoldIdenticalSetOfElements(array $arr1, array $arr2)
    {
        return $this->arraysAreIdentical($arr1, $arr2, function ($elem1, $elem2) {
            return ($elem1 === $elem2);
        });
    }
    
    /**
     * Checks whether two arrays are of the same length and hold identical elements.
     * 
     * @param array $arr1
     * @param array $arr2
     * @param callable $comparisonFunction must return true if two elements are identical
     * 
     * @return boolean  true if arrays are indetical
     */
    protected function arraysAreIdentical(array $arr1, array $arr2, $comparisonFunction)
    {
        if (count($arr1) !== count($arr2)) {
            return false;
        }
        
        return empty(array_udiff($arr1, $arr2, function ($elem1, $elem2) use ($comparisonFunction) {
            return ($comparisonFunction($elem1, $elem2)) ? 0 : -1;
        }));
    }
}