<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\LmCondition\tests;

class ValueGreaterThanTest extends \PHPUnit_Framework_TestCase
{
    public function testTrueCondition()
    {
        $c = new ValueGreaterThan(42);
        $this->assertTrue($c->isTrueIn(new KeyValueContext(['value' => 43])));
    }
    
    public function testFalseCondition()
    {
        $c = new ValueGreaterThan(42);
        $this->assertFalse($c->isTrueIn(new KeyValueContext(['value' => 42])));
    }
}
