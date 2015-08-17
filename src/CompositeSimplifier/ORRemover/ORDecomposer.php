<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\LmCondition\CompositeSimplifier\ORRemover;

use lukaszmakuch\LmCondition\Condition;
use lukaszmakuch\LmCondition\ConditionComposite;

/**
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ORDecomposer
{
    public function decompose(Condition $c)
    {
        if ($c instanceof ConditionComposite) {
            return $this->decomposeComposite($c);
        }
        
        return [[$c]];
    }
    
    protected function decomposeComposite(ConditionComposite $c)
    {
        return array_merge(
            $this->decomposeANDConditionsOf($c),
            $this->decomposeORConditionsOf($c)
        );
    }
    
    protected function decomposeANDConditionsOf(ConditionComposite $c)
    {
        $ANDConditions = $c->getANDConditions();
        if (empty($ANDConditions)) {
            return [];
        }
        
        $decomposedANDConditions = array_map(function (Condition $c) {
            return $this->decompose($c);
        }, $ANDConditions);
        
        return array_reduce($decomposedANDConditions, function ($result, $ANDConditions) {
            return $this->mergeConditionsIntoChains($ANDConditions, $result);
        }, [[]]);
    }
    
    protected function mergeConditionsIntoChains(array $conditions, array $chains)
    {
        $newChains = [];
        foreach ($conditions as $condition) {
            foreach ($chains as $currentChain) {
                $newChain = array_merge($currentChain, $condition);
                $newChains[] = $newChain;
            }
        }
        
        return $newChains;
    }
    
    protected function decomposeORConditionsOf(ConditionComposite $c)
    {
        return array_reduce(
            $c->getORConditions(),
            function (array $decomposed, Condition $c) {
                return array_merge($decomposed, $this->decompose($c));
            },
            []
        );
    }
}
