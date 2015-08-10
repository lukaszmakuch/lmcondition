<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\LmCondition\tests\EqualityComparator;

use lukaszmakuch\LmCondition\Condition;
use lukaszmakuch\LmCondition\EqualityComparator\EqualityComparator;
use lukaszmakuch\LmCondition\tests\ValueGreaterThan;

/**
 * Compares two {@see ValueGreaterThan} conditions.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ValueGreaterThanEqCmp implements EqualityComparator
{
    public function equal(Condition $c1, Condition $c2)
    {
        return $this->equalImpl($c1, $c2);
    }
    
    protected function equalImpl(ValueGreaterThan $c1, ValueGreaterThan $c2)
    {
        return (float)$c1->getMustBeGreaterThanValue() === (float)$c2->getMustBeGreaterThanValue();
    }
}
