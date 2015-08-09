<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\LmCondition;

class ConditionAbstractTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Condition
     */
    protected $c;

    protected function setUp()
    {
        $this->c = $this->getMockBuilder(ConditionAbstract::class)
            ->setMethods(['isTrueInImpl'])
            ->getMock();
    }
    
    public function testNotNegatedByDefault()
    {
        $this->assertFalse($this->c->isNegated());
    }
}