<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\LmCondition\EqualityComparator;

use InvalidArgumentException;
use lukaszmakuch\ClassBasedRegistry\ClassBasedRegistry;
use lukaszmakuch\LmCondition\Condition;

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
    protected $objectToClassesRegistry;
    
    public function __construct(ClassBasedRegistry $objectToClassesRegistry)
    {
        $this->objectToClassesRegistry = $objectToClassesRegistry;
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
        $this->objectToClassesRegistry->associateValueWithClasses(
            $actualComparator,
            [$conditionClass1, $conditionClass2]
        );
    }
    
    /**
     * @param Condition $c1
     * @param Condition $c2
     * 
     * @throws InvalidArgumentException
     * @return EqualityComparator
     */
    protected function findComparatorFor(Condition $c1, Condition $c2)
    {
        return $this->objectToClassesRegistry->fetchValueByObjects([$c1, $c2]);
    }
    
}
