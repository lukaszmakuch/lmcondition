<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\LmCondition\examples\IntersectDetector;

use lukaszmakuch\LmCondition\Condition;
use lukaszmakuch\LmCondition\IntersectDetector\IntersectDetector;

class AlwaysFoundIntersectDetector implements IntersectDetector
{
    public function intersectExists(Condition $c1, Condition $c2)
    {
        return true;
    }
}