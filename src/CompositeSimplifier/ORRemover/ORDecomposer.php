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
        return $this->decomposerORImpl($c);
    }
    
    protected function decomposerORImpl(Condition $c)
    {
        if (!($c instanceof ConditionComposite)) {
            return [[$c]];
        }
        
        return $this->processComposite($c);
    }
    
    protected function processComposite(ConditionComposite $c)
    {
        $decomposedANDConditions = array_map(function (Condition $c) {
            return $this->decomposeOR($c);
        }, $c->getANDConditions());
        
        $result = [[]];
        foreach ($decomposedANDConditions as $setOfANDConditions) {
            $result = $this->mergeAND($setOfANDConditions, $result);
        }
        
        $decomposedORConditions = [];
        $ORConditions = $c->getORConditions();
        foreach ($ORConditions as $ORCondition) {
            $decomposedORConditions = array_merge(
                $decomposedORConditions, 
                $this->decomposeOR($ORCondition)
            );
        }
        
        if (empty($result[0])) {
            $result = $decomposedORConditions;
        } else {
            $result = array_merge($result, $decomposedORConditions);
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
}
