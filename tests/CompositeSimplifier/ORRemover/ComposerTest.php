<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\LmCondition\CompositeSimplifier\ORRemover;

use InvalidArgumentException;
use lukaszmakuch\ArrayUtils\ArrayComparator;
use lukaszmakuch\LmCondition\ConditionComposite;
use lukaszmakuch\LmCondition\tests\FakeCondition;
use PHPUnit_Framework_TestCase;

class ComposerTest extends PHPUnit_Framework_TestCase
{
    protected $composer;
    
    protected function setUp()
    {
        $this->composer = new Composer();
    }
    
    /**
     * [
     *     [A, B],
     *     [C, D]
     * ]
     * 
     * should be composed to:
     * 
     * ((A AND B) OR (C AND D))
     */
    public function testComposing()
    {
        $A = new FakeCondition("A");
        $B = new FakeCondition("B");
        $C = new FakeCondition("C");
        $D = new FakeCondition("D");
        
        $decomposedStructure = [
            [$A, $B],
            [$C, $D],
        ];
        
        $composedCondition = $this->composer->compose($decomposedStructure);
        
        $this->assertTrue($this->compositeExactlyMatchesStructure(
            $composedCondition,
            $decomposedStructure
        ));
    }
    
    protected function compositeExactlyMatchesStructure(
        ConditionComposite $c,
        array $desiredDecomposedStructure
    ) {
        return ArrayComparator::arraysHoldEqualElements(
            $this->decompose($c), 
            $desiredDecomposedStructure, 
            function(array $row1, array $row2) {
                return ArrayComparator::arraysHoldEqualElements($row1, $row2);
            }
        );
    }
    
    protected function decompose(ConditionComposite $c)
    {
       if (false === empty($c->getANDConditions())) {
           throw new InvalidArgumentException(
               "in this test there should be no AND conditions"
           );
       }
       
       $result = [];
       foreach ($c->getORConditions() as $c) {
           $result[] = $this->decomposeRow($c);
       }
       
       return $result;
    }
    
    protected function decomposeRow(ConditionComposite $c)
    {
       if (false === empty($c->getORConditions())) {
           throw new InvalidArgumentException(
               "in this test there should be no OR conditions"
           );
       }
       
       $result = [];
       foreach($c->getANDConditions() as $c) {
           if ($c instanceof ConditionComposite) {
               throw new InvalidArgumentException(
                   "in this test there should be no composite"
               );
           }
           
           $result[] = $c;
       }
       
       return $result;
    }
}
