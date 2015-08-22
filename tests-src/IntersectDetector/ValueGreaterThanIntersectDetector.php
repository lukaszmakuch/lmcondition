<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\LmCondition\tests\IntersectDetector;

use lukaszmakuch\LmCondition\Condition as Condition;
use lukaszmakuch\LmCondition\IntersectDetector\IntersectDetector;
use lukaszmakuch\LmCondition\tests\ValueGreaterThan;

class ValueGreaterThanIntersectDetector implements IntersectDetector
{
    public function intersectExists(Condition $c1, Condition $c2)
    {
        return $this->intersectExistsImpl($c1, $c2);
    }
    
    protected function intersectExistsImpl(ValueGreaterThan $c1, ValueGreaterThan $c2)
    {
        if (!$c1->isNegated() && $c2->isNegated()) {
            return ($c2->getMustBeGreaterThanValue() > $c1->getMustBeGreaterThanValue());
        } elseif ($c1->isNegated() && !$c2->isNegated()) {
            return ($c1->getMustBeGreaterThanValue() > $c2->getMustBeGreaterThanValue());
        }
        
        return true;
    }
}
