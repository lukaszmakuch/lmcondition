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
}