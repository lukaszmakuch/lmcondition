<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

use lukaszmakuch\LmCondition\ConditionComposite;
use lukaszmakuch\LmCondition\tests\BooleanCondition;

class ConditionCompositeTest extends \PHPUnit_Framework_TestCase
{
    protected $composite;
    
    protected function setUp()
    {
        $this->composite = new ConditionComposite();
    }
    
    public function testHoldingANDCondition()
    {
        $andCond1 = new BooleanCondition(true);
        $andCond2 = new BooleanCondition(false);
        $this->composite->addAND($andCond1);
        $this->composite->addAND($andCond2);
        
        $this->assertEquals(
            [$andCond1, $andCond2],
            $this->composite->getANDConditions()
        );
    }
    
    public function testHoldingORCondition()
    {
        $orCond1 = new BooleanCondition(true);
        $orCond2 = new BooleanCondition(false);
        $this->composite->addOR($orCond1);
        $this->composite->addOR($orCond2);
        
        $this->assertEquals(
            [$orCond1, $orCond2],
            $this->composite->getORConditions()
        );
    }
}