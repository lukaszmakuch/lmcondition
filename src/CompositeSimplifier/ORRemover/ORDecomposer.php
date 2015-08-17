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
    public function decomposeOR(Condition $c)
    {
        if (!($c instanceof ConditionComposite)) {
            return [[$c]];
        }
        
        return $this->decomposeComposite($c);
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
            return $this->decomposeOR($c);
        }, $ANDConditions);
        
        $result = [[]];
        foreach ($decomposedANDConditions as $setOfANDConditions) {
            $result = $this->mergeAND($setOfANDConditions, $result);
        }
        
        return $result;
    }
    
    protected function mergeAND(array $ANDConditions, $currentChains)
    {
        $newChains = [];
        foreach ($ANDConditions as $ANDCondition) {
            foreach ($currentChains as $currentChain) {
                $newChain = array_merge($currentChain, $ANDCondition);
                $newChains[] = $newChain;
            }
        }
        
        return $newChains;
    }
    
    protected function decomposeORConditionsOf(ConditionComposite $c)
    {
        $decomposedORConditions = [];
        $ORConditions = $c->getORConditions();
        foreach ($ORConditions as $ORCondition) {
            $decomposedORConditions = array_merge(
                $decomposedORConditions, 
                $this->decomposeOR($ORCondition)
            );
        }
        
        return $decomposedORConditions;
    }
}
