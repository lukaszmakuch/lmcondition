<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\LmCondition\CompositeSimplifier\ORRemover;

use lukaszmakuch\ArrayUtils\ArrayComparator;
use lukaszmakuch\LmCondition\ConditionComposite;
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
        $this->decomposer = new Decomposer();
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
        $decompositionResult = $this->decomposer->decompose($composite);
        
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
        $decompositionResult = $this->decomposer->decompose($composite);
        
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
        $decompositionResult = $this->decomposer->decompose($composite);
        
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
        $decompositionResult = $this->decomposer->decompose($comp2);
        
        $this->assertTrue($this->decomposedStructuresAreIdentical(
            $expected,
            $decompositionResult
        ));
    }
    
    /**
     * (A AND (B OR (C AND D AND (E OR F))))
     * 
     * should be decomposed to:
     * 
     * [
     *     [A, B],
     *     [A, C, D, E]
     *     [A, C, D, F]
     * ]
     * 
     */
    public function testComplexStructureWithManyLevels()
    {
        //leaves
        $A = new FakeCondition("A");
        $B = new FakeCondition("B");
        $C = new FakeCondition("C");
        $D = new FakeCondition("D");
        $E = new FakeCondition("E");
        $F = new FakeCondition("F");
        
        //E OR F
        $comp1 = new ConditionComposite();
        $comp1->addOR($E)->addOR($F);
        //C AND D AND (E OR F)
        $comp2 = new ConditionComposite();
        $comp2->addAND($C)->addAND($D)->addAND($comp1);
        //B OR (C AND D AND (E OR F))
        $comp3 = new ConditionComposite();
        $comp3->addOR($B)->addOR($comp2);
        //A AND (B OR (C AND D AND (E OR F)))
        $composite = new ConditionComposite();
        $composite->addAND($A)->addAND($comp3);
        
        $expected = [
            [$A, $B],
            [$A, $C, $D, $E],
            [$A, $C, $D, $F],
        ];
        $decompositionResult = $this->decomposer->decompose($composite);
        
        $this->assertTrue($this->decomposedStructuresAreIdentical(
            $expected,
            $decompositionResult
        ));
    }
    
    protected function decomposedStructuresAreIdentical(array $arr1, array $arr2)
    {
        return ArrayComparator::arraysHoldEqualElements(
            $arr1,
            $arr2,
            function ($arr1Row, $arr2Row) {
                return ArrayComparator::arraysHoldEqualElements($arr1Row, $arr2Row);
            }
        );
    }
}