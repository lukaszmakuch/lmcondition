<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\LmCondition\tests\EqualityComparator;

use lukaszmakuch\LmCondition\tests\ValueGreaterThan;
use PHPUnit_Framework_TestCase;

class ValueGreaterThanEqCmpTest extends PHPUnit_Framework_TestCase
{
    protected $cmp;
    
    protected function setUp()
    {
        $this->cmp = new ValueGreaterThanEqCmp();
    }
    
    public function testComparingEqualConditions()
    {
        $this->assertTrue($this->cmp->equal(
            new ValueGreaterThan(42), 
            new ValueGreaterThan(42)
        ));
    }
}
