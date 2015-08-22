<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\LmCondition\EqualityComparator;

use lukaszmakuch\ArrayUtils\ArrayComparator;
use lukaszmakuch\LmCondition\CompositeSimplifier\ORRemover\ORRemover;
use lukaszmakuch\LmCondition\Condition;
use lukaszmakuch\LmCondition\ConditionComposite;

class CompositeEqualityComparator implements EqualityComparator
{
    protected $leafEqualityComparator;
    protected $ORRemoverr;
    
    public function __construct(
        EqualityComparator $leafEqualityComparator,
        ORRemover $ORRemover
    ) {
        $this->leafEqualityComparator = $leafEqualityComparator;
        $this->ORRemoverr = $ORRemover;
    }
    
    public function equal(Condition $c1, Condition $c2)
    {
        return ArrayComparator::arraysHoldEqualElements(
            $this->ORRemoverr->leaveOnlyOneLevelAND($c1)->getORConditions(),
            $this->ORRemoverr->leaveOnlyOneLevelAND($c2)->getORConditions(),
            function (ConditionComposite $comp1, ConditionComposite $comp2) {
                return $this->ANDCompositesAreEqual($comp1, $comp2);
            }
        );
    }
    
    protected function ANDCompositesAreEqual(
        ConditionComposite $comp1,
        ConditionComposite $comp2
    ) {
        return ArrayComparator::arraysHoldEqualElements(
            $comp1->getANDConditions(),
            $comp2->getANDConditions(),
            function (Condition $c1, Condition $c2) {
                return $this->leafEqualityComparator->equal($c1, $c2);
            }
        );
    }
}
