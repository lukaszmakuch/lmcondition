<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\LmCondition\EqualityComparator;

use \lukaszmakuch\LmCondition\Condition;

/**
 * Hides many actual comparators behind the common interface.
 * 
 * Allows to register a proper comparator for a pair of condition classes.
 * Inheritance is taken into account, so registering a comparator for the base
 * Condition interface will affect in using it to compare all derivatives
 * of this interface.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class EqualityComparatorProxy implements EqualityComparator
{
    
    public function equal(Condition $c1, Condition $c2)
    {
        throw new \InvalidArgumentException();
    }
}
