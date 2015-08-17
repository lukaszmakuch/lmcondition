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
 * Decomposes any complex ConditionComposite into a 2D array of pure Condition
 * objects (without any ConditionComposite).
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ORDecomposer
{
    /**
     * Takes any condition (while it's useful only with composites) and transform
     * it into a 2D array where rows represent OR statements and columns 
     * represent AND statements.
     * 
     * (A AND (B OR (C AND D AND (E OR F))))
     * 
     * is decomposed to:
     * <pre>
     * [
     *     [A, B],
     *     [A, C, D, E]
     *     [A, C, D, F]
     * ]
     * </pre>
     * 
     * @param Condition $c Condition(Composite) to decompose
     * 
     * @return array like [[Condition, Condition], [Condition]]
     */
    public function decompose(Condition $c)
    {
        if ($c instanceof ConditionComposite) {
            return $this->decomposeComposite($c);
        }
        
        return [[$c]];
    }
    
    /**
     * Transforms AND/OR conditions of a composite object into and array.
     * 
     * @param ConditionComposite $c
     * 
     * @return array like [[Condition, Condition], [Condition]]
     */
    protected function decomposeComposite(ConditionComposite $c)
    {
        return array_merge(
            $this->decomposeANDConditionsOf($c),
            $this->decomposeORConditionsOf($c)
        );
    }
    
    /**
     * Turns (A AND B) into [[A, B]].
     * 
     * @see ORDecomposer::decomposeORConditionsOf() for supporting OR-branches like
     * (A AND (B OR C)) into [[A, B], [A, C]].
     * @param ConditionComposite $c
     * 
     * @return array [[Condition, Condition], [Condition]]
     */
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
    
    /**
     * Merges [[A]] and [[B], [C]] into [[A, B], [A, C]].
     * 
     * @param Condition[] $conditions
     * @param array $chains
     * 
     * @return array
     */
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
    
    /**
     * Gets all OR conditions of a composite and processes them through decompose(Condition).
     * 
     * @param ConditionComposite $c
     * 
     * @return array
     */
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
