<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\LmCondition\EqualityComparator;

use lukaszmakuch\LmCondition\Condition;

/**
 * Allows to compare two condition for equality.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface EqualityComparator
{
    /**
     * Performs equality checking on two given conditions.
     * 
     * Elements order doesn't matter.
     * 
     * @param Condition $c1
     * @param Condition $c2
     * 
     * @throws \InvalidArgumentException if it's not possible to compare these conditions
     * @return bool true if these conditions are equal, false otherwise
     */
    public function equal(Condition $c1, Condition $c2);
}
