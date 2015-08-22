<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\LmCondition\IntersectDetector;

use lukaszmakuch\LmCondition\CompositeSimplifier\ORRemover\ORRemover;
use lukaszmakuch\LmCondition\Condition;
use lukaszmakuch\LmCondition\ConditionComposite;

/**
 * Looks for intersection between two condition composites of any complexity.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class CompositeIntersectDetector implements IntersectDetector
{
    protected $leafIntersectDetector;
    protected $ORRemover;
    
    /**
     * Provides dependencies.
     * 
     * @param IntersectDetector $leafIntersectDetector actually compares 
     * condition leaves
     * @param ORRemover $ORRemover simplifies detection process
     */
    public function __construct(
        IntersectDetector $leafIntersectDetector,
        ORRemover $ORRemover
    ) {
        $this->leafIntersectDetector = $leafIntersectDetector;
        $this->ORRemover = $ORRemover;
    }
    
    public function intersectExists(Condition $c1, Condition $c2)
    {
        return $this->intersectionExistsBetweenAtLeastOnePairOfANDChains(
            $this->ORRemover->leaveOnlyOneLevelAND($c1)->getORConditions(),
            $this->ORRemover->leaveOnlyOneLevelAND($c2)->getORConditions()
        );
    }
    
    /**
     * Checks whether intersection exists between at least one pair of 
     * composites from the first and the second set.
     * 
     * @param ConditionComposite[] $setOfANDComposites1 array of composites
     * that hold only AND conditions that are not composites.
     * @param ConditionComposite[] $setOfANDComposites2 array of composites
     * that hold only AND conditions that are not composites.
     * 
     * @throws \InvalidArgumentException if it's not possible to look for
     * intersection between given conditions
     * @return boolean true if intersection exists
     */
    protected function intersectionExistsBetweenAtLeastOnePairOfANDChains(
        array $setOfANDComposites1,
        array $setOfANDComposites2
    ) {
        foreach ($setOfANDComposites1 as $ANDComposite1) {
            foreach ($setOfANDComposites2 as $ANDComposite2) {
                if ($this->intersectionExistsBetween($ANDComposite1, $ANDComposite2)) {
                    return true;
                }
            }
        }
        
        return false;
    }
    
    /**
     * Checks whether intersection exsits among all AND conditions within
     * these composites.
     * 
     * @param ConditionComposite $ANDComposite1
     * @param ConditionComposite $ANDComposite2
     * 
     * @throws \InvalidArgumentException if it's not possible to look for
     * intersection between given conditions
     * @return boolean if intersection exist among all AND conditions
     * withing these composites
     */
    protected function intersectionExistsBetween(
        ConditionComposite $ANDComposite1,
        ConditionComposite $ANDComposite2
    ) {
        $setOfANDConditions1 = $ANDComposite1->getANDConditions();
        $setOfANDConditions2 = $ANDComposite2->getANDConditions();
        foreach ($setOfANDConditions1 as $ANDCondition1) {
            foreach ($setOfANDConditions2 as $ANDCondition2) {
                if (false === $this->leafIntersectDetector->intersectExists(
                    $ANDCondition1, 
                    $ANDCondition2
                )) {
                    return false;
                }
            }
        } 
        
        return true;
    }
}
