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

class CompositeIntersectDetector implements IntersectDetector
{
    protected $leafIntersectDetector;
    protected $ORRemover;
    
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
