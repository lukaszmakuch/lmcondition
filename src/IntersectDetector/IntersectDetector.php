<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\LmCondition\IntersectDetector;

use lukaszmakuch\LmCondition\Condition;
use lukaszmakuch\LmCondition\IntersectDetector\Exception\ImpossibleToLookForIntersection;

/**
 * Tells whether any intersection occurs between two conditions.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface IntersectDetector
{
    /**
     * Looks for intersection between conditions.
     * 
     * Does not take into account any specified context data,
     * just given conditions. 
     * For example:
     * condition "greater than 5" and "greater than 6" will always 
     * have intersection.
     * Order of arguments doesn't matter.
     * 
     * @param Condition $c1
     * @param Condition $c2
     * 
     * @throws ImpossibleToLookForIntersection
     * @return bool true if intersection occurs, false otherwise
     */
    public function intersectExists(Condition $c1, Condition $c2);
}
