<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\LmCondition\examples\IntersectDetector;

use lukaszmakuch\LmCondition\Condition;
use lukaszmakuch\LmCondition\examples\Condition\TitleOfMoreCharsThan;
use lukaszmakuch\LmCondition\IntersectDetector\IntersectDetector;
use InvalidArgumentException;

class TitleOfMoreCharsThanIntersectDetector implements IntersectDetector
{
    public function intersectExists(Condition $c1, Condition $c2)
    {
        if (
            !($c1 instanceof TitleOfMoreCharsThan) 
            || !($c2 instanceof TitleOfMoreCharsThan)
        ) {
            throw new InvalidArgumentException("unsupported conditions");
        }
        
        if (!$c1->isNegated() && $c2->isNegated()) {
            return ($c2->getThreshold() > $c1->getThreshold());
        } elseif ($c1->isNegated() && !$c2->isNegated()) {
            return ($c1->getThreshold() > $c2->getThreshold());
        }
        
        return true;
    }
}
