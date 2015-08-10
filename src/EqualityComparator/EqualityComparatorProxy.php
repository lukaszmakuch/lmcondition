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
    const REGISTERED_CMPS_OBJ = 0;
    const REGISTERED_CMPS_CLASS1 = 1;
    const REGISTERED_CMPS_CLASS2 = 2;
    
    protected $registeredComparators;
    
    public function __construct()
    {
        $this->registeredComparators = [];
    }
    
    public function equal(Condition $c1, Condition $c2)
    {
        return $this->findComparatorFor($c1, $c2)->equal($c1, $c2);
    }
    
    public function register(
        EqualityComparator $actualComparator,
        $conditionClass1,
        $conditionClass2
    ) {
        $this->registeredComparators[] = [
            self::REGISTERED_CMPS_OBJ => $actualComparator,
            self::REGISTERED_CMPS_CLASS1 => $conditionClass1,
            self::REGISTERED_CMPS_CLASS2 => $conditionClass2,
        ];
    }
    
    /**
     * @param Condition $c1
     * @param Condition $c2
     * 
     * @return EqualityComparator
     */
    protected function findComparatorFor(Condition $c1, Condition $c2)
    {
        foreach ($this->registeredComparators as $touple) {
            if ($this->conditionsMatchRegisteredCmpTuple($c1, $c2, $touple)) {
                return $touple[self::REGISTERED_CMPS_OBJ];
            }
        }
        throw new \InvalidArgumentException();
    }
    
    protected function conditionsMatchRegisteredCmpTuple(
        Condition $c1,
        Condition $c2,
        array $registeredCmpTuple
    ) {
        return (
            ($c1 instanceof $registeredCmpTuple[self::REGISTERED_CMPS_CLASS1])
            && ($c2 instanceof $registeredCmpTuple[self::REGISTERED_CMPS_CLASS2])    
        ) || (
            ($c2 instanceof $registeredCmpTuple[self::REGISTERED_CMPS_CLASS1])
            && ($c1 instanceof $registeredCmpTuple[self::REGISTERED_CMPS_CLASS2])    
        );
    }
}
