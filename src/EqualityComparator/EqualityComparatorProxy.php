<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\LmCondition\EqualityComparator;

use lukaszmakuch\ClassBasedRegistry\ClassBasedRegistry;
use lukaszmakuch\ClassBasedRegistry\Exception\ValueNotFound;
use lukaszmakuch\LmCondition\Condition;
use lukaszmakuch\LmCondition\EqualityComparator\Exception\IncomparableConditions;

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
    
    /**
     * Provides dependencie.
     * 
     * @param ClassBasedRegistry $objectToClassesRegistry used to hold actual
     * comparators together with classes associated to them
     */
    public function __construct(ClassBasedRegistry $objectToClassesRegistry)
    {
        $this->objectToClassesRegistry = $objectToClassesRegistry;
    }
    
    public function equal(Condition $c1, Condition $c2)
    {
        return $this->findComparatorFor($c1, $c2)->equal($c1, $c2);
    }
    
    /**
     * Associates a comparator with condition classes.
     * 
     * Condition classes order doesn't matter.
     * 
     * @param EqualityComparator $actualComparator
     * @param String $conditionClass1 condition class
     * @param String $conditionClass2 another condition class
     * 
     * @return EqualityComparator
     * @throws IncomparableConditions if it's not possible to find proper
     * comparator
     */
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
     * @throws IncomparableConditions
     * @return EqualityComparator
     */
    protected function findComparatorFor(Condition $c1, Condition $c2)
    {
        try {
            return $this->objectToClassesRegistry->fetchValueByObjects([$c1, $c2]);
        } catch (ValueNotFound $e) {
            throw new IncomparableConditions("no suitable comparator found");
        }
    }
}
